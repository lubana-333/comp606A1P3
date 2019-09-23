<?php
	include('admin/lib_include/connection.inc.php');
	if(!empty($_SESSION['regid'])){
		header('location: '.$basepath);
	}
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Therapy</title>
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="author" content="">

	<?php include "lib/top.inc.php" ?>
</head>
	
<body>
<?php include "lib/header.inc.php" ?>
<!-- Showcase Section Starts here -->
<section id="sub-banner">
	<!-- <img src="img/consultation-banner.jpg" alt="login" /> -->
	<div class="bredcrumb">
		<div class="container">
			<div class="row">	
				<div class="col-sm-12">
					<h3>Login</h3>
					<ul>
						<li><a href="<?php echo $basepath;?>">Home</a></li>
						<li><a href="<?php echo $basepath;?>register.php">Registeration</a></li>
						<li><a href="javascript:void;">Login</a></li>						
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>


<!--Registration start -->

<div class="container" style="margin-top:30px;">
    <div id="get_cons">
		<div class="container" style="margin-top:30px;">
			<div class="row">
				<div class="col-sm-12">
					<h1>Login</h1>
					<?php
						if(!empty($_REQUEST['login'])){
							$email 		= $_REQUEST['email'];
							$password 	= $_REQUEST['password'];

							$dbc->where("publish", 0);
							$dbc->where("email", $email);
							$dbc->where("password", md5($password));
							$userData = $dbc->getOne("customers", null);
							
							if(count($userData)>0){ //if login match
								session_start();
								$_SESSION['customer_name'] 	= $userData['firstname']." ".$userData['lastname'];
								$_SESSION['email'] 			= $userData['email'];
								$_SESSION['regid'] 			= $userData['id'];

								if(isset($userData)){
			        	            header('location: '.$basepath.'book_appointment.php'); //if login
								}
								else{
									header('location: '.$basepath.'login.php');	//if not valid user nd pwd
								}
							}
							else{ //if login not match
								?>
				  				<font color="#FF0000";> <?php echo 'Please check your email and password'; ?> </font> 	
								<?php
							}
						} //login button click
					?>
					<form id="consultantion" method="post">
						<div class="row">
							<div class="col-sm-6 form-group">						  
								<label for="email">Email</label>
								<input type="text" class="form-control" name="email" placeholder="Email"/>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 form-group">							
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" placeholder="Password" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 form-group">
								<input type="submit" class="btn" value="Login" name="login" />
							</div>
							<div class='col-sm-12'>
                                Not a Registered yet ?
                                <a href="<?php echo $basepath?>register.php" >Register</a>
                            </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<!--Registration end -->

<?php include "lib/footer.inc.php" ?>
<!-- All Javasripts included here -->

</body>
</html>
