<?php

namespace App\Http\Livewire\Finance\Sanitary;

use Illuminate\Support\Facades\Auth;
use App\Support\SanitaryAccess;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Screen extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '', $filterItem = '', $fromDate = '', $toDate = '';

    public function boot()
    {
        abort_unless(SanitaryAccess::canView(Auth::user()), 403);
    }

    public function getCanManageProperty()
    {
        return SanitaryAccess::canManage(Auth::user());
    }

    protected function authorizeManagement(): void
    {
        abort_unless(SanitaryAccess::canManage(Auth::user()), 403);
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterItem() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); }
}
