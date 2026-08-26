<?php
namespace App\Http\Livewire\Finance;
use App\Models\{Fee,FinanceActivityLog,Gender,Section,SectionClass,SectionClassFee,SectionClassFeeItem,Term};
use Illuminate\Support\Facades\{Auth,DB};
use Livewire\Component;
class FeeSettings extends Component
{
    public $sectionId,$classId,$feeId,$itemId,$categoryName,$termId,$genderId,$amount,$showForm=false;
    public function boot(){abort_unless(Auth::check()&&Auth::user()->hasPermission('manage-fees'),403);}
    public function updatedSectionId(){$this->classId=null;}
    public function create(){$this->resetForm();$this->showForm=true;}
    public function edit($id){$item=SectionClassFeeItem::with('sectionClassFee.sectionClass')->findOrFail($id);$this->itemId=$id;$this->classId=$item->sectionClassFee->section_class_id;$this->feeId=$item->sectionClassFee->fee_id;$this->termId=$item->term_id;$this->genderId=$item->gender_id;$this->amount=$item->amount;$this->sectionId=$item->sectionClassFee->sectionClass->section_id;$this->showForm=true;}
    public function save(){$data=$this->validate(['classId'=>'required|exists:section_classes,id','feeId'=>'required|exists:fees,id','termId'=>'required|exists:terms,id','genderId'=>'nullable|exists:genders,id','amount'=>'required|numeric|min:0']);DB::transaction(function()use($data){$fee=SectionClassFee::firstOrCreate(['section_class_id'=>$data['classId'],'fee_id'=>$data['feeId']]);$item=SectionClassFeeItem::updateOrCreate(['id'=>$this->itemId],['section_class_fee_id'=>$fee->id,'term_id'=>$data['termId'],'gender_id'=>$data['genderId'],'amount'=>$data['amount'],'description'=>$fee->fee->name]);FinanceActivityLog::record('fee_setting',$item,'Fee setting saved',$data['amount']);});$this->resetForm();session()->flash('success','Fee setting saved.');}
    public function delete($id){$item=SectionClassFeeItem::findOrFail($id);if($item->sectionClassFee->payments()->exists()){session()->flash('error','This fee has payment history and cannot be deleted.');return;}$item->delete();session()->flash('success','Fee setting deleted.');}
    public function addCategory(){$this->validate(['categoryName'=>'required|max:100']);Fee::firstOrCreate(['name'=>$this->categoryName]);$this->categoryName=null;}
    public function resetForm(){$this->reset(['itemId','feeId','termId','genderId','amount','showForm']);$this->resetValidation();}
    public function render(){$items=SectionClassFeeItem::with(['sectionClassFee.fee','sectionClassFee.sectionClass.section','term','gender'])->when($this->sectionId,fn($q)=>$q->whereHas('sectionClassFee.sectionClass',fn($x)=>$x->where('section_id',$this->sectionId)))->when($this->classId,fn($q)=>$q->whereHas('sectionClassFee',fn($x)=>$x->where('section_class_id',$this->classId)))->latest()->get();return view('livewire.finance.fee-settings',compact('items')+['sections'=>Section::orderBy('name')->get(),'classes'=>SectionClass::when($this->sectionId,fn($q)=>$q->where('section_id',$this->sectionId))->get(),'fees'=>Fee::orderBy('name')->get(),'terms'=>Term::all(),'genders'=>Gender::all()]);}
}
