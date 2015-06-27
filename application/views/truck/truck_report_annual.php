<?php include "/../admin_lte_header.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Annual Income Report</h1>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class='col-xs-12'>
                    <div class="box box-primary">
                        <div class="box-header with-border"><h4>Options</h4></div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php echo form_open( '', array( 'id'=>'myForm','class'=>''));?>
                                    <div class='form-group' id='date'>
                                        <?php echo form_label( 'Select Year', '',array( 'class'=>'control-label'));?>
                                        <input type="text" class="form-control datepicker" id="year" name="year">
                                        <span class='error-message control-label'></span>
                                    </div>
                                    <div class='form-group pull-right'>
                                        <button type='submit' class='btn btn-primary'><i class='fa fa-gears'></i> Generate Report</button>
                                    </div>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                          
                        </div>
                    </div>                  
                </div>
            </div>
            <div class="row" id="preview" style="height: 100%; padding: 25px; margin: 0px; background-color: rgb(230, 230, 230);">
                <div class="col-md-8 col-md-offset-2" style="background-color: #fff;">
                    <iframe id="iframe" frameborder="0" style="width: 100%; height: 500px;" scrolling="no"></iframe>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</div>
<?php include "/../admin_lte_footer.php"; ?>
          
<script type='text/javascript'>
    $(document).ready(function () {
        $('.sidebar-menu > li').removeClass('active');
        $('.sidebar-menu > li:nth-child(8)').addClass('active');

        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            viewMode: "year"
        });
    });

    $('#myForm').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();

        $('#date').removeClass('has-error');

        $.ajax({
            type: 'post',
            url: '<?php echo base_url();?>truck/generate_report_annual',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#iframe').attr('srcdoc', response.result);
                } else {
                    if (response.error.empty) {
                        $("iframe").contents().find("body").html('');
                        alert('There are no records to show.');
                    }
                    if (response.error.year) {
                        $('#date').addClass('has-error');
                    }
                }
            }
        });
    });
</script>

</body>
</html>
