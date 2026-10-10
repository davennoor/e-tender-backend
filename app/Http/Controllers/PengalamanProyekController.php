<?php

namespace App\Http\Controllers;

use App\Models\PengalamanProyek;
use Exception;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PengalamanProyekController extends Controller
{
    
    public function index()
    {
        try{
        $pengalamanproyek = PengalamanProyek::all();
        return response()->json([
            'status'=>'success',
            'data'=>$pengalamanproyek
        ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Data pengalaman proyek not found',
                'error'=>$ex->getMessage()
            ],404);
        }
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_proyek'=>'required|string',//['required','string',Rule::unique('pengalaman_proyeks')->withoutTrashed()],
            'pemberi_kerja'=>'required|string',
            'tahun_pelaksanaan'=>'required|int|digits:4',
            'nilai_kontrak'=>'required|numeric|min:0',
            'lokasi_proyek'=>'required|string',
            'status_proyek'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid Field',
                'error'=>$validator->errors()
            ],401);
        }

        try{
            $pengalamanproyek = PengalamanProyek::create($validator->validated());
            return response()->json([
                'status'=>'success',
                'data'=>$pengalamanproyek
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot create the data',
                'error'=>$ex->getMessage()
            ],404);
        }
    }

    
    public function show(int $id)
    {
        try{
            $pengalamanproyek = PengalamanProyek::where('id',$id)->firstOrFail();
            return response()->json([
                'status'=>'success',
                'data'=>$pengalamanproyek
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Data pengalaman proyek not found',
                'error'=>$ex->getMessage()
            ],404);
        }
    }

    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'nama_proyek'=>'required|string',//['required','string',Rule::unique('pengalaman_proyeks')->withoutTrashed()->ignore($nama)],
            'pemberi_kerja'=>'required|string',
            'tahun_pelaksanaan'=>'required|int|digits:4',
            'nilai_kontrak'=>'required|numeric|min:0',
            'lokasi_proyek'=>'required|string',
            'status_proyek'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid Field',
                'error'=>$validator->errors()
            ],401);
        }

        try{
            $pengalamanproyek = PengalamanProyek::where('id',$id)->firstOrFail();
            $pengalamanproyek->update($validator->validated());

            return response()->json([
                'status'=>'success',
                'data'=>$pengalamanproyek
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot update this data',
                'error'=>$ex->getMessage()
            ],500);
        }
    }

    
    public function destroy(int $id)
    {
        try{
            $pengalamanproyek = PengalamanProyek::where('id', $id)->firstOrFail();
            $pengalamanproyek->delete();
            return response()->json([
                'status'=>'succes'
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot delete this data',
                'error'=>$ex->getMessage()
            ],500);
        }
    }
}
