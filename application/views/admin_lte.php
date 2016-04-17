<?php include "admin_lte_header.php"; ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class="pull-left">
				Dashboard
				<small>Control panel</small>
			</h1>
			<h4 class="text-right">
				<?php echo date("l, F d,Y");?>
			</h4>
		</section>

		<!-- Main content -->
		<section class="content">
			<?php if($this->session->flashdata('notification')):?>
				<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
			<?php endif?>
			<!-- Small boxes (Stat box) -->
			<div class="row">
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div class="inner">
							<h3><?php echo $count_received ? $count_received : 0; ?></h3>
							<p>Received</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
					</div>
				</div><!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3><?php echo $total_prepaid ? $total_prepaid : 0; ?></h3>
							<p>Prepaid</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
					</div>
				</div><!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							<h3><?php echo $count_uncollected ? $count_uncollected : 0; ?></h3>
							<p>Uncollected</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
					</div>
				</div><!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3><?php echo $backload_count;?></h3>
							<p>Backload</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
					</div>
				</div><!-- ./col -->
			</div><!-- /.row -->
			<!-- Main row -->
			<div class="row">
				<!-- Left col -->
				<section class="col-lg-6">
					<!-- Chat box -->
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">MESSAGE BOARD</h3>
						</div>
						<div class="box-body">
							<!-- chat item -->
							<?php if($posts) :?>
							<?php foreach($posts as $post): ?>
							<div class="item">
								<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?php echo date('F d,Y g:i a', time());?></small>
								<small class="text-muted pull-left text-primary"><i class="fa fa-user"></i> <?php echo $post->first_name . " " . $post->last_name;?></small>
								<div class="clearfix"></div>
								<p class="message">
									<h4><?php echo $post->post_title;?></h4>
									<p><?php  echo $post->post_body;?></p>
									<?php $session = $this->session->userdata('logged_in'); ?>
									<?php if($session['role'] == 'admin' && $session['user_id'] == $post->user_id ):?>
									<div class="tools pull-right">
										<a href="javascript:void(0)" data-post-id="<?php echo $post->id;?>" class="editPost"><i class="fa fa-edit"></i></a>
										<a href="<?php echo $base_url;?>post/delete/<?php echo $post->id;?>"><i class="fa fa-trash-o"></i></a>
									</div>
									<div class="clearfix"></div>
									<?php endif; ?>
								</p>
								<hr>
							</div><!-- /.item -->
							<?php endforeach;?>
							<?php else: ?>
								<p class="text-center"> <?php echo "There are no posts to show."; ?></p>
							<?php endif; ?>
						</div><!-- /.chat -->
					</div><!-- /.box (chat box) -->
				</section><!-- /.Left col -->

				</section><!-- right col -->
			</div><!-- /.row (main row) -->
		</section><!-- /.content -->

	</div><!-- /.content-wrapper -->

<?php include "admin_lte_footer.php"; ?>

</body>
</html>