<?php

use App\Http\Controllers\PrintController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function (Request $request) {
    return response()->json(["status"=>"Working"]);
});

Route::post('print-receipt', [PrintController::class, 'printReceipt'])->name('print-receipt');
