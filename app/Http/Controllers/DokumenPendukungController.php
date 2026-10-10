<?php

namespace App\Http\Controllers;

use App\Models\DokumenPendukung;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DokumenPendukungController extends Controller
{ 
    public function index()
    {
        try{
        $dokumenpendukung = DokumenPendukung::all();
        return response()->json([
            'status'=>'success',
            'data'=>$dokumenpendukung
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
    {//['required','string',Rule::unique('pengalaman_proyeks')->withoutTrashed()],
        $validator = Validator::make($request->all(),[
            'nama_dokumen'=>'required|string',
            'jenis_dokumen'=>'required|string',
            'tanggal_upload'=>'required|date|before_or_equal:today',
            'file_dokumen'=>'required|string',
            'status_dokumen'=>'required|in:aktif,tidak_aktif',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid Field',
                'error'=>$validator->errors()
            ],401);
        }

        try{
            $dokumenpendukung = DokumenPendukung::create($validator->validated());
            return response()->json([
                'status'=>'success',
                'data'=>$dokumenpendukung
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
            $dokumenpendukung = DokumenPendukung::where('id',$id)->firstOrFail();
            return response()->json([
                'status'=>'success',
                'data'=>$dokumenpendukung
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
    {//['required','string',Rule::unique('pengalaman_proyeks')->withoutTrashed()->ignore($nama)],
        $validator = Validator::make($request->all(),[
            'nama_dokumen'=>'required|string',
            'jenis_dokumen'=>'required|string',
            'tanggal_upload'=>'required|date|before_or_equal:today',
            'file_dokumen'=>'required|string',
            'status_dokumen'=>'required|in:aktif,tidak_aktif',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid Field',
                'error'=>$validator->errors()
            ],401);
        }

        try{
            $dokumenpendukung = DokumenPendukung::where('id',$id)->firstOrFail();
            $dokumenpendukung->update($validator->validated());

            return response()->json([
                'status'=>'success',
                'data'=>$dokumenpendukung
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
            $dokumenpendukung = DokumenPendukung::where('id', $id)->firstOrFail();
            $dokumenpendukung->delete();
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
