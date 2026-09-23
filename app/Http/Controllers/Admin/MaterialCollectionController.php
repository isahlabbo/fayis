<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\InventoryItem;
use App\Models\MaterialCollection;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialCollectionController extends Controller
{
    public function index()
    {
        return view('admin.material-collection.index');
    }

    public function records()
    {
        return view('admin.material-collection.records');
    }

    public function report(Request $request)
    {
        $records = MaterialCollection::with([
            'sectionClassStudent.student.guardian',
            'sectionClassStudent.sectionClass.section',
            'targetSectionClass.section',
            'academicSession',
            'items',
        ])
            ->when($request->session, fn ($q) => $q->where('academic_session_id', $request->session))
            ->when($request->section, fn ($q) => $q->whereHas('targetSectionClass', fn ($x) => $x->where('section_id', $request->section)))
            ->when($request->class, fn ($q) => $q->where('target_section_class_id', $request->class))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->whereHas('sectionClassStudent.student', fn ($x) => $x->where('name', 'like', '%'.$request->search.'%')->orWhere('admission_no', 'like', '%'.$request->search.'%')))
            ->get()
            ->sortBy('sectionClassStudent.student.name');

        $collected = $records->where('status', 'Collected')->values();
        $notCollected = $records->where('status', '!=', 'Collected')->values();

        return view('admin.material-collection.report', compact('collected', 'notCollected'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'session' => 'nullable|integer',
            'target_class' => 'required|integer',
        ]);

        $ids = array_filter(explode(',', $request->ids));

        abort_if(empty($ids), 404);

        $records = SectionClassStudent::with(['student.guardian', 'sectionClass.section'])
            ->whereIn('id', $ids)
            ->whereHas('student')
            ->get()
            ->sortBy('student.name');

        abort_if($records->isEmpty(), 404);

        $session = $request->session ? AcademicSession::find($request->session) : null;

        // The class students are being promoted into for the upcoming session, printed instead of their current class.
        $targetClass = SectionClass::with('section')->findOrFail($request->target_class);

        $materials = MaterialCollection::MATERIALS;

        $this->persistRecords($records, $targetClass, $session, $materials);

        return view('admin.material-collection.print', compact('records', 'session', 'materials', 'targetClass'));
    }

    /** Save the electronic collection record (idempotent) each time a sheet is generated, so it can be tracked and reported on. */
    private function persistRecords($records, SectionClass $targetClass, ?AcademicSession $session, array $materials)
    {
        DB::transaction(function () use ($records, $targetClass, $session, $materials) {
            foreach ($records as $record) {
                $collection = MaterialCollection::firstOrCreate([
                    'section_class_student_id' => $record->id,
                    'target_section_class_id' => $targetClass->id,
                    'academic_session_id' => $session->id ?? null,
                ]);

                foreach ($materials as $material) {
                    if ($collection->items()->where('name', $material)->exists()) {
                        continue;
                    }

                    $inventoryItem = InventoryItem::whereRaw('LOWER(name) = ?', [strtolower($material)])->first();

                    $collection->items()->create([
                        'name' => $material,
                        'inventory_item_id' => $inventoryItem->id ?? null,
                        'quantity' => 1,
                    ]);
                }
            }
        });
    }
}
