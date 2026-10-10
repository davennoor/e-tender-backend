<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengalamanProyek extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    //m to m => pengalaman proyek ke tenders
    public function tenders()
    {
        return $this->belongsToMany(Tender::class, 'pengalaman_proyek_tender');//dari pivot
    }
}
