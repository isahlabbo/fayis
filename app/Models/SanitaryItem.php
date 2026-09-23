<?php

namespace App\Models;

class SanitaryItem extends BaseModel
{
    public function stocks() { return $this->hasMany(SanitaryStock::class); }
}
