<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

trait PrinterTrait
{
    public function printHeaderDetails($printer, $headerDetails = []): void
    {
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->text($headerDetails['companyName'] . "\n");
        $printer->selectPrintMode();
        $printer->setEmphasis(false);
        $printer->text($headerDetails['companyAddress'] . "\n");
        $printer->text($headerDetails['companyPhone'] . "\n");
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
        $os = strtolower(php_uname('s'));
        try {
            if ($os == 'linux') {
                $subject = shell_exec("ls /dev/usb/ | grep lp");
                preg_match_all('/(lp\d)/', $subject, $match);
                if (!empty($subject) && !empty($match)) {
                    $device_url = "/dev/usb/" . $match[0][0];
                } else {
                    $device_url = "php://stdout";
                }
                $connector = new FilePrintConnector($device_url);
            } else if ($os == "windows nt") {
                $connector = new WindowsPrintConnector("smb://DESKTOP-3V4JSK2/pos_print");//Shared Printer
            } else {
                $connector = new FilePrintConnector("data.txt");
            }
        } catch (\Exception $e) {
            Log::error("Could not get the printer connector. " . $e->getMessage());
            $connector = new FilePrintConnector("data.txt");
        }
        return $connector;
    }

}
