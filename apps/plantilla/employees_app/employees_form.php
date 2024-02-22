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
        Employees
        <small>Version 2.0</small>
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
      .table > tbody > tr > td {
     vertical-align: middle;
}
    </style>



  



    <div class="modal fade" id="modal-add_employee">
          <div class="modal-dialog modal-lg">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title text-center">Register New Employee</h3>
              </div>
              <div class="modal-body">
                <form class="generic_form_trigger" data-url="employees" autocomplete="off">
                <input type="hidden" name="action" value="add_employee">
                  <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Biometric Number</label>
                      <input required name="biometric_number" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>First Name</label>
                      <input required name="first_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last Name</label>
                      <input required name="last_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Middle Name</label>
                      <input  name="middle_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Suffix</label>
                      <input name="suffix" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                </div>

               

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Department Asssign</label>
                        <select name="department" required class="form-control select2" style="width: 100%;">
                            <option selected="selected" disabled value="">Please Select Department</option>
                            <?php foreach($department as $d): ?>
                              <option value="<?php echo($d["Deptid"]); ?>"><?php echo($d["DeptCode"] . " - " . $d["DeptName"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Group</label>
                        <select name="group" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="">Please select Group</option>
                            <?php foreach($group as $g): ?>
                              <option value="<?php echo($g["group_id"]); ?>"><?php echo($g["group_name"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                  </div>

                  <div class="col-md-12">

                    <div class="form-group">
                          <label>Print Remarks</label>
                          <select required name="print_remarks" class="form-control select2" style="width: 100%;">
                              <option selected="selected" value="">Select Print Remarks</option>
                              <option value="BOTH">BOTH</option>
                              <option value="DTR">DTR</option>
                              <option value="TIMESHEET">TIMESHEET</option>
                          </select>
                    </div>


                    <div class="form-group">
                        <label>Employment Status</label>
                        <select required name="employment" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="">Select Employment Status</option>
                            <option value="JOB ORDER">JOB ORDER</option>
                            <option value="HONORARIUM">HONORARIUM</option>
                            <option value="CASUAL">CASUAL</option>
                            <option value="COTERMINOUS">COTERMINOUS</option>
                            <option value="ELECTIVE">ELECTIVE</option>
                            <option value="PERMANENT">PERMANENT</option>
                           
                        </select>
                    </div>
                  </div>
                </div>
                    </div>
                    <div class="box-footer">
                      <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                      <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>



        <div class="modal fade" id="modal_edit_employees">
          <div class="modal-dialog modal-xl">
            <div class="modal-content ">
              <div class="modal-header bg-primary">

					    <h3 class="modal-title text-center">Update Employee</h3>
              </div>
              <div class="modal-body">
                <form class="generic_form_trigger" data-url="employees" autocomplete="off">
                    <input type="hidden" name="action" value="updateEmployee">
                      <div class="fetch-data-emp"></div>
                    <div class="box-footer">
                      <button class=" btn btn-danger pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                      <button type="submit" class="btn btn-success pull-right">Submit</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modalContinuous">
          <div class="modal-dialog modal-xl">
            <div class="modal-content ">
              <div class="modal-header bg-primary">

					    <h3 class="modal-title text-center">Continuous Year</h3>
              </div>
              <div class="modal-body">
                    <input type="hidden" name="action" value="updateEmployee">
                      <div class="fetch"></div>
                 
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modalPromotion">
          <div class="modal-dialog modal-xl">
            <div class="modal-content ">
              <div class="modal-header bg-primary">

					    <h3 class="modal-title text-center">Promotion Year</h3>
              </div>
              <div class="modal-body">
                <div class="fetch"></div>
              </div>
            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_update_payroll">
          <div class="modal-dialog modal-xl">
            <div class="modal-content ">
              <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
					    <h3 class="modal-title text-center">Update Employee's Payroll Settings</h3>
              </div>
              <div class="modal-body" style="-webkit-user-select: none;  /* Chrome all / Safari all */
              -moz-user-select: none;     /* Firefox all */
              -ms-user-select: none;  ">
                  <form class="generic_form" url="employees" autocomplete="off">
                    <div class="fetched-payroll"></div>
                      <div class="box-footer">
                        <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                      </div>
                  </form>
              </div>
            </div>
          </div>
        </div>


    <div class="row">
    <div class="col-md-3">
    <div class="form-group">
                  <div class="input-group mb-3">
                    <select multiple style="width:100%;" class="form-control" id="job_type" name="job_type[]">
                      <option value="PERMANENT">PERMANENT</option>
                      <option value="ELECTIVE">ELECTIVE</option>
                      <option value="COTERMINOUS">COTERMINOUS</option>
                      <option value="CASUAL">CASUAL</option>
                      <option value="JOB ORDER">JOB ORDER</option>
                      <option value="HONORARIUM">HONORARIUM</option>
                    </select>
                    </div>
                </div>

    </div>
    <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="department_select" name="department[]" multiple></select>
                    </div>
                </div>

    </div>
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-4">
        <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="active_status" name="active_status">
                      <option selected value="">Please Select Status</option>
                      <option value="1">ACTIVE</option>
                      <option value="0">INACTIVE</option>
                    </select>
                    </div>
                </div>
        </div>
        <div class="col-md-4">
        <button onclick="filter();" class="btn btn-primary btn-block">Filter</button>

        </div>
        <div class="col-md-4">
         <a href="#" data-toggle="modal" data-target="#modal-add_employee" class="btn btn-success btn-block">Register Employee</a>

        </div>
      </div>
    </div>
  </div>

    <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered" id="employees-datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Fund</th>
                              <th>Assigned</th>
                              <th>Position</th>
                              <th>PACS</th>
                              <th>Job Type</th>
                              <th>SG</th>
                              <th>Step</th>
                              <th>Class</th>
                              <th>Salary</th>
                              <th>Date Hired</th>
                              <th>Original</th>
                              <th>Continuous</th>
                              <th>Promotion</th>
                              <th>Print</th>
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

  <?php
	require("apps/plantilla/employees_app/employees_js.php");
	require("layouts/footer_end.php");
  ?>