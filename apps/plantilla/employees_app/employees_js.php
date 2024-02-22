<script>
  function closeAllModals() {
    $('.modal').modal('hide');
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

    $('#job_type').select2({
            placeholder: 'Please Select JOB TYPE' // Placeholder text
        });
    $('#active_status').select2();

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
            $('#employees-datatable').DataTable({
                // "searching": false,

    //             'columnDefs': [
    //               { 'width': '35%', 'targets': [2,5] },
    //             { 'width': '30%', 'targets': '_all' }
    //     // Adjust the percentage values based on your requirements
    // ],
    'scrollX': true,
                "pageLength": 10,
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
                    'url':'employees',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                     }
                },
                'columns': [
                  { data: 'Employeeid', "visible": false, "searchable": false },
                    // { data: 'action', "orderable": false },
                    { data: 'biometric_id', "orderable": false },
                    { data: 'name', "orderable": false  },
                    { data: 'department', "orderable": false  },
                    { data: 'department_assigned', "orderable": false  },
                    { data: 'position', "orderable": false  },
                    { data: 'lbp_number', "orderable": false  },
                    { data: 'JobType', "orderable": false  },
                    { data: 'salary_grade', "orderable": false  },
                    { data: 'salary_step', "orderable": false  },
                    { data: 'salary_class', "orderable": false  },
                    { data: 'salary', "orderable": false  },
                    { data: 'date_hired', "orderable": false  },
                    { data: 'original_appointment', "orderable": false  },
                    { data: 'continuous', "orderable": false  },
                    { data: 'promotion', "orderable": false  },
                    { data: 'print_remarks', "orderable": false  },
                    { data: 'active_status', "visible": false, "orderable": false  },
          
                ],
                

  
                'rowCallback': function(row, data) {

                  console.log(data.active_status);
                  var activeStatus = data.active_status;
                  // Check if 'active_status' is equal to 'active'
                  if (activeStatus == 0)
                      $(row).css('color', '#EE4266');
           
                  
                    // Add a click event listener to each row
                    $(row).on('dblclick', function() {
                        // Get the DataTable API instance
                        var table = $('#employees-datatable').DataTable();

                        

                        // Get the index of the clicked row using dataTables API
                        var rowIndex = table.row(this).index();

                        // Alternatively, you can use the 'data' parameter to access the row data
                        // var rowIndex = data.yourPrimaryKeyColumn;
                        var employeeId = data.Employeeid;
                        // alert(employeeId);

                        $.ajax({
                            type: 'post',
                            url: 'employees',
                            data: {
                                employee_id: employeeId,
                                rowIndex: rowIndex,
                                action: "modal_edit_employees",
                                // awit: "awit",
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Please wait...',
                                    imageUrl: 'AdminLTE/dist/img/loader.gif',
                                    showConfirmButton: false
                                });
                            },
                            success: function(data) {
                                // Populate the modal with fetched data
                                $('#modal_edit_employees .fetch-data-emp').html(data);

                                // Close the loading modal
                                Swal.close();

                                // Open the modal
                                $('#modal_edit_employees').modal('show');
                                $('#position_select').select2();
                                $('#salary_select').select2();
                            }
                        });

                        // Now you can use rowIndex in your filter2 function or elsewhere
                        // alert('Clicked row index: ' + employeeId);
                    });
                },


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

            function filter() {
    var jobtypeData = $('#job_type').select2('data');
    var depData = $('#department_select').select2('data');
    var activeStatusData = $('#active_status').select2('data');

    var jobType = [];
    var depIds = [];
    var activeStatus = "";

    // Extracting IDs from selected options
    jobtypeData.forEach(function(option) {
        jobType.push(option.id);
    });

    depData.forEach(function(option) {
        depIds.push(option.id);
    });

    if (activeStatusData.length > 0) {
        activeStatus = activeStatusData[0].id;
    }

    // Constructing URL based on selected options
    var url = 'employees?action=datatable';
    if (jobType.length > 0) {
        url += '&jobType=' + jobType.join(',');
    }
    if (depIds.length > 0) {
        url += '&depId=' + depIds.join(',');
    }
    if (activeStatus) {
        url += '&activeStatus=' + activeStatus;
    }

    // Load data with constructed URL
    datatable.ajax.url(url).load();
}









function dialog_trigger(ele){
    $('.modal-body').load(dataURL,function(){
        $('#myModal').modal({show:true});
    });

}




$(document).ready(function(){
  $('#modalContinuous').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    reloadModalContentContinuous(rowid);
  });
});



function reloadModalContentContinuous(employeeId) {
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });

  $.ajax({
    type: 'post',
    url: 'employees',
    data: {
      employee_id: employeeId,
      action: "modalContinuous"
    },
    success: function (data) {
      $('#modalContinuous .fetch').html(data);
      Swal.close();
    }
  });
}

function deleteContinuous(id, employeeId){
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });
  // alert(id);
  var arrayData = {
    action: 'deleteContinuous',
    id: id
  };

  $.ajax({
    type: 'post',
    url: "employees",
    data: arrayData,
    success: function (status) {
      var o = jQuery.parseJSON(status);
      if(o.status === "success") {
        reloadModalContentContinuous(employeeId);
        Swal.close();
      }
      // Reload the modal content based on the employee_id

      // Close Swal
    },
    error: function (results) {
      console.log(results);
      swal("Error!", "Unexpected error occur!", "error");
    }
  });
}


$('body').on('submit', '.form_continuous', function (e) {
  var form = $(this)[0];
  var formData = new FormData(form);
  var url = "employees";
  e.preventDefault();
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });

  var employeeId = formData.get('employee_id');

  // var actionBtn = $(this).find('[name="actionButton"]:focus').val();
  $.ajax({
    type: 'post',
    url: url,
    data: formData,
    processData: false,
    contentType: false,
    success: function (status) {
      var o = jQuery.parseJSON(status);
      if(o.status === "success") {
        reloadModalContentContinuous(employeeId);
        Swal.close();
      }
      // Reload the modal content based on the employee_id

      // Close Swal
    },
    error: function (results) {
      console.log(results);
      swal("Error!", "Unexpected error occur!", "error");
    }
  });
});


function duplicateRowContinuous(button) {
        // Find the form element and clone it
        var originalForm = $(button).prev('.form_continuous');
        var form = originalForm.clone();

        // Get the original employee_id value
        var originalEmployeeId = originalForm.find('[name="employee_id"]').val();
        var action = originalForm.find('[name="action"]').val();

        // Clear the values in the cloned form
        form.find(':input').val('');
        form.find('[name="status"]').val('INACTIVE');
        // Set the cloned employee_id value
        form.find('[name="employee_id"]').val(originalEmployeeId);
        form.find('[name="action"]').val(action);

        // Append the cloned form after the original form
        form.insertAfter(originalForm);
        form.find('.col-1 a').attr('onclick', 'deleteRowContinuous(this)').text('Delete').addClass('cloned-row');
    }
    function deleteRowContinuous(link) {
        // Remove the row
        $(link).closest('.form_continuous').remove();
    }

    function resetFormContinuous(link) {
        // Reset all input fields within the form
        $(link).closest('.form_continuous').find(':input').val('');
    }











$(document).ready(function(){
  $('#modalPromotion').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    reloadModalContentPromotion(rowid);
  });
});



function reloadModalContentPromotion(employeeId) {
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });

  $.ajax({
    type: 'post',
    url: 'employees',
    data: {
      employee_id: employeeId,
      action: "modalPromotion"
    },
    success: function (data) {
      $('#modalPromotion .fetch').html(data);
      Swal.close();
    }
  });
}

function deletePromotion(id, employeeId){
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });
  // alert(id);
  var arrayData = {
    action: 'deletePromotion',
    id: id
  };

  $.ajax({
    type: 'post',
    url: "employees",
    data: arrayData,
    success: function (status) {
      var o = jQuery.parseJSON(status);
      if(o.status === "success") {
        reloadModalContentPromotion(employeeId);
        Swal.close();
      }
      // Reload the modal content based on the employee_id

      // Close Swal
    },
    error: function (results) {
      console.log(results);
      swal("Error!", "Unexpected error occur!", "error");
    }
  });
}


$('body').on('submit', '.form_promotion', function (e) {
  var form = $(this)[0];
  var formData = new FormData(form);
  var url = "employees";
  e.preventDefault();
  Swal.fire({
    title: 'Please wait...',
    imageUrl: 'AdminLTE/dist/img/loader.gif',
    showConfirmButton: false
  });

  var employeeId = formData.get('employee_id');

  // var actionBtn = $(this).find('[name="actionButton"]:focus').val();
  $.ajax({
    type: 'post',
    url: url,
    data: formData,
    processData: false,
    contentType: false,
    success: function (status) {
      var o = jQuery.parseJSON(status);
      if(o.status === "success") {
        reloadModalContentPromotion(employeeId);
        Swal.close();
      }
      // Reload the modal content based on the employee_id

      // Close Swal
    },
    error: function (results) {
      console.log(results);
      swal("Error!", "Unexpected error occur!", "error");
    }
  });
});


function duplicateRowPromotion(button) {
        // Find the form element and clone it
        var originalForm = $(button).prev('.form_promotion');
        var form = originalForm.clone();

        // Get the original employee_id value
        var originalEmployeeId = originalForm.find('[name="employee_id"]').val();
        var action = originalForm.find('[name="action"]').val();

        // Clear the values in the cloned form
        form.find(':input').val('');
        form.find('[name="status"]').val('INACTIVE');
        // Set the cloned employee_id value
        form.find('[name="employee_id"]').val(originalEmployeeId);
        form.find('[name="action"]').val(action);

        // Append the cloned form after the original form
        form.insertAfter(originalForm);
        form.find('.col-1 a').attr('onclick', 'deleteRowPromotion(this)').text('Delete').addClass('cloned-row');
    }
    function deleteRowPromotion(link) {
        // Remove the row
        $(link).closest('.form_promotion').remove();
    }

    function resetFormPromotion(link) {
        // Reset all input fields within the form
        $(link).closest('.form_promotion').find(':input').val('');
    }





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


        // $('#position_select_appointment').select2({
        //     minimumInputLength: 3,
        //     placeholder: "Search by Position Name or Title",
        //     ajax: {
        //         url: 'ajax_position_appointment',
        //         dataType: 'json',
        //         processResults: function (data) {
        //         return {
        //           results: $.map(data.results, function (item) {
        //             return {
        //                   text: item.name,
        //                   id: item.Positionid,
        //                 }
        //             })
        //           }
        //         }
        //       },
        //     });


        //     $('.department_select_appointment').select2({
        //         minimumInputLength: 3,
        //         placeholder: "Search by Department",
        //         ajax: {
        //             url: 'ajax_department_appointment',
        //             dataType: 'json',
        //             processResults: function (data) {
        //             return {
        //               results: $.map(data.results, function (item) {
        //                 return {
        //                       text: item.name,
        //                       id: item.id,
        //                     }
        //                 })
        //               }
        //             }
        //           },
        //         });

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
              
            //   $('.select2').select2()
</script>