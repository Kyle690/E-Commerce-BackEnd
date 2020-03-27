<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/09
 * Time: 3:19 PM
 */
include ("../inc/sub_head.php");
// Top Nav bar
include("../inc/sub_top_nav_bar.php");
//side_nav
include ('../inc/sub_side_nav_bar.php');
?>
// Browse product modal
<div class="modal fade" id="browse_products">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Select some products</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <table class="table  table-stripped">
                            <thead>
                                <th colspan="2">Name</th>
                                <th>Variant</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                        <?php
                        $sql = "SELECT * FROM products WHERE status='enabled'";
                        $products = $db->select($sql);
                        if(sizeof($products)>0){

                            foreach ($products as $product_info){
                                echo"
                                    <tr>
                                    <td><img height='50px' width='50px' class='img-fluid' src='http://192.168.64.2/vanita_store/storefront/img/product_img/{$product_info['main_img']}'></td>
                                    <td>{$product_info['title']}</td>
                                    <td><select class='form-control var_select'>
                                            ";
                                            $sql_var = "SELECT * FROM product_variants WHERE product_id = '".$product_info['id']."'";
                                            $var = $db->select($sql_var);
                                            $first_price = $var[0]['var_price'];
                                            $first_sku = $var[0]['var_sku_code'];
                                            $first_id = $var[0]['id'];
                                            $first_name = $var[0]['var_name'];
                                            foreach ($var as $var_info){
                                            echo "<option  data-price='{$var_info['var_price']}' data-stockCode = '{$var_info['var_sku_code']}' data-varid = '{$var_info['id']}' data-varName = '{$var_info['var_name']}' >{$var_info['var_name']}</option>";
                                            }
                                            echo"
                                        </select>
                                    </td>
                                    <td align='right'><h6>R <span class='display_price'>{$first_price}</span></h6></td>
                                    <td><button class='btn btn-primary btn-sm float-right productItem' data-price='{$first_price}' data-variant='{$first_name}' data-skucode ='{$first_sku}' data-id='{$product_info['id']}' data-variant_id ='{$first_id}' data-name='{$product_info['title']}'>Add to order</button></td>
                                </tr>
                                ";
                            }




                        }else{
                            echo "
                        <tr class='text-center'><td colspan='4'><p>There are no products loaded yet!</td></tr>";
                    }

                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success btn-sm" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
// Page Loader modal
<div class="modal fade" id="page_loader">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>
// Main Container
<div class="main-container" id="main-container">
    <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
        <form method="post" action="../process_files/create_order.php" id="order_form">
        <div class="row">
            <!-- BreadCrumbs -->
            <div class="col-md-10 offset-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../admin_home.php">Home</a></li>
                    <li class="breadcrumb-item"><a>Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create order</li>
                </ol>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-8">
                 <div class="card">
                     <div class="card-header">
                         <h4 class="float-left">Order</h4>
                         <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#browse_products">Browse Products</button>

                     </div>
                     <div class="card-body">
                         <table class="table  table-sm table-hover">
                             <thead>
                             <tr>
                                <th >Product Name</th>
                                <th >Variant</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-center" width="15%">Qty</th>
                                <th class="text-right" >Totals</th>
                                <th ></th>
                             </tr>
                             <tr id="no_items">
                                 <td colspan="6" class="text-center">No Products in cart</td>
                             </tr>
                             </thead>
                             <tbody id="cartOutPut">


                             </tbody>
                             <tfoot>
                             <tr>
                                 <td colspan="4" class="text-right">Subtotal (<span class="no_of_items_2"></span> items) </td>
                                 <td align="right"><span class="subtotal"></span></td>
                             </tr>
                             <tr>
                                 <td colspan="4" class="text-right">Shipping </td>
                                 <td align="right"><span class="shipping_costs"></span></td>
                             </tr>
                             <tr>
                                 <td colspan="4" class="text-right">Tax @ 15% (included)</td>
                                 <td align="right"><span class="tax"></span></td>
                             </tr>
                             <tr>
                                 <td colspan="4" class="text-right"><b>Total</b></td>
                                 <td align="right"><span class="Cart_total"></span></td>
                             </tr>

                             </tfoot>
                         </table>
                         <input type="hidden" name="channel" value="admin">
                         <button type="button" class="btn btn-outline-primary btn-sm float-right create_order">Create Order</button>
                     </div>
                 </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Customer</h5><br>

                        <div class="form-group">
                            <label>Select a customer</label>
                            <div class="input-group">
                                <select class="form-control" id="select_customer">
                                    <option></option>
                                    <?php
                                    $sql_cust = "SELECT * FROM customer_details ORDER BY first_name ";
                                    $customers = $db->select($sql_cust);
                                    if(sizeof($customers)>0){
                                        foreach ($customers as $customer){
                                            $sql_cust_ship = "SELECT * FROM customer_shipping_details WHERE customer_id = '{$customer['id']}' ";
                                            $ship_result = $db->select($sql_cust_ship);
                                            echo"
                                            <option 
                                            data-custid = '{$customer['id']}'
                                            data-first_name = '{$customer['first_name']} '
                                            data-last_name = '{$customer['last_name']}'
                                            data-contact = '{$customer['contact_num']}'
                                            data-email = '{$customer['email']}'
                                            data-building = '{$ship_result[0]['building_name']}'
                                            data-street = '{$ship_result[0]['street']}'
                                            data-suburb = '{$ship_result[0]['suburb']}'
                                            data-city = '{$ship_result[0]['city']}'
                                            data-province = '{$ship_result[0]['province']}'
                                            data-postal = '{$ship_result[0]['postal_code']}'
                                            > {$customer['first_name']} {$customer['last_name']}           
                                            </option>  
                                            ";
                                        }
                                    }else{
                                        echo "<option>No customers loaded</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <a href="../customers/customers.php">Add a customer</a>

                    </div>
                </div><br>
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Select Payment type</label>
                            <select class="form-control" name="payment_type">
                                <option value="COD">Cash on Delivery</option>
                                <option value="EFT">EFT</option>
                            </select>
                        </div>
                    </div>
                </div><br>
                <div class="card">
                    <div class="card-body">
                        <h5>Customer details</h5><br>
                        <div class="display_customer_details">

                        </div>
                        <div id="customer_details">
                            <input type="hidden" name="customer_id" id="customer_id" class="form-control">
                            <input type="hidden" name="first_name" id="first-name" class="form-control">
                            <input type="hidden" name="last_name" id="last-name" class="form-control">
                            <input type="hidden" name="email" id="email" class="form-control">
                            <input type="hidden" name="contact" id="contact" class="form-control">
                            <br>
                            <h5>Shipping details</h5>
                            <div class="text-center">
                                <input type="checkbox" id="no_ship" name="no_shipping" class="form-check-input">
                                <label class="form-check-label">Collect from Store</label><br>

                            </div><br>
                            <div id="cust_shipping"></div>

                            <div id="shipping_info">
                                <input type="hidden" name="building" id="building" class="form-control">
                                <input type="hidden" name="street" id="street" class="form-control">
                                <input type="hidden" name="suburb" id="subrub" class="form-control">
                                <input type="hidden" name="city" id="city" class="form-control">
                                <input type="hidden" name="province" id="province" class="form-control">
                                <input type="hidden" name="postal" id="postal" class="form-control">
                                <input type="hidden" name="shipping_cost" id="shipping_cost" class="form-control">
                            </div>
                            <div id="input_for_carts">
                            </div>
                            <div id="input_for_totals">
                                <input type="hidden" name="subtotal" id="subtotal" value="">
                                <input type="hidden" name="ship_total"  id="ship_total" value="">
                                <input type="hidden" name="tax" id="tax" value="">
                                <input type="hidden" name="final_total" id="final_total" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div class="col-sm-12">
        <div class=" footer-wrap bg-white pd-20 mb-20 border-radius-5 box-shadow">
            <p>Copyright - Vania Pasta 2018</p>
        </div>
    </div>
</div>

<script src="../../vendors/scripts/script.js"></script>
<script src="../../src/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="../../src/plugins/datatables/media/js/dataTables.bootstrap4.js"></script>
<script src="../../src/plugins/datatables/media/js/dataTables.responsive.js"></script>
<script src="../../src/plugins/datatables/media/js/responsive.bootstrap4.js"></script>
<!-- buttons for Export datatable -->
<script src="../../src/plugins/datatables/media/js/button/dataTables.buttons.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.bootstrap4.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.print.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.html5.js"></script>
<script src="../../src/plugins/datatables/media/js/button/buttons.flash.js"></script>
<script src="../../src/plugins/datatables/media/js/button/pdfmake.min.js"></script>
<script src="../../src/plugins/datatables/media/js/button/vfs_fonts.js"></script>
<script type="text/javascript">
    var shopcart = [];
    $(document).ready(function(){



        $(".var_select").on("change", function(){

            var variant_name = $(this).val();
            var variant_price =  $("option:selected", this).data("price");
            var stockcode = $("option:selected", this).data("stockcode");
            var var_id = $("option:selected", this).data("varid");

            // Update data in button info
            $(this).closest('tr').find(".productItem").attr({"data-variant":variant_name, "data-price":variant_price, "data-skucode":stockcode, "data-variant_id": var_id });
            // change display amount
            $(this).closest("tr").find(".display_price").text(variant_price);


        });

        outputCart();

        // Remove itesm from cart
        $('#cartOutPut').on("click", ".remove-item",function(){

            var itemToDelete = $(".remove-item").index(this);


            //$(this).closest(".row").find("input").data(itemToDelete).remove();

            shopcart.splice(itemToDelete,1);

            sessionStorage["sc"]=JSON.stringify(shopcart);

            outputCart();
            if(shopcart == ''){
                $(".create_order").addClass("d-none");
                $("#no_items").removeClass("d-none");
            }

        });
        // Add items to cart
        $(".productItem").click(function(e){
            e.preventDefault();
            var iteminfo = $(this.dataset)[0];
            iteminfo.qty = 1;
            var iteminCart = false;

            // for each product, checking if item is in cart, if so then update the qty.
            $.each(shopcart, function(index, value){
                //console.log(index+ " "+ value.id);
                if(value.id == iteminfo.id && value.variant_id == iteminfo.variant_id){
                    value.qty = parseInt(value.qty) + parseInt(iteminfo.qty);
                    iteminCart = true;

                    $("#qty").data(index).val(value.qty);




                }
            });
            // if item is not in the cart, adds item to cart
            if(!iteminCart){
                shopcart.push(iteminfo);
                $("#checkOutdiv").removeClass("d-none");

            }
            sessionStorage['sc'] = JSON.stringify(shopcart);
            outputCart();

        });
        // output cart function
        function outputCart(){
            if(sessionStorage['sc'] != null){
                shopcart = JSON.parse(sessionStorage['sc'].toString());
                //console.log(sessionStorage['sc']);
                $(".create_order").removeClass("d-none");
                $("#no_items").addClass("d-none");
            }
            var holderHTML = "";
            var total = 0;
            var itemCount = 0;
            var tax = 0.15;
            var final_total = 0;
            var taxCal = 0;

            $.each(shopcart, function(index, value){
                //console.log(value);
                var sTotal = value.qty * value.price;
                var a = (index+1);
                total += sTotal ;
                itemCount += parseInt(value.qty);
                final_total = total;
                taxCal = total * tax;

                holderHTML = holderHTML+"<tr><input type='hidden'  name='product_id[]' value='"+value.id+"'>" + "<td><input type='hidden' name='product_name[]' value='"+value.name+"'>"+ value.name+"</td><td><input type='hidden' name='variant_id[]' value='"+value.variant_id+"'><input type='hidden' name='variant_name[]' value='"+value.variant+"'>"+value.variant+"</td><td align='right'><input type='hidden' name='unit_price[]' value='"+value.price+"'>"+formatMoney(value.price)+"</td><td class='text-center'><input type='number' class='form-control dynamicQty' data-variant='"+value.variant+"' data-id='"+value.id+"' name='qty_[]' value='"+value.qty+"'></td><td align='right'><input type='hidden' name='line_total[]' value='"+sTotal+"'>"+ formatMoney(sTotal)+"</td><td class='text-center'><span class='btn btn-danger btn-sm remove-item'><i class='fa fa-close'></i></span></td>";


            });



            $("#cartOutPut").html(holderHTML);
            $(".subtotal").html(total);
            $(".Cart_total").html(formatMoney(final_total));

            $(".no_of_items").attr("data-count", itemCount);
            $(".no_of_items_2").html(itemCount);
            $(".tax").html(formatMoney(taxCal));
            update_data();
        }
        // Money formatting
        function formatMoney(n){
            return "R " + (n/1).toFixed(2);
        }

        // Select customer function
        $("#select_customer").on("change", function () {
            var cust_id = $("option:selected", this).data("custid");
            var first_name = $("option:selected", this).data("first_name");
            var last_name = $("option:selected", this).data("last_name");
            var email = $("option:selected", this).data("email");
            var contact_num = $("option:selected", this).data("contact");
            var building = $("option:selected", this).data("building");
            var street = $("option:selected", this).data("street");
            var suburb = $("option:selected", this).data("suburb");
            var city = $("option:selected", this).data("city");
            var province = $("option:selected", this).data("province");
            var postal = $("option:selected", this).data("postal");

            $("#customer_id").val(cust_id);
            $("#first-name").val(first_name);
            $("#last-name").val(last_name);
            $("#email").val(email);
            $("#contact").val(contact_num);
            $("#building").val(building);
            $("#street").val(street);
            $("#suburb").val(suburb);
            $("#city").val(city);
            $("#province").val(province);
            $("#postal").val(postal);

            var customer_para = "<p>" + first_name + " " + last_name + "<br>" + contact_num + "<br>" + email + "</p>";
            $(".display_customer_details").children('p').remove();
            $(".display_customer_details").append(customer_para);


            var cust_shipping_para = "<p>" + building + "<br>" + street + "<br>" + suburb + "<br>" + city + "<br>" + province + "<br>" + postal + "</p>";
            $("#cust_shipping").children('p').remove();
            $("#cust_shipping").append(cust_shipping_para);

            get_shipping_costs();
        });

        // get shipping costs with google
        function get_shipping_costs(){
           var street =  $("#street").val();
           var suburb = $("#suburb").val();
           var city = $("#city").val();
           var province = $("#province").val();

            var destination_loaded = street + suburb + city + province;
            var origin = 'reef industrial park, dunswart, boksburg, south africa';
            var destination = destination_loaded;

            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination,],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.METRIC,
                    avoidHighways: false,
                    avoidTolls: false,
                }, callback);

            function callback(response, status) {
                if (status == 'OK') {
                    var origins = response.originAddresses;
                    var destinations = response.destinationAddresses;

                    for (var i = 0; i < origins.length; i++) {
                        var results = response.rows[i].elements;
                        for (var j = 0; j < results.length; j++) {
                            var element = results[j];
                            var distance = element.distance.text;
                            var distance_val = element.distance.value;
                            var distance_in_km = distance_val /1000;
                        }

                        // Shipping cost calculator
                        no_shipping = false;
                        if(distance_in_km <= 5){
                            var shipping_final_cost = 0;
                            $("#shipping_cost").val(shipping_final_cost);
                            $(".shipping_costs").text(formatMoney(shipping_final_cost));
                        }else{
                            if(distance_in_km < 100){
                                var cost_per_kilo = 5;
                                var distance_less_free_shipping = distance_in_km - 5;
                                var shipping_final_cost = Math.floor(distance_less_free_shipping * cost_per_kilo);
                                $("#shipping_cost").val(shipping_final_cost);
                                $(".shipping_costs").text(formatMoney(shipping_final_cost));
                                update_data();


                            }else{


                                alert("Address is out of delivery area");
                                $("#shipping_cost").val('0');
                                $(".shipping_costs").text(formatMoney(0));
                                var no_shipping = true;
                            }

                        }

                    }
                }

            }



        }
        // No shipping function
        $("#no_ship").on("change", function () {
            var no_shipping_cost = $(this).is(":checked");

            if(no_shipping_cost){
                $("#shipping_cost").val(0);
                $(".shipping_costs").text(formatMoney(0));
                update_data();
            }else{
                get_shipping_costs();
            }
        });
        // Update the totals
        function update_data (){
            var subtotal = $(".subtotal").text();
            var shipping_cost = $("#shipping_cost").val();

            if(shipping_cost == ''){
                shipping_cost = 0;
            }
            var tax = 0.15;
            var final_total = parseFloat(subtotal) + parseFloat(shipping_cost);
            var tax_amount = (final_total * tax).toFixed(2);
            $(".Cart_total").html(formatMoney(final_total));
            $(".tax").html(formatMoney(tax_amount));

            $("#subtotal").val(subtotal);
            $("#ship_total").val(shipping_cost);
            $("#tax").val(tax_amount);
            $("#final_total").val(final_total);


        }

        // Update Quantities
        $('#cartOutPut').on("change keyup",".dynamicQty", function(){
            var iteminfo = $(this.dataset)[0];

            var iteminCart = false;

            var updatedQty = $(this).val();

            // for each product, checking if item is in cart, if so then update the qty.
            $.each(shopcart, function(index, value){

                if(value.id == iteminfo.id && value.variant == iteminfo.variant){
                    shopcart[index].qty = updatedQty;
                    iteminCart = true;

                }
            });
            sessionStorage["sc"]=JSON.stringify(shopcart);

            outputCart();


        });
        
        $(".create_order").click(function () {
            var customer_selected = $("#select_customer").val();
            if(shopcart != ''){
                if(customer_selected  != ''){
                    // form Submit
                    sessionStorage.clear();
                    $("#order_form").submit();
                    $("#page_loader").modal('show');

                }else{
                    alert("Please select a customer!");
                }
            }else{
                alert("please select some products!");
            }


        });






    });
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPwjkdQEg9eLknV7RPE-6I6lsoZkIyk8c">
</script>
</body>
</html>
