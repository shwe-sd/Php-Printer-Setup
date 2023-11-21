<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/../../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;

/**
 * Install the printer using USB printing support, and the "Generic / Text Only" driver,
 * then share it (you can use a firewall so that it can only be seen locally).
 *
 * Use a WindowsPrintConnector with the share name to print.
 *
 * Troubleshooting: Fire up a command prompt, and ensure that (if your printer is shared as
 * "Receipt Printer), the following commands work:
 *
 *  echo "Hello World" > testfile
 *  copy testfile "\\%COMPUTERNAME%\Receipt Printer"
 *  del testfile
 */
try {
    // Enter the share name for your USB printer here
    // $connector = null;
    $connector = new WindowsPrintConnector("SDEpsonT88IV");

    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    // $printer -> text("Hello World!\n");
    // $printer -> cut();
    // $logo = EscposImage::load(__DIR__ . "/casio-logo-top.png");
    // $printer -> graphics($logo);
    // $printer -> barcode('012345678901');
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->setBarcodeHeight(100);
    $printer->setBarcodeWidth(1);
    $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
    $printer->barcode("SAL-001202311090001", Printer::BARCODE_CODE39);
    // $hri = array (
    //     Printer::BARCODE_TEXT_NONE => "No text",
    //     Printer::BARCODE_TEXT_ABOVE => "Above",
    //     Printer::BARCODE_TEXT_BELOW => "Below",
    //     Printer::BARCODE_TEXT_ABOVE | Printer::BARCODE_TEXT_BELOW => "Both"
    // );
    // foreach ($hri as $position => $caption) {
    //     $printer->text($caption . "\n");
    //     $printer->setBarcodeTextPosition($position);
    //     $printer->barcode("012345678901", Printer::BARCODE_JAN13);
    //     $printer->feed();
    // }
    $printer -> cut();
    
    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}
