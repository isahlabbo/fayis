<?php
namespace App\Models;
class FinanceActivityLog extends BaseModel
{
    protected $casts=['metadata'=>'array','amount'=>'decimal:2'];
    public function user(){ return $this->belongsTo(User::class); }
    public static function record($type,$model,$description,$amount=0,$metadata=[]){ return static::create(['user_id'=>auth()->id(),'activity_type'=>$type,'reference_type'=>is_object($model)?get_class($model):null,'reference_id'=>is_object($model)?$model->id:null,'description'=>$description,'amount'=>$amount,'metadata'=>$metadata]); }
}
