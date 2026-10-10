<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    //1 to m => perusahaan ke tenders
    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
    public function personels()
{
    return $this->hasMany(Personel::class, 'perusahaan_id');
}
}
