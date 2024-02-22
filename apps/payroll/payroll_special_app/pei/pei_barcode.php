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
      <h1>PEI: 
        <?php
        $department = query("select * from tbldepartment where Deptid = ?", $payroll_group[0]["department_fund"]);
        // dump($payroll_group); 
        ?>
        <?php echo($department[0]["DeptCode"] . " | " . to_month($payroll_group[0]["month"]) . " " . $payroll_group[0]["year"] . " " . $payroll_group[0]["remarks"]); ?>
        <form class="generic_form_trigger" style="float:right;" data-url="pei" autocomplete="off">
          <input type="hidden" name="action" value="save_payroll">
          <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]) ?>">
          <button class="btn btn-warning"  type="submit">Save Payroll</button>
        </form>
      </h1>
    </section>
    <section class="content">
    <div class="modal fade" id="modal_leave">
          <div class="modal-dialog">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Leave Registry</h3>
              </div>
              <div class="modal-body">
                  <form class="generic_form_trigger" data-url="payroll_permanent" autocomplete="off">
                    <input type="hidden" name="action" value="update_leave">
                      <div class="fetched-data"></div>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                      </div>
                  </form>
              </div>
            </div>
          </div>
        </div>


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
                  <form class="generic_form_trigger" data-url="payroll_permanent" autocomplete="off">
                    <input type="hidden" name="action" value="update_ps">
                      <div class="fetched-data"></div>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                      </div>
                  </form>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_employee">
          <div class="modal-dialog">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Employee</h3>
              </div>
              <div class="modal-body">
                  <form class="generic_form_trigger" data-url="pei" autocomplete="off">
                    <input type="hidden" name="action" value="update_employee">
                      <div class="fetched-data"></div>
                      <div class="box-footer">
                        <button class=" btn btn-default btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                      </div>
                  </form>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_others">
          <div class="modal-dialog modal-xl">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Other Deductions</h3>
              </div>
              <div class="modal-body" style="max-height: 500px;overflow:hidden;overflow-y:scroll;">
                  <form class="generic_form_trigger" data-url="payroll_permanent" autocomplete="off">
                    <input type="hidden" name="action" value="save_loan">
                      <div class="fetched-data"></div>
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
              <div class="row">
                <div class="col-md-7">
                <form class="generic_form_trigger"  data-url="pei" autocomplete="off">
                  <input type="hidden" name="action" value="add_employee">
                  <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]); ?>">
                <div class="input-group mb-3" style="float:right;">
                <select style="width:70%;" class="form-control" id="employee_selection" name="employee" ></select>
                  <span class="input-group-append">
                  <button class="btn btn-info" type="submit">Add for Payroll</button>
                  <!-- <button onclick="addPayroll();" class="btn btn-info" type="button">Add for Payroll</button> -->
                  </span>
                </div>
                </form>
               
                </div>
                <div class="col-md-5">
                <form class="generic_form_trigger" style="float:right;" data-url="pei" autocomplete="off">
                  <input type="hidden" name="action" value="add_all_employees">
                  <input type="hidden" name="barcode" value="<?php echo($_GET["barcode"]); ?>">
                  <button class="btn btn-info" type="submit">ADD ALL EMPLOYEES</button>
                  <!-- <button onclick="addPayroll();" class="btn btn-info" type="button">Add for Payroll</button> -->
                </form>
                <!-- <a href="#"><h3 style="float:right;"><?php echo($payroll_group[0]["barcode"]); ?></h3></a> -->
                </div>
        </div>
              </div>
              <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                    <th rowspan="2" width="8%"></th>
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
                    <?php $total = 0; foreach($payroll_actual as $row):
                      $salary = ($Salary[$row["salary_class"]][$row["salary_grade"]][$row["salary_step"]]["salary"]);
                      $cash_gift = 5000;
                      

                      $accrued_cash_gift = $cash_gift;
              

                      $length_of_service = "";
                      $percentage = "";


                        $settings = query("select * from payroll_pei_settings where length_of_service = ?", $row["length_of_service"]);
                        if(!empty($row["percentagae"])):
                        $accrued_cash_gift = $cash_gift * $row["percentage"];
                        else:
                          $accrued_cash_gift = $cash_gift * 1;
                        endif;
                        $length_of_service = $row["length_of_service"];
                        if(!empty($row["percentagae"])):
                          $percentage = $row["percentage"] * 100;
                          else:
                            $percentage = 1 * 100;
                          endif;
                        
                        $percentage = $percentage . "%";
                      $total = $total + ($accrued_cash_gift);
                      ?>
                      <tr>
                          <td>
                            <form class="generic_form_trigger" style="display:inline;" data-url="pei" autocomplete="off">
                              <input type="hidden" name="action" value="delete_employee">
                              <input type="hidden" name="payroll_id" value="<?php echo($row["payroll_id"]); ?>">
                              <input type="hidden" name="employee_id" value="<?php echo($row["Employeeid"]); ?>">
                              <button type="submit" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash"></i></button>
                            </form>
                            <a class="btn btn-warning btn-flat" href="#" data-toggle="modal" data-target="#modal_employee" data-employee_id="<?php echo($row["Employeeid"]); ?>" data-payroll_id="<?php echo($payroll_group[0]["payroll_id"]); ?>"><i class="fa fa-edit"></i></a>
                          </td>
                          <td><?php echo($row["LastName"] . ", " . $row["FirstName"]); ?></td>
                          <td><?php echo($row["Fingerid"]); ?></td>
                          <td><?php echo($row["lbp_number"]); ?></td>
                          <td class="text-right"><?php echo(to_peso($cash_gift)); ?></td>
                          <td class="text-right"><?php echo($length_of_service); ?></td>
                          <td class="text-right"><?php echo($percentage); ?></td>
                          <td class="text-right"><?php echo(to_peso($accrued_cash_gift)); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <th colspan="7" class="text-right">TOTAL</th>
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


     $('#modal_employee').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');


        $.ajax({
            type : 'post',
            url : 'pei', //Here you will fetch records 
            data: {
                employee_id: employee_id, 
                payroll_id: payroll_id, 
                action: "modal_employee",

            },
            success : function(data){
                $('#modal_employee .fetched-data').html(data);

                $("#salary_select").select2();
            }
        });


        
     });

 


     $('#modal_gs').on('show.bs.modal', function (e) {
        var gsis_gs = $(e.relatedTarget).data('gsis_gs');
        var phic_gs = $(e.relatedTarget).data('phic_gs');
        var ecc = $(e.relatedTarget).data('ecc');
        var hdmf_gs = $(e.relatedTarget).data('hdmf_gs');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
                gsis_gs: gsis_gs, 
                phic_gs: phic_gs, 
                ecc: ecc, 
                hdmf_gs: hdmf_gs, 
                action: "modal_gs",

            },
            success : function(data){
                $('#modal_gs .fetched-data').html(data);
            }
        });
     });

     $('#modal_ps').on('show.bs.modal', function (e) {
        var gsis_ps = $(e.relatedTarget).data('gsis_ps');
        var phic_ps = $(e.relatedTarget).data('phic_ps');
        var employee_id = $(e.relatedTarget).data('employee_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
              employee_id: employee_id, 
              gsis_ps: gsis_ps, 
              phic_ps: phic_ps, 
              action: "modal_ps",

            },
            success : function(data){
                $('#modal_ps .fetched-data').html(data);
            }
        });
     });


     $('#modal_others').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
            data: {
              employee_id: employee_id, 
              action: "modal_others",

            },
            success : function(data){
                $('#modal_others .fetched-data').html(data);
                // $('#loans_datatable').DataTable({
                //     "paging": false,
                //     "searching": true
                // });






            }
        });
     });

    //  $('#employee_selection').select2('destroy');
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

