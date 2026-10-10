<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    //m to 1 => tender ke perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    //m to 1 => tender ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //m to m => tender ke personel
    public function personels()
    {
        return $this->belongsToMany(Personel::class, 'personel_tender');
    }

    //m to m -> tender ke dokumen pendukung
    public function dokumenPendukungs()
    {
        return $this->belongsToMany(DokumenPendukung::class, 'dokumen_pendukung_tender');//dari pivot
    }

    //m to m => tenders ke pengalaman proyek
    public function pengalamanProyeks()
    {
        return $this->belongsToMany(PengalamanProyek::class, 'pengalaman_proyek_tender');//dari pivot
    }


}
