<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/11
 * Time: 8:12 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {


echo'
     Page Loader modal
        <div class="modal " id="page_loader">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>';





    $admin_id = $_SESSION['admin_id'];
    $date_created = date("Y-m-d H-i-s");
    $sql_check = "SELECT order_num FROM orders ORDER BY order_num DESC ";
    $result = $db->select($sql_check);
    if(sizeof($result) == 0){
        $order_number = 100;
    }else{
        $order_number = $result[0]['order_num'] + 1;
    }
    $payment_status = "Waiting Payment";
    if(isset($_POST['final_total'])){
        // customer info
        $customer_id = mysqli_real_escape_string($con,$_POST['customer_id']);
        $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $contact = mysqli_real_escape_string($con,$_POST['contact']);
        // Ship details & payment type
        $building = mysqli_real_escape_string($con,$_POST['building']);
        $street = mysqli_real_escape_string($con, $_POST['street']);
        $suburb = mysqli_real_escape_string($con, $_POST['suburb']);
        $city = mysqli_real_escape_string($con, $_POST['city']);
        $province = mysqli_real_escape_string($con,$_POST['province']);
        $postal = mysqli_real_escape_string($con,$_POST['postal']);
        if(isset($_POST['no_shipping'])){
            $shipping_status = 'No Shipping' ;
        }else{
            $shipping_status = 'Shipping';
        }
        $payment_type = mysqli_real_escape_string($con,$_POST['payment_type']);

        //echo $customer_id.$first_name.$last_name."<br>".$email."<br>".$contact."<br>"."<br>";
    //    echo $building."<br>".$street."<br>".$suburb."<br>".$city."<br>".$province."<br>".$postal."<br>".$shipping_status."<br>".$payment_type."<br>";

        $sql_order = "INSERT INTO orders (customer_id,
                                          order_num,
                                          first_name,
                                          last_name,
                                          email,
                                          contact,
                                          building,
                                          street,
                                          suburb,
                                          city,
                                          province,
                                          shipping_method,
                                          date_created,
                                          payment_method,
                                          payment_status,
                                          created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        if($stmt= $mysqli->prepare($sql_order)){
            $stmt->bind_param('ssssssssssssssss',
            $customer_id,
            $order_number,
            $first_name,
            $last_name,
            $email,
            $contact,
            $building,
            $street,
            $suburb,
            $city,
            $province,
            $shipping_status,
            $date_created,
            $payment_type,
            $payment_status,
            $admin_id);

            if($stmt->execute()){
               $order_inserted = TRUE;
            }else{
                // failer with the execute of stmt
                echo "error with executing sql of order".$mysqli->error;;
            }
        }else{
            // failure with preparing the statment
            echo "error with preparing the statement";
        }


        //cart arrays
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $var_id = $_POST['variant_id'];
        $var_name = $_POST['variant_name'];
        $qty = $_POST['qty_'];
        $unit_price = $_POST['unit_price'];
        $line_total = $_POST['line_total'];
        // totals
        $subtotal = $_POST['subtotal'];
        $ship_total = $_POST['ship_total'];
        $tax = $_POST['tax'];
        $final_total = $_POST['final_total'];
        if(isset($_POST['channel'])){
            $channel = $_POST['channel'];
        }


        if($order_inserted){
            $sql_check_last_order_id = "SELECT id FROM orders ORDER BY id DESC ";
            $result_id = $db->select($sql_check_last_order_id);
            $order_id = $result_id[0]['id'];


            for($i=0;$i<sizeof($product_id);$i++){
                $sql_insert_product = "INSERT INTO order_product (order_id,
                                                                  order_number,
                                                                  product_id,
                                                                  product_name,
                                                                  variant_id,
                                                                  variant_name,
                                                                  price,
                                                                  qty,
                                                                  line_total) VALUES (?,?,?,?,?,?,?,?,?)";
                if($stmt= $mysqli->prepare($sql_insert_product)){
                    $stmt->bind_param('iiisisdid', $order_id,
                    $order_number,
                    $product_id[$i],
                    $product_name[$i],
                    $var_id[$i],
                    $var_name[$i],
                    $unit_price[$i],
                    $qty[$i],
                    $line_total[$i]);

                    if($stmt->execute()){
                        $order_products_inserted = TRUE;
                    }else{
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                }else{
                    // failure with preparing the statment
                    echo "error with preparing the statement";
                }
            }
        }
        if($order_products_inserted){
            $sql_order_totals = "INSERT INTO order_totals (order_id, order_number, customer_id, subtotal,ship_total, tax,final_total)VALUES(?,?,?,?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_order_totals)){
                $stmt->bind_param('iiidddd', $order_id,$order_number, $customer_id, $subtotal, $ship_total,$tax,$final_total);


                if($stmt->execute()){
                    $order_totals_inserted = TRUE;
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statment
                echo "error with preparing the statement";
            }
        }
        if($order_totals_inserted){
            $status = "Received";
            $sql_status = "INSERT INTO order_status (order_id, order_num, status, date_created, channel, sales_id)VALUES (?,?,?,?,?,?)";
            if($stmt= $mysqli->prepare($sql_status)){
                $stmt->bind_param('ssssss', $order_id, $order_number, $status, $date_created, $channel, $admin_id);


                if($stmt->execute()){
                    $totals_inserted = TRUE;
                }else{
                    // failer with the execute of stmt
                    echo "error with executing sql ";
                }
            }else{
                // failure with preparing the statement
                echo "error with preparing the statement";
            }
        }
        //$stock_updated = TRUE;
        if($totals_inserted) {


            for($i=0; $i<sizeof($product_id); $i++) {
                $sql_stock = "SELECT var_stock FROM product_variants WHERE id ='" . $var_id[$i] . "'";
                $in_stock = $db->select($sql_stock);

                $stock_left = floatval($in_stock[0]['var_stock']) - floatval($qty[$i]);
                //echo $stock_left;
               // $stock_updated = TRUE;
                $sql_stock_left = "UPDATE product_variants SET var_stock=? WHERE id = '" . $var_id[$i] . "'";

                if ($stmt = $mysqli->prepare($sql_stock_left)) {
                    $stmt->bind_param('s', $stock_left);


                    if ($stmt->execute()) {
                        $stock_updated = TRUE;
                    } else {
                        // failer with the execute of stmt
                        echo "error with executing sql ";
                    }
                } else {
                    // failure with preparing the statment
                    echo "error with preparing the statement";
                }
            }

        }

            // Creates pdf
            if($stock_updated) {
                require "../inc/fpdf181/fpdf.php";

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
                $pdf->Cell(20 ,5,'Date:',1,0);
                $pdf->Cell(39 ,5,$date_created ,1,1);

                $pdf->Cell(60,5,"",0,0);
                $pdf->Cell(70 ,5,'Dunswart',0,0);
                $pdf->Cell(20 ,5,'Invoice #',1,0);
                $pdf->Cell(39 ,5,$order_number,1,1);//end of line

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
                $pdf->Cell(100 ,5,'',0,1);//end of line

//add dummy cell at beginning of each line for indentation
                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$first_name." ".$last_name,0,1);
                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$email,0,1);
                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$contact,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$building,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$street,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$suburb,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$city,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$province,0,1);

                $pdf->Cell(10 ,5,'',0,0);
                $pdf->Cell(90 ,5,$postal,0,1);

                //make a dummy empty cell as a vertical spacer
                $pdf->Cell(189 ,10,'',0,1);//end of line

                //Invoice table Head
                $pdf->SetFont('Arial','B',12);

                $pdf->Cell(50 ,5,'Product Name',1,0, 'C');
                $pdf->Cell(50 ,5,'Variant',1,0,'C');
                $pdf->Cell(25,5,'Unit Price', 1,0, 'C');
                $pdf->Cell(30,5,'Qty', 1,0, 'C');
                $pdf->Cell(34 ,5,'Totals',1,1, 'C');//end of line

                $pdf->SetFont('Arial','',10);
                // Invoice Table body

                for($i=0;$i<sizeof($product_id);$i++){

                    $pdf->Cell(50 ,5,$product_name[$i],1,0, 'L');
                    $pdf->Cell(50 ,5,$var_name[$i],1,0,'C');
                    $pdf->Cell(25,5,$unit_price[$i], 1,0, 'R');
                    $pdf->Cell(30,5,$qty[$i], 1,0, 'C');
                    $pdf->Cell(34 ,5,number_format((float)$line_total[$i],2,',', ''),1,1, 'R');//end of line

                }


                $pdf->SetFont('Arial','',10);

                //Invoice table footer
                //summary
                $pdf->Cell(125 ,5,'',0,0);
                $pdf->Cell(30,5,'Subtotal',1,0,'R');
                $pdf->Cell(34 ,5,'R '.$subtotal,1,1,'R');//end of line

                $pdf->Cell(125 ,5,'',0,0);
                $pdf->Cell(30 ,5,'Shipping Costs',1,0,'R');
                $pdf->Cell(34 ,5,'R '.$ship_total,1,1,'R');//end of line

                $pdf->Cell(125 ,5,'',0,0);
                $pdf->Cell(30 ,5,'Tax Rate @ 15%',1,0,'R');
                $pdf->Cell(34 ,5,'R '.$tax,1,1,'R');//end of line

                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(125 ,5,'',0,0);
                $pdf->Cell(30 ,5,'Total ',1,0,'R');
                $pdf->Cell(34 ,5,'R '.$final_total,1,1,'R');//end of line

                if($payment_type == "EFT"){
                    $pdf->Cell(189 ,15,'',0,1);//end of line


                    $pdf->SetFont('Arial','BI',10);
                    $pdf->Cell(130 ,5,'Banking Details',0,1,'L');// End of line
                    $pdf->SetFont('Arial','',10);

                    $pdf->Cell(130 ,5,'Bank Account Holder: Vanita`CC',0,1,'L');
                    $pdf->Cell(130 ,5,'Bank Name: Standard Bank',0,1,'L');

                    $pdf->Cell(130 ,5,'Bank Branch Code: 051001',0,1,'L');
                    $pdf->Cell(130 ,5,'Bank Acc No: 271820241',0,1,'L');
                }
                $pdf->Cell(189 ,15,'',0,1);//end of line
                if($shipping_status == "Shipping"){
                    $pdf->Cell(59 ,5,'Delivery method: Shipping',0,1, 'L');//end of line
                    $pdf->Cell(59 ,5,'Items will be delivered in 2-3 working days',0,1);//end of line
                }else{
                    $pdf->Cell(59 ,5,'Delivery method: Collect From Store',0,1, 'L');//end of line
                    $pdf->Cell(59 ,5,'Items will be ready to collect in 2-3 working days',0,1);//end of line
                }

                $pdf->Cell(189 ,15,'',0,1);//end of line
                $pdf->Cell(189 ,15,'',0,1);//end of line



                $pdf->SetFont('Arial','I',10);
                $pdf->Cell(189 ,15,'Thank you for your business !',0,1, "C");//end of line


                $dir = "../orders/orders_pdf/";
                $file_name = $order_number;
                $format = '.pdf';


                $pdf->Output("F",$dir.$file_name.$format);
                $pdf_created = TRUE;

            }

            // Create and send email
            if($pdf_created){
                require_once '../../src/plugins/PHPMailer/src/Exception.php';
                require_once '../../src/plugins/PHPMailer/src/PHPMailer.php';
                require_once '../../src/plugins/PHPMailer/src/SMTP.php';
                $html = '';
                $html .='<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Vanita Pasta | Order Confirmation:'.$order_number.'</title>
        
    <style type="text/css">
		p{
			margin:10px 0;
			padding:0;
		}
		table{
			border-collapse:collapse;
		}
		h1,h2,h3,h4,h5,h6{
			display:block;
			margin:0;
			padding:0;
		}
		img,a img{
			border:0;
			height:auto;
			outline:none;
			text-decoration:none;
		}
		body,#bodyTable,#bodyCell{
			height:100%;
			margin:0;
			padding:0;
			width:100%;
		}
		.mcnPreviewText{
			display:none !important;
		}
		#outlook a{
			padding:0;
		}
		img{
			-ms-interpolation-mode:bicubic;
		}
		table{
			mso-table-lspace:0pt;
			mso-table-rspace:0pt;
		}
		.ReadMsgBody{
			width:100%;
		}
		.ExternalClass{
			width:100%;
		}
		p,a,li,td,blockquote{
			mso-line-height-rule:exactly;
		}
		a[href^=tel],a[href^=sms]{
			color:inherit;
			cursor:default;
			text-decoration:none;
		}
		p,a,li,td,body,table,blockquote{
			-ms-text-size-adjust:100%;
			-webkit-text-size-adjust:100%;
		}
		.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
			line-height:100%;
		}
		a[x-apple-data-detectors]{
			color:inherit !important;
			text-decoration:none !important;
			font-size:inherit !important;
			font-family:inherit !important;
			font-weight:inherit !important;
			line-height:inherit !important;
		}
		#bodyCell{
			padding:10px;
		}
		.templateContainer{
			max-width:600px !important;
		}
		a.mcnButton{
			display:block;
		}
		.mcnImage,.mcnRetinaImage{
			vertical-align:bottom;
		}
		.mcnTextContent{
			word-break:break-word;
		}
		.mcnTextContent img{
			height:auto !important;
		}
		.mcnDividerBlock{
			table-layout:fixed !important;
		}
	/*
	@tab Page
	@section Background Style
	@tip Set the background color and top border for your email. You may want to choose colors that match your company\'s branding.
	*/
		body,#bodyTable{
			/*@editable*/background-color:#ddedff;
		}
	/*
	@tab Page
	@section Background Style
	@tip Set the background color and top border for your email. You may want to choose colors that match your company\'s branding.
	*/
		#bodyCell{
			/*@editable*/border-top:0;
		}
	/*
	@tab Page
	@section Email Border
	@tip Set the border for your email.
	*/
		.templateContainer{
			/*@editable*/border:0;
		}
	/*
	@tab Page
	@section Heading 1
	@tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
	@style heading 1
	*/
		h1{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:26px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 2
	@tip Set the styling for all second-level headings in your emails.
	@style heading 2
	*/
		h2{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:22px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 3
	@tip Set the styling for all third-level headings in your emails.
	@style heading 3
	*/
		h3{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:20px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 4
	@tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
	@style heading 4
	*/
		h4{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:18px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Preheader
	@section Preheader Style
	@tip Set the background color and borders for your email\'s preheader area.
	*/
		#templatePreheader{
			/*@editable*/background-color:#4b74cc;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Preheader
	@section Preheader Text
	@tip Set the styling for your email\'s preheader text. Choose a size and color that is easy to read.
	*/
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			/*@editable*/color:#656565;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:12px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Preheader
	@section Preheader Link
	@tip Set the styling for your email\'s preheader links. Choose a color that helps them stand out from your text.
	*/
		#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{
			/*@editable*/color:#ffffff;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Header
	@section Header Style
	@tip Set the background color and borders for your email\'s header area.
	*/
		#templateHeader{
			/*@editable*/background-color:#ffffff;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:0;
		}
	/*
	@tab Header
	@section Header Text
	@tip Set the styling for your email\'s header text. Choose a size and color that is easy to read.
	*/
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:16px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Header
	@section Header Link
	@tip Set the styling for your email\'s header links. Choose a color that helps them stand out from your text.
	*/
		#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{
			/*@editable*/color:#2BAADF;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Body
	@section Body Style
	@tip Set the background color and borders for your email\'s body area.
	*/
		#templateBody{
			/*@editable*/background-color:#fffdfd;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:2px solid #EAEAEA;
			/*@editable*/padding-top:0;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Body
	@section Body Text
	@tip Set the styling for your email\'s body text. Choose a size and color that is easy to read.
	*/
		#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
			/*@editable*/color:#202020;
			/*@editable*/font-family:\'Helvetica Neue\', Helvetica, Arial, Verdana, sans-serif;
			/*@editable*/font-size:16px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Body
	@section Body Link
	@tip Set the styling for your email\'s body links. Choose a color that helps them stand out from your text.
	*/
		#templateBody .mcnTextContent a,#templateBody .mcnTextContent p a{
			/*@editable*/color:#2BAADF;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Footer
	@section Footer Style
	@tip Set the background color and borders for your email\'s footer area.
	*/
		#templateFooter{
			/*@editable*/background-color:#4b74cc;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Footer
	@section Footer Text
	@tip Set the styling for your email\'s footer text. Choose a size and color that is easy to read.
	*/
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			/*@editable*/color:#656565;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:12px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:center;
		}
	/*
	@tab Footer
	@section Footer Link
	@tip Set the styling for your email\'s footer links. Choose a color that helps them stand out from your text.
	*/
		#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{
			/*@editable*/color:#ffffff;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	@media only screen and (min-width:768px){
		.templateContainer{
			width:600px !important;
		}

}	@media only screen and (max-width: 480px){
		body,table,td,p,a,li,blockquote{
			-webkit-text-size-adjust:none !important;
		}

}	@media only screen and (max-width: 480px){
		body{
			width:100% !important;
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		#bodyCell{
			padding-top:10px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnRetinaImage{
			max-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImage{
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
			max-width:100% !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnBoxedTextContentContainer{
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupContent{
			padding:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
			padding-top:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
			padding-top:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardBottomImageContent{
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockInner{
			padding-top:0 !important;
			padding-bottom:0 !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockOuter{
			padding-top:9px !important;
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnTextContent,.mcnBoxedTextContentColumn{
			padding-right:18px !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
			padding-right:18px !important;
			padding-bottom:0 !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcpreview-image-uploader{
			display:none !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 1
	@tip Make the first-level headings larger in size for better readability on small screens.
	*/
		h1{
			/*@editable*/font-size:22px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 2
	@tip Make the second-level headings larger in size for better readability on small screens.
	*/
		h2{
			/*@editable*/font-size:20px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 3
	@tip Make the third-level headings larger in size for better readability on small screens.
	*/
		h3{
			/*@editable*/font-size:18px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 4
	@tip Make the fourth-level headings larger in size for better readability on small screens.
	*/
		h4{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Boxed Text
	@tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px.
	*/
		.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Preheader Visibility
	@tip Set the visibility of the email\'s preheader on small screens. You can hide it to save space.
	*/
		#templatePreheader{
			/*@editable*/display:block !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Preheader Text
	@tip Make the preheader text larger in size for better readability on small screens.
	*/
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Header Text
	@tip Make the header text larger in size for better readability on small screens.
	*/
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Body Text
	@tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px.
	*/
		#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Footer Text
	@tip Make the footer content text larger in size for better readability on small screens.
	*/
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}</style></head>
    <body>
		
		<span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">To ensure delivery to your inbox please add info@vanitapasta.co.za to your contact lists.</span><!--<![endif]-->
		
        <center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                       
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                            <tr>
                                <td valign="top" id="templatePreheader"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                        <tbody class="mcnTextBlockOuter">
                            <tr>
                                <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                    
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                        <tbody><tr>
                                            
                                            <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #FFFFFF;text-align: center;">
                                            
                                                <div style="text-align: right;"><a href="http://www.vanitapasta.co.za" target="_blank">View Shop</a></div>
                    
                                            </td>
                                        </tr>
                                    </tbody></table>
                                    
                                </td>
                            </tr>
                        </tbody>
</table></td>
                            </tr>
                            <tr>
                                <td valign="top" id="templateHeader"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                            <tbody class="mcnImageBlockOuter">
                                    <tr>
                                        <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                            <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                <tbody><tr>
                                                    <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                        
                                                            <a href="http://www.vanitapasta.co.za" title="" class="" target="_blank">
                                                                <img align="center" alt="" src="https://gallery.mailchimp.com/71958c77348006aa7cbbd4469/images/4ce34200-4166-44c0-8675-ea5cc2489f41.png" width="200" style="max-width:200px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                                            </a>
                                                        
                                                    </td>
                                                </tr>
                                            </tbody></table>
                                        </td>
                                    </tr>
                            </tbody>
                        </table></td>
                            </tr>
                            <tr>
                                <td valign="top" id="templateBody"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #222222;">
                        
                            <h1>Order Confirmation</h1>

                        </td>
                    </tr>
                </tbody></table>
				
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #222222;">
                        
                            <div style="text-align: right;">Order Number: '.$order_number.'<br>
                                                            Ordered on : '.$date_created.'</div>
                
                                        </td>
                                    </tr>
                                </tbody></table>
                                
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
				
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                            <tbody><tr>
                                
                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                
                                    Hi '.$first_name.',
        <br>
        Thank you for ordering from Vanita Pasta.<br>
        This email confirms your order with us. <br>You can expect delivery of your order within 2-3 working days<br>
        We will keep you up to date via email on your order status, alternatively, check your order status <a href="http://www.vanitapasta.co.za" target="_blank">here</a> on our website.
                                </td>
                            </tr>
                        </tbody></table>
                        
                    </td>
                </tr>
            </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 18px;">
                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;">
                                    <tbody><tr>
                                        <td>
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                
                            </td>
                        </tr>
                    </tbody>
                    </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                        <tbody class="mcnTextBlockOuter">
                            <tr>
                                <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                    
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                        <tbody><tr>
                                            
                                            <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                            
                                                <strong>Order Details</strong>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                        <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                        
                            '.$first_name.' '.$last_name.'<br>
                            '.$email.'<br>
                            '.$contact.'<br>
                            Payment method : '.$payment_type.'
                        </td>
                    </tr>
                </tbody></table>
				
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                        
                            <div style="text-align: justify;"><b>Shipped Address:</b><br>
                            '.$building.'<br>
                            '.$street.'<br>
                            '.$suburb.'<br>
                            '.$city.'<br>
                            '.$province.'<br>
                            '.$postal.'</div>

                        </td>
                    </tr>
                </tbody></table>
				
            </td>
        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 18px;">
                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;">
                                    <tbody><tr>
                                        <td>
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnCodeBlock">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td valign="top" class="mcnTextBlockInner">
                                <div class="mcnTextContent">
                <table width="90%">
                <tbody>';

                for($i=0;$i< sizeof($product_id);$i++){
                    // Still to update img src<img src="" width="50px" height="50px">
                    $html .='<tr>

                  <td>Img</td>
                  <td>
                    <b>'.$product_name[$i].'</b><br>
                    '.$var_name[$i].'
                
                  </td>
                  <td align="right">
                
                     R '.$unit_price[$i].' x '.$qty[$i].'<br>
                  <b>R '. number_format((float)$line_total[$i],2,',', '').'</b>
                
                  </td>

                </tr>';

                }
                $html .='
                
                
                </tbody>
                </table>
                </div>
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnCodeBlock">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td valign="top" class="mcnTextBlockInner">
                                <div class="mcnTextContent"><br>
                <hr>
                <table width="90%" style="padding-top">
                <tbody>
                <tr>
                            <td></td>
                            <td colspan="2" align="right">Subtotal</td>
                            <td align="right">R '.number_format((float)$subtotal,2,',', '').'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" align="right">Shipping</td>
                            <td align="right">R '.number_format((float)$ship_total,2,',', '').' </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" align="right">Vat (included)</td>
                            <td align="right">R '.number_format((float)$tax,2,',', '').'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2" align="right"><b>Total</b></td>
                            <td align="right">R '.number_format((float)$final_total,2,',', '').'</td>
                        </tr>
                
                
                </tbody>
                </table>
                </div>
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 18px;">
                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;">
                                    <tbody><tr>
                                        <td>
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                                
                                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                    <tbody><tr>
                                        
                                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                        
                                            <div style="text-align: center;"><span style="font-size:12px">Thank you for shopping with us.<br>
                The Vanita Pasta team</span></div>
                
                                        </td>
                                    </tr>
                                </tbody></table>
                                
                            </td>
                        </tr>
                    </tbody>
                </table></td>
                                            </tr>
                                            <tr>
                                                <td valign="top" id="templateFooter"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                    <tbody class="mcnTextBlockOuter">
                        <tr>
                            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                              
                                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                                    <tbody><tr>
                                        
                                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                        
                                            <span style="color:#FFFFFF"><span style="caret-color: #000000;">For any queries email us at info@vanitapasta.co.za or call us on&nbsp;</span>&nbsp;011 025 8033</span>
                
                <p id="u3127-11"><span style="color:#FFFFFF">5 Main Reef Rd, Boksburg North, Gauteng, South Africa</span></p>
                
                                        </td>
                                    </tr>
                                </tbody></table>
                                
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock" style="min-width:100%;">
                    <tbody class="mcnFollowBlockOuter">
                        <tr>
                            <td align="center" valign="top" style="padding:9px" class="mcnFollowBlockInner">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentContainer" style="min-width:100%;">
                    <tbody><tr>
                        <td align="center" style="padding-left:9px;padding-right:9px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnFollowContent">
                                <tbody><tr>
                                    <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                        <table align="center" border="0" cellpadding="0" cellspacing="0">
                                            <tbody><tr>
                                                <td align="center" valign="top">
                                                    
                                                        
                                                        
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                                <tbody><tr>
                                                                    <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                                        <tbody><tr>
                                                                                            
                                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                                    <a href="http://www.facebook.com" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-facebook-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                                </td>
                                                                                            
                                                                                            
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
                                                        
                                                       
                                                        
                                                        
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                                <tbody><tr>
                                                                    <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                                        <tbody><tr>
                                                                                            
                                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                                    <a href="http://instagram.com" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-instagram-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                                </td>
                                                                                            
                                                                                            
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
                                                        
                                                      
                                                        
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                                <tbody><tr>
                                                                    <td valign="top" style="padding-right:10px; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                                        <tbody><tr>
                                                                                            
                                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                                    <a href="http://www.youtube.com" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-youtube-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                                </td>
                                                                                            
                                                                                            
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
                                                        
                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" style="display:inline;">
                                                                <tbody><tr>
                                                                    <td valign="top" style="padding-right:0; padding-bottom:9px;" class="mcnFollowContentItemContainer">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentItem">
                                                                            <tbody><tr>
                                                                                <td align="left" valign="middle" style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="">
                                                                                        <tbody><tr>
                                                                                            
                                                                                                <td align="center" valign="middle" width="24" class="mcnFollowIconContent">
                                                                                                    <a href="http://plus.google.com" target="_blank"><img src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-light-googleplus-48.png" style="display:block;" height="24" width="24" class=""></a>
                                                                                                </td>
                                                                                            
                                                                                            
                                                                                        </tr>
                                                                                    </tbody></table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody></table>
                                                                    </td>
                                                                </tr>
                                                            </tbody></table>
                                                   
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
                
                            </td>
                        </tr>
                    </tbody>
                </table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                    <tbody class="mcnDividerBlockOuter">
                        <tr>
                            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 10px 18px 25px;">
                                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EEEEEE;">
                                    <tbody><tr>
                                        <td>
                                            <span></span>
                                        </td>
                                    </tr>
                                </tbody></table>
                
                            </td>
                        </tr>
                    </tbody>
                </table></td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </body>
            </html>
';





                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = "timmy.aserv.co.za";
                $mail->SMTPSecure = "ssl";
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->Username = 'no_reply@creativeplatform.co.za';
                $mail->Password = 'Noselicks101';
                $mail->isHTML(true);
                // Sent from address
                $mail->setFrom('no_reply@creativeplatform.co.za');
                $mail->addAddress(' djmwinter@gmail.com');
                $mail->Subject = 'Vanita Pasta | Order Number: '.$order_number;
                $mail->Body = "$html";

                if ($mail->send()){
                    $msg = "Your email has been sent, thank you!";
                    $client_email_sent = TRUE;
                }

                else
                    $msg = "Please try again!";
                echo $mail->ErrorInfo;

                echo $msg;



            }

            if($client_email_sent){
                //include "../../src/plugins/PHPMailer/PHPMailerAutoload.php";
                $admin_sql = "SELECT * FROM admin_user";
                $admin_details = $db->select($admin_sql);


                require_once '../../src/plugins/PHPMailer/src/Exception.php';
                    require_once '../../src/plugins/PHPMailer/src/PHPMailer.php';
                    require_once '../../src/plugins/PHPMailer/src/SMTP.php';
                foreach ($admin_details as $admins){
                    $admin_email =  '';
                    $admin_email .= '
                    <!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>New Order</title>
        
    <style type="text/css">
		p{
			margin:10px 0;
			padding:0;
		}
		table{
			border-collapse:collapse;
		}
		h1,h2,h3,h4,h5,h6{
			display:block;
			margin:0;
			padding:0;
		}
		img,a img{
			border:0;
			height:auto;
			outline:none;
			text-decoration:none;
		}
		body,#bodyTable,#bodyCell{
			height:100%;
			margin:0;
			padding:0;
			width:100%;
		}
		.mcnPreviewText{
			display:none !important;
		}
		#outlook a{
			padding:0;
		}
		img{
			-ms-interpolation-mode:bicubic;
		}
		table{
			mso-table-lspace:0pt;
			mso-table-rspace:0pt;
		}
		.ReadMsgBody{
			width:100%;
		}
		.ExternalClass{
			width:100%;
		}
		p,a,li,td,blockquote{
			mso-line-height-rule:exactly;
		}
		a[href^=tel],a[href^=sms]{
			color:inherit;
			cursor:default;
			text-decoration:none;
		}
		p,a,li,td,body,table,blockquote{
			-ms-text-size-adjust:100%;
			-webkit-text-size-adjust:100%;
		}
		.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
			line-height:100%;
		}
		a[x-apple-data-detectors]{
			color:inherit !important;
			text-decoration:none !important;
			font-size:inherit !important;
			font-family:inherit !important;
			font-weight:inherit !important;
			line-height:inherit !important;
		}
		#bodyCell{
			padding:10px;
		}
		.templateContainer{
			max-width:600px !important;
		}
		a.mcnButton{
			display:block;
		}
		.mcnImage,.mcnRetinaImage{
			vertical-align:bottom;
		}
		.mcnTextContent{
			word-break:break-word;
		}
		.mcnTextContent img{
			height:auto !important;
		}
		.mcnDividerBlock{
			table-layout:fixed !important;
		}
	/*
	@tab Page
	@section Background Style
	@tip Set the background color and top border for your email. You may want to choose colors that match your company\'s branding.
	*/
		body,#bodyTable{
			/*@editable*/background-color:#FAFAFA;
		}
	/*
	@tab Page
	@section Background Style
	@tip Set the background color and top border for your email. You may want to choose colors that match your company\'s branding.
	*/
		#bodyCell{
			/*@editable*/border-top:0;
		}
	/*
	@tab Page
	@section Email Border
	@tip Set the border for your email.
	*/
		.templateContainer{
			/*@editable*/border:0;
		}
	/*
	@tab Page
	@section Heading 1
	@tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
	@style heading 1
	*/
		h1{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:26px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 2
	@tip Set the styling for all second-level headings in your emails.
	@style heading 2
	*/
		h2{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:22px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 3
	@tip Set the styling for all third-level headings in your emails.
	@style heading 3
	*/
		h3{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:20px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Page
	@section Heading 4
	@tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
	@style heading 4
	*/
		h4{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:18px;
			/*@editable*/font-style:normal;
			/*@editable*/font-weight:bold;
			/*@editable*/line-height:125%;
			/*@editable*/letter-spacing:normal;
			/*@editable*/text-align:left;
		}
	/*
	@tab Preheader
	@section Preheader Style
	@tip Set the background color and borders for your email\'s preheader area.
	*/
		#templatePreheader{
			/*@editable*/background-color:#3182d8;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Preheader
	@section Preheader Text
	@tip Set the styling for your email\'s preheader text. Choose a size and color that is easy to read.
	*/
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			/*@editable*/color:#656565;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:12px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Preheader
	@section Preheader Link
	@tip Set the styling for your email\'s preheader links. Choose a color that helps them stand out from your text.
	*/
		#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{
			/*@editable*/color:#656565;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Header
	@section Header Style
	@tip Set the background color and borders for your email\'s header area.
	*/
		#templateHeader{
			/*@editable*/background-color:#FFFFFF;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:0;
		}
	/*
	@tab Header
	@section Header Text
	@tip Set the styling for your email\'s header text. Choose a size and color that is easy to read.
	*/
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:16px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Header
	@section Header Link
	@tip Set the styling for your email\'s header links. Choose a color that helps them stand out from your text.
	*/
		#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{
			/*@editable*/color:#2BAADF;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Body
	@section Body Style
	@tip Set the background color and borders for your email\'s body area.
	*/
		#templateBody{
			/*@editable*/background-color:#FFFFFF;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:2px solid #EAEAEA;
			/*@editable*/padding-top:0;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Body
	@section Body Text
	@tip Set the styling for your email\'s body text. Choose a size and color that is easy to read.
	*/
		#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
			/*@editable*/color:#202020;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:16px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:left;
		}
	/*
	@tab Body
	@section Body Link
	@tip Set the styling for your email\'s body links. Choose a color that helps them stand out from your text.
	*/
		#templateBody .mcnTextContent a,#templateBody .mcnTextContent p a{
			/*@editable*/color:#2BAADF;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	/*
	@tab Footer
	@section Footer Style
	@tip Set the background color and borders for your email\'s footer area.
	*/
		#templateFooter{
			/*@editable*/background-color:#FAFAFA;
			/*@editable*/background-image:none;
			/*@editable*/background-repeat:no-repeat;
			/*@editable*/background-position:center;
			/*@editable*/background-size:cover;
			/*@editable*/border-top:0;
			/*@editable*/border-bottom:0;
			/*@editable*/padding-top:9px;
			/*@editable*/padding-bottom:9px;
		}
	/*
	@tab Footer
	@section Footer Text
	@tip Set the styling for your email\'s footer text. Choose a size and color that is easy to read.
	*/
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			/*@editable*/color:#656565;
			/*@editable*/font-family:Helvetica;
			/*@editable*/font-size:12px;
			/*@editable*/line-height:150%;
			/*@editable*/text-align:center;
		}
	/*
	@tab Footer
	@section Footer Link
	@tip Set the styling for your email\'s footer links. Choose a color that helps them stand out from your text.
	*/
		#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{
			/*@editable*/color:#656565;
			/*@editable*/font-weight:normal;
			/*@editable*/text-decoration:underline;
		}
	@media only screen and (min-width:768px){
		.templateContainer{
			width:600px !important;
		}

}	@media only screen and (max-width: 480px){
		body,table,td,p,a,li,blockquote{
			-webkit-text-size-adjust:none !important;
		}

}	@media only screen and (max-width: 480px){
		body{
			width:100% !important;
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		#bodyCell{
			padding-top:10px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnRetinaImage{
			max-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImage{
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
			max-width:100% !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnBoxedTextContentContainer{
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupContent{
			padding:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
			padding-top:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
			padding-top:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardBottomImageContent{
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockInner{
			padding-top:0 !important;
			padding-bottom:0 !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockOuter{
			padding-top:9px !important;
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnTextContent,.mcnBoxedTextContentColumn{
			padding-right:18px !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
			padding-right:18px !important;
			padding-bottom:0 !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcpreview-image-uploader{
			display:none !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 1
	@tip Make the first-level headings larger in size for better readability on small screens.
	*/
		h1{
			/*@editable*/font-size:22px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 2
	@tip Make the second-level headings larger in size for better readability on small screens.
	*/
		h2{
			/*@editable*/font-size:20px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 3
	@tip Make the third-level headings larger in size for better readability on small screens.
	*/
		h3{
			/*@editable*/font-size:18px !important;
			/*@editable*/line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Heading 4
	@tip Make the fourth-level headings larger in size for better readability on small screens.
	*/
		h4{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Boxed Text
	@tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px.
	*/
		.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Preheader Visibility
	@tip Set the visibility of the email\'s preheader on small screens. You can hide it to save space.
	*/
		#templatePreheader{
			/*@editable*/display:block !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Preheader Text
	@tip Make the preheader text larger in size for better readability on small screens.
	*/
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Header Text
	@tip Make the header text larger in size for better readability on small screens.
	*/
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Body Text
	@tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px.
	*/
		#templateBody .mcnTextContent,#templateBody .mcnTextContent p{
			/*@editable*/font-size:16px !important;
			/*@editable*/line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	/*
	@tab Mobile Styles
	@section Footer Text
	@tip Make the footer content text larger in size for better readability on small screens.
	*/
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			/*@editable*/font-size:14px !important;
			/*@editable*/line-height:150% !important;
		}

}</style></head>
    <body>
		<!--*|IF:MC_PREVIEW_TEXT|*-->
		<!--[if !gte mso 9]>--><span class="mcnPreviewText" style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;">To ensure delivery to your inbox please add info@vanitapasta.co.za to your contact lists</span><!--<![endif]-->
		<!--*|END:IF|*-->
        <center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
                            <tr>
                                <td valign="top" id="templatePreheader"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	<!--[if mso]>
				<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
				<tr>
				<![endif]-->
			    
				<!--[if mso]>
				<td valign="top" width="600" style="width:600px;">
				<![endif]-->
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px;color: #FFFFFF;text-align: center;">
                        
                            Vanita Pasta | Admin Panel&nbsp;
                        </td>
                    </tr>
                </tbody></table>
				
            </td>
        </tr>
    </tbody>
</table></td>
                            </tr>
                            <tr>
                                <td valign="top" id="templateHeader"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
    <tbody class="mcnImageBlockOuter">
            <tr>
                <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                    <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                        <tbody><tr>
                            <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                
                                    
                                        <img align="center" alt="" src="https://gallery.mailchimp.com/71958c77348006aa7cbbd4469/images/4ce34200-4166-44c0-8675-ea5cc2489f41.png" width="200" style="max-width:200px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                    
                                
                            </td>
                        </tr>
                    </tbody></table>
                </td>
            </tr>
    </tbody>
</table></td>
                            </tr>
                            <tr>
                                <td valign="top" id="templateBody"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                        
                            <h1>Good Day '.$admins['firstName'].'</h1>

<p>Good News, you have a new order to attend to.<br>
<br>
To view and update this order <a href="http://www.vanitapasta.co.za" target="_blank">log in</a>.<br>
<br>
&nbsp;</p>

                        </td>
                    </tr>
                </tbody></table>
				
            </td>
        </tr>
    </tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                        
                            <div style="text-align: left;"><span style="font-size:20px"><strong>Order Details</strong></span></div>

                        </td>
                    </tr>
                </tbody></table>
				<!--[if mso]>
				</td>
				<![endif]-->
                
				<!--[if mso]>
				<td valign="top" width="300" style="width:300px;">
				<![endif]-->
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:300px;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                        
                            <div style="text-align: right;">Order Number: '.$order_number.'<br>
								Date: '.$date_created.'</div>

                        </td>
                    </tr>
                </tbody></table>
				
            </td>
        </tr>
    </tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
    <tbody class="mcnDividerBlockOuter">
        <tr>
            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 18px;">
                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;">
                    <tbody><tr>
                        <td>
                            <span></span>
                        </td>
                    </tr>
                </tbody></table>
<!--            
-->
            </td>
        </tr>
    </tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnCodeBlock">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner">
                <div class="mcnTextContent">


<table width="90%">
<tbody>
';   for($i=0;$i< sizeof($product_id);$i++){
                        // Still to update img src<img src="" width="50px" height="50px">
                        $admin_email .='<tr>

                  <td></td>
                  <td>
                    <b>'.$product_name[$i].'</b><br>
                    '.$var_name[$i].'
                
                  </td>
                  <td align="right">
                
                     R '.$unit_price[$i].' x '.$qty[$i].'<br>
                  <b>R '. number_format((float)$line_total[$i],2,',', '').'</b>
                
                  </td>

                </tr>';

                    }

                $admin_email .='

</tbody>
</table><hr>
<table width="90%">
<tbody>
<tr>
<td align="right">Subtotal: R '.number_format((float)$subtotal,2,',', '').'</td>
</tr>
<tr>
<td align="right"> Shipping:R '.number_format((float)$ship_total,2,',', '').' </td>
</tr>
<tr>
<td align="right">Tax (included): R '.number_format((float)$tax,2,',', '').'</td>
</tr><tr>
<td align="right"><b>Total: R '.number_format((float)$final_total,2,',', '').'</b></td>
</tr>
</tbody>
</table>

</div>
            </td>
        </tr>
    </tbody>
</table></td>
                            </tr>
                            <tr>
                                <td valign="top" id="templateFooter"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
    <tbody class="mcnTextBlockOuter">
        <tr>
            <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
              	
			
                <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                    <tbody><tr>
                        
                        <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                        
                            <em>Copyright © Vanita Pasta, All rights reserved.</em><br>
<br>
<br>
<br>
<br>
<br>
&nbsp;
                        </td>
                    </tr>
                </tbody></table>
				
            </td>
        </tr>
    </tbody>
</table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
    <tbody class="mcnDividerBlockOuter">
        <tr>
            <td class="mcnDividerBlockInner" style="min-width: 100%; padding: 10px 18px 25px;">
                <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EEEEEE;">
                    <tbody><tr>
                        <td>
                            <span></span>
                        </td>
                    </tr>
                </tbody></table>
          
               
            </td>
        </tr>
    </tbody>
</table></td>
                            </tr>
                        </table>
						
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>

                    
                    
                    
                    
                    
                    ';

                    

                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = "timmy.aserv.co.za";
                    $mail->SMTPSecure = "ssl";
                    $mail->Port = 465;
                    $mail->SMTPAuth = true;
                    $mail->Username = 'no_reply@creativeplatform.co.za';
                    $mail->Password = 'Noselicks101';
                    $mail->isHTML(true);
                    // Sent from address
                    $mail->setFrom('no_reply@creativeplatform.co.za');
                    $mail->addAddress($admins["email"]);
                    $mail->Subject = 'Vanita Pasta | Order Number: '.$order_number;
                    $mail->Body = "$admin_email";

                    if ($mail->send()){
                        $msg = "Your admin email has been sent, thank you!";
                        header("location:../orders/all_orders.php");
                    }

                    else
                        $msg = "Please try again!";
                    echo $mail->ErrorInfo;

                    echo $msg;



                }




            }
        }










}else{
    header('Location: ../../index.php');
};

