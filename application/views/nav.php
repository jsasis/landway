
<body>
	<!-- Notification -->
	<div class='notification'>
		<strong><h4><i class='fa fa-check-circle'></i>
			<span id='message'> Success! New Waybill has been created.</span></h4></strong>
	</div>
	<!--  Navigation -->
	<div id='wrapper'>
		<!-- Main Navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="margin-bottom: 0px">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo base_url();?>waybill">Landway Cargo Services</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							<span class='glyphicon glyphicon-user'></span> 
								<?php $session_data = $this->session->userdata('logged_in'); echo $session_data['first_name']. ' ' .$session_data['last_name'];?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo base_url();?>user/changePassword"> Change Password</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo base_url();?>logout"><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
		<!-- Sidebar -->
		<div class="navbar-default sidebar no-print" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<!-- Dashboard -->
					<li>
						<a href='<?php echo base_url();?>dashboard'><i class='fa fa-dashboard'></i> Dashboard</a></li>
						<?php if($this->session->userdata('logged_in')['role'] !== 'admin'):?>
							<li><a href='<?php echo base_url();?>waybill/'>	<span class='glyphicon glyphicon-home'></span> Waybills</a></li>
							<li><a href='<?php echo base_url();?>manifest/'><span class='glyphicon glyphicon-th-list'></span> Manifest</a></li>
						<?php else:?>
					<!-- Waybills -->
					<li>
						<a href='#' style="background: none"><span class='glyphicon glyphicon-home'></span> Waybills <i class='fa arrow'></i></a>
						<ul class='nav nav-second-level'>
							<li><a href='<?php echo base_url();?>waybill/add'><i class='fa fa-angle-double-right'></i> Create New </a></li>
							<li><a href='<?php echo base_url();?>waybill/'><i class='fa fa-angle-double-right'></i> Manage </a></li>
							<li><a href='<?php echo base_url();?>waybill/getPrepaid'><i class='fa fa-angle-double-right'></i> Prepaid</a></li>
							<li><a href='<?php echo base_url();?>waybill/getUncollected'><i class='fa fa-angle-double-right'></i> Uncollected</a></li>
							<li><a href='<?php echo base_url();?>waybill/getBackload'><i class='fa fa-angle-double-right'></i> Backload</a></li>
						</ul>
					</li>
					<!-- Manifest -->
					<li><a href='<?php echo base_url();?>manifest/'>	<span class='glyphicon glyphicon-th-list'></span> Manifest</a></li>
					<!-- Customers -->
			       	<li><a href='<?php echo base_url();?>customer/'>    <span class='glyphicon glyphicon-user'></span> Customers</a></li>
			       	<!-- Rates -->
			       	<li>
			       		<a href='#' style="background: none"><span class='glyphicon glyphicon-list-alt'></span> Rates <i class="fa arrow"></i></a>
			       		<ul class='nav nav-second-level'>
			       			<li><a href='<?php echo base_url();?>unit/'><i class='fa fa-angle-double-right'></i> Units</a></li>
			       			<li><a href='<?php echo base_url();?>unit_category/'><i class='fa fa-angle-double-right'></i> Items</a></li>
			       			<!-- <li><a href='#'><i class='fa fa-angle-double-right'></i> Price History</a></li> -->
			       		</ul>
			       	</li>
			       	<!-- Payment -->
			       	<li><a href='<?php echo base_url();?>payment/'>     <i class='fa fa-calculator'></i> Payments</a></li>
			       	<!-- Truck -->
			       	<li>
			       		<a href='#' style="background: none"><i class='fa fa-truck'></i> Truck <i class='fa arrow'></i></a>
			       		<ul class='nav nav-second-level'>
			       			<li><a href='<?php echo base_url();?>truck/'><i class='fa fa-angle-double-right'></i> Manage </a></li>
			       			<li><a href='<?php echo base_url();?>truck/create_report'><i class='fa fa-angle-double-right'></i> Report </a></li>
			       		</ul>
			       	</li>
			       	<!-- Users -->
			       	<li><a href='<?php echo base_url();?>user/show'>    <span class='glyphicon glyphicon-user'></span> Users</a></li>
			       	<?php endif;?>
				</ul>
			</div>
		</div>
