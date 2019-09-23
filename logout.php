<?php
	include('admin/lib_include/connection.inc.php');
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
					<h3>Logout</h3>
					<ul>
						<li><a href="<?php echo $basepath;?>">Home</a></li>
						<li><a href="javascript:void;">Logout</a></li>						
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
					<h1>Logout</h1>
					<?php
                        session_start();
                        session_destroy();
                        header('location: '.$basepath); //if login
					?>
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
