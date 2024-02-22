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
        Step Increment
      </h1>
    </section>
    <section class="content">

    <div class="card">
        <div class="card-header">
          <h3 class="card-title">For Step Increment</h3>
        </div>
            <div class="card-body">
              <table class="table table-bordered" id="stepIncrementDatatable">
                <thead>
                  <th>Employee</th>
                  <th>Department</th>
                  <th>Latest Promotion</th>
                  <th>Effectivity</th>
                  <th>SG</th>
                  <th>Step</th>
                  <th>Current</th>
                  <th>Incremented</th>
                  <th>NOSA</th>
                </thead>
              </table>
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
    var stepIncrementDatatable = 
            $('#stepIncrementDatatable').DataTable({
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
                    'url':'step_increment',
                     'type': "POST",
                     "data": function (data){
                        data.action = "stepIncrementDatatable";
                     }
                },
                'columns': [
                    { data: 'employee', "orderable": false },
                    { data: 'department', "orderable": false },
                    { data: 'latest_promotion', "orderable": false },
                    { data: 'effectivity', "orderable": false },
                    { data: 'salary_grade', "orderable": false },
                    { data: 'salary_step', "orderable": false },
                    { data: 'previous', "orderable": false },
                    { data: 'incremented', "orderable": false },
                    { data: 'generate_nosa', "orderable": false },
                ],
            });
  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>