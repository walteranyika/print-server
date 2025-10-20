<?php

namespace App\Http\Controllers;

use App\Custom\PrintableItem;
use App\Traits\PrinterTrait;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    use PrinterTrait;

    public function printReceipt(Request $request)
    {
        $request->validate([
            'details' => 'required|array|min:1',
            'details.*.product' => 'required|string|max:255',
            'details.*.price' => 'required|numeric|min:0',
            'details.*.quantity' => 'required|numeric|min:1',

            'user' => 'required|string|max:100',
            'barcode' => 'required|string|max:50',
            'total' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'headerDetails' => 'required|array',
            'headerDetails.companyName' => 'required|string|max:255',
            'headerDetails.companyAddress' => 'required|string|max:255',
            'headerDetails.companyPhone' => [
                'required',
                'regex:/^(?:\+?\d{1,3})?[ -]?\d{6,15}$/'
            ],
            'headerDetails.till' => 'required|string|max:50',
        ]);

        $details = $request->details;
        $user = $request->user;
        $order_date = date("Y-m-d H:i A");;
        $barcode = $request->barcode;
        $total = (float)$request->total;
        $headerDetails = $request->headerDetails;
        $discount = $request->discount ?? 0;
        $till = $request->till ?? '';
        $customer = $request->customer ?? 'Walk-In Customer';
        $this->printSalesReceipt($details, $user, $order_date, $barcode, $total, $headerDetails, $discount, $till,  $customer);
        return response()->noContent();
    }

    private function printSalesReceipt($details, $user, $order_date, $barcode, $total, $headerDetails, $discount = 0, $till = '',  $customer = 'Walk-In Customer')
    {
        $connector = $this->getPrintConnector();
        $printer = new Printer($connector);
        $this->printHeaderDetails($printer, $headerDetails);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->feed();
        $printer->setEmphasis(false);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($order_date."\n");
        //title of the receipt
        $printer->text("Sales Receipt No. $barcode\n");
        $printer->text("For $customer\n");
        $printer->feed();

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $heading = str_pad("Qty", 5, ' ') . str_pad("Item", 25, ' ') . str_pad("Price", 9, ' ', STR_PAD_LEFT) . str_pad("Total", 9, ' ', STR_PAD_LEFT);
        $printer->setEmphasis(false);
        $printer->text("$heading\n");
        $printer->text(str_repeat(".", 48) . "\n");
        //Print product details

        foreach ($details as $key => $value) {
            $product = new PrintableItem($value['product'], $value['price'], $value['quantity']);
            $printer->text($product->getPrintatbleRow());
        }

        $printer->text(str_repeat(".", 48) . "\n");
        $printer->setTextSize(1, 1);
        $subtotal = str_pad("\nSubtotal", 36, ' ') . str_pad(number_format($total), 12, ' ', STR_PAD_LEFT);
        $discount = str_pad("\nDiscount", 36, ' ') . str_pad(number_format($discount), 12, ' ', STR_PAD_LEFT);

        $printer->selectPrintMode();

        $total = str_pad("\nGRAND TOTAL", 36, ' ') . str_pad(number_format($total), 12, ' ', STR_PAD_LEFT);

        $printer->text($subtotal);
        $printer->text($discount);


        $printer->setEmphasis(true);
        $printer->text($total);
        $printer->selectPrintMode();


        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);


        $printer->feed(2);

        $this->printFooterInfo($printer, $till);

        $printer->feed();
        $printer->text("Goods once sold are not re-accepted\n");

        $printer->feed();

        $printer->text("Thank You and Come Again!\n");
        $printer->setBarcodeHeight(80);
        $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
        $barcode = str_replace('/', '', $barcode);
        $barcode = str_replace('_', '', $barcode);
        $printer->barcode($barcode);
        $printer->feed();

        $names = "Served By " . $user . "\n";
        $printer->text($names);
        $printer->feed();
        $contact = "System By Chui POS 0719247956\n";
        $printer->text($contact);

        $printer->feed();
        $printer->cut();
        //open drawer
        $printer->close();
    }

}
