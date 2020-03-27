<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/05/02
 * Time: 2:39 PM
 */
include "../inc/functions.php";
secure_session_start();
include_once '../../inc/database.php';
$db = new Database();
if(isset($_SESSION['admin_id'])) {
    if(isset($_POST['order_num'])){
        $order_num = $_POST['order_num'];
        $sql_order = "SELECT * FROM orders WHERE order_num = '".$order_num."'";
        $result = $db->select($sql_order);
        if(sizeof($result) == 1){
            $order_id = $result[0]['id'];
            $first_name = $result[0]['first_name'];
            $last_name = $result[0]['last_name'];
            $contact = $result[0]['contact'];
            $email = $result[0]['email'];
            $building = $result[0]['building'];
            $street = $result[0]['street'];
            $suburb = $result[0]['suburb'];
            $city = $result[0]['city'];
            $province = $result[0]['province'];
            $postal = $result[0]['postal'];
            $date = $result[0]['date_created'];
            $payment_status = $result[0]['payment_status'];
            $payment_method = $result[0]['payment_method'];
            $shipping_method = $result[0]['shipping_method'];

        }else{
            echo"error with order num";
        }

    }
}else{
    header('Location: ../../index.php');
}
?>
<div class="modal fade" id="delete_order_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4>Delete Order ?</h4>
                </div>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want to delete this order ?</h5>
                <p class="text-danger">Please note this cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-danger" id="delete_order" data-id="<?php echo $order_num ?>">Delete Order</button>
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="return_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5><b>Are you sure you want to return the order?</b></h5><br>

            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Reason for returning</label>
                    <textarea class="form-control" id="rtn_msg" rows="3" maxlength="300"></textarea>
                    <input type="hidden" class="order_num_edit" id="order_num_return" value="<?php echo $order_num; ?>">
                    <p>*Please note this cannot be undone!</p>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-outline-danger btn-sm" id="returnBtn" value="Return">
                <button class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- under Main contianer -->
<div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
    <div class="row">
        <!-- BreadCrumbs -->
        <div class="col-md-10 offset-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                <li class="breadcrumb-item"><a>Orders</a></li>
                <li class="breadcrumb-item">All orders</li>
                <li class="breadcrumb-item active" aria-current="page">View Order</li>
            </ol>
        </div>
    </div>
    <!-- Order info-->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn-secondary btn float-sm-right" id="cancel_vo">Cancel</button>

                    <button class="btn btn-danger float-sm-right"  data-toggle="modal" data-target="#delete_order_modal">Delete Order</button>
                </div>
            </div>
        </div>
    </div><br>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                   <h5 class="float-left">Order Details</h5>
                    <button class="btn btn-outline-secondary btn-sm float-right" onclick="window.open(href='orders_pdf/<?php echo $order_num; ?>.pdf')">Open Invoice</button>
                    <a href="orders_pdf/<?php echo $order_num; ?>.pdf" download  class="btn btn-outline-info btn-sm float-right" >Download Invoice</a>
                </div>
                <div class="card-body ">
                    <h6 class="float-sm-left"><b>Order No:</b><span class="order_num_edit"><?php echo $order_num; ?></span></h6>
                    <h6 class="float-sm-right"><b>Date:</b> <?php echo $date; ?></h6><br><br>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Variant</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Totals</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_product = "SELECT * FROM order_product WHERE order_number = '".$order_num."'";
                            $products = $db->select($sql_product);

                            foreach ($products as $product){
                                echo "<tr>
                                           <td>{$product['product_name']}</td>
                                           <td>{$product['variant_name']}</td>
                                           <td align='right'>R {$product['price']}</td>
                                           <td align='center'>{$product['qty']}</td>
                                           <td align='right'>R {$product['line_total']}</td>






                                        </tr>";






                            }
                                $sql_order_totals = "SELECT * FROM order_totals WHERE order_number ='".$order_num."'";
                                $totals = $db->select($sql_order_totals);
                                $subtotal = $totals[0]['subtotal'];
                                $shipping = $totals[0]['ship_total'];
                                $tax = $totals[0]['tax'];
                                $final_total = $totals[0]['final_total'];

                            ?>

                        </tbody>
                        <tfoot>

                            <tr>
                                <td colspan="3"></td>
                                <td><b>Sub total</b></td>
                                <td  align='right' >R <?php echo $subtotal ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td><b>Shipping Costs</b></td>
                                <td  align='right' >R <?php echo $shipping; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td><b><strong>Total</strong></b></td>
                                <td  align='right' >R <b><?php echo$final_total; ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Vat @ 15%</td>
                                <td  align='right' >R <?php echo $tax; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="card-body border-top d-inline-block"><?php
                    if($payment_status == 'paid'){
                       echo'
                       <i class="fa fa-check-circle text-success fa-2x pull-left"></i>
                         <h6>Payment of R '.$final_total.' by '.$payment_method.'</h6>
                       ';
                    }elseif ($payment_status == 'paid_pf'){
                        echo'<i class="fa fa-check-circle text-primary fa-2x pull-left"></i>
                         <h6>Payment of R '.$final_total.' by '.$payment_method.'</h6>';
                    }
                    else if($payment_status == 'Waiting Payment'){
                        echo'
                        <i class="fa fa-minus-circle text-warning fa-2x pull-left"></i>
                        <h6>Waiting for payment by '.$payment_method.'</h6>
                        ';
                    }else if($payment_status == 'unpaid'){
                        echo'
                        <i class="fa fa-minus-circle text-danger fa-2x pull-left"></i>
                        <h6>Payment is unpaid, payment method was '.$payment_method.'</h6>';
                    } else if($payment_status == 'returned'){
                        echo'
                        <i class="fa fa-minus-circle text-danger fa-2x pull-left"></i>
                        <h6>Order was returned</h6>';
                        

                    }echo "</div>";
                    $sql_order_status = "SELECT * FROM order_status WHERE order_num = '".$order_num."'";
                    $status = $db->select($sql_order_status);
                        if($status[0]['status'] == 'Received'){
                           echo '
                           <div class="card-body border-top d-inline-block">
                                <i class="fa fa-minus-circle text-primary fa-2x pull-left"></i>
                                <h6>Order has been recieved</h6>
                            </div>
                           ';
                        } else if ($status[0]['status'] == 'processing'){
                            echo'
                            <div class="card-body border-top d-inline-block">
                                <i class="fa fa-minus-circle text-info fa-2x pull-left"></i>
                                <h6>Order is being processed.</h6>
                            </div>
                            ';
                        } else if ($status[0]['status'] == 'delivering'){
                            echo'
                            <div class="card-body border-top d-inline-block">
                                <i class="fa fa-minus-circle text-warning fa-2x pull-left"></i>
                                <h6>Order is being delivered.</h6>
                            </div>
                            
                            ';
                        } elseif($status[0]['status'] == 'delivered' ){
                            echo'
                            <div class="card-body border-top d-inline-block">
                                <i class="fa fa-check-circle text-success fa-2x pull-left"></i>
                                <h6>Order was delivered.</h6>
                            </div>
                            ';
                        } else if($status[0]['status'] == 'returned' ){
                            echo '
                            <div class="card-body border-top d-inline-block">
                                <i class="fa fa-check-circle text-danger fa-2x pull-left"></i>
                                <h6>Order was returned.</h6>
                            </div>
                            ';
                        }



                    ?>
            </div><br>
            <div class="card">
                <div class="card-body">
                    <h5 class="float-left">Fulfillments</h5>
                    <?php
                    if($status[0]['status'] != 'returned'){

                        echo '<button type="button" class="btn btn-outline-danger btn-sm float-right" data-toggle="modal" data-target="#return_modal">Return Order</button>';

                   echo' <br><br><br>
                    <div class="dropdown show">
                        <a class="btn btn-primary btn-block dropdown-toggle" href="#" role="button" id="update_progress" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Update Progress
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <ul>
                                <li class="dropdown-item update_btn" data-update="processing">Processing</li>
                                <li class="dropdown-item update_btn" data-update="delivering">Delivering</li>
                                <li class="dropdown-item update_btn" data-update="delivered">Delivered</li>
                            </ul>
                        </div>
                    </div>';
                    }
                    ?>

                </div>
                <div class="card-body border-top bg-transparent">
                    <div class="container">
                        <div class="timeline">
                            <ul><?php

                                echo "
                                <li>
                                    <div class='timeline-date'>
                                        "; echo $status[0]['date_created']."
                                    </div>
                                    <div class='pd-20'>
                                        <h4>Order Received</h4>
                                        ";
                                        if($status[0]['channel'] == 'admin'){
                                            $sql_admin_details = "SELECT * FROM admin_user WHERE id = '".$status[0]['sales_id']."'";
                                            $admin_details = $db->select($sql_admin_details);

                                            echo"<p>Order purchased with ".$admin_details[0]['firstName']." through the admin panel.</p>
                                            ";

                                        }else{
                                            echo "<p>Order purchased through the online store and payment made with payfast</p>";
                                        }

                                        echo"
                                        
                                    </div>
                                </li>
                                ";

                                if($status[0]['processed_id'] > 0){
                                    echo '
                                    <li>
                                    <div class="timeline-date">
                                        '.$status[0]["date_processed"].'
                                    </div>
                                    <div class="pd-20">
                                        <h4>Order Processed</h4>
                                        <p>Order proccessed by ';
                                       $sql_admin_details_processed = "SELECT * FROM admin_user WHERE id = '".$status[0]['processed_id']."'";
                                        $processed_name = $db->select($sql_admin_details_processed);
                                    echo $processed_name[0]['firstName'].

                                    '</p>
                                    </div>
                                </li>
       ';
                                }
                                if($status[0]['delivery_id']>0){
                                    echo'
                                    <li>
                                    <div class="timeline-date">
                                        '.$status[0]["date_delivery"].'
                                    </div>
                                    <div class="pd-20">
                                        <h4>Order out for delivery</h4>
                                        <p>Order sent out for delivery by ';
                                    $sql_delivery_id = "SELECT * FROM admin_user WHERE id = '".$status[0]['delivery_id']."'";
                                    $deliveryName = $db->select($sql_delivery_id);
                                    echo $deliveryName[0]['firstName'].
                                    '</p>
                                    </div>
                                </li>                                   
                                    ';
                                }

                                if($status[0]['delivered_id']>0){
                                    echo'
                                    <li>
                                    <div class="timeline-date">
                                        '.$status[0]["date_delivered"].'
                                    </div>
                                    <div class="pd-20">
                                        <h4>Order delivered</h4>
                                        <p>Order process completed by';
                                    $sql_delivered_id = "SELECT * FROM admin_user WHERE id = '".$status[0]['delivery_id']."'";
                                    $deliveredName = $db->select($sql_delivery_id);
                                    echo $deliveredName[0]['firstName'].

                                    '</p>
                                    </div>
                                </li>                                   
                                    ';
                                }
                                if($status[0]['status'] == 'returned' ){
                                    $sql_order_returned = "SELECT * FROM order_returns WHERE order_num ='".$order_num."'";
                                    $returned = $db->select($sql_order_returned);
                                echo '
                                        <li>
                                        <div class="timeline-date">
                                            '.$returned[0]['date_returned'].' 
                                        </div>
                                        <div class="pd-20">
                                            <h4>Order Returned</h4>
                                            <p>'.$returned[0]['message'].'</p><br>
                                            <h5>Returned By : ';
                                    $sql_returned_id = "SELECT * FROM admin_user WHERE id ='".$returned[0]['admin_id']."'";
                                    $returnedName = $db->select($sql_returned_id);
                                    echo $returnedName[0]['firstName']

                                    .'</h5>
                                            
</div>
</li>
                                
                                
                                ';
                                }






                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5>Customer</h5>
                        <i class="fa fa-user-circle-o fa-2x float-right"></i>
                    </div><br>
                    <div>
                        <h6 class="text-blue"><?php echo $first_name." ".$last_name; ?></h6>

                    </div>
                </div>
                <div class="card-body border-top">
                    <h6><b>Contact info</b></h6><br>
                    <p><?php echo $contact ?></p>
                    <p><?php echo $email ?></p>
                </div>
                <div class="card-body border-top">
                    <h6><b>Shipping Address</b></h6><br>
                    <p>
                    <?php
                    echo $building."<br>".$street."<br>".$suburb."<br>".$city."<br>".$province."<br>".$postal."<br><br>";

                    echo "Shipping Method: ".$shipping_method;


                    ?>
                    </p>
                </div>

            </div><br>
            <?php

            if($payment_status != 'paid'){

                echo'
                <div class="card">
                    <div class="card-body">
                        <h5>Update Payment</h5><br>
                        <div class="form-group">
                            <select class="form-control" name="payment_status" id="update_payment">
                                <option value="Waiting Payment">Waiting for Payment</option>
                                <option value="paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-primary btn-sm float-right" id="payment_update_btn">Update Payment</button>
                    </div>
                </div>
                ';



            }




            ?>


        </div>
    </div>



</div>
