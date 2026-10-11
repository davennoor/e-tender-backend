<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DokumenPendukungController;
use App\Http\Controllers\PengalamanProyekController;
use App\Http\Controllers\PersonelController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\TenderController;
use App\Models\DokumenPendukung;
use App\Models\PengalamanProyek;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthenticationController::class)->group(function(){
    //Route::post('/register','register');
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');//kalo gak pake middleware nanti error route is not bound
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('perusahaan')->group(function(){
        Route::controller(PerusahaanController::class)->group(function(){
            Route::get('/index','index');
            Route::post('/store','store');
            Route::get('/show/{id}','show');
            Route::put('/update/{id}','update');
            Route::delete('/destroy/{id}','destroy');
        });
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('personel')->group(function(){
        Route::controller(PersonelController::class)->group(function(){
            Route::get('/index','index');
            Route::post('/store','store');
            Route::get('/show/{id}','show');
            Route::put('/update/{id}','update');
            Route::delete('/destroy/{id}','destroy');
        });
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('pengalamanproyek')->group(function(){
        Route::controller(PengalamanProyekController::class)->group(function(){
            Route::get('/index','index');
            Route::post('/store','store');
            Route::get('/show/{id}','show');
            Route::put('/update/{id}','update');
            Route::delete('/destroy/{id}','destroy');
        });
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('dokumenpendukung')->group(function(){
        Route::controller(DokumenPendukungController::class)->group(function(){
            Route::get('/index','index');
            Route::post('/store','store');
            Route::get('/show/{id}','show');
            Route::put('/update/{id}','update');
            Route::delete('/destroy/{id}','destroy');
        });
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('tender')->group(function(){
        Route::controller(TenderController::class)->group(function(){
            Route::get('/index','index');
            Route::post('/store','store');
            Route::get('/show/{id}','show');
            Route::put('/update/{id}','update');
            Route::delete('/destroy/{id}','destroy');
            Route::get('/{id}/generate-pdf','generateAndSavePdf');
        });
    });
});