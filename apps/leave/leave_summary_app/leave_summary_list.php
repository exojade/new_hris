<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/fullcalendar/main.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <div class="d-flex">
        <section class="content-header">
            <h1>
                Leave Summary List
            </h1>
        </section>
    </div>
    <section class="content">

    <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-envelope"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Force Leave</span>
                <span class="info-box-number">
                  10
                  <small>%</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-star"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Special Leave</span>
                <span class="info-box-number">41,410</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-plane"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Vacation Leave</span>
                <span class="info-box-number">760</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-hospital"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Sick Leave</span>
                <span class="info-box-number">2,000</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>

        <input type="hidden" id="employee_id" value="<?php echo($_SESSION["hris"]["employee_id"]); ?>">
        <div class="card">
        <div class="card-header">
          <h3 class="card-title">Title</h3>
        </div>
        <div class="card-body">
        <table class="table table-bordered" id="datatable">
            <thead>
                <th></th>
                <th></th>
                <th>ID</th>
                <th>Employee</th>
                <th>Office</th>
                <th>Leave Type</th>
                <th>Days Covered</th>
                <th>With Pay</th>
                <th>Without Pay</th>
                <th>Date Created</th>
                <th>Status</th>
            </thead>

        </table>
        </div>
        <!-- /.card-body -->
       
        <!-- /.card-footer-->
      </div>

    
    </section>
</div>
   
  </div>
  <?php require "layouts/footer.php"; ?>
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
<script>
var datatable = 
            $('#datatable').DataTable({
                "searching": false,
                "pageLength": 10,
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                "ordering": false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'leave_summary',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                        data.employee_id = $('#employee_id').val();;
                     }
                },
                'columns': [
                    { data: 'view', "orderable": false },
                    { data: 'print', "orderable": false },
                    { data: 'finger_id', "orderable": false },
                    { data: 'employee', "orderable": false },
                    { data: 'department', "orderable": false },
                    { data: 'leave_type', "orderable": false },
                    { data: 'days', "orderable": false },
                    { data: 'with_pay', "orderable": false  },
                    { data: 'without_pay', "orderable": false  },
                    { data: 'date_filed', "orderable": false  },
                    { data: 'status', "orderable": false  },
                ],
                'rowCallback': function(row, data) {
                },
                "footerCallback": function (row, data, start, end, display) {
               
                }
            });

            function filter() {
            var jobtypeData = $('#job_type').select2('data');
            var depData = $('#department_select').select2('data');
            var activeStatusData = $('#active_status').select2('data');
    
            var jobType ="";
            var depId ="";
            var activeStatus ="";

            if (jobtypeData[0])
                jobType = jobtypeData[0].id;
            if (depData[0])
                depId = depData[0].id;
            if (activeStatusData[0])
                activeStatus = activeStatusData[0].id;
            datatable.ajax.url('employees?action=datatable&jobType=' + jobType + '&depId=' + depId + '&activeStatus=' + activeStatus).load();
        }

</script>


  <?php require "layouts/footer_end.php";
?>
