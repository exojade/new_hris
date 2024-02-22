<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <section class="content-header">
  <input type="hidden" id="department_id" value="<?php echo($_GET["id"]); ?>">
      <h1>
      <?php echo($department["DeptName"] . " | " . $department["DeptCode"] ); ?>
      </h1>

      
    </section>
    <section class="content">
    <style>
      .table tr td {
        font-size:11px !important;
      }

      .table tr td i{
        font-size:10px !important;
      }
    </style>
    <div class="card">
    <div class="card-header">
    <button onclick="filter('all');" class="btn btn-primary btn-flat">ALL (<?php echo($all); ?>)</button>
    <button onclick="filter('PERMANENT');" class="btn btn-primary btn-flat">PERMANENT (<?php echo($permanent); ?>)</button>
    <button onclick="filter('COTERMINOUS');" class="btn btn-primary btn-flat">COTERMINOUS (<?php echo($coterminous); ?>)</button>
    <button onclick="filter('CASUAL');" class="btn btn-primary btn-flat">CASUAL (<?php echo($casual); ?>)</button>
    <button onclick="filter('JOB ORDER');" class="btn btn-primary btn-flat">JOB ORDER (<?php echo($job_order); ?>)</button>
    <button onclick="filter('HONORARIUM');" class="btn btn-primary btn-flat">HONORARIUM (<?php echo($honorarium); ?>)</button>
    <button onclick="filter('ELECTIVE');" class="btn btn-primary btn-flat">ELECTIVE (<?php echo($elective); ?>)</button>
    <div class="form-group" style="float:right;">
      <select class="form-control" id="with_salary">
        <option value="all" selected>All</option>
        <option value="with">With Salary</option>
        <option value="without">No Salary</option>
      </select>
    </div>
              </div>
      
            <!-- /.box-header -->
            <div class="card-body">
              <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="department_list_datatable" style="width:100%">
                      <thead>
                      <tr>
                              <th>ID</th>
                              <th width="20%">Fullname</th>
                              <th width="30%">Position</th>
                              <th>Gender</th>
                              <th>Dept</th>
                              <th>SG</th>
                              <th>Step</th>
                              <th>Class</th>
                              <th>Salary</th>
                              <th>LBP</th>
                              <th></th>
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
<script src="AdminLTE_new/dist/js/adminlte.min.js"></script>
<script>
department_id =$('#department_id').val();
    var with_salary = $('#with_salary').find(":selected").val();
    // alert(department_id);
    var department_list_datatable = 
            $('#department_list_datatable').DataTable({
                // "searching": false,
                // "pageLength": 10,
                "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, 1000], [10, 25, 50, 100, 200, 300, 500, 1000] ],
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                "bLengthChange": true,
                "ordering": false,
                // "info":     false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'department',
                     'type': "POST",
                     "data": function (data){
                        data.action = "department_list_datatable";
                        data.department_id = department_id;
                        data.with_salary = with_salary;
                     }
                },
                'columns': [
                    { data: 'Fingerid', "orderable": false },
                    { data: 'fullname', "orderable": false },
                    { data: 'position', "orderable": false  },
                    { data: 'gender', "orderable": false  },
                    { data: 'dept', "orderable": false  },
                    { data: 'salary_grade', "orderable": false  },
                    { data: 'salary_step', "orderable": false  },
                    { data: 'salary_class', "orderable": false  },
                    { data: 'salary', "orderable": false  },
                    { data: 'lbp_number', "orderable": false  },
                    { data: 'action', "orderable": false  },
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
  function filter(job) {
    // alert(job);
    department_id =$('#department_id').val();
    with_salary = $('#with_salary').find(":selected").val();
    department_list_datatable.ajax.url('department?department_list_datatable&department_id=' + department_id+'&job='+job+'&with_salary='+with_salary).load();
}

  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>