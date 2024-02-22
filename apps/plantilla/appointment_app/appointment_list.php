<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <section class="content-header">
      <h1>
        Appointment
      </h1>
    </section>
    <section class="content">
    <style>
      .table tr td {
        font-size:14px !important;
      }

      .table tr td i{
        font-size:12px !important;
      }
      .table > tbody > tr > td {
     vertical-align: middle;
}
textarea {
            resize: none;
        }
    </style>


    <div class="modal fade" id="modalUpdate">
      <div class="modal-dialog modal-xl">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Update Position</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="position" autocomplete="off">
                  <input type="hidden" name="action" value="update">
                  <div class="fetch-data"></div> 
                <div class="box-footer">
                  <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                  <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modalUpdate">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="generic_form_trigger" data-url="position" autocomplete="off">
                  <input type="hidden" name="action" value="update">
                  <div class="fetch-data"></div> 
                <div class="box-footer">
                  <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                  <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                </div>
            </form>
          </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


    <div class="card">
            <!-- /.box-header -->
            <div class="card-header">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="employee_selection" name="employee"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
              <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="position_select" name="position"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="department_select" name="department"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
               
                  <button onclick="filter();" class="btn btn-primary">Filter</button>
                  <!-- /.input group -->
              
                  <button onclick="filter();" class="btn btn-success">Print</button>
                  <!-- /.input group -->
              </div>
            </div>
            
          </div>
            <div class="card-body">
              <div class="table-responsive">
                    <table class="table table-bordered" id="datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th>Item #</th>
                              <th>Position</th>
                              <th>Functional</th>
                              <th>SG</th>
                              <th>Step</th>
                              <th>Schedule</th>
                              <th>Basic Salary</th>
                              <th>Department</th>
                              <th>Incumbent</th>
                              <th>Reason of Vacancy</th>
                              <th>Vacated By</th>
                          </tr>
                      </thead>
                  </table>
            </div>
            </div>
          </div>
    </section>
</div>
   
  </div>
  <?php 
    require("layouts/footer.php");
  ?>
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
<script src="AdminLTE_new/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="AdminLTE_new/plugins/select2/js/select2.full.min.js"></script>
<!-- <script src="AdminLTE_new/dist/js/adminlte.min.js"></script> -->

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


    $('#position_select').select2({
    minimumInputLength: 3,
    placeholder: "Search Position Name, Code etc...",
    ajax: {
        url: 'ajax_position',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.supplier,
                  id: item.id,
                }
            })
          }
        }
      },
    });

    $('#department_select').select2({
    minimumInputLength: 3,
    placeholder: "Search Department Name, Code etc...",
    ajax: {
        url: 'ajax_department_appointment',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.name,
                  id: item.id,
                }
            })
          }
        }
      },
    });


    var datatable = 
            $('#datatable').DataTable({
                "searching": false,
                "lengthMenu": [10, 25, 50, 100, -1],
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                // "bLengthChange": false,
                "ordering": false,
                // "info":     false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                
                'ajax': {
                    'url':'appointment',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                     }
                },
                'columns': [
                    { data: 'item_no', "orderable": false },
                    { data: 'position', "orderable": false },
                    { data: 'functional_title', "orderable": false },
                    { data: 'SGRate', "orderable": false },
                    { data: 'step', "orderable": false },
                    { data: 'salary_schedule', "orderable": false },
                    { data: 'salary', "orderable": false },
                    { data: 'department', "orderable": false },
                    { data: 'incumbent', "orderable": false },
                    { data: 'vacated_by', "orderable": false },
                    { data: 'reason_vacancy', "orderable": false },
                
                ],
                "footerCallback": function (row, data, start, end, display) {
                    // var api = this.api(), data;
                    

                    // Remove the formatting to get integer data for summation
                    // var intVal = function (i) {
                    //     return typeof i === 'string' ?
                    //         i.replace(/[\$,]/g, '') * 1 :
                    //         typeof i === 'number' ?
                    //             i : 0;
                    // };

                    // // Total over all pages
                    // received = api
                    //     .column(5)
                    //     .data()
                    //     .reduce(function (a, b) {
                    //         return intVal(a) + intVal(b);
                    //     }, 0);
                    //     console.log(received);

                    // $('#currentTotal').html('$ ' + received.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }
            });

//   function filter(job) {
//     // alert(job);
//     // department_id =$('#department_id').val();
//     // with_salary = $('#with_salary').find(":selected").val();
//     datatable.ajax.url('position?position_datatable').load();
// }

$(document).ready(function(){
  $('#modalUpdate').on('show.bs.modal', function (e) {
      var rowid = $(e.relatedTarget).data('id');
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      $.ajax({
          type : 'post',
          url : 'position', //Here you will fetch records 
          data: {
              position_id: rowid, action: "modalUpdate"
          },
          success : function(data){
              $('#modalUpdate .fetch-data').html(data);
              Swal.close();
              // $(".select2").select2();//Show fetched data from database
          }
      });
   });
});

function filter() {
            var empData = $('#employee_selection').select2('data');
            var depData = $('#department_select').select2('data');
            var posData = $('#position_select').select2('data');
    
            var empId ="";
            var depId ="";
            var posId ="";

            if (empData[0])
                empId = empData[0].id;
            if (depData[0])
                depId = depData[0].id;
            if (posData[0])
                posId = posData[0].id;
        
            else{
            datatable.ajax.url('appointment?action=datatable&empId=' + empId + '&depId=' + depId + '&posId=' + posId).load();
            }
        }


  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>