<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\InventoryUsage;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\SectionClassStudent;
use App\Models\Teacher;
use Livewire\Component;

class Usage extends Component
{
    public $section_id;
    public $class_id;
    public $student_id;
    public $teacher_id;
    public $item_id;
    public $inventory_stock_id;
    public $usage_type = 'sale';
    public $quantity = 1;
    public $unit_cost;
    public $receipt_number;
    public $evidence;
    public $usage_date;
    public $notes;
    public $sections;
    public $classes = [];
    public $students = [];
    public $teachers = [];
    public $items;
    public $stocks = [];

    protected function rules()
    {
        $rules = [
            'item_id' => 'required|exists:inventory_items,id',
            'inventory_stock_id' => 'required|exists:inventory_stocks,id',
            'usage_type' => 'required|in:sale,rent',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'receipt_number' => 'nullable|string|max:100',
            'evidence' => 'nullable|string|max:1000',
            'usage_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($this->usage_type === 'sale') {
            $rules['section_id'] = 'required|exists:sections,id';
            $rules['class_id'] = 'required|exists:section_classes,id';
            $rules['student_id'] = 'required|exists:section_class_students,id';
            $rules['teacher_id'] = 'nullable';
        } else {
            $rules['section_id'] = 'nullable';
            $rules['class_id'] = 'nullable';
            $rules['student_id'] = 'nullable';
            $rules['teacher_id'] = 'required|exists:teachers,id';
        }

        return $rules;
    }

    public function mount()
    {
        $this->sections = Section::orderBy('name')->get();
        $this->teachers = Teacher::orderBy('name')->get();
        $this->items = InventoryItem::with('category')->where('quantity', '>', 0)->orderBy('name')->get();
        $this->stocks = collect();
        $this->usage_date = now()->format('Y-m-d');
    }

    public function updatedSectionId($value)
    {
        $this->classes = SectionClass::where('section_id', $value)->pluck('name', 'id');
        $this->students = [];
        $this->class_id = null;
        $this->student_id = null;
    }

    public function updatedClassId($value)
    {
        $this->students = SectionClassStudent::where('section_class_id', $value)
            ->where('status', 'Active')
            ->with('student')
            ->get()
            ->map(function ($sectionClassStudent) {
                return ['id' => $sectionClassStudent->id, 'name' => $sectionClassStudent->student->name];
            });
        $this->student_id = null;
    }

    public function updatedUsageType($value)
    {
        $this->student_id = null;
        $this->teacher_id = null;
        $this->section_id = null;
        $this->class_id = null;
        $this->students = [];
        $this->classes = [];
        $this->inventory_stock_id = null;
        $this->stocks = [];
        $this->unit_cost = null;
    }

    public function updatedItemId($value)
    {
        $item = InventoryItem::find($value);
        $this->stocks = InventoryStock::where('inventory_item_id', $value)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('received_date')
            ->get();

        $this->inventory_stock_id = optional($this->stocks->first())->id;
        $this->unit_cost = optional($this->stocks->first())->unit_cost;
    }

    public function updatedInventoryStockId($value)
    {
        $stock = InventoryStock::find($value);
        $this->unit_cost = $stock ? $stock->unit_cost : null;
    }

    public function saveUsage()
    {
        $data = $this->validate();

        $stock = InventoryStock::findOrFail($data['inventory_stock_id']);
        if ($stock->inventory_item_id !== $data['item_id']) {
            $this->addError('inventory_stock_id', 'Selected stock batch does not belong to the selected item.');
            return;
        }

        if ($stock->remaining_quantity < $data['quantity']) {
            $this->addError('quantity', 'Selected stock batch does not have enough remaining quantity.');
            return;
        }

        $item = InventoryItem::findOrFail($this->item_id);
        $unitCost = $stock->unit_cost;
        $totalCost = $data['quantity'] * $unitCost;

        if (!$data['receipt_number']) {
            $data['receipt_number'] = 'REC-'.now()->format('YmdHis');
        }

        $usage = InventoryUsage::create(array_merge($data, [
            'inventory_stock_id' => $stock->id,
            'unit_cost' => $unitCost,
            'total_cost' => $totalCost,
        ]));

        $stock->decrement('remaining_quantity', $data['quantity']);
        $item->decrement('quantity', $data['quantity']);

        session()->flash('success', 'Inventory usage recorded successfully.');

        if ($this->usage_type === 'sale') {
            return redirect()->route('finance.inventory.usage.receipt', ['usageId' => $usage->id]);
        }

        $this->reset(['section_id', 'class_id', 'student_id', 'teacher_id', 'item_id', 'usage_type', 'quantity', 'unit_cost', 'receipt_number', 'evidence', 'usage_date', 'notes']);
        $this->usage_type = 'sale';
        $this->quantity = 1;
        $this->usage_date = now()->format('Y-m-d');
        $this->items = InventoryItem::with('category')->where('quantity', '>', 0)->orderBy('name')->get();
        $this->students = [];
        $this->classes = [];
    }

    public function render()
    {
        return view('livewire.finance.inventory.usage', [
            'sections' => $this->sections,
            'classes' => $this->classes,
            'students' => $this->students,
            'teachers' => $this->teachers,
            'items' => $this->items,
        ]);
    }
}
