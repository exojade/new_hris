<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/jquery-ui/jquery-ui.min.css">

<!-- 
<link rel="stylesheet" href="AdminLTE/bower_components/sweetalert/sweetalert2.min.css">
<link rel="stylesheet" href="AdminLTE/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="AdminLTE/dist/css/ui.min.css"> -->
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

/* ::-webkit-scrollbar { 
    display: none; 
} */
</style>
  <div class="content-wrapper">
  <section class="content-header">
      <h1>
        PERFORMANCE ENHANCEMENT INCENTIVE (PEI) PAYROLL
      </h1>
    </section>
    <section class="content">
    <div class="modal fade" id="modal_payroll">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Open a Payroll</h3>
              </div>
              <div class="modal-body">
                  <form class="generic_form_trigger" data-url="pei" autocomplete="off">
                    <input type="hidden" name="action" value="new_payroll">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Barcode</label>
                                <input name="barcode" type="text" value="<?php echo(generate_barcode()); ?>" class="form-control" readonly placeholder="# of Employees to assist">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Year</label>
                                <input name="year" type="number" value="<?php echo(date("Y")); ?>" class="form-control" placeholder="---">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Department</label>
                          <select required name="department" class="form-control select2" style="width: 100%;">
                            <option selected="selected">Please select department</option>
                            <?php foreach($department as $row): ?>
                              <option value="<?php echo($row["Deptid"]); ?>"><?php echo($row["DeptCode"] . " | " . $row["DeptName"]); ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                          <label>Job Type *</label>
                          <select multiple required name="job_type[]" class="form-control select2" style="width: 100%;">
                            <option value="PERMANENT">PERMANENT</option>
                            <option value="COTERMINOUS">COTERMINOUS</option>
                            <option value="ELECTIVE">ELECTIVE</option>
                            <option value="CASUAL">CASUAL</option>
                          </select>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Remarks (if any)</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                      </div>
                    <hr>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                      </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
    <div id="dialog" style="display: none"></div>
      <div class="card">
              <div class="card-header">
                <h5 class="m-0">Payroll List
                  <a href="#" data-toggle='modal' data-target='#modal_payroll' class="btn btn-primary" style="float:right;">New Payroll</a>
                </h5>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered datatable">
                  <thead>
                    <th></th>
                    <th>Barcode</th>
                    <th>Department</th>
                    <th>Job Type</th>
                    <!-- <th>Month</th> -->
                    <th>Year</th>
                    <th>Remarks</th>
                    <th>Status</th>
                  </thead>
                  <tbody>
                    <?php foreach($payroll_group as $row): ?>
                      <tr>
                        <td>
                            <form class="generic_form_trigger" style="display:inline;" data-url="payroll_permanent" autocomplete="off">
                              <input type="hidden" name="action" value="delete_payroll">
                              <input type="hidden" name="payroll_id" value="<?php echo($row["payroll_id"]); ?>">
                              <button type="submit" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash"></i></button>
                            </form>  
                        </td>
                        <?php if($row["payroll_status"] != "done"): ?>
                          <td><a href="pei?action=barcode&barcode=<?php echo($row["barcode"]); ?>"><?php echo($row["barcode"]); ?></a></td>
                        <?php else: ?>
                          <td><a href="pei?action=done&barcode=<?php echo($row["barcode"]); ?>"><?php echo($row["barcode"]); ?></a></td>
                        <?php endif; ?>
                        <td><?php echo(strtoupper($row["DeptCode"])); ?></td>
                        <td><?php echo(implode(", ", unserialize($row["employment_status"]))); ?></td>
                        <td><?php echo($row["year"]); ?></td>
                        <td><?php echo($row["remarks"]); ?></td>
                        <td><?php echo(strtoupper($row["payroll_status"])); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
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
  <!-- <script src="AdminLTE_new/dist/js/adminlte.min.js"></script> -->


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
<script>
  // $("#loans_employee").DataTable();

</script>



<script>
  $('.datatable').DataTable();
  $('.select2').select2();
</script>

