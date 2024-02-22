<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <section class="content-header">
    <div class="row">
      <div class="col-sm-6">
        <h1>Plantilla</h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="#" data-toggle="modal" data-target="#modalAddPlantilla" class="btn btn-primary">Add Plantilla</a>
      </div>
    </div>
    </section>
    <section class="content">
    <style>
      .table tr td {
        font-size:14px !important;
      }

      .table tr td i{
        font-size:12px !important;
      }
      .table > tbody > tr > td {
     vertical-align: middle;
}
textarea {
            resize: none;
        }
    </style>




<div class="modal fade" id="modalAddPlantilla">
      <div class="modal-dialog">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Add Plantilla</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="plantilla" autocomplete="off">
                  <input type="hidden" name="action" value="addPlantilla">
                  <div class="form-group">
                    <label>Item No. *</label>
                    <input type="text" name="item_number" class="form-control" placeholder="Enter Item Number...">
                  </div>

                  <div class="form-group">
                    <label>Department *</label>
                    <select required style="width: 100%;" id="plantillDepartmentSelect" class="form-control" name="department">
                    <option value=""></option>
                      <?php foreach($department as $row): ?>
                        <option value="<?php echo($row["Deptid"]); ?>"><?php echo($row["DeptCode"]); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Section</label>
                        <select style="width: 100%;" id="plantillSectionSelect" class="form-control" name="section">
                        <option value=""></option>
                          <?php foreach($department_section as $row): ?>
                            <option value="<?php echo($row["section"]); ?>"><?php echo($row["section"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Unit</label>
                        <select style="width: 100%;" id="plantillUnitSelect" class="form-control" name="unit">
                        <option value=""></option>
                          <?php foreach($department_unit as $row): ?>
                            <option value="<?php echo($row["unit"]); ?>"><?php echo($row["unit"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-8">
                      <div class="form-group">
                        <label>Position *</label>
                        <select required style="width: 100%;" id="plantillPositionSelect" class="form-control" name="position">
                        <option value=""></option>
                          <?php foreach($position as $row): ?>
                            <option value="<?php echo($row["Positionid"]); ?>"><?php echo($row["PositionName"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Level *</label>
                        <select required style="width: 100%;" class="form-control" name="position_level">
                          <option value="" selected disabled>Please select Level</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Status *</label>
                        <select required style="width: 100%;" class="form-control" name="status">
                          <option value="" selected disabled>Please select Status</option>
                          <option value="FILLED">FILLED</option>
                          <option value="UNFILLED">UNFILLED</option>
                          <option value="UNFUNDED">UNFUNDED</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                          <label>Job Type</label>
                          <select required style="width: 100%;" class="form-control" name="jobType">
                            <option value="" selected disabled>Please select type</option>
                            <option value="COTERMINOUS">COTERMINOUS</option>
                            <option value="ELECTIVE">ELECTIVE</option>
                            <option value="PERMANENT">PERMANENT</option>
                          </select>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Salary Schedule</label>
                    <select required style="width: 100%;" class="form-control" name="salarySchedule">
                      <option value="" selected disabled>Please select schedule</option>
                      <option value="3rd class">3rd class</option>
                      <option value="1st class">1st class</option>
                    </select>
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





    <div class="modal fade" id="addAppointmentModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Add Plantilla</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="plantilla" autocomplete="off">
                  <input type="hidden" name="action" value="addPlantilla">


                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label>Item No. *</label>
                        <input id="appointmentItemId" type="text" readonly name="item_number" class="form-control" placeholder="Enter Item Number...">
                      </div>
                    </div>
                    <div class="col-8">
                      <div class="form-group">
                        <label>Employee</label>
                        <select style="width: 100%;" class="form-control" id="employeeSelect">
                            <option value=""></option>
                          <?php foreach($employee as $row): ?>
                            <option value="<?php echo($row["Employeeid"]); ?>"><?php echo($row["fullname"]); ?></option>
                          <?php endforeach; ?>


                        </select>
                      </div>
                    </div>
                  </div>
                  

                  <div class="form-group">
                    <label>Department *</label>
                    <select required style="width: 100%;" id="plantillDepartmentSelect" class="form-control" name="department">
                    <option value=""></option>
                      <?php foreach($department as $row): ?>
                        <option value="<?php echo($row["Deptid"]); ?>"><?php echo($row["DeptCode"]); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Section</label>
                        <select style="width: 100%;" id="plantillSectionSelect" class="form-control" name="section">
                        <option value=""></option>
                          <?php foreach($department_section as $row): ?>
                            <option value="<?php echo($row["section"]); ?>"><?php echo($row["section"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label>Unit</label>
                        <select style="width: 100%;" id="plantillUnitSelect" class="form-control" name="unit">
                        <option value=""></option>
                          <?php foreach($department_unit as $row): ?>
                            <option value="<?php echo($row["unit"]); ?>"><?php echo($row["unit"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-8">
                      <div class="form-group">
                        <label>Position *</label>
                        <select required style="width: 100%;" id="plantillPositionSelect" class="form-control" name="position">
                        <option value=""></option>
                          <?php foreach($position as $row): ?>
                            <option value="<?php echo($row["Positionid"]); ?>"><?php echo($row["PositionName"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label>Level *</label>
                        <select required style="width: 100%;" class="form-control" name="position_level">
                          <option value="" selected disabled>Please select Level</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Status *</label>
                        <select required style="width: 100%;" class="form-control" name="status">
                          <option value="" selected disabled>Please select Status</option>
                          <option value="FILLED">FILLED</option>
                          <option value="UNFILLED">UNFILLED</option>
                          <option value="UNFUNDED">UNFUNDED</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                          <label>Job Type</label>
                          <select required style="width: 100%;" class="form-control" name="jobType">
                            <option value="" selected disabled>Please select type</option>
                            <option value="COTERMINOUS">COTERMINOUS</option>
                            <option value="ELECTIVE">ELECTIVE</option>
                            <option value="PERMANENT">PERMANENT</option>
                          </select>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Salary Schedule</label>
                    <select required style="width: 100%;" class="form-control" name="salarySchedule">
                      <option value="" selected disabled>Please select schedule</option>
                      <option value="3rd class">3rd class</option>
                      <option value="1st class">1st class</option>
                    </select>
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


    <div class="modal fade" id="addAppointmentModalOld">
      <div class="modal-dialog modal-lg">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Plantilla Build Up</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="plantilla" autocomplete="off">
                  <input type="hidden" name="action" value="addPlantilla">
                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label>Item No. *</label>
                        <input id="appointmentItemId" type="text" readonly name="item_number" class="form-control" placeholder="Enter Item Number...">
                      </div>
                    </div>
                    <div class="col-8">
                      <div class="form-group">
                        <label>Employee</label>
                        <select style="width: 100%;" class="form-control employeeSelect">
                            <option value=""></option>
                          <?php foreach($employee as $row): ?>
                            <option value="<?php echo($row["Employeeid"]); ?>"><?php echo($row["fullname"]); ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Effectivity Date *</label>
                    <input type="date" required name="effectivity_date" class="form-control">
                  </div>

                  <div class="form-group">
                    <label>Nature of Appointment</label>
                    <select name="nature" style="width: 100%;" class="form-control">
                        <option value="">Please select Nature of Appointment</option>
                        <option value="ORIGINAL">ORIGINAL</option>
                        <option value="PROMOTION">PROMOTION</option>
                        <option value="REAPPOINTMENT">REAPPOINTMENT</option>
                        <option value="REEMPLOYMENT">REEMPLOYMENT</option>
                        <option value="TRANSFER">TRANSFER</option>
                    </select>
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

  


    <div class="card">
            <!-- /.box-header -->
            <div class="card-header">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="employee_selection" name="employee"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
              <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="position_select" name="position"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" id="department_select" name="department"></select>
                    </div>
                </div>
              </div>

              <div class="col-md-3">
               
                  <button onclick="filter();" class="btn btn-primary">Filter</button>
                  <!-- /.input group -->
              
                  <button onclick="filter();" class="btn btn-success">Print</button>
                  <!-- /.input group -->
              </div>
            </div>
            
          </div>
            <div class="card-body">
              <div class="table-responsive">
                    <table class="table table-bordered" id="datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th>Item #</th>
                              <th>Position</th>
                              <th>Level</th>
                              <th>Department</th>
                              <th>SG</th>
                              <th>Schedule</th>
                              <th>Status</th>
                              <th>Plantilla</th>
                              <th>Incumbent</th>
                              <th>Vice</th>
                              <th>Reason of Vacancy</th>
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
<!-- <script src="AdminLTE_new/dist/js/adminlte.min.js"></script> -->

<script>
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


    $('#plantillDepartmentSelect').select2({
    placeholder: "Select Department",
    });

    $('#plantillPositionSelect').select2({
    placeholder: "Select Position",
    });

    $('#plantillSectionSelect').select2({
    placeholder: "Select Section",
    });

    $('#plantillUnitSelect').select2({
    placeholder: "Select Unit",
    });

    $('.employeeSelect').select2({
    placeholder: "Select Employee",
    });

    



    $('#position_select').select2({
    minimumInputLength: 3,
    placeholder: "Search Position Name, Code etc...",
    ajax: {
        url: 'ajax_position',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.supplier,
                  id: item.id,
                }
            })
          }
        }
      },
    });

    $('#department_select').select2({
    minimumInputLength: 3,
    placeholder: "Search Department Name, Code etc...",
    ajax: {
        url: 'ajax_department_appointment',
        dataType: 'json',
        processResults: function (data) {
        return {
          results: $.map(data.results, function (item) {
            return {
                  text: item.name,
                  id: item.id,
                }
            })
          }
        }
      },
    });


    var datatable = 
            $('#datatable').DataTable({
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
                    'url':'plantilla',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                     }
                },
                'columns': [
                    { data: 'plantilla_id', "orderable": false },
                    { data: 'position', "orderable": false },
                    { data: 'position_level', "orderable": false },
                    { data: 'department', "orderable": false },
                    { data: 'SGRate', "orderable": false },
                    { data: 'salary_class', "orderable": false },
                    { data: 'status', "orderable": false },
                    { data: 'plantilla_status', "orderable": false },
                    { data: 'incumbent', "orderable": false },
                    { data: 'vice', "orderable": false },
                    { data: 'reason_vacancy', "orderable": false },
                
                ],
                "footerCallback": function (row, data, start, end, display) {
                    // var api = this.api(), data;
                    

                    // Remove the formatting to get integer data for summation
                    // var intVal = function (i) {
                    //     return typeof i === 'string' ?
                    //         i.replace(/[\$,]/g, '') * 1 :
                    //         typeof i === 'number' ?
                    //             i : 0;
                    // };

                    // // Total over all pages
                    // received = api
                    //     .column(5)
                    //     .data()
                    //     .reduce(function (a, b) {
                    //         return intVal(a) + intVal(b);
                    //     }, 0);
                    //     console.log(received);

                    // $('#currentTotal').html('$ ' + received.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }
            });

//   function filter(job) {
//     // alert(job);
//     // department_id =$('#department_id').val();
//     // with_salary = $('#with_salary').find(":selected").val();
//     datatable.ajax.url('position?position_datatable').load();
// }

$(document).ready(function(){
  $('#modalUpdate').on('show.bs.modal', function (e) {
      var rowid = $(e.relatedTarget).data('id');
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      $.ajax({
          type : 'post',
          url : 'position', //Here you will fetch records 
          data: {
              position_id: rowid, action: "modalUpdate"
          },
          success : function(data){
              $('#modalUpdate .fetch-data').html(data);
              Swal.close();
              // $(".select2").select2();//Show fetched data from database
          }
      });
   });


   $('#addAppointmentModal').on('show.bs.modal', function (e) {
      var itemId = $(e.relatedTarget).data('id');
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      // alert(rowid);
      Swal.close();
      $('#addAppointmentModal').find('#appointmentItemId').val(itemId);

   });


   $('#addAppointmentModalOld').on('show.bs.modal', function (e) {
      var itemId = $(e.relatedTarget).data('id');
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      // alert(rowid);
      Swal.close();
      $('#addAppointmentModalOld').find('#appointmentItemId').val(itemId);

   });







});

function filter() {
            var empData = $('#employee_selection').select2('data');
            var depData = $('#department_select').select2('data');
            var posData = $('#position_select').select2('data');
    
            var empId ="";
            var depId ="";
            var posId ="";

            if (empData[0])
                empId = empData[0].id;
            if (depData[0])
                depId = depData[0].id;
            if (posData[0])
                posId = posData[0].id;
        
            else{
            datatable.ajax.url('plantilla?action=datatable&empId=' + empId + '&depId=' + depId + '&posId=' + posId).load();
            }
        }


  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>