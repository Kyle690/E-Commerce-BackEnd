<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/14
 * Time: 8:25 PM
 */
require "../inc/fpdf181/fpdf.php";
$order_num = 101;
 $pdf = new FPDF();
 $pdf->AddPage();

 $pdf->SetFont("Arial","B","20");
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )
$logo = "../../img/Vanita_Logo.png";

$pdf->Image($logo, 10,7,50,35 );
$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'',0,0);
$pdf->Cell(59 ,5,'',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',10);
$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Unit 10 Main Reef Park',0,0);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(59 ,5,'Tax Invoice',0,1);//end of line


$pdf->SetFont('Arial','',10);
$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70,5,'5 Main Reef Road',0,0);
$pdf->Cell(25 ,5,'Date:',1,0);
$pdf->Cell(34 ,5,'[dd/mm/yyyy]',1,1);

$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Dunswart',0,0);
$pdf->Cell(25 ,5,'Invoice #',1,0);
$pdf->Cell(34 ,5,'[1234567]',1,1);//end of line

$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Boksburg North',0,0);
$pdf->Cell(34 ,5,'',0,1);//end of line

$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Vat No: 4130279518',0,0);
$pdf->Cell(34 ,5,'',0,1);//end of line

$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Tel: 011 025 8033/ 072 719 6461',0,0);
$pdf->Cell(34 ,5,'',0,1);//end of line

$pdf->Cell(60,5,"",0,0);
$pdf->Cell(70 ,5,'Email: sales@vanitapasta.co.za',0,0);
$pdf->Cell(34 ,5,'',0,1);//end of line





$pdf->Cell(34 ,5,'',0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//billing address
$pdf->Cell(100 ,15,'',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Name]',0,1);
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Email]',0,1);
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Contact Number]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Building address]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[ Street Address]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Suburb]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[City]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Province]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Postal]',0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(50 ,5,'Product Name',1,0, 'C');
$pdf->Cell(50 ,5,'Variant',1,0,'C');
$pdf->Cell(25,5,'Unit Price', 1,0, 'C');
$pdf->Cell(30,5,'Qty', 1,0, 'C');
$pdf->Cell(34 ,5,'Totals',1,1, 'C');//end of line

$pdf->SetFont('Arial','',10);

//Numbers are right-aligned so we give 'R' after new line parameter

$pdf->Cell(130 ,5,'UltraCool Fridge',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'3,250',1,1,'R');//end of line

$pdf->Cell(130 ,5,'Supaclean Diswasher',1,0 );
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'1,200',1,1,'R');//end of line

$pdf->Cell(130 ,5,'Something Else',1,0);
$pdf->Cell(25 ,5,'-',1,0);
$pdf->Cell(34 ,5,'1,000',1,1,'R');//end of line


$sub_total = '4 450';
//summary
$pdf->Cell(125 ,5,'',0,0);
$pdf->Cell(30,5,'Subtotal',1,0,'R');
$pdf->Cell(34 ,5,'R '.$sub_total,1,1,'R');//end of line

$pdf->Cell(125 ,5,'',0,0);
$pdf->Cell(30 ,5,'Shipping Costs',1,0,'R');
$pdf->Cell(34 ,5,'R 0',1,1,'R');//end of line

$pdf->Cell(125 ,5,'',0,0);
$pdf->Cell(30 ,5,'Tax Rate @ 15%',1,0,'R');
$pdf->Cell(34 ,5,'R ',1,1,'R');//end of line

$pdf->SetFont('Arial','B',12);
$pdf->Cell(125 ,5,'',0,0);
$pdf->Cell(30 ,5,'Total ',1,0,'R');
$pdf->Cell(34 ,5,'R 4,450',1,1,'R');//end of line

$pdf->Cell(189 ,15,'',0,1);//end of line


$pdf->SetFont('Arial','BI',10);
$pdf->Cell(130 ,5,'Banking Details',0,1,'L');// End of line
$pdf->SetFont('Arial','',10);

$pdf->Cell(130 ,5,'Bank Account Holder: Vanita`CC',0,0,'L');
$pdf->Cell(59 ,5,'Delivery method:',0,1);//end of line
$pdf->Cell(130 ,5,'Bank Name: Standard Bank',0,0,'L');
// If shipping
$pdf->Cell(59 ,5,'Shipping',0,1);//end of line
$pdf->Cell(130 ,5,'Bank Branch Code: 051001',0,0,'L');
$pdf->Cell(59 ,5,'Items will be delivered in 3-5 working days',0,1);//end of line
$pdf->Cell(130 ,5,'Bank Acc No: 271820241',0,1,'L');


$pdf->Cell(189 ,15,'',0,1);//end of line
$pdf->Cell(189 ,15,'',0,1);//end of line



$pdf->SetFont('Arial','I',10);
$pdf->Cell(189 ,15,'Thank you for your business !',0,1, "C");//end of line

$dir = "../orders/orders_pdf/";
$file_name = $order_num;
$format = '.pdf';


$pdf->Output();
 //$pdf->Output('F',$dir.$order_num.$format);