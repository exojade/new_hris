<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<!-- <link rel="stylesheet" href="AdminLTE_new/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css"> -->
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<style>
        /* Custom styles for the scrollable table */
        .scrollable-table {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 15px; /* Adjust as needed */
        }


    </style>

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>MIRA DATABASE</h1>
            </div>
            <div class="col-sm-6">
            <a class="float-right btn btn-primary" data-toggle="modal" data-target="#myModal">Upload</a>
            </div>
        </div>
    </div>
</section>
    <section class="content">



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="generic_form_trigger" data-url="mira_database">
        <input type="hidden" name="action" value="upload_records">
        <div class="form-group">
          <label for="exampleInputFile">Upload Zip Only</label>
          <input required="" accept=".csv" type="file" name="logzips" multiple="multiple" id="exampleInputFile">
        </div>
        <p>Modal content goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="transferModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Transfer to Original Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="generic_form_trigger" data-url="mira_database">
        <input type="hidden" name="action" value="update_employee">
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="rowIndex" id="rowIndex">
        <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" required class="form-control" id="employee_selection" name="employee">
                    </select>
                    </div>
                </div>



        
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

    <style>
      .table tr td {
        font-size:14px !important;
      }

      .table tr td i{
        font-size:12px !important;
      }
    </style>
    <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered scrollable-table" id="employees-datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th>Name</th>
                              <th>Position</th>
                              <th>Job Type</th>
                              <th>Office</th>
                              <th>Gender Male</th>
                              <th>Gender Female</th>
                              <th>Date Hired</th>
                              <th>Original Appointment</th>
                              <th>Continuous Year</th>
                              <th>New Year Started</th>
                              <th>Latest Promotion</th>
                              <th>New Latest Promotion</th>
                              <th>Eligibility</th>
                              <th>Salary Grade</th>
                              <th>Salary Step</th>
                              <th>Birthday</th>
                              <th>Place of Birth</th>
                              <th>Education</th>
                              <th>Graduates</th>
                              <th>Address</th>
                              <th>Contact</th>
                              <th>Email</th>
                              <th>Skills</th>
                              <th>Indigenous</th>
                              <th>Disability</th>
                              <th>Solo Parent</th>
                              <th>Philhealth</th>
                              <th>TIN</th>
                              <th>GSIS</th>
                              <th>PAGIBIG</th>
                              <th>Civil Status</th>
                              <th>Children</th>
                              <th>Blood Type</th>
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
<script src="AdminLTE_new/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="AdminLTE_new/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="AdminLTE_new/plugins/select2/js/select2.full.min.js"></script>




<script>
$(function () {
    var datatable = 
            $('#employees-datatable').DataTable({
                // "searching": false,
                "pageLength": 2,
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
                    'url':'mira_database',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                     }
                },
                'columns': [
                    { data: 'id', "visible": false, "searchable": false },
                    { data: 'action', "orderable": false },
                    { data: 'name', "orderable": false },
                    { data: 'position', "orderable": false },
                    { data: 'job_type', "orderable": false },
                    { data: 'office', "orderable": false },
                    { data: 'gender_male', "orderable": false },
                    { data: 'gender_female', "orderable": false },
                    { data: 'date_hired', "orderable": false },
                    { data: 'original_appointment', "orderable": false },
                    { data: 'continuous_year', "orderable": false },
                    { data: 'new_year_started', "orderable": false },
                    { data: 'latest_promotion', "orderable": false },
                    { data: 'new_latest_promotion', "orderable": false },
                    { data: 'eligibility', "orderable": false },
                    { data: 'salary_grade', "orderable": false },
                    { data: 'step', "orderable": false },
                    { data: 'birthday', "orderable": false },
                    { data: 'place_birth', "orderable": false },
                    { data: 'education', "orderable": false },
                    { data: 'graduates', "orderable": false },
                    { data: 'address', "orderable": false },
                    { data: 'contact', "orderable": false },
                    { data: 'email', "orderable": false },
                    { data: 'skills', "orderable": false },
                    { data: 'indigenous', "orderable": false },
                    { data: 'disability', "orderable": false },
                    { data: 'solo_parent', "orderable": false },
                    { data: 'philhealth', "orderable": false },
                    { data: 'tin_number', "orderable": false },
                    { data: 'sss_number', "orderable": false },
                    { data: 'pagibig', "orderable": false },
                    { data: 'civil_status', "orderable": false },
                    { data: 'children', "orderable": false },
                    { data: 'blood_type', "orderable": false }
                  
                ],

                'rowCallback': function(row, data) {

                $(row).on('dblclick', function() {
                    // Get the DataTable API instance
                    var table = $('#employees-datatable').DataTable();
                    var rowIndex = table.row(this).index();
                    var employeeId = data.id;
                    $('#transferModal').modal('show');
                    $('#transferModal #id').val(employeeId);
                    $('#transferModal #rowIndex').val(rowIndex);

                });
              },




                "footerCallback": function (row, data, start, end, display) {
             
                }
            });
  })




























    $('#transferModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $('#id').val(id);

        

    });
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



  </script>





  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>