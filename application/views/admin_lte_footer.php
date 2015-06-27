
<!-- Modal -->
<div class="modal fade modal-primary" id="addPost">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Add New Post</h4>
			</div>
			<div class="modal-body">
				<?php echo form_open('', array('id'=>'newPost'));?>
				<input type="hidden" id="id" name="id">
				<div class="form-group" id="post_title">
					<?php echo form_input(array('name'=>'post_title', 'class'=>'form-control', 'placeholder'=>'Title', 'id'=>'post_title'));?>
					<span class="error-message control-label"></span>
				</div>
				<div class="form-group" id="post_body">
					<?php echo form_textarea(array('name'=>'post_body', 'class'=>'form-control', 'placeholder'=>'Body', 'id'=>'post_body'));?>
					<span class="error-message control-label"></span>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cancel</button>
				<button type="submit" class="btn btn-success"> Publish</button>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div> <!-- /.modal -->

</div>
<!-- Jquery -->
<script src="<?php echo $base_url;?>js/jQuery-1.11.1.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo $base_url;?>js/bootstrap.min.js" type="text/javascript"></script>    
<!-- AdminLTE App -->
<script src="<?php echo $base_url;?>js/app.min.js" type="text/javascript"></script>
<!-- Numeral -->
<script src='<?php echo $base_url;?>js/numeral.min.js'></script>
<!-- JQuery Loader -->
<script src='<?php echo $base_url;?>js/jquery.loader.min.js'></script>
<!-- Datepicker -->
<script src='<?php echo $base_url;?>js/bootstrap-datepicker.js'></script>
<!-- Type Ahead -->
<script src='<?php echo $base_url;?>js/typeahead.js'></script>
<!-- Table Sorter -->
<script src='<?php echo $base_url;?>js/tablesorter.min.js'></script>

<script type="text/javascript">
	$("#newPost").submit(function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$('#post_title, #post_body').removeClass('has-error');
		$('#post_title .error-message, #post_body .error-message').empty();

		$.ajax({
			type: 'post',
			data: data,
			url: '<?php echo $base_url;?>post/save',
			dataType: 'json',
			success: function(response) {
				if(response.success)
				{
					alert("Post has been saved.");
					$("#newPost")[0].reset();
					$("#addPost").modal("hide");
					window.setTimeout(1000, function(){
						window.location = "<?php echo $base_url;?>dashboard";
					});
					
				} else {
					if(response.error.title)
					{
						$("#post_title").addClass("has-error");
						$("#post_title .error-message").html(response.error.title);
					}
					if(response.error.body)
					{
						$("#post_body").addClass("has-error");
						$("#post_body .error-message").html(response.error.body);
					}
				}
			},

		});
	});

	$('.editPost').click(function(){
		var post_id = $(this).data('post-id');

		$.ajax({
			url: '<?php echo $base_url;?>post/edit/' + post_id,
			dataType: 'json',
			success: function(response){
				$('#newPost #id').val(response.id);
				$('#newPost #post_title').val(response.post_title);
				$('#newPost #post_body').val(response.post_body);

				$('#addPost').modal();
			}
		});
	});
</script>