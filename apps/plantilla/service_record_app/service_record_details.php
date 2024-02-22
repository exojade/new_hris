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
        Service Record

        
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


      <div class="modal fade" id="modalUpdateServiceRecord">
        <div class="modal-dialog modal-lg">
          <div class="modal-content ">
            <div class="modal-header bg-primary">
          
                <h3 class="modal-title text-center">Update Service Record</h3>
            </div>
            <form class="generic_form_trigger_datatable" data-url="service_record">
            <input type="hidden" name="action" value="updateServiceRecord">
            <div class="modal-body">
              
              <div class="fetched-data"></div>


            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalAddServiceRecord">
        <div class="modal-dialog modal-lg">
          <div class="modal-content ">
            <div class="modal-header bg-primary">
          
                <h3 class="modal-title text-center">Add Service Record</h3>
            </div>
            <form class="generic_form_trigger_datatable" data-url="service_record">
            <input type="hidden" name="action" value="addServiceRecord">
            <div class="modal-body">
              
              <div class="fetched-data"></div>


            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Regiser Service Record</button>
            </div>
            </form>
          </div>
        </div>
      </div>




    






    <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
            <form id="printForm" data-url="service_record">
    <input type="hidden" name="action" value="print">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-group mb-3">
                    <select style="width:100%;" required class="form-control" id="employee_selection" name="employee"></select>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <button type="button" onclick="filter();" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-6">
                    <select name="type_print" class="form-control" id="type_print">
                        <option value="SERVICE RECORD">SERVICE RECORD</option>
                        <option value="COE">CERT OF EMPLOYMENT</option>
                    </select>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-success" id="submitButton">Print</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group" style="float:right;">
                <a href="#" id="addServiceRecord" class="btn btn-success" data-id="" style="display:none;">ADD SERVICE RECORD</a>
            </div>
        </div>
    </div>
</form>

<!-- Modal for COE input -->
<div class="modal fade" id="coeModal" tabindex="-1" role="dialog" aria-labelledby="coeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coeModalLabel">Enter Purpose</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <div class="form-group">
                    <label for="name_prefix">Select Prefix:</label>
                    <select class="form-control" id="name_prefix" id="name_prefix" name="name_prefix">
                        <option value="MR. ">MR</option>
                        <option value="MS. ">MS</option>
                        <option value="ATTY. ">ATTY</option>
                        <option value="DR. ">DR</option>
                        <option value="ENGR. ">ENGR</option>
                    </select>
                </div>

                <div class="form-group">
                  <label>Purpose</label>
                
                <input type="text" id="coeInput" class="form-control" required placeholder="Enter Purpose Here">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="coeSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

              <div class="table-responsive">
                <table class="table table-striped table-bordered" id="serviceRecordDatatable" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th>ID</th>
                              <th>Name</th>
                              <th>From</th>
                              <th>To</th>
                              <th>Designation</th>
                              <th>Status</th>
                              <th>BMS</th>
                              <th>Agency</th>
                              <th>Branch</th>
                              <th>Office</th>
                              <th>Remarks</th>
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
    <script src="AdminLTE_new/plugins/select2/js/select2.full.min.js"></script>
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

  <script src="AdminLTE_new/dist/js/adminlte.min.js"></script>

  <script>
  

  $(document).ready(function() {
    $('body').on('submit', '#printForm', function(e) {
        var form = $(this)[0];
        var formData = new FormData(form);
        var url = $(this).data('url');
        var typePrint = $(this).find('select[name="type_print"]').val(); // Get the value of type_print dropdown

        e.preventDefault();
        Swal.fire({
            title: 'Do you want to submit the changes?',
            showCancelButton: true,
            confirmButtonText: 'Save',
        }).then((result) => {
            if (result.isConfirmed) {
                if (typePrint === "COE") {
                    // Open COE modal
                    $("#coeModal").modal('show');
                } else {
                    // Normal form submission
                    submitForm(formData, url);
                }
            } else if (result.isDenied) {
                // Handle if user denies submission
            }
        });
    });

    // Event listener for COE modal submission
    $("#coeSubmit").click(function() {
          var coeInput = $("#coeInput").val();
          var namePrefix = $("select[name='name_prefix']").val(); // Get the selected value of name_prefix
          if (coeInput.trim() !== "") {
              // Append COE input value and name_prefix to form and submit
              var formData = new FormData($('#printForm')[0]); // Get form data
              formData.append('coe_input', coeInput); // Append COE input
              formData.append('name_prefix', namePrefix); // Append name_prefix
              var url = $('#printForm').data('url'); // Get form URL
              $("#coeModal").modal('hide'); // Hide COE modal
              submitForm(formData, url); // Submit form via AJAX
          } else {
              alert("Please enter a value.");
          }
      });
});

// Function to submit the form via AJAX
function submitForm(formData, url) {
    Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
    $.ajax({
        type: 'post',
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        success: function(results) {
            var o = jQuery.parseJSON(results);
            if (o.status === "success") {
                Swal.close();
                Swal.fire({
                    title: "Submit success",
                    text: o.message,
                    icon: 'success',
                }).then(function() {
                    if (typeof(o.option) != "undefined" && o.option !== null) {
                        if (o.option == "new_tab") {
                            if (o.link == "refresh")
                                window.location.reload();
                            else if (o.link == "not_refresh")
                                console.log("");
                            else
                                window.open(o.link, "_blank");
                        } else if (o.option == "theFunctions") {
                            eval(o.theFunctions);
                        }
                    } else {
                        if (o.link == "refresh")
                            window.location.reload();
                        else if (o.link == "not_refresh")
                            console.log("");
                        else
                            window.location.replace(o.link);
                    }
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: o.message,
                    icon: "error"
                });
            }
        },
        error: function(results) {
            console.log(results);
            swal("Error!", "Unexpected error occur!", "error");
        }
    });
}




  




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
    function closeAllModals() {
    $('.modal').modal('hide');
  }

    $('body').on('submit', '.generic_form_trigger_datatable', function(e) {
      var form = $(this)[0];
  var formData = new FormData(form);
  var url = $(this).data('url');
  e.preventDefault();
  Swal.fire({
  title: 'Do you want to submit the changes?',
  showCancelButton: true,
  confirmButtonText: 'Save',
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      $.ajax({
                type: 'post',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (results) {
                var o = jQuery.parseJSON(results);
                if(o.status === "success") {
                    Swal.close();
                    Swal.fire({title: "Submit success",
                    text: o.message,
                    icon: 'success',})
                    .then(function () {
                     
                      if(typeof(o.option) != "undefined" && o.option !== null) {
                          if(o.option == "new_tab"){
                            console.log("nisulod sa newtab");
                            // alert(o.link);
                            if(o.link == "datatable"){
                              var selectData = $('#employee_selection').select2('data');
                              console.log(selectData);
                            var id = '';
                            if (selectData[0]) {
                                id = selectData[0].id;
                                employee_id = id;
                                console.log(id);
                                closeAllModals();
                            datatable.ajax.url('service_record?action=serviceRecordDatatable&employee=' + id).load();
                        }
                            }
                            else if(o.link == "not_refresh")
                              console.log("");
                            else
                              window.open(o.link, "_blank");
                          }
                      }
                      else{
                        if(o.link == "datatable"){
                          var selectData = $('#employee_selection').select2('data');
                              console.log(selectData);
                            var id = '';
                            if (selectData[0]) {
                                id = selectData[0].id;
                                employee_id = id;
                                console.log(id);
                                closeAllModals();
                            datatable.ajax.url('service_record?action=serviceRecordDatatable&employee=' + id).load();
                            }
                        }
                        else if(o.link == "not_refresh")
                          console.log("");
                        else
                          window.location.replace(o.link);

                      }
                      
                    });
                }
                else {
                    Swal.fire({
                    title: "Error!",
                    text: o.message,
                    icon:"error"
                    });
                    console.log(results);
                }
                },
                error: function(results) {
                console.log(results);
                swal("Error!", "Unexpected error occur!", "error");
                }
            });
    } else if (result.isDenied) {
    
    }
  })

            // The rest of your JavaScript code (Swal and AJAX calls) goes here...
        });



    var datatable = 
            $('#serviceRecordDatatable').DataTable({
                // "searching": false,
                "pageLength": 100,
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                searching: false,
                "bLengthChange": false,
                // "info":     false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                
                'ajax': {
                    'url':'service_record',
                     'type': "POST",
                     "data": function (data){
                        data.action = "serviceRecordDatatable";
                     }
                },
                'columns': [
                    { data: 'update', "orderable": false },
                    { data: 'delete', "orderable": false },
                    { data: 'fingerid', "orderable": false },
                    { data: 'employee_name', "orderable": false },
                    { data: 'from', "orderable": false  },
                    { data: 'to', "orderable": false  },
                    { data: 'position', "orderable": false  },
                    { data: 'status', "orderable": false  },
                    { data: 'bms', "orderable": false  },
                    { data: 'assignment', "orderable": false  },
                    { data: 'branch', "orderable": false  },
                    { data: 'office_assignment', "orderable": false  },
                    { data: 'remarks', "orderable": false  },
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
            employee_id = "";
            function filter() {
            var selectData = $('#employee_selection').select2('data');
            var id = '';
            if (selectData[0]) {
                id = selectData[0].id;
                $('#addServiceRecord').show();
                $('#addServiceRecord').attr('data-id', id);
                employee_id = id;
            }
            else
            $('#addServiceRecord').hide();
            datatable.ajax.url('service_record?action=serviceRecordDatatable&employee=' + id).load();
        }



        $('#addServiceRecord').on('click', function () {
          Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
          id = employee_id;
          $.ajax({
          type : 'post',
          url : 'service_record', //Here you will fetch records 
          data: {
              id: id, action: "modalAddServiceRecord"
          },
          success : function(data){
            var o = jQuery.parseJSON(data);
            console.log(o);
            if(o.status == "failed"){
     
              Swal.close();
              Swal.fire({
                    title: "Error!",
                    text: o.message,
                    icon:"error"
                    });
                    // $('#modalAddServiceRecord').hide();
            }
            else{
              Swal.close();
              console.log(o);
              $('#modalAddServiceRecord .fetched-data').html(o.message);
              $('#designation_select2').select2()
              $('#modalAddServiceRecord').modal('show');
            }
              // $(".select2").select2();//Show fetched data from database
          }
      });
      // Get the data-id attribute value
      // var dataId = $(this).data('id');

      // // Log the data-id for debugging purposes
      // console.log('Data ID:', dataId);

      // Do something with the data-id, for example, open a modal
      // ...

      // If you want to hide the modalAddServiceRecord after clicking addServiceRecord
      // $('#modalAddServiceRecord').modal('hide');
    });

  //     $('#modalAddServiceRecord').on('show.bs.modal', function (e) {
  //     id = employee_id;
  //     // console.log(id);
  //     Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
  //     $.ajax({
  //         type : 'post',
  //         url : 'service_record', //Here you will fetch records 
  //         data: {
  //             id: id, action: "modalAddServiceRecord"
  //         },
  //         success : function(data){
  //           var o = jQuery.parseJSON(data);
  //           console.log(o);
  //           if(o.status == "failed"){
     
  //             Swal.close();
  //             Swal.fire({
  //                   title: "Error!",
  //                   text: o.message,
  //                   icon:"error"
  //                   });
  //                   // $('#modalAddServiceRecord').hide();
  //           }
  //           else{
  //             $('#modalAddServiceRecord .fetched-data').html(o.message);
  //           }


              
        
  //             // $(".select2").select2();//Show fetched data from database
  //         }

          
  //     });

  //     $('#modalAddServiceRecord').attr('data-id', id);
  //  });






  // $('.select2').select2()



function dialog_trigger(ele){
    $('.modal-body').load(dataURL,function(){
        $('#myModal').modal({show:true});
    });

}




$(document).ready(function(){
  $('#modalUpdateServiceRecord').on('show.bs.modal', function (e) {
      var id = $(e.relatedTarget).data('id');
      Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
      $.ajax({
          type : 'post',
          url : 'service_record', //Here you will fetch records 
          data: {
              id: id, action: "modalUpdateServiceRecord"
          },
          success : function(data){
              $('.fetched-data').html(data);
               $('#designation_select').select2()

              Swal.close();
              // $(".select2").select2();//Show fetched data from database
          }
      });
   });
});




  



$(document).ready(function(){
    
    $('#modal_update_payroll').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
        $.ajax({
            type : 'post',
            url : 'employees', //Here you will fetch records 
            data: {
                employee_id: rowid, action: "modal_update_payroll"
            },
            success : function(data){
                $('.fetched-payroll').html(data);
                Swal.close();
                // $(".select2").select2();//Show fetched data from database
            }
        });
     });


     $('#modal_edit_deduction').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        var deduction = $(e.relatedTarget).data('deduction');
        swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
        $.ajax({
            type : 'post',
            url : 'employees', //Here you will fetch records 
            data: {
                employee_id: rowid, deduction: deduction ,action: "modal_edit_deduction"
            },
            success : function(data){
                $('.fetched_edit_deduction').html(data);
                swal.close();
                // $(".select2").select2();//Show fetched data from database
            }
        });
     });


     $('#modal_update_record').on('show.bs.modal', function (e) {
        var rowid = $(e.relatedTarget).data('id');
        swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
        $.ajax({
            type : 'post',
            url : 'employees', //Here you will fetch records 
            data: {
                service_record_id: rowid ,action: "modal_update_record"
            },
            success : function(data){
                $('.fetched_record_data').html(data);
                swal.close();
                // $(".select2").select2();//Show fetched data from database
            }
        });
     });




  });






$(document).ready(function(){
    $('#modal_appointment_specific').on('show.bs.modal', function (e) {
        var dataURL = $(e.relatedTarget).data('id');
        console.log(dataURL);
        $('.modal-body').load(dataURL,function(){
            $('#modal_appointment_specific').modal({show:true});
            
        });
     
     });
});


$('#select_option').click(function(){
    var method_select = $('#method_select').find(":selected").val();
    if(method_select == "")
        alert("Please Select a method for adding a service record");
    else{
        if(method_select == "appointment_record")
            $('#modal_add_appointment').modal({show:true});
        else if (method_select == "step_increment")
            $('#modal_step_increment').modal({show:true});
        else if (method_select == "salary_adjustment")
            $('#modal_salary_adjustment').modal({show:true});




    }
});







function print(ele) {
    var employee_id = $(ele).attr("data-id");
    var action = $(ele).attr("data-action");
    var from_date = "";
    var to_date = ""
    var url = "";

    if(action == "generate_individual_timesheet"){
        from_date = $("#from_date_timesheet").val();
        to_date = $("#to_date_timesheet").val();
        url = "dtr"
    }

    if(action == "generate_individual_dtr"){
        from_date = $("#from_date").val();
        to_date = $("#to_date").val();
        url = "dtr"
    }

    if(action == "print_appointment_form"){
      url = "print";
    }




    // console.log(transaction_type);
    swal({title: 'Please wait...', imageUrl: './public/images/loader/green-loader.gif', showConfirmButton: false});
    $.ajax({
      type: 'post',
      url: url,
      data: {
        employee_id: employee_id, 
        from_date: from_date, 
        to_date: to_date, 
        action: action, 
      },
      success: function (results) {
        var o = jQuery.parseJSON(results);
        o = o.info["0"];
        console.log(o);
        if(o.result === "success") {
          swal({
            title: "Success!",
            text: "Successfully rendered List: " + o.filename,
            icon: "warning",
             showCancelButton: true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'View',
              cancelButtonText: "Cancel",
              closeOnConfirm: false,
              closeOnCancel: false,
            dangerMode: true,
          }).then(function(isConfirm) {
            if (isConfirm) {
                var fileName = o.path;
                    $("#dialog").dialog({
                        modal: true,
                        title: fileName,
                        width: 1200,
                        height: "auto",
                        resizable: false,
                        draggable: false,
                        closeOnEscape: true,
                        dialogClass: 'fixed-dialog',
                        buttons: {
                            Close: function () {
                                $(this).dialog('close');
                            }
                        },
                        open: function () {
                            var object = "<object data=\""+o.path+"\" type=\"application/pdf\" width=\"1200px\" height=\"500px\">";
                            object += "If you are unable to view file, you can download from <a href = \"{FileName}\">here</a>";
                            object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
                            object += "</object>";
                            object = object.replace(/{FileName}/g, "Files/" + fileName);
                            $("#dialog").html(object);
                        }
                    });
             
              // console.log("confirmed");
              // window.open(o.path, '_blank');
        // window.location = parsed.path;
        swal.close();
            } else {
              console.log("cancelled");
        
        swal.close();
            }
          });
        }
      }
      });
    }




    $('#position_select').select2({
        minimumInputLength: 3,
        placeholder: "Search by Position Name or Title",
        ajax: {
            url: 'ajax_position',
            dataType: 'json',
            processResults: function (data) {
            return {
              results: $.map(data.results, function (item) {
                return {
                      text: item.supplier,
                      id: item.supplier,
                    }
                })
              }
            }
          },
        });

        $('#position_service').select2({
            minimumInputLength: 3,
            placeholder: "Search Position",
            ajax: {
                url: 'ajax_service_position',
                dataType: 'json',
                processResults: function (data) {
                return {
                  results: $.map(data.results, function (item) {
                    return {
                          text: item.supplier,
                          id: item.supplier,
                        }
                    })
                  }
                }
              },
            });


        $('#position_select_appointment').select2({
            minimumInputLength: 3,
            placeholder: "Search by Position Name or Title",
            ajax: {
                url: 'ajax_position_appointment',
                dataType: 'json',
                processResults: function (data) {
                return {
                  results: $.map(data.results, function (item) {
                    return {
                          text: item.name,
                          id: item.Positionid,
                        }
                    })
                  }
                }
              },
            });


            $('.department_select_appointment').select2({
                minimumInputLength: 3,
                placeholder: "Search by Department",
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

                $(document).ready(function(){
                    $('.appointment_select').select2({
                        minimumInputLength: 3,
                        placeholder: "Search Appointment",
                        ajax: {
                            url: 'ajax_appointment',
                            dataType: 'json',
                            processResults: function (data) {
                            return {
                              results: $.map(data.results, function (item) {
                                return {
                                      text: item.supplier,
                                      id: item.supplier,
                                    }
                                })
                              }
                            }
                          },
                        });
                });



       


        $('#place_select').select2({
            minimumInputLength: 2,
            placeholder: "Search place of assignment",
            ajax: {
                url: 'ajax_assignment',
                dataType: 'json',
                processResults: function (data) {
                return {
                  results: $.map(data.results, function (item) {
                    return {
                          text: item.supplier,
                          id: item.supplier,
                        }
                    })
                  }
                }
              },
            });



    $('#add_record_form').submit(function(e) {
        e.preventDefault();
        swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
        $.ajax({
            type: 'post',
            url: 'employees',
            data: $('#add_record_form').serialize(),
            success: function (results) {
                var o = jQuery.parseJSON(results);
                if(o.result == 'success'){
                    window.location.replace(o.link);
                }
                else if(o.result == 'failed'){
                    swal({
                        title: o.title,
                        text: o.message,
                        type: "error"
                    });
                }
            }
        });
        });


        $('.form_pass_general').submit(function(e) {
          e.preventDefault();
          swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
          $.ajax({
              type: 'post',
              url: 'employees',
              data: $('.form_pass_general').serialize(),
              success: function (results) {
                  var o = jQuery.parseJSON(results);
                  if(o.result == 'success'){
                      window.location.replace(o.link);
                  }
                  else if(o.result == 'failed'){
                      swal({
                          title: o.title,
                          text: o.message,
                          type: "error"
                      });
                  }
              }
          });
          });



          $('#employee_compensation_form').submit(function(e) {
            e.preventDefault();
            swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
            $.ajax({
                type: 'post',
                url: 'employees',
                data: $('#employee_compensation_form').serialize(),
                success: function (results) {
                    var o = jQuery.parseJSON(results);
                    if(o.result == 'success'){
                        window.location.replace(o.link);
                    }
                    else if(o.result == 'failed'){
                        swal({
                            title: o.title,
                            text: o.message,
                            type: "error"
                        });
                    }
                }
            });
            });



        $('#update_appointment_information').submit(function(e) {
            e.preventDefault();
            swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
            $.ajax({
                type: 'post',
                url: 'employees',
                data: $('#update_appointment_information').serialize(),
                success: function (results) {
                    var o = jQuery.parseJSON(results);
                    if(o.result == 'success'){
                        window.location.replace(o.link);
                    }
                    else if(o.result == 'failed'){
                        swal({
                            title: o.title,
                            text: o.message,
                            type: "error"
                        });
                    }
                }
            });
            });

         



            $('#register_deduction_form').submit(function(e) {
                e.preventDefault();
                swal({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
                $.ajax({
                  type: 'post',
                  url: 'employees',
                  data: $('#register_deduction_form').serialize(),
                  success: function (results) {
                      var o = jQuery.parseJSON(results);
                    //   alert(o.result);
                      if(o.result == 'success'){
                          window.location.replace(o.link);
                      }
                      else if(o.result == 'failed'){
                          swal({
                              title: o.title,
                              text: o.message,
                              type: "error"
                          });
                      }
                  }
                });
              });
            
            
            
            $('#deduction_select').on('change', function() {
            //   ( this.value );
              var rule_based = $(this).find(':selected').attr('data-id');
              var rule = $(this).find(':selected').attr('data-rule');
              if(rule_based == 1){
                $("#amount_group").css("display", "none");
                $("#rule").val(rule);
                $("#amount_input").val("");
              }
              else{
                $("#amount_group").css("display", "block");
                $("#rule").val("");
              }
            });


            $('.datepicker').datepicker({
                autoclose: true
              })
              
             //$('.select2').select2()

  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>