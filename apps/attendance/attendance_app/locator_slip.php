


<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/jquery-ui/jquery-ui.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

<style>
  .fixed-dialog{
  position: fixed;
  /* top: 50px !important;
  left: 250px !important; */
  height: 600px !important;
  z-index:1049 !important;
}
#scroll-wrap {
    max-height: 50vh;
    overflow-y: auto;
}

::-webkit-scrollbar { 
    display: none; 
}
</style>


  <div class="content-wrapper">
  <section class="content-header">
      <h1>
        Locator Slip (DTRAS)
        <button  type="button" class="btn btn-primary btn-flat" style="float:right;" data-toggle="modal" data-target="#modal-primary">Upload DTRAS</button>
      </h1>
    </section>
    <section class="content">
    <div id="dialog" style="display: none"></div>

    <div class="modal fade" id="modal-primary">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-primary">
 
                <h3 class="modal-title text-center">Upload CSV</h3>
              </div>
              <div class="modal-body">

              <form role="form" class="generic_form_trigger" data-url="attendance">
                <input type="hidden" name="action" value="upload_dtras">
                <div class="form-group">
                  <label for="exampleInputFile">Upload Zip Only</label>
                  <input required accept=".csv" type="file" name="logzips" multiple="multiple" id="exampleInputFile">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
      </div>


    <div class="row">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-5">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="employee_selection" name="employee" ></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                  <input id="from" type="date" class="form-control pull-right">
                    </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                      <input id="to" type="date" class="form-control pull-right">
                    </div>
                </div>
              </div>

              <div class="col-md-1">
                <div class="form-group">
                  <button onclick="filter();" class="btn btn-primary">Filter</button>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            
          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered" id="dtras_table" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th>Employee</th>
                              <th>Type</th>
                              <th>Date</th>
                          </tr>
                      </thead>
            </table>
          </div>
          <div class="card-footer">
            Footer
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><b>ENCODE LOCATOR SLIP</b></h3>
          </div>
          <div class="card-body">
          <?php 
//           $data = query("SELECT
//     YEAR(DATE) AS year,
//     LPAD(MONTH(DATE), 2, '0') AS month,
//     COUNT(*) AS count
// FROM
//     tblbiologs
// GROUP BY
//     YEAR(DATE),
//     MONTH(DATE)
// ORDER BY
//     YEAR(DATE),
//     MONTH(DATE)");

// $data = [];
     ?>

    <!-- <table class="table table-bordered table-striped">
      <thead>
        <th>Year</th>
        <th>Month</th>
        <th>Count</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php foreach($data as $row): ?>
        <tr>
          <td><?php echo($row["year"]); ?></td>
          <td><?php echo($row["month"]); ?></td>
          <td><?php echo($row["count"]); ?></td>
          <td><form class="generic_form_trigger" data-url="attendance">
              <input type="hidden" name="year" value="<?php echo($row["year"]); ?>">
              <input type="hidden" name="month" value="<?php echo($row["month"]); ?>">
              <input type="hidden" name="action" value="repair_logs">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Repair</button>

            </form></td>
        </tr>
        <?php endforeach; ?>
      </tbody>

    </table> -->



    <form class="generic_form_trigger" autocomplete="off" data-url="attendance">
      <input type="hidden" name="action" value="newDTRAS">

          <div class="form-group">
            <label>Select Employee</label>
              <select required class="form-control" id="employee_selection2" name="employee" style="width: 100%;"></select>
          </div>


          <div class="row">
              <div class="col-md-6">


              <div class="bootstrap-timepicker">
                  <div class="form-group">
                    <label>AM OUT:</label>

                    <div class="input-group date" id="timepickerAM" data-target-input="#amoutext">
                 
                      <input name="amout" placeholder="Select Time" type="text" id="amoutext" class="form-control datetimepicker-input"  data-target="#timepickerAM" data-toggle="datetimepicker"/>
                      <div class="input-group-append">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                      </div>
                  </div>
                </div>
                </div> 


                <div class="col-md-6">


              <div class="bootstrap-timepicker">
                  <div class="form-group">
                    <label>AM OUT:</label>

                    <div class="input-group date" id="timepickerPM" data-target-input="#pmintext">
                 
                      <input name="pmin" placeholder="Select Time" type="text" id="pmintext" class="form-control datetimepicker-input" data-target="#timepickerPM" data-toggle="datetimepicker"/>
                      <div class="input-group-append">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                      </div>
                  </div>
                </div>
                </div> 
              </div>



              <div class="form-group">
                  <label>Date:</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                    <input required name="date_included" type="text" class="form-control pull-right" id="datepicker">
                  </div>
                  <!-- /.input group -->
                </div>


                <div class="form-group">
                <label>Type</label>
                  <select class="form-control select2" name="type" id="type_select" style="width: 100%;">
                    <option value="OFFICIAL" selected>OFFICIAL</option>
                    <option value="PERSONAL" >PERSONAL</option>
                  </select>
              </div>


              <div class="form-group">
                        <label>Reason</label>
                        <textarea name="reason" rows="3" placeholder="(OPTIONAL)" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                      </div>

              <button type="submit" class="btn btn-primary">Submit</button>

              <!-- <div class="form-group">
                <label>Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input name="date_included" type="text" class="form-control pull-right" id="datepicker">
                </div>
              </div> -->

       







            

          </div>
        </div>

      </div>
    </div>


    <div class="modal fade" id="modal_edit_dtras">
      <div class="modal-dialog modal-lg">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
         
              <h3 class="modal-title text-center">Update DTRAS</h3>
          </div>
          <div class="modal-body fetcher">
                
          </div>
        </div>
      </div>
    </div>


    



    </section>
</div>
   
  </div>
  <?php 
    require("layouts/footer.php");
  ?>

  <script src="AdminLTE_new/plugins/sweetalert2/sweetalert2.min.js"></script>
  <script src="AdminLTE_new/plugins/select2/js/select2.full.min.js"></script>
  <script src="AdminLTE_new/dist/js/adminlte.min.js"></script>


  <!-- <script src="AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="AdminLTE/assets/js/jquery-1.12.0.js"></script>
  <script src="AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="AdminLTE/bower_components/fastclick/lib/fastclick.js"></script> -->

  <script src="AdminLTE_new/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="AdminLTE_new/plugins/jszip/jszip.min.js"></script>
<script src="AdminLTE_new/plugins/pdfmake/pdfmake.min.js"></script>
<script src="AdminLTE_new/plugins/pdfmake/vfs_fonts.js"></script>
<script src="AdminLTE_new/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="AdminLTE_new/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="AdminLTE_new/plugins/moment/moment.min.js"></script>
<script src="AdminLTE_new/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

  <!-- <script src="AdminLTE/dist/js/adminlte.min.js"></script> -->
  <!-- <script src="AdminLTE/dist/js/demo.js"></script> -->
  <!-- <script src="AdminLTE/bower_components/sweetalert/sweetalert2.min.js"></script> -->
  <!-- <script src="AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script> -->
  <script>
  $('#employee_selection').select2({
    minimumInputLength: 3,
    placeholder: "Search by Biometric ID, First Name, Last Name",
    ajax: {
        url: 'ajax_employees',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.name,
                  id: item.Employeeid,
                }
            })
          }
        }
      },
    });


    // $("#amoutext").attr('readonly',true);
  function checkamout() {
  var checkBox = document.getElementById("amoutcheck");
  if (checkBox.checked == true){
  
  $("#amoutext").attr('readonly',false);
  } else {
  $('#amoutext').val("");
  $("#amoutext").attr('readonly',true);
  }
}
$("#pmintext").attr('readonly',true);
function checkpmin() {
  var checkBox = document.getElementById("pmincheck");
  if (checkBox.checked == true){
  
  $("#pmintext").attr('readonly',false);
  } else {
  $('#pmintext').val("");
  $("#pmintext").attr('readonly',true);
  }
}

$('#datepicker').datepicker({
  multidate: true,
	format: 'yyyy-mm-dd'
})


$('#timepickerAM').datetimepicker({
      format: 'HH:mm',
      defaultDate: moment('2024-01-08T12:00:00'),
      // useCurrent: false
    })
    $('#amoutext').val("");
    $('#timepickerPM').datetimepicker({
      format: 'HH:mm',
      defaultDate: moment('2024-01-08T13:00:00'),
      // useCurrent: false
    })
    $('#pmintext').val("");


    $('#employee_selection2').select2({
    minimumInputLength: 3,
    placeholder: "Search by Biometric ID, First Name, Last Name",
    ajax: {
        url: 'ajax_employees',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.name,
                  id: item.Employeeid,
                }
            })
          }
        }
      },
    });

    var datatable = 
            $('#dtras_table').DataTable({
                // "searching": false,
                "pageLength": 10,
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                searching: false,
                "bLengthChange": false,
                "ordering": false,
                // "info":     false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                
                'ajax': {
                    'url':'attendance',
                     'type': "POST",
                     "data": function (data){
                        data.action = "locator_datatable";
                     }
                },
                'columns': [
                    { data: 'edit', "orderable": false },
                    { data: 'delete', "orderable": false },
                    { data: 'name', "orderable": false },
                    { data: 'Type', "orderable": false  },
                    { data: 'date_encoded', "orderable": false  },
                ],
                "footerCallback": function (row, data, start, end, display) {
                    
                }
            });


            function filter() {
            var selectData = $('#employee_selection').select2('data');
            var id = '';
            var from = $('#from').val();
            var to = $('#to').val();
            console.log(from);
            console.log(to);
            if (selectData[0]) {
                id = selectData[0].id;
            }
            // console.log(selectData);
            if(jQuery.isEmptyObject(selectData)){
            datatable.ajax.url('attendance?action=locator_datatable&from='+from+'&to='+to).load();
            }
            else{
            datatable.ajax.url('attendance?action=locator_datatable&employee=' + id + '&from=' + from + '&to=' + to).load();
            }
        }


  function awit(wildcard){
  var link = "attendance?action=modal_edit_dtras&dtras_id="+wildcard;
  Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
  $('.modal-body').load(link,function(){
    swal.close();
  $('#modal_edit_dtras').modal({show:true});
        });
  // alert("awit");
}






function delete_dtras(wildcard){
  var promptmessage = 'This DTRAS will be deleted';
  var prompttitle = 'Data Deletion';


  // Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
  Swal.fire({
    title: prompttitle,
    text: promptmessage,
    type: 'info',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    
    if (result.value) {
      Swal.fire({
        title: 'Please wait...', 
        imageUrl: 'AdminLTE/dist/img/loader.gif', 
        // allowOutsideClick: false,
        // allowEscapeKey: false,
        showConfirmButton: false
      });
      $.ajax({
        type: 'post',
        url: 'attendance',
        data: 'dtras_id=' + wildcard + '&action=delete_dtras' ,
        success: function (results) {
          
          var o = jQuery.parseJSON(results);
          if(o.status === "success") {
            Swal.fire({title: "Submit success",
              text: o.message,
              type:"success"})
            .then(function () {
              swal.close();
              //window.location.replace('./applicant.php?page=list');
              window.location.replace(o.link);
            });
          }
          else {
            Swal.fire({
              title: "Error!",
              text: o.message,
              type:"error"
            });
          }
        },
        error: function(results) {
          Swal.fire("Error!", "Unexpected error occur!", "error");
        }
      });
    }
  });

}


  </script>
