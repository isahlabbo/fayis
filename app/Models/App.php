<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends BaseModel
{
    public function user()
    {
        $this->belongsTo(App::class);
    }

    public function configured()
    {
        if($this->appColor){
            return true;
        }
    }

    public function appConfig()
    {
        return $this->hasOne(AppColor::class);
    }
}
