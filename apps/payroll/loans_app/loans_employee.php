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

::-webkit-scrollbar { 
    display: none; 
}

.greenColor{
    background-color: #33CC33;
}
.redColor{
    background-color: #E60000;
}

</style>


  <div class="content-wrapper">
  <section class="content-header">
      <h1>
        Loans Management
        <small>Version 2.0</small>
      </h1>
    </section>
    <section class="content">
    <div id="dialog" style="display: none"></div>
      <div class="card">
              <div class="card-header">
                <h5 class="m-0">LOAN DASHBOARD of <?php echo("(".$employee["Fingerid"] . ") " . $employee["name"]); ?></h5>
              </div>
              <div class="card-body table-responsive">
              <form class="generic_form_trigger" data-url="loans_management" autocomplete="off">
              <input type="hidden" name="action" value="save_loan">
              <input type="hidden" name="employee_id" value="<?php echo($_GET["id"]); ?>">
              <table class="table table-bordered">
                <thead>
                  <th>Loan Title</th>
                  <th>Loan Amount</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Status</th>
                </thead>
                <tbody>
                  <?php
                  foreach($lenders as $row): ?>
                    <tr>
                      <td><?php echo($row["loan_name"]); ?></td>
                      <td><input value="<?php echo($LoansEmployee[$row["lenders_id"]]["loans_amount"]); ?>" type="text" name="loan_amount_<?php echo($row["lenders_id"]); ?>" class="form-control" placeholder="<?php echo($row["lender"]); ?>"></td>
                      <td><input value="<?php echo($LoansEmployee[$row["lenders_id"]]["from_date"]); ?>" type="date" name="from_date_<?php echo($row["lenders_id"]); ?>" class="form-control" placeholder="<?php echo($row["lender"]); ?>"></td>
                      <td><input value="<?php echo($LoansEmployee[$row["lenders_id"]]["to_date"]); ?>" type="date" name="to_date_<?php echo($row["lenders_id"]); ?>" class="form-control" placeholder="<?php echo($row["lender"]); ?>"></td>
                      <td>
                          <select class="form-control" name="active_status_<?php echo($row["lenders_id"]); ?>">
                            <?php if(!isset($LoansEmployee[$row["lenders_id"]])): ?>
                              <option selected value="inactive">🔴 inactive</option>
                              <option value="active">🟢 active</option>
                            <?php else: ?>
                                <?php if($LoansEmployee[$row["lenders_id"]]["active_status"] == "active"): ?>
                              <option value="inactive">🔴 inactive</option>
                              <option selected value="active">🟢 active</option>
                              <?php else: ?>
                                <option selected value="inactive">🔴 inactive</option>
                              <option  value="active">🟢 active</option>
                              <?php endif; ?>
                            <?php endif; ?>
                          </select>
                      </td>

                    </tr>

                  <?php endforeach;?>
                </tbody>
              </table>
              <button class="btn btn-primary" type="submit">Save</button>
              </form>
                
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



