<?php include('admin/lib_include/connection.inc.php'); ?>
<?php
    ob_start();
    session_start();
    $ses_id= session_id();
    if(empty($_SESSION['regid']))
    {
        header('location: '.$basepath.'login.php');
    }

    $userid = (int)$_SESSION['regid'];
    $dbc->where("publish", 0);
    $dbc->where("id", $userid);
    $row_loginuser = $dbc->getOne('customers', null);
    $firstname = $row_loginuser['firstname'];  
?>
<!DOCTYPE html>
<html>    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Therapy</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "lib/therapy_top.inc.php"; ?>
    </head>
    <body>	
		<div class="wrapper">
    	    <div class="side-bar">
                <?php include "lib/therapy_leftside.inc.php";?>
    	    </div>   
            <div class="content content-body">
                <?php include "lib/therapy_header.inc.php";?>
                <div class="container-fluid">
                    <div class="dsb-content">
                        <?php /*  Show entries */ ?>
                        <div class="panel tabbed-wrap cst-shadow">
                            <div class="db-tabber">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#administrative-data">View Booked Appointment</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="administrative-data" class="tab-pane fade in active">
                                        <div class="gn-form-wrap">
                                            <form method="post" class="form-inline">
                                                <table class="table table-bordered" id="tblData">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center">Appointment For</th>
                                                            <th>Booking Type</th>
                                                            <th>Price</th>
                                                            <th style="text-align:center">Date/Time</th>
                                                            <th style="text-align:center">Cancel</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $todaydate= date("Y-m-d");
                                                            $sqlData = "SELECT * from booked_appointment where bookby='$userid' and countcheck_appointment='1' and bookedslot_date > '$todaydate' ORDER BY bid ASC";

                                                            $checkbooked_appointment = $dbc->rawQuery($sqlData);
                                                            foreach($checkbooked_appointment as $bookedItem){
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $bookedItem['bookfor_name']; ?></td>
                                                                    <td><?php echo $bookedItem['book_type']; ?></td>
                                                                    <td>$ <?php echo $bookedItem['consulation_fee']; ?></td>
                                                                    <td>
                                                                        <p><?php echo date("d-m-Y", strtotime($bookedItem['bookedslot_date'])); ?></p>
                                                                        <p><?php echo $bookedItem['bookslotstart'];  ?> - <?php echo $bookedItem['bookslotend']; ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            if($bookedItem['statusbooked'] == 1){
                                                                                ?>
                                                                                <a class="btn btn-success cancel_appointment" href="<?php echo $basepath; ?>cancel_booking.php?appid=<?php echo $bookedItem['bid']; ?>">Cancel Appointment</a>
                                                                                <?php
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <button type="button" class="btn btn-danger disabled" disabled>Booking Cancelled</button>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
		</div>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".push_menu").click(function(){
                    $(".wrapper").toggleClass("active");
                });

                $('.cancel_appointment').click(function(){
                    if (confirm('Are you Sure to cancel appointment!')) {
                        return true;
                    } else {
                        return false;
                    }
                })
            });
        </script>
    </body>
</html>