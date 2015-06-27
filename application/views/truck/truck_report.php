<?php include "/../admin_lte_header.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Reports</h1>
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
                                    <?php echo form_open( '', array( 'id'=>'myForm'));?>
                                    <div class="form-group" id="report_type">
                                        <?php $type = array(''=>'Choose Report Type', 'income'=>'Gross Income', 'prepaid'=>'Total Prepaid', 'received'=>'Total Received', 'backload'=>'Total Backload'); ?>
                                        <?php echo form_dropdown('report_type', $type, '', 'class=form-control');?>
                                    </div>
                                    <div class='form-group' id='truck'>
                                        <?php $attrib="id=trucks class=form-control" ;?>
                                        <?php $options[ 'all']='All Trucks' ;?>
                                        <?php foreach($trucks as $truck):?>
                                        <?php $options[$truck->truck_id] = $truck->plate_number;?>
                                        <?php endforeach;?>
                                        <?php echo form_dropdown( 'truck', $options, '', $attrib);?>
                                        <span class='error-message control-label'></span>
                                    </div>
                                    <div class='form-group' id='date'>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control" name="start_date" placeholder="From" />
                                            <span class="input-group-addon">-</span>
                                            <input type="text" class="form-control" name="end_date" placeholder="To"  />
                                        </div>
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
        $('.sidebar-menu > li:nth-child(9)').addClass('active');

        $('.input-daterange').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd"
        });

        $('#trucks').hide();
        $("#report_type").change(function(){
           $( "#report_type option:selected").each(function(){
               if($(this).attr("value") == "income"){
                    $('#trucks').show();
               }else{
                    $('#trucks').hide();
               }
           });
        }).change();
    });

    $('#myForm').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
      
        $('#truck, #start_date, #end_date').removeClass('has-error');

        $.ajax({
            type: 'post',
            url: '<?php echo base_url();?>truck/generate_report',
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
                    if (response.error.report_type) {
                        $('#report_type').addClass('has-error');
                    }
                    if (response.error.start_date) {
                        $('#date').addClass('has-error');
                    }
                    if (response.error.end_date) {
                        $('#date').addClass('has-error');
                    }
                }
            }
        });
    });
</script>

</body>
</html>
