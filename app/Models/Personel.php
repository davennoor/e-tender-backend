<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personel extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    public function tenders()
    {
        return $this->belongsToMany(Tender::class, 'personel_tender');
    }
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
