<?php
/* Change to the correct path if you copy this example! */
require __DIR__ . '/../../vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
// use jc21\CliTable;
header("Access-Control-Allow-Origin: *");

// Allow the following HTTP methods (you can adjust this based on your needs)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow the following headers to be included in the actual request (you can adjust this based on your needs)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Allow credentials (cookies, HTTP authentication) to be included in the request
header("Access-Control-Allow-Credentials: true");

// Set the response content type to JSON (adjust as needed)
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data === null) {
        echo json_encode(['error' => 'Invalid JSON data']);
    } else {
        echo json_encode(['message' => 'Request received successfully', 'data' => $data]);
        // $jsonData = '[
        //     {
        //         "type": "text",
        //         "align": "center",
        //         "data": "Some of the text will be shown here. Checking with the another line"
        //     },
        //     {
        //         "type": "text",
        //         "align": "left",
        //         "data": "Some of the text will be shown here. Checking with the another line"
        //     },
        //     {
        //         "type": "line"
        //     },
        //     {
        //         "type": "tab-2",
        //         "data": [
        //             {
        //                 "left": "Some left text",
        //                 "right": "Some right text"
        //             },
        //             {
        //                 "left": "Some left text with a long description",
        //                 "right": "Some right text"
        //             }
        //         ]
        //     }
        // ]';
        

        sendCommandsToPrinter($json);

        
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function listingTable($jsonTable) {
    $data = $jsonTable;
    // $data = [
    //     ["Qty", "Description", "Price"],
    //     ['5x', "Lorem ipsum dolor sit amet, consectetur adipiscing elit.", "$251.00"],
    //     ['2x', "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "$10.50"],
    //     ['3x', "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.", "$15.75"],
    // ];
    
    // Create a ConsoleOutput instance to format and display the table
    $output = new ConsoleOutput();
    
    // Create a table instance with no borders
    $table = new Table($output);
    $table->setStyle('compact');
    
    // Set the column widths and spacing
    $table->setColumnWidths([4, 27, 8]);
    // Remove the headers from the data
    // array_shift($data);
    
    // Add rows to the table
    foreach ($data as $row) {
        // Right-align the 'Price' column (index 2) and break the 'Description' column
        $formattedRow = [
            $row[0],
            wordwrap($row[1], 27, "\n", true),
            str_pad($row[2], 8, ' ', STR_PAD_LEFT),
        ];
        
        $table->addRow($formattedRow);
    }
    
    $retList = $table->render();
    
    print_r($retList);
    return $retList;
}

function listingTable2($jsonTable) {
    $data = $jsonTable;

    $output = new ConsoleOutput();
    
    // Create a table instance with no borders
    $table = new Table($output);
    $table->setStyle('compact');
    
    // Set the column widths and spacing
    $table->setColumnWidths([30, 10]);
    foreach ($data as $row) {
        // Right-align the 'Price' column (index 2) and break the 'Description' column
        $formattedRow = [
            // '',
            wordwrap($row[0], 30, "\n", true),
            // str_pad($row[0], 20, ' ', STR_PAD_RIGHT),
            str_pad($row[1], 10, ' ', STR_PAD_LEFT),
        ];
        
        $table->addRow($formattedRow);
    }
    
    $retList = $table->render();
    
    print_r($retList);
    return $retList;
}

function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
{
    $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
    $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

    $leftLines = explode("\n", $leftWrapped);
    $rightLines = explode("\n", $rightWrapped);
    $allLines = array();
    for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
        $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
        $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
        $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
    }
    // return implode($allLines, "\n") . "\n";
    return implode("\n", $allLines);
    // return $allLines;
}

function sendCommandsToPrinter($json) {
    try {
        
        // Loop through the lines
        // Define the text to be center-aligned
        // Set text justification to left (default)

        $data = json_decode($json, true);

        if ($data === null) {
            // JSON parsing error
            return;
        }

        $connector = new WindowsPrintConnector("SDEpsonT88IV");
        $printer = new Printer($connector);
        $logo = EscposImage::load(__DIR__ . "/casio-logo-top.png");
        $printer -> graphics($logo);
        foreach ($data as $item) {
            $type = '';
            $toPrint = '';
            if(gettype($item) === 'string') {
                $type = 'text';
                $alignment = Printer::JUSTIFY_LEFT;
                $toPrint = $item;
            } else {
                if(array_key_exists('text', $item)) {
                    $type = 'text';
                } else if(array_key_exists('canvas', $item)) {
                    $type = 'line';
                    $alignment = Printer::JUSTIFY_CENTER;
                }

                echo 'something ' . $item;
                print_r($item);

                if(array_key_exists('alignment', $item) && $type == 'text') {
                    $alignment = $type === 'text' ? (
                        $item['alignment'] === 'center' ? Printer::JUSTIFY_CENTER :
                        ($item['alignment'] === 'left' ? Printer::JUSTIFY_LEFT : Printer::JUSTIFY_RIGHT)
                    ) : Printer::JUSTIFY_LEFT; // Default alignment for non-text types
                } else if(array_key_exists('columns', $item)) {
                    $type = 'tab-2';
                    $alignment = Printer::JUSTIFY_CENTER;
                }
            }

            
            

            $printer->setJustification($alignment);

            if ($type === 'text') {
                if(gettype($item) === 'string') {
                    $printer->text($item . "\n");
                } else {
                    if($item['text'] != '') {
                        $printer->text($item['text'] . "\n");
                    }
                }
                // $printer->text(gettype($item) === 'string'? 'what is that': $item['text'] . "\n");
            } elseif ($type === 'line') {
                $printer->text("------------------------------------------\n");
            } elseif ($type === 'tab-2') {
                $row = $item['columns'];
                if($row[0]['text'] == 'Qty') {
                    // $left = $row[1]['text'];
                    // $right = $row[2]['text'];
                    // $tabularText = columnify($left, $right, 22, 22);
                    // $printer->text($tabularText);
                    // $preJson = [
                    //     ["Qty", "Description", "Price"],
                    //     ['5x', "Lorem ipsum dolor sit amet, consectetur adipiscing elit.", "$251.00"],
                    //     ['2x', "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "$10.50"],
                    //     ['3x', "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.", "$15.75"],
                    // ];
                    $preJson = [
                        [$row[0]['text'], $row[1]['text'], $row[2]['text']]
                    ];
                    $dataRows = listingTable($preJson);
                    foreach ($dataRows as $item) {
                        $printer->text($item);
                    }
                } elseif ($row[0]['width'] == '*') {
                    $preJson = [
                        [$row[0]['text'], $row[1]['text']]
                    ];
                    $dataRows = listingTable2($preJson);
                    foreach ($dataRows as $item) {
                        $printer->text($item);
                    }
                } else {
                    // $left = $row[0]['text'] . 'x ' . $row[1]['text'];
                    // $right = $row[2]['text'];
                    // $tabularText = columnify($left, $right, 22, 22);
                    // $printer->text($tabularText);
                    // $preJson = [
                    //     ["Qty", "Description", "Price"],
                    //     ['5x', "Lorem ipsum dolor sit amet, consectetur adipiscing elit.", "$251.00"],
                    //     ['2x', "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.", "$10.50"],
                    //     ['3x', "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.", "$15.75"],
                    // ];
                    $preJson = [
                        [$row[0]['text'], $row[1]['text'], $row[2]['text']]
                    ];
                    $dataRows = listingTable($preJson);
                    foreach ($dataRows as $item) {
                        $printer->text($item);
                    }
                }
                
            }
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->setBarcodeHeight(100);
            // $printer->setBarcodeWidth(1);
            // $printer->setBarcodeTextPosition(Printer::BARCODE_TEXT_BELOW);
            // $printer->barcode("SAL-001202311090001", Printer::BARCODE_CODE39);
        }
        
        $printer -> cut();
        
        // /* Close printer */
        $printer -> close();
    } catch (Exception $e) {
        echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
    }
}

// function arrayToString($name, $price)
// {
//     $rightCols = 10;
//     $leftCols = 38;
//     // if ($this -> dollarSign) {
//     //     $leftCols = $leftCols / 2 - $rightCols / 2;
//     // }
//     $left = str_pad($name, $leftCols) ;
    
//     // $sign = ($this -> dollarSign ? '$ ' : '');
//     $sign = '$';
//     $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
//     return "$left$right\n";
// }
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }
    
    public function toStrFormat()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;
        
        $sign = ($this -> dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}

// [{"canvas":[
//     {"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,4]},
//     {"text":"G-SHOCK CASIO\n200 Victoria Street #01-11\nBugis Junction S188021\nTel: 6808 1703\nCompany Reg. No. : 197200980M","alignment":"center"},
//     {"alignment":"center"},
//     {"text":"","margin":[0,6]},
//     {"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,6]},
//     "Sales No : SAL-001202311070009",
//     "Status : Paid",
//     "Register : POS01",
//     "Date : 2023-11-07 10:32:23",
//     "User : Chong Wei Ting",
//     {"columns":[
//         {"width":30,"text":"Qty"},
//         {"width":"*","text":"Items"},
//         {"width":"auto","text":"Amount"}],"columnGap":5},
//     {"text":"","margin":[0,4]},
//     {"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,2]},
//     {"columns":[{"width":30,"text":2},{"width":"*","text":"EX-FR100BKEKB @629.00\nActive Selfie Series (Black)"},{"width":"auto","text":"$1258.00","alignment":"bottom"}],"columnGap":5},
//     {"columns":[{"width":30,"text":-1},{"width":"*","text":"EX-FR10GNSETFKA @449.00\nActive Selfie Series (Green)"},{"width":"auto","text":"$-449.00","alignment":"bottom"}],"columnGap":5},
//     {"columns":[{"width":30,"text":-1},{"width":"*","text":"EX-FR100BKEKB @629.00\nActive Selfie Series (Black)"},{"width":"auto","text":"$-629.00","alignment":"bottom"}],"columnGap":5},
//     {"columns":[{"width":30,"text":1},{"width":"*","text":"EX-FR10GNSETFKA @449.00\nActive Selfie Series (Green)"},{"width":"auto","text":"$449.00","alignment":"bottom"}],"columnGap":5},
//     {"text":"","margin":[0,4]},
//     {"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,2]},
//     {"columns":[{"width":"*","text":"Sub Total: "},{"width":"auto","text":"$1707.00"}],"columnGap":5},
//     {"columns":[{"width":"*","text":"GST 8% (Incl.)"},{"width":"auto","text":"$126.45"}],"columnGap":5},"",
//     {"columns":[{"width":"*","text":"Total: "},{"width":"auto","text":"$629.00"}],"columnGap":5},
//     {"columns":[{"width":"*","text":"Received Amount: "},{"width":"auto","text":"$629.00"}],"columnGap":5},
//     {"columns":[{"width":"*","text":"Change Amount: "},{"width":"auto","text":"$0.00"}],"columnGap":5},
//     {"text":"","margin":[0,4]},
//     {"text":"CONSUMER PROTECTION TEXT MOD","alignment":"center"},
//     {"text":"","margin":[0,4]},
//     {"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},{"text":"","margin":[0,2]},{"text":"CONSUMER PROTECTION TEXT","alignment":"center"},{"text":"","margin":[0,2]},{"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},{"text":"","margin":[0,2]}]

// [{"canvas":[
//     {"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,4]},
//     {"text":"G-SHOCK CASIO\n200 Victoria Street #01-11\nBugis Junction S188021\nTel: 6808 1703\nCompany Reg. No. : 197200980M","alignment":"center"},
//     {"alignment":"center"},
//     {"text":"","margin":[0,6]},
//     {"canvas":[{"type":"line","x1":0,"y1":0,"x2":240,"y2":0,"dash":{"length":5,"space":1}}]},
//     {"text":"","margin":[0,6]},
//     "Sales No : SAL-001202311070009",
//     "Status : Paid",
//     "Register : POS01",
//     "Date : 2023-11-07 10:32:23",
//     "User : Chong Wei Ting",
//     {"columns":[
//         {"width":30,"text":"Qty"},
//         {"width":"*","text":"Items"},
//         {"width":"auto","text":"Amount"}],"columnGap":5},
// ]
?>


