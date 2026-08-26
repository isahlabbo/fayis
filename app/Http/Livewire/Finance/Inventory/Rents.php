<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryRent;
use App\Models\AcademicSession;
use App\Models\FinanceActivityLog;
use App\Models\Teacher;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Rents extends Component
{
    public function boot() { abort_unless(Auth::check() && (Auth::user()->role === 'finance_officer' || Auth::user()->hasPermission('manage-rents')), 403); }
    public $teacher_id;
    public $item_id;
    public $quantity = 1;
    public $usage_date;
    public $notes;
    public $teachers;
    public $items;
    public $academic_session_id;

    protected function rules()
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'academic_session_id' => 'required|exists:academic_sessions,id',
        ];
    }

    public function mount()
    {
        $this->teachers = Teacher::with('user')->orderBy('id')->get();
        $this->items = InventoryItem::with('category')->where('quantity', '>', 0)->orderBy('name')->get();
        $this->usage_date = now()->format('Y-m-d');
        $this->academic_session_id = AcademicSession::where('status','Active')->value('id');
    }

    public function updatedItemId($value)
    {
        // no unit cost required for rent
    }

    public function saveRent()
    {
        $data = $this->validate();
        $item = InventoryItem::findOrFail($this->item_id);
        $totalCost = 0;

        abort_if($item->quantity < $data['quantity'], 422, 'Insufficient stock.');
        $rent = InventoryRent::create([
            'inventory_item_id' => $data['item_id'],
            'teacher_id' => $data['teacher_id'],
            'quantity' => $data['quantity'],
            'unit_cost' => 0,
            'total_cost' => 0,
            'receipt_number' => null,
            'evidence' => null,
            'usage_date' => $data['usage_date'],
            'notes' => $data['notes'],
            'academic_session_id' => $data['academic_session_id'], 'status'=>'Rented',
        ]);

        $item->decrement('quantity', $data['quantity']);
        FinanceActivityLog::record('inventory_rent', $rent, 'Material rented to teacher', 0, ['quantity'=>$data['quantity'],'balance'=>$item->fresh()->quantity]);

        session()->flash('success', 'Inventory rent recorded successfully.');

        $this->reset(['teacher_id', 'item_id', 'quantity', 'usage_date', 'notes']);
        $this->quantity = 1;
        $this->usage_date = now()->format('Y-m-d');
    }

    public function returnItems($id, $quantity = null)
    {
        $rent=InventoryRent::findOrFail($id);$balance=$rent->quantity-$rent->returned_quantity;$returnQty=$quantity ? (int)$quantity : $balance;
        abort_if($returnQty<1 || $returnQty>$balance,422,'Invalid return quantity.');
        $rent->increment('returned_quantity',$returnQty);$rent->item()->increment('quantity',$returnQty);$rent->refresh();
        $rent->update(['returned_at'=>now()->toDateString(),'status'=>$rent->returned_quantity >= $rent->quantity ? 'Returned' : 'Partially Returned']);
        FinanceActivityLog::record('inventory_return',$rent,'Material returned by teacher',0,['quantity'=>$returnQty,'balance'=>$rent->item->fresh()->quantity]);session()->flash('success','Return recorded.');
    }

    public function render()
    {
        return view('livewire.finance.inventory.rents', [
            'teachers' => $this->teachers,
            'items' => $this->items,
            'sessions' => AcademicSession::latest('id')->get(), 'rents'=>InventoryRent::with(['teacher.user','item','academicSession'])->latest()->get(),
        ]);
    }
}
