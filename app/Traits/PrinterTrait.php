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

        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> setFont(Printer::FONT_B);
        $printer -> setTextSize(2, 2);
        // $printer->setEmphasis(true);
        $printer->text($headerDetails['companyName']."\n");
        $printer->selectPrintMode();
        $printer->setEmphasis(true);
        $printer->text($headerDetails['companyAddress']."\n");
        $printer->text("www.olukuluguesthouse.co.ke\n");
        $printer->text("Phone : ".$headerDetails['companyPhone']."\n");
        $printer->text("KRA PIN : P052256969U\n");
    }

    public function printFooterInfo($printer, $till="4455852"): void
    {
        $printer->setEmphasis(true);
        $printer->feed();
        $printer->text("MPESA TILL. ".$till." : MMH GUEST HOUSE\n");
       // $printer->text("PAYBILL BUSINESS NO. 522533 ACCOUNT NO. 7594825\n");
        $printer->setEmphasis(false);
    }

    private function getPrintConnector(){
        $connector = null;
        $os= strtolower(php_uname('s'));
        try{
            if($os=='linux'){
                $subject=shell_exec("ls /dev/usb/ | grep lp");
                preg_match_all('/(lp\d)/', $subject, $match);
                if(!empty($subject) && !empty($match)){
                    $device_url = "/dev/usb/".$match[0][0];
                }else{
                    $device_url= "php://stdout";
                }
                $connector = new FilePrintConnector($device_url);
                //$connector = new FilePrintConnector("/dev/usb/lp0");
                //$connector = new FilePrintConnector("php://stdout");
                //$connector = new NetworkPrintConnector("10.x.x.x", 9100);
                //$connector = new FilePrintConnector("data.txt");
            }else if($os=="windows nt"){
                $printerName = config('app.printer_name');
                $connector = new WindowsPrintConnector("smb://$printerName");
            }else{
                $connector = new FilePrintConnector("data.txt");
            }
        }catch (\Exception $e){
            Log::error("Could not get the printer connector. ". $e->getMessage());
        }
        return $connector;
    }

   /* private function getPrintConnector(): WindowsPrintConnector|FilePrintConnector|null
    {
        //$connector = new WindowsPrintConnector("smb://DESKTOP-3V4JSK2/pos_print");//Shared Printer
        //$connector = new FilePrintConnector("php://stdout");
       // $connector = new FilePrintConnector("data.txt");
        return $connector;
    }*/

}
