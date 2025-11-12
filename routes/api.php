<?php

use App\Http\Controllers\PrintController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function (Request $request) {
    return response()->json(["status" => "Running"]);
});

Route::post('print-receipt', [PrintController::class, 'printReceipt'])->name('print-receipt');
Route::post('print', [PrintController::class, 'printOrderReceipt'])->name('print-order-receipt');
Route::post('print-customer-receipt', [PrintController::class, 'printCustomerReceipt'])->name('print-customer-receipt');
