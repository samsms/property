<?php 
require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ExcelHelper {
public $reader;
public $spreadsheet;
public $cellcount=13;
public $rows=10;
public $writer;
public $sheet;
public $totals;
public $attetion;
public $campaign;
public $estimate;
public $preparedBy;
public $approvedBy;

public $center=['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ]];
public $align=['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'font' => [
        'underline' => true,
        'size'=>24
    ],

];
public $right=['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    ],
    'font' => [
        'bold'=>true,
        'size'=>16
    ],

];
public $linedcenter=['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    
 'borders' => [
       
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFF'],
        ],
        

    ],
       
    ];
    function __construct() {}
    function generateExcelTempMePO(){
         $spreadsheet = new Spreadsheet();
    
    $sheet = $spreadsheet->getActiveSheet();

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath('logo.png'); /* put your path and image here */
    $drawing->setCoordinates('c1');
    $sheet->setShowGridLines(false);
    $sheet->getDefaultRowDimension()->setRowHeight(20);
    $sheet->getDefaultColumnDimension()->setWidth(17);

    $spreadsheet->getDefaultStyle()->getFont()->setName('Dubai');
    $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $sheet->getColumnDimension("D")->setWidth(27.19);
    // $sheet->getColumnDimension("A")->setWidth(3.19);
  
    $sheet->getRowDimension('3')->setRowHeight(40);
    $sheet->setCellValue("a1","Faden Advertising Agency Co. ");

    $sheet->setCellValue("a3","KSA - Riyadh");
    $sheet->setCellValue("a4","P O Box 4496 Riyadh 11491");
    $sheet->setCellValue("a5","Phone +966 11 2884609   Fax +966 11 2884609 Ext 115");
    $sheet->mergeCells("D3:E4");
    $sheet->getStyle('D3:E4')->applyFromArray($this->align);
    $sheet->setCellValue("e1","PO No.");
    $sheet->setCellValue("E2","DATE");
    $sheet->getStyle("e1:E2")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'dddddd',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],

    ]);
 $sheet->getStyle("F1:F2")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
     'font' => [
        'bold'=>true
    ],
 'borders' => [
       
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFF'],
        ],
         'horizontal' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => 'FFFFFFF'],
        ],

    ],
       
    ]);
// $sheet->mergeCells("G3:H3");
// // $sheet->mergeCells("G4:H4");
// $sheet->getStyle("G3:H4")->applyFromArray($this->center);
$sheet->setCellValue("F1","he");
$sheet->setCellValue("F2","=TODAY()");
$sheet->getStyle("F2")->getNumberFormat()
->setFormatCode("mm/dd/yyyy");
$sheet->setCellValue("A7","To:");
$sheet->setCellValue("A8","Name");
$sheet->setCellValue("A9","Company");
$sheet->setCellValue("A10","Address");
$sheet->setCellValue("A11","City, State ZIP");
$sheet->setCellValue("A12","Phone & Fax");

 $sheet->mergeCells("B7:C7");
  $sheet->mergeCells("B8:C8");
    $sheet->mergeCells("B9:C9");
     $sheet->mergeCells("B10:C10");
  $sheet->mergeCells("B11:C11");
    $sheet->mergeCells("B12:C12");
$sheet->getStyle("B7:b12")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        
    ],
    'font' => [
       
        'bold'=>true
    ],

       
    ]);
$sheet->getStyle("A7")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
     'font' => [
        'bold'=>true
    ],
     'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'dddddd',
        ]
    ],
 
       
    ]);
$sheet->getStyle("A8:c12")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    //  'font' => [
    //     'bold'=>true
    // ],
 'borders' => [
       
        // 'outline' => [
        //     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //     'color' => ['argb' => 'FFFFFFF'],
        // ],
         'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFF'],
        ],

    ],
       
    ]);
$sheet->setCellValue("A14","Campaign");
$sheet->setCellValue("B14","Hanaa Coconut Oil Elec Conn");
$sheet->mergeCells("B14:D14");
$sheet->getStyle("A14:B14")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
     'font' => [
        'bold'=>true
    ],
     'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'dddddd',
        ]
    ],
 
       
    ]);
    $drawing->setOffsetX(50);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $this->sheet=$sheet;
    $this->spreadsheet=$spreadsheet;
    $this->rows=15;
    //  $fileName="s.xlsx";
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

    //     $writer->save('php://output');
       
   
   
    }
    function generateExcelTempMedia($title,$columns,$rows){
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
     $sheet->setShowGridLines(false);
      $length=count($columns)+1;
    foreach($columns as $key=>$col){
        $index=$key+1;
         // $sheet->setCellValue("B".$index,$col);
         $sheet->getCellByColumnAndRow($index+1,2)->getStyle()->applyFromArray($this->linedcenter);
        $cell= $sheet->getCellByColumnAndRow($index+1,2)->getColumn() ;
        $sheet->getCellByColumnAndRow($index+1,2)->setValue($col);
        $sheet->getColumnDimension(   $sheet->getCellByColumnAndRow($index+1,2)->getColumn() )->setAutoSize( true );
       
        //exit;
    }
    $r=2;
  foreach ($rows as $keyrow=>$row){
    $r+=1;
        foreach($row as $key=>$cols){
        $index=$key+1;
         // $sheet->setCellValue("A".$index,$col);
         // $sheet->setCellValue("A2",$col);
       
         $sheet->getCellByColumnAndRow($index+1,$r)->getStyle()->applyFromArray($this->linedcenter);
        //$cell= $sheet->getCellByColumnAndRow($index+1,$r)->getColumn() ;
        $sheet->getCellByColumnAndRow($index+1,$r)->setValue($cols);
        $sheet->getColumnDimension(   $sheet->getCellByColumnAndRow($index+1,$r)->getColumn() )->setAutoSize( true );
       
        //exit;
    }
  } 
 
  $sheet->setCellValue("B1",$title);
  $sheet->getCellByColumnAndRow($index+1,$r)->getStyle()->applyFromArray($this->linedcenter);
$c=$sheet->getCellByColumnAndRow($length,1)->getColumn()."2";
  $length=$sheet->getCellByColumnAndRow($length,1)->getColumn()."1";

  $sheet->mergeCells("B1:$length");
  $sheet->getStyle("B1:$length")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'e2efd9',
        ]
    ],
    'font' => [
       'size'=>24,
        'bold'=>true
    ],

    ]);
   $sheet->getDefaultRowDimension()->setRowHeight(24);
  $sheet->getStyle("B2:$c")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'EE888B',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],

    ]);
     $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
       $fileName="Template.xlsx";
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
     $writer->save('php://output');
     exit;
    } 
    function generateExcelTemp($columns){
         $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
   
    foreach($columns as $key=>$col){
        $index=$key+1;
         // $sheet->setCellValue("A".$index,$col);
         // $sheet->setCellValue("A2",$col);
        $cell= $sheet->getCellByColumnAndRow($index,1)->getColumn() ;
        $sheet->getCellByColumnAndRow($index,1)->setValue($col);
        $sheet->getColumnDimension(   $sheet->getCellByColumnAndRow($index,1)->getColumn() )->setAutoSize( true );
     

       
        //exit;
    }
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
       $fileName="Template.xlsx";
         header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
     $writer->save('php://output');
    } 

    function generateExcelTempWIthOPtions($columns){
      $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
   
    foreach($columns as $key=>$col){
        $index=$key+1;
         // $sheet->setCellValue("A".$index,$col);
         // $sheet->setCellValue("A2",$col);
        $cell= $sheet->getCellByColumnAndRow($index,1)->getColumn() ;
        $sheet->getCellByColumnAndRow($index,1)->setValue($col);
        $sheet->getColumnDimension(   $sheet->getCellByColumnAndRow($index,1)->getColumn() )->setAutoSize( true );
        $validation = $sheet->getDataValidation('A1:A10');
        $validation->setErrorTitle('');
        $validation->setError("Cell must be y or n");
           $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"Option 1, Option 2"');

    }
    
    
  $writer = new Xlsx($this->spreadsheet);
     $fileName="s.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

        $writer->save('php://output');
        die();

    }

    function initImport( $inputFileName){
       $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        /**  Advise the Reader that we only want to load cell data  **/
        $reader->setReadDataOnly(true);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $worksheet = $spreadsheet->getSheet(0);//
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $data = array();

    for ($row = 1; $row <= $highestRow; $row++) {
        $riga = array();
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $riga[] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        }
        if (1 === $row) {
            // Header row. Save it in "$keys".
            $keys = $riga;
            continue;
        }
        // This is not the first row; so it is a data row.
        // Transform $riga into a dictionary and add it to $data.
        $data[] = array_combine($keys, $riga);
    }  

return $data;

    }
    function initCO() {
       $spreadsheet = new Spreadsheet();
    
    $sheet = $spreadsheet->getActiveSheet();

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath('logo.png'); /* put your path and image here */
    $drawing->setCoordinates('B3');
    $sheet->setShowGridLines(false);
    $sheet->getDefaultRowDimension()->setRowHeight(20);
    $sheet->getDefaultColumnDimension()->setWidth(17);

    $spreadsheet->getDefaultStyle()->getFont()->setName('Dubai');
    $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
    $sheet->getColumnDimension("D")->setWidth(27.19);
    $sheet->getColumnDimension("A")->setWidth(3.19);
  
    $sheet->getRowDimension('3')->setRowHeight(40);
    $sheet->setCellValue("D3","Cost Estimate");
    $sheet->mergeCells("D3:E4");
    $sheet->getStyle('D3:E4')->applyFromArray($this->align);
    $sheet->setCellValue("F3","Estimate No.");
    $sheet->setCellValue("F4","DATE");
    $sheet->getStyle("F3:F4")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'dddddd',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],

    ]);
 $sheet->getStyle("F3:H4")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
     'font' => [
        'bold'=>true
    ],
 'borders' => [
       
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFF'],
        ],
         'horizontal' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => 'FFFFFFF'],
        ],

    ],
       
    ]);
$sheet->mergeCells("G3:H3");
$sheet->mergeCells("G4:H4");
$sheet->getStyle("G3:H4")->applyFromArray($this->center);
$sheet->setCellValue("G3","he");
$sheet->setCellValue("G4","=TODAY()");
$sheet->getStyle("G4")->getNumberFormat()
->setFormatCode("mm/dd/yyyy");
$sheet->setCellValue("B7","Attention :");
$sheet->setCellValue("B8","Campaign");

 $sheet->mergeCells("C7:D7");
  $sheet->mergeCells("C8:D8");
$sheet->getStyle("B7:b8")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],

    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'dddddd',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],

       
    ]);
$sheet->getStyle("B7:D8")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
     'font' => [
        'bold'=>true
    ],
 'borders' => [
       
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => 'FFFFFFF'],
        ],
         'horizontal' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            'color' => ['argb' => 'FFFFFFF'],
        ],

    ],
       
    ]);
    $drawing->setOffsetX(10);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());
    $this->sheet=$sheet;
    $this->spreadsheet=$spreadsheet;
}
function headers( $estimate,$attetion,$campaign, $preparedBy, $approvedBy){
    $this->sheet->setCellValue("G3",$estimate);
    $this->sheet->setCellValue("C7",$attetion);
    $this->sheet->setCellValue("C8",$campaign);
   $this->preparedBy=$preparedBy;
   $this->approvedBy=$approvedBy;
}
function bodyPO($title,$data){
       $row=$this->rows;
     $color=substr(md5(rand()), 0, 6);
       $this->sheet ->getStyle("A$row")
        ->getFill()
         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB("$color"); 
        $this->sheet->getStyle("A$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("A$row",$title);
     $row+=1;
    $this->sheet->setCellValue("A$row", 'QUANTITY');
    $this->sheet->getCell("B$row")->setValue('SIZE');
    $this->sheet->mergeCells("C$row:D$row");
    $this->sheet->setCellValue("c$row", 'DESCRIPTION');
    $this->sheet->setCellValue("E$row", "UNIT PRICE");
    $this->sheet->setCellValue("F$row", "TOTAL");

    $this->sheet->getStyle("A$row:F$row")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => '00b0f0',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],
       
    ]);
      $this->sheet->getStyle("A$row:F$row")->applyFromArray($this->linedcenter);
    foreach($data as $dt){

    $row+=1;
    $this->sheet->getStyle("A$row:F$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("A$row", $dt[0]);
    $this->sheet->getCell("B$row")->setValue($dt[1]);
    $this->sheet->mergeCells("c$row:D$row");
    $this->sheet->setCellValue("c$row", $dt[2]);
    $this->sheet->setCellValue("e$row", $dt[3]);
    $this->sheet->setCellValue("F$row", "=sum(A$row*E$row)");
    }
    $row+=1;
   
    $this->sheet->setCellValue("E$row", "SUB-TOTAL");
    $this->sheet->getStyle("E$row")->applyFromArray($this->right);
    $from=$this->rows+2;
    $to=$row-1;
     $this->sheet->getStyle("F$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("F$row", "=sum(F$from:F$to)");
    if($this->totals==null){
        $this->totals="=F$row";
    }
    else{
         $this->totals.="+F$row";
    }
    $this->rows=$row;
    $this->rows+=2;
     //  $writer = new Xlsx($this->spreadsheet);
     // $writer->save('name-of-the-generated-file.xlsx');
     // exit;

}
function body($title,$data){
       $row=$this->rows;
     $color=substr(md5(rand()), 0, 6);
       $this->sheet ->getStyle("B$row")
        ->getFill()
         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB("$color"); 
        $this->sheet->getStyle("B$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("B$row",$title);
     $row+=1;
    $this->sheet->setCellValue("B$row", 'Elements');
    $this->sheet->getCell("C$row")->setValue('Material Discriptopn');
    $this->sheet->mergeCells("C$row:D$row");
    $this->sheet->setCellValue("E$row", 'Size');
    $this->sheet->setCellValue("F$row", "QTY");
    $this->sheet->setCellValue("G$row", "Cost / Unit");
    $this->sheet->setCellValue("H$row", "TOTAL");
    $this->sheet->getStyle("B$row:H$row")->applyFromArray(['alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => '00b0f0',
        ]
    ],
    'font' => [
       
        'bold'=>true
    ],
       
    ]);
      $this->sheet->getStyle("B$row:H$row")->applyFromArray($this->linedcenter);
    foreach($data as $dt){

    $row+=1;
    $this->sheet->getStyle("B$row:H$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("B$row", $dt->element_name);
    $this->sheet->getCell("C$row")->setValue($dt->description);
    $this->sheet->mergeCells("C$row:D$row");
    $this->sheet->setCellValue("E$row", $dt->size);
    $this->sheet->setCellValue("F$row", $dt->tot_qty);
    $this->sheet->setCellValue("G$row", 1);
    $this->sheet->setCellValue("H$row", "=sum(F$row*G$row)");
    }
    $row+=1;
   
    $this->sheet->setCellValue("G$row", "SUB-TOTAL");
    $this->sheet->getStyle("G$row")->applyFromArray($this->right);
    $from=$this->rows+2;
    $to=$row-1;
     $this->sheet->getStyle("H$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("H$row", "=sum(H$from:H$to)");
    if($this->totals==null){
        $this->totals="=H$row";
    }
    else{
         $this->totals.="+H$row";
    }
    $this->rows=$row;
    $this->rows+=2;

}
function footerPO(){
    $row=$this->rows+3;
    $to=$row+3;
    $this->sheet->mergeCells("A$row:B$to");
    $this->sheet->setCellValue("A$row", "Prepared By :$this->preparedBy");
    $this->sheet->getStyle("A$row")->applyFromArray($this->center);
    $this->sheet->mergeCells("C$row:D$to");
    $this->sheet->setCellValue("C$row", "Approved By :
$this->approvedBy");
    $this->sheet->getStyle("C$row")->applyFromArray($this->center);
    $total=$this->totals;
     $this->sheet->getStyle("F$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("f$row", "$total");
    $this->sheet->setCellValue("e$row", "URGENT FEE");
    $this->sheet->getStyle("E$row")->applyFromArray($this->right);
    $row+=1;
    $this->sheet->getStyle("F$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("f$row", "");
    $this->sheet->setCellValue("e$row", "OTHER");
    $this->sheet->getStyle("E$row")->applyFromArray($this->right);
    $row+=1;
    $this->sheet->getStyle("F$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("E$row", "Discount");
    $this->sheet->getStyle("E$row")->applyFromArray($this->right);
    $row+=1;
    $this->sheet->getStyle("F$row")->applyFromArray($this->linedcenter);
       $this->sheet->setCellValue("f$row", "$total");
    $this->sheet->getStyle("E$row")->applyFromArray($this->right);
    $this->sheet->setCellValue("E$row", "Total");
    $from=$row-2;
    $to=$row-1;
    $this->sheet->setCellValue("F$row", "=F$from-F$to");
}
function footer(){
    $row=$this->rows+3;
    $to=$row+3;
    $this->sheet->mergeCells("B$row:C$to");
    $this->sheet->setCellValue("B$row", "Prepared By :$this->preparedBy");
    $this->sheet->getStyle("B$row")->applyFromArray($this->center);
    $this->sheet->mergeCells("D$row:E$to");
    $this->sheet->setCellValue("D$row", "Approved By :
$this->approvedBy");
    $this->sheet->getStyle("D$row")->applyFromArray($this->center);
    $total=$this->totals;
    $this->sheet->getStyle("H$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("H$row", "$total");
    $this->sheet->setCellValue("G$row", "Total");
    $this->sheet->getStyle("G$row")->applyFromArray($this->right);
    $row+=1;
    $this->sheet->getStyle("H$row")->applyFromArray($this->linedcenter);
    $this->sheet->setCellValue("G$row", "Discount");
    $this->sheet->getStyle("G$row")->applyFromArray($this->right);
    $row+=1;
    $this->sheet->getStyle("H$row")->applyFromArray($this->linedcenter);
    $this->sheet->getStyle("G$row")->applyFromArray($this->right);
    $this->sheet->setCellValue("G$row", "Total");
    $from=$row-2;
    $to=$row-1;
    $this->sheet->setCellValue("H$row", "=H$from-H$to");
}

function save(){
     $writer = new Xlsx($this->spreadsheet);
     $fileName="s.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');

        $writer->save('php://output');
       exit;
}

}

echo "hello";
$fun=new ExcelHelper();
$fun->initImport("ExcelHelper.php");


  
?>
