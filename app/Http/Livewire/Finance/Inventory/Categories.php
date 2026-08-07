<?php

namespace App\Http\Livewire\Finance\Inventory;

use App\Models\InventoryCategory;
use Livewire\Component;

class Categories extends Component
{
    public $searchTerm;
    public $categoryId;
    public $name;
    public $description;
    public $showForm = false;
    public $isEdit = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];

        if ($this->isEdit) {
            $rules['name'] = 'required|string|max:255|unique:inventory_categories,name,'.$this->categoryId;
        }

        return $rules;
    }

    public function render()
    {
        $categories = InventoryCategory::when($this->searchTerm, fn($query) => $query->where('name', 'like', '%'.$this->searchTerm.'%'))
            ->orderBy('name')
            ->get();

        return view('livewire.finance.inventory.categories', [
            'categories' => $categories,
        ]);
    }

    public function createCategory()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editCategory($id)
    {
        $category = InventoryCategory::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->showForm = true;
        $this->isEdit = true;
    }

    public function saveCategory()
    {
        $data = $this->validate();

        if ($this->isEdit) {
            $category = InventoryCategory::findOrFail($this->categoryId);
            $category->update($data);
            session()->flash('success', 'Category updated successfully.');
        } else {
            InventoryCategory::create($data);
            session()->flash('success', 'Category created successfully.');
        }

        $this->resetForm();
    }

    public function deleteCategory($id)
    {
        $category = InventoryCategory::findOrFail($id);
        $category->delete();

        session()->flash('success', 'Category deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'description', 'showForm', 'isEdit']);
    }
}
