<?php

namespace App\Http\Livewire\Admin;

use App\Models\AcademicSession;
use App\Models\FinanceActivityLog;
use App\Models\InventoryStock;
use App\Models\MaterialCollection;
use App\Models\Section;
use App\Models\SectionClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MaterialCollectionRecords extends Component
{
    public $academicSessionId = '';
    public $sectionId = '';
    public $classId = '';
    public $status = '';
    public $search = '';

    public $markingId = null;
    public $receiverName = '';

    public function boot()
    {
        abort_unless(Auth::check() && Auth::user()->hasPermission('manage-material-collection'), 403);
    }

    public function updatedSectionId()
    {
        $this->classId = '';
    }

    public function openMarkModal($id)
    {
        $this->markingId = $id;
        $this->receiverName = '';
        $this->resetValidation();
    }

    public function cancelMark()
    {
        $this->markingId = null;
        $this->receiverName = '';
    }

    public function confirmCollection()
    {
        $this->validate(['receiverName' => 'required|string|max:255']);

        DB::transaction(function () {
            $collection = MaterialCollection::with('items.inventoryItem')->lockForUpdate()->findOrFail($this->markingId);

            foreach ($collection->items as $item) {
                if ($item->is_collected) {
                    continue;
                }

                if ($item->inventoryItem) {
                    $this->deductStock($item, $collection);
                }

                $item->update(['is_collected' => true, 'collected_at' => now()]);
            }

            $collection->update([
                'status' => 'Collected',
                'receiver_name' => $this->receiverName,
                'collected_at' => now(),
                'recorded_by' => Auth::id(),
            ]);
        });

        $this->markingId = null;
        $this->receiverName = '';
        session()->flash('success', 'Material collection recorded and stock updated.');
    }

    private function deductStock($item, MaterialCollection $collection)
    {
        $inventoryItem = $item->inventoryItem()->lockForUpdate()->first();
        $remaining = $item->quantity;

        $batches = InventoryStock::where('inventory_item_id', $inventoryItem->id)
            ->where('remaining_quantity', '>', 0)
            ->orderByRaw('received_date IS NULL')
            ->orderBy('received_date')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }
            $take = min($remaining, $batch->remaining_quantity);
            $batch->decrement('remaining_quantity', $take);
            $remaining -= $take;
        }

        $inventoryItem->decrement('quantity', min($item->quantity, $inventoryItem->quantity));

        FinanceActivityLog::record(
            'material_collection_issue',
            $collection,
            'Material issued: '.$inventoryItem->name.' to '.optional($collection->sectionClassStudent->student)->name,
            0,
            ['quantity' => $item->quantity, 'fulfilled' => $item->quantity - max($remaining, 0)]
        );
    }

    private function filteredQuery()
    {
        return MaterialCollection::with([
            'sectionClassStudent.student.guardian',
            'sectionClassStudent.sectionClass.section',
            'targetSectionClass.section',
            'academicSession',
            'items.inventoryItem',
        ])
            ->when($this->academicSessionId, fn ($q) => $q->where('academic_session_id', $this->academicSessionId))
            ->when($this->sectionId, fn ($q) => $q->whereHas('targetSectionClass', fn ($x) => $x->where('section_id', $this->sectionId)))
            ->when($this->classId, fn ($q) => $q->where('target_section_class_id', $this->classId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->search, fn ($q) => $q->whereHas('sectionClassStudent.student', fn ($x) => $x->where('name', 'like', '%'.$this->search.'%')->orWhere('admission_no', 'like', '%'.$this->search.'%')));
    }

    public function render()
    {
        $records = $this->filteredQuery()->latest()->get();

        return view('livewire.admin.material-collection-records', [
            'sessions' => AcademicSession::orderByDesc('id')->get(),
            'sections' => Section::orderBy('name')->get(),
            'classes' => SectionClass::when($this->sectionId, fn ($q) => $q->where('section_id', $this->sectionId))->orderBy('name')->get(),
            'records' => $records,
            'collectedCount' => $records->where('status', 'Collected')->count(),
            'pendingCount' => $records->where('status', 'Pending')->count(),
        ]);
    }
}
