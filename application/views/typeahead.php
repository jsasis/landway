<html>
<head>
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class='container'>
		<div class='row'>
			<div class='col-md-2'>
				<div class='form-group'>
			  		<input type="text" class='form-control' id="search"/>
			  	</div>
			</div>
		</div>
	</div>
</body>
 </html>
<script src="<?php echo base_url();?>js/jquery-1.11.1.min.js"></script>
<script src="<?php echo base_url();?>js/typeahead.js"></script>
 
<script>
   $('#search').typeahead({
   	source: function (query, process) {
	 	$.ajax({
	 		type: 'post',
	 		url: '<?php echo base_url();?>waybill/typeAhead',
	 		dataType: 'json',
	 		success: function(result){
	 			process(result);
	 		}
	 	});
	},
	matcher: function (item) {
	    if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
	        return true;
	    }
	},
	highlighter: function (item) {
	    var regex = new RegExp( '(' + this.query + ')', 'gi' );
	    return item.replace( regex, "<strong>$1</strong>" );
	}
});
</script>
 