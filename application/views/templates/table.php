<?php include_once('/../includes/header.php'); ?>
<?php include_once('/../includes/sidebar.php'); ?>

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<h3><i class="fa fa-angle-right"></i> Manage <?php echo $page; ?></h3>
		<div class="row mt">
			<div class="col-lg-12">
				<div class="content-panel">
					<!-- Flash messages -->
					<?php if($this->session->flashdata('notification')): ?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php elseif($this->session->flashdata('warning')): ?>
						<div class='alert alert-danger'><?php echo $this->session->flashdata('warning');?></div>
					<?php endif?>

					<!-- Action Buttons -->
					<h4 class="pull-left">
						<button data-toggle="tooltip" title="Delete" data-placement="bottom" id='delete' class='btn btn-danger btn-sm'><i class='fa fa-minus-circle'></i> Delete</button>
					</h4>

					<!-- Search box -->
					<form action='<? echo base_url();?><?php echo strtolower($page); ?>/search' method='POST' class="navbar-form navbar-left pull-right" role="search">
						<div class="input-group">
							<?php echo form_input(array('name'=>'search_key','class'=>'form-control','placeholder'=>'Search...'));?>
							<span class="input-group-btn"><button type='submit' class='btn btn-default' id='search'>Go</button></span>
						</div>
					</form>

					<!-- Data table -->
					<section id="unseen">
						<?php echo $content; ?>
						
					</section>
					
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
		</div><!-- /row -->

		<!-- PAGINATION LINKS -->
		<div class="row mt">
			<div class="col-md-12">
				<div class="pagination-links">
					<div class='pull-left'>
						<?php echo $links;?>
					</div>
					<div class='pull-right'>
						<label class='control-label'>Showing <?php echo ($start == null) ? '1' : $start ?> to <?php echo $end;?> of <?php echo $total;?> results</label>
					</div>
				</div>
			</div>
		</div>

	</section><! --/wrapper -->
</section><!-- /MAIN CONTENT -->

</section><!-- /CONTAINER -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Confirm</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				<button id='yes' type="button" class="btn btn-primary">Yes</button>
			</div>
		</div>
	</div>
</div>

<?php include_once('/../includes/footer.php'); ?>