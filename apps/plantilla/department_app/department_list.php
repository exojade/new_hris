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
        Department
    
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
    </style>
    <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
              <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="simple-datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th>Code</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Type</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php foreach($department as $row): ?>
                            <tr>
                                <td><a href="department?action=view&id=<?php echo($row["Deptid"]); ?>" class="btn btn-success">View</a></td>
                                <td><?php echo($row["DeptCode"]); ?></td>
                                <td><?php echo($row["DeptName"]); ?></td>
                                <td><?php echo($row["email_address"]); ?></td>
                                <td><?php echo($row["Type"]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                      </tbody>
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


$('#simple-datatable').DataTable({
      "paging": true,
    //   "lengthChange": false,
    //   "ordering": true,
      "info": true,
    //   "autoWidth": false,
      "responsive": true,
    });

  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>