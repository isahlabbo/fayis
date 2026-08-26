<?php
namespace App\Http\Livewire\Finance;
use App\Models\FinanceActivityLog;use Illuminate\Support\Facades\Auth;use Livewire\Component;
class Reports extends Component
{
 public $fromDate,$toDate,$type='';
 public function boot(){abort_unless(Auth::check()&&Auth::user()->hasPermission('manage-payments'),403);}
 public function mount(){$this->fromDate=now()->startOfMonth()->toDateString();$this->toDate=now()->toDateString();}
 private function baseQuery(){return FinanceActivityLog::query()->when($this->fromDate,fn($q)=>$q->whereDate('created_at','>=',$this->fromDate))->when($this->toDate,fn($q)=>$q->whereDate('created_at','<=',$this->toDate));}
 public function render(){$logs=$this->baseQuery()->with('user')->when($this->type,fn($q)=>$q->where('activity_type',$this->type))->latest()->get();$summary=['sales'=>(clone $this->baseQuery())->where('activity_type','inventory_sale')->sum('amount'),'cancelled_payments'=>(clone $this->baseQuery())->where('activity_type','payment_cancelled')->sum('amount'),'deleted_sales'=>(clone $this->baseQuery())->where('activity_type','sale_deleted')->sum('amount'),'sales_profit'=>(clone $this->baseQuery())->where('activity_type','sales_profit')->sum('amount'),'payments_collected'=>(clone $this->baseQuery())->where('activity_type','payment')->sum('amount')];return view('livewire.finance.reports',compact('logs','summary')+['types'=>FinanceActivityLog::distinct()->orderBy('activity_type')->pluck('activity_type')]);}
}
