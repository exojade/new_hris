<link rel="stylesheet" href="AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<style>
.rheader {
    padding: 10px 10px 10px 10px !important;

}
</style>

<!-- <h5 class="rheader bg-primary">Employment Information</h5> -->

<form method="post" action="attendance" autocomplete="off">
<input type="hidden" name="action" value="update_dtras">
<input type="hidden" name="dtras_id" value="<?php echo($dtras["dtras_id"]); ?>">
<div class="form-group">
                <label>Employee</label>
                  <input type="text" readonly value="<?php echo($name); ?>" class="form-control">
              </div>

              <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>AM In</label>
                        <input type="text" value="<?php echo($dtras["AMArrival"]); ?>" name="am_in" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>AM Out</label>
                        <input type="text" value="<?php echo($dtras["AMDeparture"]); ?>" name="am_out" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>PM In</label>
                        <input type="text" value="<?php echo($dtras["PMArrival"]); ?>" name="pm_in" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>PM Out</label>
                        <input type="text" value="<?php echo($dtras["PMDeparture"]); ?>" name="pm_out" class="form-control">
                    </div>
                </div>
              </div>

      

              <div class="form-group">
                  <label>Date Included:</label>
                    <div class="input-group date">
                    <input name="date_included" type="text" value="<?php echo($dates); ?>" class="datepicker2 form-control">
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>


              <div class="form-group">
                <label>Type</label>
                  <select class="form-control select2" name="type" style="width: 100%;">
                    <option value="<?php echo($dtras["Type"]); ?>" selected ><?php echo($dtras["Type"]); ?></option>
                    <option value="PERSONAL">PERSONAL</option>
                    <option value="OFFICIAL">OFFICIAL</option>
                  </select>
              </div>

              <div class="form-group">
                  <label>Reason</label>
                  <textarea class="form-control" name="reason" rows="3" placeholder="(OPTIONAL)"><?php echo($dtras["reason"]); ?></textarea>
                </div>


<button type="submit" class="btn btn-flat btn-primary pull-right">Submit</button>



</form>
<br>
<br>
<script src="AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>


<script>
// $(".select2").select2();
$('.datepicker2').datepicker({
  multidate: true,
	format: 'yyyy-mm-dd'
})
$('.generic_form').submit(function(e) {
  e.preventDefault();
  Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
  var url = $(this).data('url');
  $.ajax({
    type: 'post',
    url: url,
    data: $(this).serialize(),
    success: function (results) {
      
        var o = jQuery.parseJSON(results);
        console.log(o);
        if(o.result == 'success'){
            modal.close();
        }
        else if(o.result == 'failed'){
            swal({
                title: o.title,
                text: o.message,
                type: "error"
            }).then(function() {
                swal.close();
            });
        }
    }
  });
});

$('#datepicker').datepicker({
  multidate: true,
	format: 'yyyy-mm-dd'
})

</script>
