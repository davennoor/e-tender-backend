<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerusahaanController extends Controller
{
    public function index()
    {
        try{
            $perusahaan = Perusahaan::all();
            return response()->json([
                'data'=>$perusahaan
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Data tidak ditemukan',
                'errors'=>$ex->getMessage()
            ],401);
        }
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_perusahaan'=>'required|string',
            'alamat'=>'required|string',
            'npwp'=>['required','string','max:25',' regex:/^[0-9.-]+$/',Rule::unique('perusahaans')->withoutTrashed()],
            'nib'=>['required','string','digits:13',Rule::unique('perusahaans')->withoutTrashed()],
            'nama_direktur'=>'required|string',
            'jabatan_direktur'=>'required|string',
            'nomor_telepon'=>'required|string|digits_between:10,13',
            'email'=>'required|string|email:rfc'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=>'invalid field',
                'error'=>$validator->errors()
            ], 401);
        }

        try{
            $perusahaan = Perusahaan::create([
                'nama_perusahaan'=>$request->nama_perusahaan,
                'alamat'=>$request->alamat,
                'npwp'=>$request->npwp,
                'nib'=>$request->nib,
                'nama_direktur'=>$request->nama_direktur,
                'jabatan_direktur'=>$request->jabatan_direktur,
                'nomor_telepon'=>$request->nomor_telepon,
                'email'=>$request->email
            ]);
            return response()->json([
                'status'=>'success',
                'data'=>$perusahaan
            ]);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot create data perusahaan',
                'error'=>$ex->getMessage()
            ],500);
        }
    }

    
    public function show(int $id)
    {
        try{
            $perusahaan = Perusahaan::findOrFail($id);
            return response()->json([
                'status'=>'success',
                'data'=>$perusahaan
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Data perusahaan not found',
                'error'=>$ex->getMessage()
            ],500);
        }
    }

    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'nama_perusahaan'=>'required|string',
            'alamat'=>'required|string',
            'npwp'=>['required','string',Rule::unique('perusahaans')->withoutTrashed()->ignore($id)],
            'nib'=>['required','string',Rule::unique('perusahaans')->withoutTrashed()->ignore($id)],
            'nama_direktur'=>'required|string',
            'jabatan_direktur'=>'required|string',
            'nomor_telepon'=>'required|string|digits_between:10,13',
            'email'=>'required|string|email:rfc'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=>'invalid field',
                'error'=>$validator->errors()
            ], 422);
        }

        try{
            $perusahaan = Perusahaan::findOrFail($id);
            $perusahaan->update([
                'nama_perusahaan'=>$request->nama_perusahaan,
                'alamat'=>$request->alamat,
                'npwp'=>$request->npwp,
                'nib'=>$request->nib,
                'nama_direktur'=>$request->nama_direktur,
                'jabatan_direktur'=>$request->jabatan_direktur,
                'nomor_telepon'=>$request->nomor_telepon,
                'email'=>$request->email
            ]);
            return response()->json([
                'status'=>'success',
                'data'=>$perusahaan
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot update data perusahaan',
                'error'=>$ex->getMessage()
            ],500);
        }
    }

    
    public function destroy(string $id)
    {
        try{
            $perusahaan = Perusahaan::findOrFail($id);
            $perusahaan->delete();

            return response()->json([
                'status'=>'success'
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'cannot delete data perusahaan'
            ], 500);
        }
    }
}
