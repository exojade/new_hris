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

.table>thead>tr>th {
  vertical-align: middle !important;
  text-align: center;
}

/* ::-webkit-scrollbar { 
    display: none; 
} */
</style>
  <div class="content-wrapper">
  <section class="content-header">
      <h1>
      PEI :
      <?php
      // dump($pg);
        $department = query("select * from tbldepartment where Deptid = ?", $pg["department_fund"]);
        // dump($payroll_group); 
        ?>
        <?php echo($department[0]["DeptCode"] . " | " . to_month($pg["month"]) . " " . $pg["year"] . " " . $pg["remarks"]); ?>
        <!-- <form class="generic_form_trigger" style="float:right;" data-url="payroll_permanent" autocomplete="off">
          <input type="hidden" name="action" value="save_payroll">
          <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
          <button class="btn btn-warning"  type="submit">Save Payroll</button>
        </form> -->
      </h1>
    </section>
    <section class="content">
        <div class="modal fade" id="modal_gs">
          <div class="modal-dialog">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Government Share</h3>
              </div>
              <div class="modal-body">
                  <!-- <form class="generic_form_trigger" data-url="payroll_permanent" autocomplete="off"> -->
                    <!-- <input type="hidden" name="action" value="update_leave"> -->
                      <div class="fetched-data"></div>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        
                      </div>
                  <!-- </form> -->
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_ps">
          <div class="modal-dialog">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Personal Share</h3>
              </div>
              <div class="modal-body">
        
                      <div class="fetched-data"></div>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                      </div>
          
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_others">
          <div class="modal-dialog">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Other Deductions</h3>
              </div>
              <div class="modal-body" style="max-height: 500px;overflow:hidden;overflow-y:scroll;">
              <div class="fetched-data"></div>
                    
              </div>
              <div class="modal-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                      </div>
            
            </div>
          </div>
        </div>



    <div id="dialog" style="display: none"></div>
      <div class="card">
              <div class="card-header">
              
              <form class="generic_form_trigger" style="float:right;" data-url="pei" autocomplete="off">
                <input type="hidden" name="action" value="revert_payroll">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <button class="btn btn-danger"  type="submit">Revert</button>
              </form>
            
              <form class="generic_form_trigger" style="float:right; margin-right:10px;" data-url="pei" autocomplete="off">
                <input type="hidden" name="action" value="pacs">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <button class="btn btn-primary"  type="submit">PACS</button>
              </form>

              <form class="generic_form_trigger" style="float:right; margin-right:10px;" data-url="pei" autocomplete="off">
                <input type="hidden" name="action" value="generate_excel">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <div class="row">
                  <div class="col-md-7">
                      <select required class="form-control" name="form_type" width="100%">
                      <option value="" selected disabled>Select Form Type</option>
                      <option value="standard">Default</option>
                      <option value="otc">Over the Counter</option>
                      <option value="pro_rated">Pro Rated</option>
                    </select>
                  </div>
                  <div class="col-md-5">
                  <button class="btn btn-success"  type="submit">Payroll Excel</button>

                  </div>
                </div>
                
              </form>
       
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                    <th rowspan="2">Employee</th>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">PACS</th>
                    <th colspan="3">Compensation</th>
                    <th rowspan="2">Total</th>
                    </tr>
                    <tr>
                      <th>PEI</th>
                      <th>Length of Service</th>
                      <th>Percentage</th>
                 
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $total = 0;
                    foreach($payroll_actual as $row):
                      $total = $total + ($row["accrued_pei"]);
                      $percentage = "";
                      if($row["percentage"] != ""):
                        $percentage = $row["percentage"] * 100;
                        $percentage = $percentage . "%";
                      endif;
                      
                      ?>
                      <tr>
                       
                          <td><?php echo($row["LastName"] . ", " . $row["FirstName"]); ?></td>
                          <td><?php echo($row["finger_id"]); ?></td>
                          <td><?php echo($row["lbp_number"]); ?></a></td>
                          <td class="text-right"><?php echo(to_peso($row["pei"])); ?></td>
                          <td class="text-right"><?php echo($row["length_of_service"]); ?></td>
                          <td class="text-right"><?php echo($percentage); ?></td>
                          <td class="text-right"><?php echo(to_peso($row["accrued_pei"])); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <th class="text-right" colspan="6">TOTAL</th>
                    <th class="text-right"><?php echo(to_peso($total)); ?></th>
                  </tfoot>
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

$(document).ready(function(){
    $('#modal_leave').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
                employee_id: employee_id, 
                payroll_id: payroll_id, 
                action: "modal_leave",

            },
            success : function(data){
                $('#modal_leave .fetched-data').html(data);
            }
        });
     });


     $('#modal_gs').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
                employee_id: employee_id, 
                payroll_id: payroll_id, 
                action: "modal_gs_done",

            },
            success : function(data){
                $('#modal_gs .fetched-data').html(data);
            }
        });
     });

     $('#modal_ps').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
              employee_id: employee_id, 
                payroll_id: payroll_id, 
                action: "modal_ps_done",
            },
            success : function(data){
                $('#modal_ps .fetched-data').html(data);
            }
        });
     });

     $('#modal_others').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
              employee_id: employee_id, 
                payroll_id: payroll_id, 
              action: "modal_others_done",

            },
            success : function(data){
                $('#modal_others .fetched-data').html(data);
            }
        });
     });
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


    function addPayroll(){
    var emp = $("#employee_selection").val();
    if(emp == null || emp == ""){
      alert("Please select employee");
    }
    else{
      swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      $.ajax({
        type: 'post',
        url: 'payroll_permanent',
        data: {emp_id:emp,action: "add_employee"},
        success: function (results) {
           swal.close();
           var o = jQuery.parseJSON(results);
           $('#modal_addPayroll #employee_name').val(o.employee_name);
           $('#modal_addPayroll #emp_id').val(o.employee_id);
           $('#modal_addPayroll #rate').val(o.rate);
           $('#modal_addPayroll #tax').val(o.tax);
           $('#modal_addPayroll #sss').val(o.sss_personal);
           $('#modal_addPayroll #phic').val(o.phic_personal);
           $('#modal_addPayroll #hdmf').val(o.hdmf_personal);
           $('#modal_addPayroll #ecc').val(o.ecc);
           $('#modal_addPayroll').modal('show'); 
          //  $('.fetched-leave-details').html(data);
        }
      });
    }
    // console.log(emp);
  }


</script>



<script>
  $('.datatable').DataTable();
</script>

