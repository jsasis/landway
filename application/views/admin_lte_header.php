<?php 
	$session_data = $this->session->userdata('logged_in'); 
	$user_id    = $session_data['user_id'];
	$first_name = $session_data['first_name'];
	$last_name  = $session_data['last_name'];

	$base_url = base_url();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Landway-Cargo</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<!-- Bootstrap 3.3.2 -->
	<link href="<?php echo $base_url;?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />    
	<!-- Theme style -->
	<link href="<?php echo $base_url;?>css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
	<!-- AdminLTE Skin Blue-->
	<link href="<?php echo $base_url;?>css/skin-blue.min.css" rel="stylesheet" type="text/css" />
	<!-- Font Awesome -->
	<link rel='stylesheet' href='<?php echo $base_url;?>fonts/font-awesome/css/font-awesome.min.css'>
	<!-- JQuery Loader -->
	<link rel='stylesheet' href='<?php echo $base_url;?>css/jquery.loader.min.css'>
	<!-- Datepicker -->
	<link rel='stylesheet' href='<?php echo $base_url;?>css/datepicker3.css'>
	<!-- Tablesorter -->
	<link rel="stylesheet" href="<?php echo $base_url;?>css/theme.default.css">
	<!-- Custom Styles -->
	<link rel="stylesheet" href="<?php echo $base_url;?>css/styles.css">
</head>
	<body class="skin-blue">
		<div class="wrapper">
		
			<header class="main-header">
				<!-- Logo -->
				<a href="index2.html" class="logo"><b>Admin</b>LTE</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="<?php echo $base_url;?>img/avatar5.png" class="user-image" alt="User Image"/>
									<span class="hidden-xs"><?php echo $first_name .' '. $last_name;?></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<img src="<?php echo $base_url;?>img/avatar5.png" class="img-circle" alt="User Image" />
										<p>
											<?php echo $first_name .' '. $last_name;?>
										</p>
									</li>
									<!-- Menu Body -->
									<li class="user-body">
										<div class="col-xs-12 text-center">
											<a href="">Change Password</a>
										</div>
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="#" class="btn btn-default btn-flat">Profile</a>
										</div>
										<div class="pull-right">
											<a href="<?php echo base_url();?>logout" class="btn btn-default btn-flat">Sign out</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<img src="<?php echo $base_url;?>img/avatar5.png" class="img-circle" alt="User Image" />
						</div>
						<div class="pull-left info">
							<p><?php echo $first_name .' '. $last_name;?></p>
						</div>
					</div>
					<!-- search form -->
				
						<a class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#addPost"><i class="fa fa-plus-square"></i> Add New Post</a>
						<!-- <div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search..."/>
							<span class="input-group-btn">
								<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
							</span>
						</div> -->
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">MAIN NAVIGATION</li>
						<li id="dashboard" class="active treeview">
							<a href="<?php echo $base_url;?>dashboard">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>
						<?php if($this->session->userdata('logged_in')['role'] !== 'admin'):?>
							<li><a href='<?php echo base_url();?>waybill/'>	<span class='glyphicon glyphicon-home'></span> Waybills</a></li>
							<li><a href='<?php echo base_url();?>manifest/'><span class='glyphicon glyphicon-th-list'></span> Manifest</a></li>
						<?php else: ?>
						<li id="waybill" class="treeview">
							<a href="#">
								<i class="fa fa-files-o"></i>
								<span>Waybill</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?php echo $base_url;?>waybill/add"><i class="fa fa-circle-o"></i> Create New</a></li>
								<li><a href="<?php echo $base_url;?>waybill/"><i class="fa fa-circle-o"></i> Manage</a></li>
								<li><a href="<?php echo $base_url;?>waybill/getUncollected"><i class="fa fa-circle-o"></i> Uncollected</a></li>
								<li><a href="<?php echo $base_url;?>waybill/getPrepaid"><i class="fa fa-circle-o"></i> Prepaid</a></li>
								<li><a href="<?php echo $base_url;?>waybill/getBackload"><i class="fa fa-circle-o"></i> Backload</a></li>
							</ul>
						</li>
						<li id="manifest">
							<a href="<?php echo $base_url;?>manifest/">
								<i class="fa fa-th-list"></i> <span>Manifest</span>
							</a>
						</li>
						<li id="customer">
							<a href="<?php echo $base_url;?>customer/">
								<i class="fa fa-group"></i> <span>Customer</span>
							</a>
						</li>
						<li id="truck">
							<a href="<?php echo $base_url;?>truck/">
								<i class="fa fa-truck"></i> <span>Truck</span>
							</a>
						</li>
						<li id="rate" class="treeview">
							<a href="#">
								<i class="fa fa-barcode"></i> <span>Rate</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?php echo $base_url;?>unit"><i class="fa fa-circle-o"></i> Unit</a></li>
								<li><a href="<?php echo $base_url;?>unit_category"><i class="fa fa-circle-o"></i> Item</a></li>
							</ul>
						</li>
						<li id="user_account">
							<a href="<?php echo $base_url;?>user/show/">
								<i class="fa fa-user"></i> <span>User Account</span>
							</a>
						</li>
						<li id="report" class="treeview">
							<a href="#">
								<i class="fa fa-folder"></i> <span>Report</span>
								<i class="fa fa-angle-left pull-right"></i>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?php echo $base_url;?>truck/report/gross"><i class="fa fa-circle-o"></i> Reports</a></li>
								<li><a href="<?php echo $base_url;?>truck/report/annual"><i class="fa fa-circle-o"></i> Annual Income</a></li>
							</ul>
						</li>

						<?php endif; ?>
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>
			<!-- Notification -->
			<div class='notification'>
				<strong>
					<h4>
						<i class='fa fa-check-circle'></i><span id='message'> Success! New Waybill has been created.</span>
					</h4>
				</strong>
			</div>