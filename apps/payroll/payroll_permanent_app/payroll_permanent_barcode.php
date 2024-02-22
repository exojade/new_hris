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
        $department = query("select * from tbldepartment where Deptid = ?", $payroll_group[0]["department_fund"]);
        // dump($payroll_group); 
        ?>
        <?php echo($department[0]["DeptCode"] . " | " . to_month($payroll_group[0]["month"]) . " " . $payroll_group[0]["year"] . " " . $payroll_group[0]["remarks"]); ?>
        <form class="generic_form_trigger" style="float:right;" data-url="payroll_permanent" autocomplete="off">
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
          <div class="modal-dialog modal-lg">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
					    <h3 class="modal-title text-center">Employee</h3>
              </div>
              <div class="modal-body">
                  <form class="generic_form_trigger" data-url="payroll_permanent" autocomplete="off">
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
                <form class="generic_form_trigger"  data-url="payroll_permanent" autocomplete="off">
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
                <form class="generic_form_trigger" style="float:right;" data-url="payroll_permanent" autocomplete="off">
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
                <table class="table table-bordered">
                  <thead>
                    <tr>
                    <th rowspan="2" width="8%"></th>
                    <th rowspan="2">Employee</th>
                    <th colspan="3">Leave</th>
                    <th rowspan="2">Salary</th>
                    <th rowspan="2">PERA</th>
                    <th rowspan="2">RA</th>
                    <th rowspan="2">TA</th>
                    <th colspan="2">Mandatory</th>
                    <th rowspan="2">Others</th>
                    <th rowspan="2">PCHGEA</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="2">NET</th>
                    <th rowspan="2">1Q</th>
                    <th rowspan="2">2Q</th>
                    </tr>
                    <tr>
                      <th><i class="fas fa-edit"></th>
                      <th>w/ Pay</th>
                      <th>LWOP</th>
                      <th>Gov't</th>
                      <th>Personal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($payroll_actual as $row):
                      $salary = ($Salary[$row["salary_class"]][$row["salary_grade"]][$row["salary_step"]]["salary"]);
                      $salary_earned = $salary - (($salary / 22) * to_amount($row["lwop"]));
                      $pera = 2000;
                      $days = 22 - (to_amount($row["leave_days"]) + to_amount($row["lwop"]));
                      $pera = $pera - (($pera / 22) * to_amount($row["lwop"]));
                      $ra = rata($row["representation_allowance"], $days);
                      $ta = rata($row["travel_allowance"], $days);
                      //gs
                      $philhealth = philhealth($salary);
                      
                      // $gsis_gs = gsis_gs();

                      if($row["gsis_option"] == "FORMULA")
                        $gsis_gs = gsis_gs($salary, $row["lwop"], $payroll_group[0]["default_days"]);
                      else if($row["gsis_option"] == "DEFAULT")
                        $gsis_gs = $salary * .12;




                      $ecc = 100;
                      $hdmf_gs = 100;
                      $gs = ($philhealth/2) + $hdmf_gs + $gsis_gs + $ecc;
                      //endgs

                      // dump($payroll_group);

                      if($row["gsis_option"] == "FORMULA")
                        $gsis_ps = gsis_ps($salary, $row["lwop"], $payroll_group[0]["default_days"]);
                      else if($row["gsis_option"] == "DEFAULT")
                        $gsis_ps = $salary * 0.09;
                      $hdmf_ps = 100;
                      $ps = $gsis_ps + ($philhealth / 2) + $hdmf_ps;
                      // dump($Mandatory);
                      if(isset($Mandatory[$row["employee_id"]])):
                        $mandatory = $Mandatory[$row["employee_id"]];
                        $ps = $ps + to_amount($mandatory["sss_ps"]) + to_amount($mandatory["hdmf_mp2"]) + to_amount($mandatory["witholding_tax"]);
                        if($mandatory["hdmf_ps"] != ""):
                          $ps = $ps + $mandatory["hdmf_ps"] - $hdmf_ps;
                        endif;
                      endif;

      // dump($Loans);
                      $total_loans = 0;
                      if(isset($Loans[$row["employee_id"]])):
                        $loans = $Loans[$row["employee_id"]];
                        foreach($loans as $l):
                          if($l["active_status"] == "active")
                          $total_loans = $total_loans + $l["loans_amount"];
                        endforeach;
                      endif;
                      // dump($total_loans);

                      $pchgea_total = 0;
                      if($row["pchgea_dues"] == "ACTIVE")
                          $pchgea_total = $pchgea_total + 175;
                      if(!empty($pchgea_burial)):
                        if($row["pchgea_burial"] == "ACTIVE")
                          $pchgea_total = $pchgea_burial[0]["amount_fee"] * $pchgea_burial[0]["dependents"] + $pchgea_total;
                      endif;

                      $total_deductions = $total_loans + $pchgea_total + $ps;
                      $net = $salary_earned + $pera - $total_deductions;
                      $net_ra_ta = $net + to_amount($ra) + to_amount($ta);
                      $q1 = first_quincena($net) + to_amount($ra) + to_amount($ta);
                      $q2 = $net_ra_ta - $q1;
                      ?>
                      <tr>
                          <td>
                            <form class="generic_form_trigger" style="display:inline;" data-url="payroll_permanent" autocomplete="off">
                              <input type="hidden" name="action" value="delete_employee">
                              <input type="hidden" name="payroll_id" value="<?php echo($row["payroll_id"]); ?>">
                              <input type="hidden" name="employee_id" value="<?php echo($row["Employeeid"]); ?>">
                              <button type="submit" class="btn btn-danger btn-flat pull-right"><i class="fa fa-trash"></i></button>
                            </form>
                            <a class="btn btn-warning btn-flat" href="#" data-toggle="modal" data-target="#modal_employee" data-employee_id="<?php echo($row["Employeeid"]); ?>" data-payroll_id="<?php echo($payroll_group[0]["payroll_id"]); ?>"><i class="fa fa-edit"></i></a>
                          </td>
                          <td><?php echo($row["LastName"] . ", " . $row["FirstName"]); ?></td>
                          <td><a href="#" data-toggle="modal" data-target="#modal_leave" data-employee_id="<?php echo($row["Employeeid"]); ?>" data-payroll_id="<?php echo($payroll_group[0]["payroll_id"]); ?>"><i class="fa fa-edit"></a></td>
                          <td><?php echo($row["leave_days"]); ?></a></td>
                          <td><?php echo($row["lwop"]); ?></a></td>
                          <td><?php echo(to_peso($salary_earned)); ?></td>
                          <td><?php echo(to_peso($pera)); ?></td>
                          <td><?php echo(to_peso($ra)); ?></td>
                          <td><?php echo(to_peso($ta)); ?></td>
                          <td><a href="#" data-toggle="modal" data-target="#modal_gs"
                          data-gsis_gs="<?php echo($gsis_gs); ?>" 
                          data-hdmf_gs="100" 
                          data-ecc="100" 
                          data-phic_gs="<?php echo($philhealth / 2); ?>" 
                          ><?php echo(to_peso($gs)); ?></a></td>
                          <td><a href="#" data-toggle="modal" data-target="#modal_ps"
                          data-gsis_ps="<?php echo($gsis_ps); ?>" 
                          data-phic_ps="<?php echo($philhealth / 2); ?>" 
                          data-employee_id="<?php echo($row["Employeeid"]); ?>" 
                          ><?php echo(to_peso($ps)); ?></a></td>
                          <td>
                          <a href="#" data-toggle="modal" data-target="#modal_others"
                            data-employee_id="<?php echo($row["Employeeid"]); ?>" 
                          >
                          <?php echo(to_peso($total_loans)); ?>
                          </a></td>
                          <td><?php echo(to_peso($pchgea_total)); ?></td>
                          <td><?php echo(to_peso($total_deductions)); ?></td>
                          <td><?php echo(to_peso($net_ra_ta)); ?></td>
                          <td><?php echo(to_peso($q1)); ?></td>
                          <td><?php echo(to_peso($q2)); ?></td>
               
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


     $('#modal_employee').on('show.bs.modal', function (e) {
        var employee_id = $(e.relatedTarget).data('employee_id');
        var payroll_id = $(e.relatedTarget).data('payroll_id');


        $.ajax({
            type : 'post',
            url : 'payroll_permanent', //Here you will fetch records 
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

