<?php include('admin/lib_include/connection.inc.php'); ?>
<?php include('lib/dbconnect.php'); ?>

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

	$book_type = $_GET['book_type'];
	$price = (int)$_GET['price'];

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
		<!--No javascript files after this -->
		<link rel="stylesheet" type="text/css" href="css/calender1.css">
		<!--No javascript files after this -->
		<link rel="stylesheet" type="text/css" href="css/calender2.css">
		<style type="text/css">
			.error{color:red !important;}
			.error1{color:red !important;}
			.error2{color:green;}
			.gn-form-wrap label{max-width: none;}
			.btn{margin-bottom: 20px;}
		</style>
	</head>
	<body>
		<?php
			$_SESSION['drid'] 		= (int)$_GET['drid']; //docter id
			$drid					= $_SESSION['drid']; //docter id
			$consolttype			= $_SESSION['consolttype'];//consultation type
			$consolttypeview		= $_GET['consolttypeview'];
			$selectclicnic			= $_GET['selectclicnic']; //clinic id
			$eventslotid			= $_GET['eventslotid']; //slot id
			$selectdate				= $_GET['selectdate']; //date
			$selectdayno			= date('N', strtotime($selectdate)); //day in number
			if($selectdayno==7){
				$selectdayno = 0;
			}else{
		    	$selectdayno = $selectdayno;
      		}
      
		  	//book appointment
			if(!empty($_REQUEST['addappointbooked'])){
				$bookedby_headpatent  = $userid; //booked by user
				$bookslotdetail       = $_POST['bookslotstart'];//book slot start time and end time
				$slot_detail          = explode( ',', $bookslotdetail );
				$bookslotstart        = $slot_detail[0]; //patient id
				$bookslotend          = $slot_detail[1]; //main patient id
				$avaliable_bookdate   = $selectdate; //booked date
				$bookedbyname         = $firstname .' '. $row_loginuser['lastname'];
				$bookedbyemail        = $row_loginuser['email'];
				$bookedbymobile       = $row_loginuser['mobile'];
				//patient id _ main patient id
				$patientbooked_for_by = $_POST['patient_select']; //patient appoint book for
				$keywords             = explode( '-', $patientbooked_for_by );
				
				$pat_for  = $keywords[0]; //patient id
				$pat_by   = $keywords[1]; //main patient id
				if($pat_by == 0){
					$bookedfor_name   = $firstname .' '. $row_loginuser['lastname'];
					$bookedfor_email  = $row_loginuser['email'];
					$bookedfor_mobile = $row_loginuser['countryCode'].$row_loginuser['mobile'];
				}
        		else if($pat_by > 0){
					$bookedfor_name     = $row_loginuser['firstname']." ".$row_loginuser['lastname'];
					$bookedfor_email    = $row_loginuser['email'];
					$bookedfor_mobile   = $row_loginuser['countryCode'].$row_loginuser['mobile'];
			  	}
			  	$drid   = $_SESSION['drid']; //docter id  
				//Get docter detail
				$dbc->where('docID', $drid);
				$rowdctrname = $dbc->getOne('therapy_center_info', null);
				$drname                 = $rowdctrname['doctorname1'].' '.$rowdctrname['doctorname2'].' '.$rowdctrname['doctorname3'].' '.$rowdctrname['doctorname4'];//docter name			
				$doctoremail            = $rowdctrname['doctoremail'];//docter email detail
				$doctermobile           = $rowdctrname['doctormobile1']. ',' . $rowdctrname['doctormobile2'];//docter mobile
				$docterphone            = $rowdctrname['doctorphone'];//docter phone
				$clinic_typeid          = (int)$_GET['selectclicnic']; //clinic id
				$consultationtype       = $_POST['consultationtype']; //consultation type
				$consulation_validfee   = (int)$_POST['consulation_valid'];//consult fee id
				//Get Consultation fee info.
				$dbc->where('id', $consulation_validfee);
				$row_drconsult_type = $dbc->getOne('therapyconsult_type', null);
				$feeclinic              = $row_drconsult_type['consult_inr'];
				$consult_valid          = $row_drconsult_type['consult_valid'];//if 1
				$feefirsttime           = $row_drconsult_type['consult_Frsttime'];
				
				if($consult_valid='1'){
					$consult_day=$row_drconsult_type['consult_day'];
					$consult_number=$row_drconsult_type['consult_number'];
				}
				// if($bookslotstart!="" && $patientbooked_for_by!="" && $consulation_validfee!=""){
				if($bookslotstart!="" && $patientbooked_for_by!=""){
					//check if same slot already not added
					$dbc->where('bookedslot_date', $selectdate);
					$dbc->where('bookslotstart', $bookslotstart);
					$dbc->where('bookslotend', $bookslotend);
					$dbc->where('bookby_patient_for', $userid);
					$dbc->where('clinic_typeid', $clinic_typeid);
					$dbc->where('statusbooked', '1');
					$get_checkslotalredbook = $dbc->get('booked_appointment', null);
					$checktotal_checkslotalredbook = count($get_checkslotalredbook);
					if($checktotal_checkslotalredbook>0){
						$new_mssg= "slot is already booked";
					}
          			else{
						$add_date=date("Y-m-d h:i:s");
						$sqlQuery = "INSERT INTO booked_appointment (book_type, bookby,bookslotstart,bookslotend,bookedslot_date,bookbyname,bookbyemail,bookbymobile,bookfor_name,bookfor_email,bookfor_mobile,bookby_patient_for,bookby_patient_by,drbookedid,drbookedname,drbookedemail,drbookedphone,drbookedmobile,consulation_fee,consulation_firstfee,consulation_day,consulation_number,clinic_typeid,consult_type,statusbooked, appointmentdate) values ('$book_type', '$bookedby_headpatent','$bookslotstart','$bookslotend','$selectdate','$bookedbyname','$bookedbyemail','$bookedbymobile','$bookedfor_name','$bookedfor_email','$bookedfor_mobile','$pat_for','$pat_by','$drid','$drname','$doctoremail','$docterphone','$doctermobile','$price','$feefirsttime','$consult_day','$consult_number','$clinic_typeid','$consultationtype','1', '$add_date')";
						$insertQuery = mysqli_query($conns, $sqlQuery) or die("error");
						
						// exit;
						$dbc->orderBy('bid', 'DESC');
						$latestId = $dbc->getOne('booked_appointment', null);
						$totalcheckslotbook = $latestId['bid'];
						if($totalcheckslotbook > 0){
							// $updatebooksot="UPDATE dateslot_detail SET slot_booked='1' WHERE sloat_date='$selectdate' and docterid='$drid' and slotdayid='$selectdayno' and slotclinic_id='$clinic_typeid' and slotstartfrom='$bookslotstart' and slotendto='$bookslotend'";
							// $result_updatebooksot=mysqli_query($con,$updatebooksot);

							$dataUpdate = Array(
								'slot_booked' => 1,
							);

							$dbc->where('sloat_date', $selectdate);
							$dbc->where('docterid', $drid);
							$dbc->where('slotdayid', $selectdayno);
							$dbc->where('slotclinic_id', $clinic_typeid);
							$dbc->where('slotstartfrom', $bookslotstart);
							$dbc->where('slotendto', $bookslotend);
							$result_updatebooksot = $dbc->update('dateslot_detail', $dataUpdate);
						} //if end
						//$mssg= "your appointment has been booked";
						header('refresh:1;url='.$basepath.'thankyou.php?bookbyid='.$totalcheckslotbook);
			    	} //if
			  	}//if bookslot & patient book by & fee is select end
				else{
					$errormsg= "please select all feilds";
				}
			} //if end on button click
		?>
		<div class="wrapper">
			<div class="side-bar">
				<?php include "lib/therapy_leftside.inc.php";?>               
			</div>
			<div class="content content-body">
				<?php include "lib/therapy_header.inc.php";?>
				<div class="container-fluid">
					<div class="dsb-content">
						<div class="panel tabbed-wrap cst-shadow">
							<div class="db-tabber">
								<ul class="nav nav-tabs">
									<li class="active"><a data-toggle="tab" href="#administrative-data">Book Appointment (Step 2)</a></li>
									<div class="col-sm-1 pull-right">
										<h2> <a href="javascript:history.back();">Back</a> </h2>
									</div>
								</ul>
								<div class="tab-content">
									<div id="administrative-data" class="tab-pane fade in active">
										<form action="" method="post" id="calender_detailbooked">
											<div class="gn-form-wrap">
												<div class="container-fluid row bottom-btn">
													<div class="col-md-12">
														<font class="error1"><?php echo $err;?></font> 
													</div>
												</div>
												<div class="col-sm-6">
													<?php
														$dbc->where('docID', $drid);
														$rowdrname = $dbc->getOne('therapy_center_info', null);
													?>
													<input type="hidden" value="<?php echo $rowdrname['docID'];?>" name="docternameid" id="docternameid">
												</div>
												<div class="col-sm-6">
													<input type="hidden" value="33" name="clinicname" id="clinicname">
												</div>
												<div class="col-sm-6">
													<label for="Type"><b>Appointment Date:</b></label>
													<input class='form-control' value="<?php echo $selectdate;?>" readonly>
													<input type="hidden" value="<?php echo $selectdate;?>" name="app_date" id="app_date">
												</div>
												<div class="col-sm-6">
													<label for="Type"><b>Day:</b></label>
													<input class='form-control' value="<?php echo date('l', strtotime($selectdate));?>" readonly>
													<input type="hidden" value="<?php echo date('N', strtotime($selectdate));?>" name="app_day" id="app_day">
												</div>
												<div class="col-sm-12">
													<label for="Slots"><b>Book Slot:</b></label> 
													<div class="form-group">
														<?php
															// $eventslotid1 = "select * from dateslot_detail where docterid='$drid' and slotdayid='$selectdayno' and sloat_date='$selectdate' and slotclinic_id='$selectclicnic' and slotinfotype='1'"; 
															// $get_eventslotid1 = mysqli_query($con,$eventslotid1);

															$dbc->where('docterid', $drid);
															$dbc->where('slotdayid', $selectdayno);
															$dbc->where('sloat_date', $selectdate);
															$dbc->where('slotclinic_id', $selectclicnic);
															$dbc->where('slotinfotype', '1');
															$get_eventslotid1 = $dbc->get('dateslot_detail', null);
															$tot_eventslotid1 = count($get_eventslotid1);
															if($tot_eventslotid1 > 0){
                                								//if date added start
																foreach($get_eventslotid1 as $row_eventslotid1){
																	$start_from1  = $row_eventslotid1['slotstartfrom'];
																	$end_to1      = $row_eventslotid1['slotendto'];
									
																	$checkdate    = $selectdate; //date booked
																	// $checkslotbooked      = "SELECT * from booked_appointment where bookedslot_date='$checkdate' and bookslotstart='$start_from1' and bookslotend='$end_to1' and clinic_typeid='$selectclicnic' and statusbooked='1'";
																	// $get_checkslotbooked  = mysqli_query($con,$checkslotbooked);

																	$dbc->where('bookedslot_date', $checkdate);
																	$dbc->where('bookslotstart', $start_from1);
																	$dbc->where('bookslotend', $end_to1);
																	$dbc->where('clinic_typeid', $selectclicnic);
																	$dbc->where('statusbooked', '1');
																	$get_checkslotbooked = $dbc->get('booked_appointment', null);
																	$totalcheckslotbooked = count($get_checkslotbooked);
																	?>
																	<span class="col-sm-3">
																		<input type="radio" value="<?php echo $start_from1;?>,<?php echo $end_to1;?>" name="bookslotstart" id="bookslotstart" class="required" <?php if($totalcheckslotbooked>0){?> disabled="disabled" <?php }?>>
																		<span <?php if($totalcheckslotbooked>0){?>style="opacity:0.5;" <?php }?>>  <?php echo $start_from1; //date booked?> - <?php echo $end_to1;?>  </span> 
																		<input type="hidden" value="<?php echo $row_eventslotid1['slotinfotype']; ?>" name="consultationtype"/>
																	</span>
																	<?php
																} //foreach end
															} //if date add end
															?>
														  	<?php
																$dbc->where('docterid', $drid);
																$dbc->where('clinicname_id', $selectclicnic);
																$dbc->where('clinic_dayid', $selectdayno);
																$dbc->where('id', $eventslotid);
																$get_slotdate_detail1 = $dbc->getOne('therapydayslot', null);
																$totalslot1             = count($get_slotdate_detail1);
																$row_slotdate_detail1   = $get_slotdate_detail1;
																$idslotdetail           = $row_slotdate_detail1['id'];

																$dbc->where('slotid', $idslotdetail);
																$dbc->where('docterdayslot_type', $consolttypeview);
																$get_docterdayslot_allsub1 = $dbc->get('therapydayslot_allsub', null);
																$totdocslt  = count($get_docterdayslot_allsub1);
																if($totdocslt>0){
																	foreach($get_docterdayslot_allsub1 as $row_docterdayslot_allsub1){
																		$start_from2 = $row_docterdayslot_allsub1['slotstartfrom'];
																		$end_to2 = $row_docterdayslot_allsub1['slotendto'];
																		//check if same time slot is booked form bookappointment
																		$checkdate = $selectdate;//date booked
																		$checkslotbooked = "SELECT * from booked_appointment where bookedslot_date='$checkdate' and bookslotstart='$start_from2' and bookslotend='$end_to2' and clinic_typeid='$selectclicnic' and statusbooked='1'";
																		$get_checkslotbooked = mysqli_query($con,$checkslotbooked);
																		
																		$dbc->where('bookedslot_date', $checkdate);
																		$dbc->where('bookslotstart', $start_from2);
																		$dbc->where('bookslotend', $end_to2);
																		$dbc->where('clinic_typeid', $selectclicnic);
																		$dbc->where('statusbooked', '1');
																		$get_checkslotbooked = $dbc->get('booked_appointment', null);
																		$totalcheckslotbooked = count($get_checkslotbooked);
																		//bug solved, if doctor not available then patient should not book appointment.
																		// $verifyNASlotSql = "SELECT * FROM dateslot_detail WHERE docterid='$drid' AND slotdayid='$selectdayno' AND sloat_date='$selectdate' AND slotclinic_id='$selectclicnic' AND slotstartfrom='$start_from2' AND slotendto='$end_to2'";
																		// $verifyNASlotQuery = mysqli_query($con, $verifyNASlotSql);

																		$dbc->where('docterid', $drid);
																		$dbc->where('slotdayid', $selectdayno);
																		$dbc->where('sloat_date', $selectdate);
																		$dbc->where('slotclinic_id', $selectclicnic);
																		$dbc->where('slotstartfrom', $start_from2);
																		$dbc->where('slotendto', $end_to2);
																		$verifyNASlotQuery = $dbc->get('dateslot_detail', null);
																		$totalVerifyNASlotQuery = count($verifyNASlotQuery);
																		
																		$dumpArray = Array();
																		foreach($verifyNASlotQuery as $verifyNASlotArray){
																		  $dumpArray = $verifyNASlotArray['slotinfotype'];
																		}
																		//bug solved, if doctor not available then patient should not book appointment.
																		?>
																		<span class="col-sm-3">
																			<input type="radio" value="<?php echo $start_from2;?>,<?php echo $end_to2;?>" name="bookslotstart" id="bookslotstart" class="required" <?php if($totalcheckslotbooked>0){?> disabled="disabled" <?php } if($totalVerifyNASlotQuery>0 && $dumpArray['slotinfotype'] == 3){ echo 'disabled="disabled"'; } ?>>
																			<span <?php if($totalcheckslotbooked>0){?>style="opacity:0.5;" <?php }?> <?php if($totalVerifyNASlotQuery>0 && $dumpArray['slotinfotype'] == 3){ echo 'style="opacity:0.5;"'; } ?> >  <?php echo $start_from2;?> - <?php echo $end_to2;?> </span>
																			<input type="hidden" value="<?php echo $row_docterdayslot_allsub1['docterdayslot_type']; ?>" name="consultationtype"/>
																		</span>
																		<?php 
																	} //foreach sub slot end
																} //if end
																else{ //when no slot add?>
																	<div class="col-sm-12 form-group radio-toolbar">
																		<font class="error1"> <?php echo "no slot time added";?> </font>
																	</div>
																	<?php
																} //else end 
															?>
													  	</div>
												  	</div>
												  	<div class="col-sm-12 form-group">
														<label for="Patients"><b>Customer:</b></label>
														<select class="form-control required" name="patient_select" id="patient_select">
															<option value='<?php echo $row_loginuser['id'];?>' selected><?php echo $firstname.' '.$row_loginuser['lastname'];?></option>
														</select>
												  	</div>
													  <div class="col-sm-12 form-group">
														<label class="col-sm-12" for="type pull-left"><b>Therapy Type:</b></label> 
													  	
													  	<span class="col-sm-8">
															<b> <?php echo $book_type;?> </b>
													  	</span>
												  	</div>
												  	<div class="col-sm-12 form-group">
														<label class="col-sm-12" for="type pull-left"><b>Booking Fee:</b></label> 
													  	<?php
															$dbc->where('docterid', $drid);
															$dbc->where('consult_type', '1');
															$row_drconsult_type = $dbc->getOne('therapyconsult_type', null);
														?>
													  	<span class="col-sm-8">
															<input type="hidden" value="<?php echo $row_drconsult_type['id'];?>" name="consulation_valid" id="consulation_valid" style="margin-right:10px;"/> 
															 Fee: <b>$ <?php echo $price;?> </b>
													  	</span>
												  	</div>
											  	</div>
											  	<!--col-sm-12 end-->
												<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<font class="error1"><?php echo $errormsg;?></font>
													<font class="error1"><?php echo $new_mssg;?></font>
												</div>
												<div class="container-fluid row bottom-btn">
													<div class="col-md-12">
														<input type="submit" name="addappointbooked" value="Submit" class="btn btn-primary">    
													</div>
												</div><!--container-fluid row bottom-btn end-->
									    	</div>
									  	</form>
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
			});
		</script> 
		<!-- All Javasripts included here -->
		<script type="text/javascript" src="js/jquery.validate.js"></script>
		<!--personal detail-->
		
		<script type='text/javascript'>
			$(document).ready(function(e){
				$('#calender_detailbooked').validate({
					rules: {
						bookslotstart: {
							required:true,
						},
						patient_select: {
							required:true,
						},
					},
					messages: {
						bookslotstart: {
							required:"please select time slot",
						},
						patient_select: {
							required:"please select customer",
						},
					}
				});
			});
		</script>
	</body>
</html>
