<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionClassSubjectDownloads extends  BaseModel
{
    public function sectionClassSubject()
    {
        return $this->belongsTo(SectionClassSubjet::class);
    }
    
    public function academicSessionTerm()
    {
        return $this->belongsTo(AcademicSessionTerm::class);
    }
}
