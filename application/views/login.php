<html lang='en'>
<head>
	<link rel='stylesheet' href='<?php echo base_url();?>css/bootstrap.min.css'>
	<link rel='stylesheet' href='<?php echo base_url();?>css/styles.css'>
	<script src='<?php echo base_url();?>js/jquery-1.11.1.min.js'></script>
</head>
<body class='login'>
	<div class='container'>
		<div class 'row'>
			<div class='col-md-6 col-md-offset-3'>
				<div class='loginWrapper'>
					<div class='panel panel-primary login'>
						<div class='panel-heading text-center'>
							<h3 class='text-center'><strong><i class='fa fa-truck fa-2x'></i> LANDWAY CARGO SERVICES<strong></h3>
						</div>
						<div class='panel-body login'>
							<div class='row'>
								<div class='col-md-8 col-md-offset-2'>
									<?php echo form_open( "auth/login", array( 'class'=>'form-horizontal'));?>
									<?php if(validation_errors()): ?>
									<div class='form-group'>
										<div class='alert alert-danger'>
											<p>Invalid login credentials</p>
										</div>
									</div>

									<?php endif; ?>
									<div class='form-group'>
										<?php echo form_input(array( 'id'=>'username', 'name'=>'username', 'class'=>'form-control', 'placeholder' => 'Username', 'autocomplete'=>'off'));?>
									</div>
									<div class='form-group'>
										<?php echo form_input(array( 'id'=>'password', 'name'=>'password', 'class'=>'form-control', 'placeholder' => 'Password','type'=>'password'));?>
									</div>
								</div>
							</div>
						</div>
						<div class='panel-footer'>
							<button type='submit' class='btn btn-success btn-block'><i class='fa fa-lock'></i> LOGIN</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>