<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenPendukung extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    //m to m => dokumen pendukung ke tenders
    public function tenders()
    {
        return $this->belongsToMany(Tender::class, 'dokumen_pendukung_tender');//dari pivot
    }
}
