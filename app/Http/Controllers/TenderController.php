<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;

class TenderController extends Controller
{
    public function index()
    {
        try{
            $tender = Tender::with('perusahaan','user','personels','dokumenPendukungs','pengalamanProyeks')->get();
            
            return response()->json([
                'status'=>'success',
                'data'=>$tender
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Something went wrong',
                'error'=>$ex->getMessage()
            ],401);
        }
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_tender'=>'required|string',
            'instansi'=>'required|string',
            'lokasi_pekerjaan'=>'required|string',
            'kota_pengadaan'=>'required|string',
            'tanggal_tender'=>'required|string|date|before_or_equal:today',
            'tahun_anggaran'=>'required|integer|digits:4',
            'durasi'=>'required|int',
            'nilai_penawaran'=>'required|numeric',
            'file_mdp'=>'required|string',
            'status_generate'=>'required|in:draft,generated',
            'perusahaan_id'=>'required|exists:perusahaans,id',
            'user_id'=>'required|exists:users,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'invalid field',
                'error'=>$validator->errors()
            ],422);
        }

        

        try{
            $tender = Tender::create($validator->validated());
            
            if($request->has('personels')){
                $tender->personels()->attach($request->personels);
            }
            if($request->has('dokumenPendukungs')){
                $tender->dokumenPendukungs()->attach($request->dokumenPendukungs);
            }
            if($request->has('pengalamanProyeks')){
                $tender->pengalamanProyeks()->attach($request->pengalamanProyeks);
            }
            $tender->load('perusahaan','user','personels','dokumenPendukungs','pengalamanProyeks');
            $result = [
                'id'=>$tender->id,
                'nama_tender'=>$tender->nama_tender,
                'instansi'=>$tender->instansi,
                'lokasi_pekerjaan'=>$tender->lokasi_pekerjaan,
                'kota_pengadaan'=>$tender->kota_pengadaan,
                'tanggal_tender'=>$tender->tanggal_tender,
                'tahun_anggaran'=>$tender->tahun_anggaran,
                'durasi'=>$tender->durasi,
                'nilai_penawaran'=>$tender->nilai_penawaran,
                'file_mdp'=>$tender->file_mdp,
                'status_generate'=>$tender->status_generate,
                'perusahaan_id'=>$tender->perusahaan_id,
                'user_id'=>$tender->user_id,
                'created_at'=>$tender->created_at,
                'updated_at'=>$tender->updated_at,
                'perusahaan'=>$tender->perusahaan,
                'personels'=>$tender->personels->map(function($personel){
                    return[
                        'id'=>$personel->id,
                        'nama_personel'=>$personel->nama_personel,
                        'jabatan'=>$personel->jabatan,
                        'pendidikan'=>$personel->pendidikan,
                        'sertifikasi'=>$personel->sertifikasi,
                        'pengalaman_kerja'=>$personel->pengalaman_kerja,
                        'nomor_sertifikat'=>$personel->nomor_sertifikat
                    ];
                }),
                'dokumenPendukungs'=>$tender->dokumenPendukungs->map(function($dokumen){
                    return[
                        'id'=>$dokumen->id,
                        'nama_dokumen'=>$dokumen->nama_dokumen,
                        'jenis_dokumen'=>$dokumen->jenis_dokumen,
                        'tanggal_upload'=>$dokumen->tanggal_upload,
                        'file_dokumen'=>$dokumen->file_dokumen,
                        'status_dokumen'=>$dokumen->status_dokumen
                    ];
                }),
                'pengalamanProyeks'=>$tender->pengalamanProyeks->map(function($pengalaman){
                    return[
                        'id'=>$pengalaman->id,
                        'nama_proyek'=>$pengalaman->nama_proyek,
                        'pemberi_kerja'=>$pengalaman->pemberi_kerja,
                        'tahun_pelaksanaan'=>$pengalaman->tahun_pelaksanaan,
                        'nilai_kontrak'=>$pengalaman->nilai_kontrak,
                        'lokasi_proyek'=>$pengalaman->lokasi_proyek,
                        'status_proyek'=>$pengalaman->status_proyek
                    ];
                })
            ];
            return response()->json([
                'data'=>$result
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'error'=>$ex->getMessage()
            ],422);
        }
    }

    public function show(int $id)
    {
        $tender = Tender::with('perusahaan','user','personels','dokumenPendukungs','pengalamanProyeks')->find($id);
        if(!$tender){
            return response()->json([
                'message'=>'data tender not found'
            ],404);
        }
        if($tender->user_id == !Auth::id()){
            return response()->json([
                'message'=>'Forbidan Access'
            ],403);
        }

        return response()->json([
            'status'=>'success',
            'data'=>$tender
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'nama_tender'=>'required|string',
            'instansi'=>'required|string',
            'lokasi_pekerjaan'=>'required|string',
            'kota_pengadaan'=>'required|string',
            'tanggal_tender'=>'required|string|date|before_or_equal:today',
            'tahun_anggaran'=>'required|integer|digits:4',
            'durasi'=>'required|int',
            'nilai_penawaran'=>'required|numeric',
            'file_mdp'=>'required|string',
            'status_generate'=>'required|in:draft,generated',
            'perusahaan_id'=>'required|exists:perusahaans,id',
            'user_id'=>'required|exists:users,id',

            // Opsional: Validasi array pivot agar tidak error saat di-sync
            'personels'          => 'nullable|array',
            'personels.*'        => 'exists:personels,id',
            'dokumenPendukungs'  => 'nullable|array',
            'dokumenPendukungs.*'=> 'exists:dokumen_pendukungs,id',
            'pengalamanProyeks'  => 'nullable|array',
            'pengalamanProyeks.*'=> 'exists:pengalaman_proyeks,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'invalid field',
                'error'=>$validator->errors()
            ],422);
        }

        $tender = Tender::with('perusahaan','user','personels','dokumenPendukungs','pengalamanProyeks')->find($id);
        if(!$tender){
            return response()->json([
                'message'=>'data tender not found'
            ],404);
        }
        if($tender->user_id !== Auth::id()){
            return response()->json([
                'message'=>'Forbidan Access'
            ],403);
        }

        

        try{
            $tender->update($validator->validated());
            if($request->has('personels')){
                $tender->personels()->sync($request->personels);
            }
            if($request->has('dokumenPendukungs')){
                $tender->dokumenPendukungs()->sync($request->dokumenPendukungs);
            }
            if($request->has('pengalamanProyeks')){
                $tender->pengalamanProyeks()->sync($request->pengalamanProyeks);
            }
            $tender->load('perusahaan','user','personels','dokumenPendukungs','pengalamanProyeks');
            return response()->json([
                'data'=>$tender
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'error'=>$ex->getMessage()
            ],422);
        }
    }

    
    public function destroy(string $id)
    {

    $tender = Tender::find($id);
        if(!$tender){
            return response()->json([
                'message'=>'data tender not found'
            ],404);
        }
        if($tender->user_id == !Auth::id()){
            return response()->json([
                'message'=>'Forbidan Access'
            ],403);
        }
        try{
            $tender->personels()->detach();
            $tender->dokumenPendukungs()->detach();
            $tender->pengalamanProyeks()->detach();
            $tender->delete();
            return response()->json([
                'status'=>'success'
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot delete this data',
                'error'=>$ex->getMessage()
            ],401);
        }
    }

    public function generateAndSavePdf(Request $request, string $id)
{
    // Daftar template yang diizinkan = nama file di resources/views/pdf/templates
    $templates = ['formal', 'modern', 'ringkas'];
    $template  = $request->query('template', 'formal');

    if (!in_array($template, $templates, true)) {
        return response()->json([
            'message'  => 'Template tidak dikenal',
            'tersedia' => $templates,
        ], 422);
    }

    $tender = Tender::with(['perusahaan', 'user', 'personels', 'dokumenPendukungs', 'pengalamanProyeks'])->find($id);

    if (!$tender) {
        return response()->json(['message' => 'Data tender tidak ditemukan'], 404);
    }

    if ($tender->user_id !== Auth::id()) {
        return response()->json(['message' => 'Forbidden Access'], 403);
    }

    try {
        $pdf = Pdf::loadView("templates.{$template}", compact('tender'))
                  ->setPaper('A4', 'portrait');

        $fileName    = "tender-{$tender->id}-{$template}-" . time() . ".pdf";
        $storagePath = "pdfs/tenders/{$fileName}";
        Storage::disk('public')->put($storagePath, $pdf->output());

        $tender->update(['status_generate' => 'generated']);

        return $pdf->stream($fileName);
    } catch (\Exception $ex) {
        return response()->json([
            'message' => 'Gagal membuat dokumen PDF',
            'error'   => $ex->getMessage(),
        ], 500);
    }
}
}
