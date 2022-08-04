<?php ini_set("display_errors", 1);
	include_once('login_authenticate.php');
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Location" content="index.php">
  <title>Computer Center | Lab Allocator</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="bootstrap/css/full-slider.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <!--effects for textboxes-->
    <link type="text/css" rel="stylesheet" href="bootstrap/css/animate.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
</head>

<body class=" login-page">

<!-- Full Page Image Background Carousel Header -->
    <div id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <!--ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol-->

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <div class="item active">
                <!-- Set the first background image using inline CSS below. -->
                <div class="fill" style="background-image:url('images/cc-1.jpg');"></div>
                <div class="carousel-caption">
                    <!--h2>Caption 1</h2-->
                </div>
            </div>
            <div class="item">
                <!-- Set the second background image using inline CSS below. -->
                <div class="fill" style="background-image:url('images/cc-4.jpg');"></div>
                <div class="carousel-caption">
                    <!--h2>Caption 2</h2-->
                </div>
            </div>
            <div class="item">
                <!-- Set the third background image using inline CSS below. -->
                <div class="fill" style="background-image:url('images/cc-6.jpeg');"></div>
                <div class="carousel-caption">
                    <!--h2>Caption 3</h2-->
                </div>
            </div>
        </div>

        <!-- Controls -->
        <!--a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a-->

    </div>
	<!--full page slider-->
	
		<div class="login-box">
			  <div class="login-logo">
				<a href="#"><b>Lab Allocator</b></a>
			  </div>
			  <!-- /.login-logo -->
			  <div class="login-box-body" >
				<p class="login-box-msg">Sign in to get started</p>

				<form action="#" method="post" id="login_form">
					  <div class="form-group has-feedback" >
						<input type="email" id="change-transitions1" class="form-control email" data-toggle="dropdown" data-value="pulse" name="username"  placeholder="Email">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					  </div>
					  <div class="form-group has-feedback" id="change-transitions">
						<input type="password" id="change-transitions2"class="form-control password" data-toggle="dropdown" data-value="pulse" name="password" placeholder="Password">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					  </div>
					<div class="row">
						<div class="col-xs-8">
						  <!--div class="checkbox icheck">
							<label>
							  <input type="checkbox"> Remember Me
							</label>
						  </div-->
						</div>
						<!-- /.col -->
						<div class="col-xs-4">
						  <input type="submit" name="submit" class="btn btn-primary btn-block btn-flat" value="Sign In"/>
						</div>
						<!-- /.col -->
					</div>
				</form>
			
    <!--div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div-->
    <!-- /.social-auth-links -->
	 <div class="help-block text-center">
	 
	</div>
  
    <!--a href="forgot_password.php">I forgot my password</a><br>
    <a href="register.php" class="text-center">Register a new membership</a-->
	<p>For any queries, Please Contact :<br/></p>
		<h4>Prof. Vidyadheesh Pandurangi</h3>
		<h5>Contact : +91-8095013250</h5>
		<h5>Email : vjpandurangi@git.edu</h5>
        <h5>InterCom : 274</h5>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="plugins/jQueryUI/jquery-ui.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!--Animation-->
 <script src="bootstrap/js/animation.js"></script>
 <script src="bootstrap/js/jquery.cookie.js"></script>
 
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
	
$(document).ready(function(){
	
	
});
</script>
</body>
</html>
