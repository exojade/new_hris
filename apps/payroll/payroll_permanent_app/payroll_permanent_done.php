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
              
              <form class="generic_form_trigger" style="float:right;" data-url="payroll_permanent" autocomplete="off">
                <input type="hidden" name="action" value="revert_payroll">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <button class="btn btn-danger"  type="submit">Revert</button>
              </form>
              <form class="generic_form_trigger" style="float:right; margin-right:10px;" data-url="payroll_permanent" autocomplete="off">
                <input type="hidden" name="action" value="pacs">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <input type="hidden" name="pacs" value="q2">
                <button class="btn btn-primary"  type="submit">PACS 2Q</button>
              </form>
              <form class="generic_form_trigger" style="float:right; margin-right:10px;" data-url="payroll_permanent" autocomplete="off">
                <input type="hidden" name="action" value="pacs">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <input type="hidden" name="pacs" value="q1">
                <button class="btn btn-primary"  type="submit">PACS 1Q</button>
              </form>

              <form class="generic_form_trigger" style="float:right; margin-right:10px;" data-url="payroll_permanent" autocomplete="off">
                <input type="hidden" name="action" value="generate_excel">
                <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
                <button class="btn btn-success"  type="submit">Payroll Excel</button>
              </form>
       
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                    <th rowspan="2">Employee</th>
                    <th colspan="2">Leave</th>
                    <th rowspan="2">Salary</th>
                    <th rowspan="2">PERA</th>
                    <th rowspan="2">RA</th>
                    <th rowspan="2">TA</th>
                    <th colspan="2">Mandatory</th>
                    <th rowspan="2">Others</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="2">NET</th>
                    <th rowspan="2">1Q</th>
                    <th rowspan="2">2Q</th>
                    </tr>
                    <tr>
                      <th>w/ Pay</th>
                      <th>LWOP</th>
                      <th>Gov't</th>
                      <th>Personal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($payroll_actual as $row):
                    //  dump($row);




                      ?>
                      <tr>
                       
                          <td><?php echo($row["LastName"] . ", " . $row["FirstName"]); ?></td>
                          <td><?php echo($row["leave_days"]); ?></a></td>
                          <td><?php echo($row["lwop"]); ?></a></td>
                          <td><?php echo(to_peso($row["accrued_salary"])); ?></td>
                          <td><?php echo(to_peso($row["accrued_pera"])); ?></td>
                          <td><?php echo(to_peso($row["accrued_ra"])); ?></td>
                          <td><?php echo(to_peso($row["accrued_ta"])); ?></td>
                          <td><a href="#" 
                          data-toggle="modal" data-target="#modal_gs" 
                          data-employee_id="<?php echo($row["employee_id"]); ?>"
                          data-payroll_id="<?php echo($row["payroll_id"]); ?>"
                          ><?php echo(to_peso($row["government_total"])); ?></a></td>
                          <td><a href="#" data-toggle="modal" data-target="#modal_ps"
                                data-employee_id="<?php echo($row["employee_id"]); ?>" 
                                data-payroll_id="<?php echo($row["payroll_id"]); ?>"
                          ><?php echo(to_peso($row["personal_total"])); ?></a></td>
                          <td>
                          <a href="#" data-toggle="modal" data-target="#modal_others"
                            data-employee_id="<?php echo($row["employee_id"]); ?>" 
                            data-payroll_id="<?php echo($row["payroll_id"]); ?>"
                          >
                          <?php echo(to_peso($row["other_total"])); ?>
                          </a></td>
                          <td><?php echo(to_peso($row["other_total"] + $row["personal_total"])); ?></td>
                          <td><?php echo(to_peso($row["net_amount"] + to_amount($row["accrued_ra"]) + to_amount($row["accrued_ta"]))); ?></td>
                          <td><?php echo(to_peso($row["first_quincena"])); ?></td>
                          <td><?php echo(to_peso($row["second_quincena"])); ?></td>
               
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

