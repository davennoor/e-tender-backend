<?php

namespace App\Http\Controllers;

use App\Models\Personel;
use App\Models\Perusahaan;
use Exception;
use Faker\Provider\Person;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonelController extends Controller
{
    
    public function index()
    {
        try{
        $personel = Personel::with('perusahaan')->get();
            return response()->json([
                'status'=>'success',
                'data'=>$personel
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Data personel not found',
                'error'=>$ex->getMessage()
            ],401);
        }
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'perusahaan_id'=>'required|string|exists:perusahaans,id',
            'nama_personel'=>'required|string',
            'jabatan'=>'required|string',
            'pendidikan'=>'required|string',
            'pengalaman_kerja'=>'required|int',
            'sertifikasi'=>'required|string',
            'nomor_sertifikat'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid field',
                'error'=>$validator->errors()
            ], 401);
        };

        try{
            $perusahaan = Personel::create([
                'perusahaan_id'=>$request->perusahaan_id,
                'nama_personel'=>$request->nama_personel,
                'jabatan'=>$request->jabatan,
                'pendidikan'=>$request->pendidikan,
                'pengalaman_kerja'=>$request->pengalaman_kerja,
                'sertifikasi'=>$request->sertifikasi,
                'nomor_sertifikat'=>$request->nomor_sertifikat,  
            ]);

            return response()->json([
                'status'=>'success',
                'data'=>$perusahaan
            ],200);
        }
        catch(Exception $ex){
            return response()->json([
                'message'=>'Cannot create data personel',
                'error'=>$ex->getMessage()
            ],500);
        }
        
    }

    
    public function show(int $id)
    {
        $personel = Personel::where('id',$id)->find();
        if(!$personel){
            return response()->json([
                'message'=>'cannot find this name'
            ],404);
        }

        return response()->json([
            'status'=>'success',
            'data'=>$personel
        ],200);
        
    }

    
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'nama_personel'=>'required|string',
            'jabatan'=>'required|string',
            'pendidikan'=>'required|string',
            'pengalaman_kerja'=>'required|int',
            'sertifikasi'=>'required|string',
            'nomor_sertifikat'=>'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid field',
                'error'=>$validator->errors()
            ], 401);
        };

        try{
            $perusahaan = Personel::where('id',$id)->firstOrFail();
            $perusahaan->update($validator->validated());

            return response()->json([
                'status'=>'success',
                'data'=>$perusahaan
            ], 200);
        }
        catch(Exception $ex){
            return response([
                'message'=>'Cannot update data perusahaan',
                'error'=>$ex->getMessage()
            ],500);
        }

        
    }

    public function destroy(int $id)
    {
        try{
            $personel = Personel::where('id', $id);
            $personel->delete();

            return response()->json([
                'status'=>'success'
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
