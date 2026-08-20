<?php

namespace App\Http\Livewire\Head\Teachers;

use App\Http\Livewire\Admin\ResourceManager;

class Index extends ResourceManager
{
    public function mount($resource = null)
    {
        parent::mount('teachers');
    }
}
