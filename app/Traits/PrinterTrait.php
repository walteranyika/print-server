<?php

namespace App\Traits;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

trait PrinterTrait
{
    public function printHeaderDetails($printer, $headerDetails = []): void
    {
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> setFont(Printer::FONT_B);
        $printer -> setTextSize(2, 2);
        // $printer->setEmphasis(true);
        $printer->text($headerDetails['companyName']."\n");
        $printer->selectPrintMode();
        $printer->feed();
        $printer->setEmphasis(true);
        $printer->text($headerDetails['companyAddress']."\n");
        $printer->text("www.olukuluguesthouse.co.ke\n");
        $printer->text("Phone : ".$headerDetails['companyPhone']."\n");
        $printer->text("KRA PIN : P052256969U\n");
    }

    public function printFooterInfo($printer, $till): void
    {
        if (!empty($till)) {
            $printer->setEmphasis(true);
            $printer->text($till);
            $printer->feed();
            $printer->setEmphasis(false);
        }
    }

    private function getPrintConnector(): WindowsPrintConnector|FilePrintConnector|null
    {
        //$connector = new WindowsPrintConnector("smb://DESKTOP-3V4JSK2/pos_print");//Shared Printer
        //$connector = new FilePrintConnector("php://stdout");
        $connector = new FilePrintConnector("data.txt");
        return $connector;
    }

}
