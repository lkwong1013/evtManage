<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- icon setting -->
    
    <link rel="icon" href="<?php echo base_url(); ?>source/icon.gif">
    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>source/icon.gif">
	<!-- For the iPad mini and the first- and second-generation iPad on iOS ? 6: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url();?>source/icon_72.gif">
	<!-- For the iPad mini and the first- and second-generation iPad on iOS ? 7: -->
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo base_url();?>source/icon_76.gif">
	<!-- For iPhone with high-resolution Retina display running iOS ? 6: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url();?>source/icon_114.gif">
	<!-- For iPhone with high-resolution Retina display running iOS ? 7: -->
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo base_url();?>source/icon_120.gif">
	<!-- For iPad with high-resolution Retina display running iOS ? 6: -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url();?>source/icon_144.gif">
	<!-- For iPad with high-resolution Retina display running iOS ? 7: -->
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo base_url();?>source/icon_152.gif">

    <title>Max Project 2016</title>
    
    <!-- jQuery Core -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
	<script src="<?php echo base_url(); ?>lib/js/jquery.js"></script>
    <!-- <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
	
	<!-- jQuery mobile template -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>lib/jquery.mobile-1.0.css" />
	
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>lib/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap DatTime picker CSS -->
    <link href="<?php echo base_url(); ?>lib/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Bootstrap Toogle template -->
    <link href="<?php echo base_url(); ?>lib/css/bootstrap-toggle.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url(); ?>lib/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>lib/jumbotron.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>lib/layout.css" rel="stylesheet">	

	<!-- fullCalendar plugin template -->
	<link href="<?php echo base_url(); ?>lib/fullCalendar/fullcalendar.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>lib/fullCalendar/fullcalendar.print.css" media="print" rel="stylesheet">
	
	<!-- swipe plugin template -->
	<link href="<?php echo base_url(); ?>lib/swipe/style.css" rel="stylesheet">
	
	<!-- summernote plugin template -->
	<link href="<?php echo base_url(); ?>lib/summernote/summernote.css" rel="stylesheet">
	
	<!-- select2 plugin template -->
	<link href="<?php echo base_url(); ?>lib/select2/dist/css/select2.min.css" rel="stylesheet">
	
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo base_url(); ?>lib/assets/js/ie-emulation-modes-warning.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!--  Global CSS Style -->
    <style>
    th.header { 
	    background-image: url(<?php echo base_url(); ?>lib/tableSorter/themes/blue/bg.gif); 
	    cursor: pointer; 
	    font-weight: bold; 
	    background-repeat: no-repeat; 
	    background-position: center right; 
	    padding-left: 20px; 
	    margin-left: -1px; 
	} 
    th.headerSortUp { 
	    background-image: url(<?php echo base_url(); ?>lib/tableSorter/themes/blue/asc.gif); 
	    
	} 
	th.headerSortDown { 
	    background-image: url(<?php echo base_url(); ?>lib/tableSorter/themes/blue/desc.gif);
	} 
	
    </style>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url().$currentLang."/welcome/testFront"; ?>">Max Project 2016</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#"><?php echo lang('headerHome'); ?></a></li>
				<li><a href="#about">Join Event</a></li>
				<li><a href="#contact">Admin Panel</a></li>
				<!--<li><a href="#contact"><?php //echo $showLoginForm; ?></a></li>  -->
			</ul>
			
		<!--**** Login Form (Obselete)****-->	
          <form class="navbar-form navbar-right" id="header-loginForm" name="userForm" style="display:none<?php //echo $showLoginForm; ?>">
            <div class="form-group">
              <input type="text" placeholder="<?php echo lang('headerFormUserName'); ?>" class="form-control" name="userName">
            </div>
            <div class="form-group">
              <input type="password" placeholder="<?php echo lang('headerFormPwd'); ?>" class="form-control" name="password">
            </div>
            <input type="button" onclick="loginValidation('<?php echo $currentLang; ?>')" class="btn btn-success" value="Login"/>
          </form>
         <!-- **** Login button ****-->
         <ul class="nav navbar-nav navbar-right" id="header-userInfo" style="<?php echo $showLoginForm; ?>">
				<li class=""><a href="<?php echo base_url().$currentLang."/welcome/signIn"; ?>">Login</a></li>				
		 </ul>
          
         <!--**** Logged in info ****-->	
         
          <ul class="nav navbar-nav navbar-right" id="header-userInfo" style="<?php echo $showLoginInfo; ?>">
				<li class="active"><a>Hello, <?php echo $currentUserName; ?>!</a></li>
				<li><a href="<?php echo base_url()."welcome/logout"; ?>"><?php echo lang('headerLogout'); ?></a></li>
		  </ul>
          
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <div class="container" style="margin-top:15px;" id="alertBoxContainer">


    </div>
    <!-- 
    <div class="container" style="margin-top:15px;">
        <div class="alert alert-success" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Success! </strong>
            Product have added to your wishlist.
    </div>
    <div class="alert alert-danger" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <strong>Failed! </strong>
            Warning Message
    </div> -->