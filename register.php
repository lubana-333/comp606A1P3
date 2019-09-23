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

        <!-- javascript files after this -->
        <script type="text/javascript" src="js/vendor/jquery-1.11.3.min.js"></script>  
        <link href="css/contactStyles.css" rel="stylesheet" type="text/css" />  

        <script type='text/javascript'>
		
      
		 $(document).ready(function(){
                $('#send_consultation').click(function(e){
					
					
					var contactFNameEN=$("#contactFNameEN").val();
					var contactLNameEN=$("#contactLNameEN").val();
					var contactAccountUsername=$("#contactAccountUsername").val();
					var contactcode=$("#contactcode").val();
					var contactTelephone=$("#contactTelephone").val();
					var address=$("#contactTelephone").val();
					var password=$("#password").val();
					
					
					if(contactFNameEN=="" || contactLNameEN=="" || contactAccountUsername=="" || contactTelephone=="" || address=="" || password=="" )
					{
						
						if(contactFNameEN=="")
						{
							$("#contactFNameEN").css("borderColor","red");
						}
						else{
							$("#contactFNameEN").css("borderColor","");
						}
						if(contactLNameEN=="")
						{
							$("#contactLNameEN").css("borderColor","red");
						}
						else{
							$("#contactLNameEN").css("borderColor","");
						}
						
						if(contactAccountUsername=="")
						{
							$("#contactAccountUsername").css("borderColor","red");
						}
						else{
							$("#contactAccountUsername").css("borderColor","");
						}
						
						
						if(contactcode=="")
						{
							$("#contactcode").css("borderColor","red");
						}
						else{
							$("#contactcode").css("borderColor","");
						}
						
						
						if(contactTelephone=="")
						{
							$("#contactTelephone").css("borderColor","red");
						}
						else{
							$("#contactTelephone").css("borderColor","");
						}
						
						if(address=="")
						{
							$("#address").css("borderColor","red");
						}
						else{
							$("#address").css("borderColor","");
						}
						
						
						if(password=="")
						{
							$("#password").css("borderColor","red");
						}
						else{
							$("#password").css("borderColor","");
						}
						
						
						return false;
						
					}
					else{
					
						e.preventDefault();
                    $.post("scripts/register.php", $("#consultantion").serialize(),function(result){
                        if(result.trim() == 'sent'){
                            $("#consultantion").remove();
                            $('#mail_success').fadeIn(500);
                            $('#mail_fail').fadeOut(500);
                        }            
                        else{
                            $('#mail_fail').fadeIn(500);
                            $('#mail_success').fadeOut(500);

                        }
                    });
					return true;
		}
		  //stop the form from being submitted
            				
                });
            });
		
        </script>
    </head>
    <body>

        <?php include "lib/header.inc.php" ?>

        <!-- Showcase Section Starts here -->
        <section id="sub-banner">
            <!-- <img src="img/consultation-banner.jpg" alt="Dashboard" /> -->
            <div class="bredcrumb">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Registeration</h3>
                            <ul>
                                <li><a href="<?php echo $basepath;?>">Home</a></li>
                                <li><a href="javascript:void;">Registeration</a></li>
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
                            <h1>Register</h1>
                            <form method="post" id='consultantion'>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="firstname">First Name:</label>
                                        <input type="text" class="form-control" placeholder="First Name" name='firstname' id='contactFNameEN' />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="lastname">Last Name:</label>
                                        <input type="text" class="form-control" placeholder="Last Name" name='lastname' id='contactLNameEN' />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="email">Email:</label>
                                        <input type="text" class="form-control" placeholder="Email" name='email' id='contactAccountUsername' />
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <div class="col-sm-3 form-group">
                                            <label for="code">Country Code:</label>
                                            <input type="text" class="form-control" placeholder="Code" name="contactcode" id="contactcode" />
                                        </div>
                                        <div class="col-sm-9 form-group">
                                            <label for="mobile">Mobile:</label>
                                            <input type="text" class="form-control" placeholder="Mobile" name="mobile" id="contactTelephone" />
                                        </div>
                                    </div>
                                </div>							

                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" placeholder="Address" name="address" id="address"/>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label for="password">Password</label>
                                        <input type="text" class="form-control" placeholder="Password" name="password" id="password"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <input type="submit" class="btn submit_consult" value="Submit" id="send_consultation"/>
                                    </div>
                                </div>
                            </form>
                            <div id='mail_success' class='success' style="color:green;font-size:16px;">Thank you for Registeration in Body Therpy. 
                            <br /></div>
                            <div id='mail_fail' style="color:red; text-align:center; font-size:16px;" class='error'>There was an error while trying to save your details, please try again later. </div>
                            <div class="row">
                                <div class='col-sm-12'>
                                    Already a Registered ?
                                    <a href="<?php echo $basepath?>login.php" >Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Registration end -->
        <?php include "lib/footer.inc.php" ?>
    </body>
</html>
