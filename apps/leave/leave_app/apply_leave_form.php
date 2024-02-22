<?php 


?>




<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/fullcalendar/main.css">
<link rel="stylesheet" href="AdminLTE_new/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">

<!-- <link rel="stylesheet" href="layouts/ios.css"> -->
  <div class="content-wrapper">
  <div class="d-flex">
        <section class="content-header">
            <h1>
                Leave Application
            </h1>
        </section>
        <div class="d-flex ml-auto content-header">
            <div class="px-5"><h5>Vacation Leave</h5><a href="#"><?php echo($vacation_leave); ?></a></div>
            <div class="px-5"><h5>Sick Leave</h5><a href="#"><?php echo($sick_leave); ?></a></div>
            <div class="px-5"><h5>Special Leave</h5><a href="#"><?php echo($special_leave); ?></a></div>
        </div>
    </div>
    <section class="content">
    <div class="modal fade" id="applyLeave">
      <div class="modal-dialog modal-lg">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Apply Leave</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="apply_leave" autocomplete="off">
                  <input type="hidden" name="action" value="applyLeave">
                  <?php if(isset($_GET["employee"])): ?>
                    <input type="hidden" name="employee_id" value="<?php echo($_GET["employee"]); ?>"> 
                  <?php else: ?>
                    <input type="hidden" name="employee_id" value="<?php echo($_SESSION["hris"]["employee_id"]); ?>"> 
                  <?php endif; ?>
          

                  <div class="row">
                    <div class="col-md-3">
                    <div class="form-group">
                    <label>From Date</label>
                    <input name="from_date" type="date" class="form-control" id="from_date" placeholder="Enter email" >
                  </div>
                  </div>
                  <div class="col-md-3">

                  <div class="form-group">
                    <label>Time</label>
                    <select name="from_time" class="form-control" id="from_time">
                      <option selected value="MORNING">MORNING</option>
                      <option value="AFTERNOON">AFTERNOON</option>
                    </select>
                  </div>
                  </div>
                    
                    <div class="col-md-3">
                    <div class="form-group">
                    <label>From Date</label>
                    <input name="to_date" type="date" class="form-control" id="to_date" placeholder="Enter email" >
                  </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                    <label>Time</label>
                      <select name="to_time" class="form-control" id="to_time">
                        <option value="MORNING">MORNING</option>
                        <option selected value="AFTERNOON">AFTERNOON</option>
                      </select>
                    </div>
                  </div>
                  </div>
                  <div id="holidayDiv" class="bg-success disabled color-palette"></div>
                  <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                    <label>Select Type of Leave</label>
                      <select name="leaveType" class="form-control" id="leaveType">
                        <option value="SICK LEAVE">SICK LEAVE</option>
                        <option value="VACATION LEAVE">VACATION LEAVE</option>
                        <option value="SPECIAL PRIVELEGE LEAVE">SPECIAL PRIVELEGE LEAVE</option>
                        <option value="MANDATORY / FORCED LEAVE">MANDATORY / FORCED LEAVE</option>
                        <option value="MATERNITY LEAVE">MATERNITY LEAVE</option>
                        <option value="PATERNITY LEAVE">PATERNITY LEAVE</option>
                        <option value="SOLO PARENT LEAVE">SOLO PARENT LEAVE</option>
                        <option value="STUDY LEAVE">STUDY LEAVE</option>
                        <option value="REHABILITATION LEAVE">REHABILITATION LEAVE</option>
                      </select>
                    </div>

                    </div>
                    <div class="col-md-6">

                    </div>
                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Dates Covered</label>
                        <input name="daysCovered" type="text" class="form-control" id="number_of_days" readonly>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label>Remaining Leave Balance</label>
                        <input type="text" class="form-control" id="leave_balance" readonly>
                      </div>
                      
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label>With Pay / Without Pay</label>
                        <input type="text" class="form-control" id="withorwithoutpay" readonly>
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


    <div class="row">
   
      <div class="col-md-12">


      <div class="row">
        <div class="col-8">
          <h3><?php 
            if(!isset($_GET["employee"])):
              echo($_SESSION["hris"]["fullname"]);
            else:
              $emp = query("select * from tblemployee where Employeeid = ?", $_GET["employee"]);
              echo($emp[0]["FirstName"] . " " . $emp[0]["LastName"]);

            endif;
          ?>  
          </h3>

        </div>
      <div class="col-4">
      <?php if (checkRole($_SESSION["hris"]["roles"], "AO LEAVE")): ?>
      <form class="generic_form_no_trigger" data-url="apply_leave">
      <input type="hidden" value="redirectLeave" name="action">
      <div class="row">
        <div class="col-6">
          <div class="form-group">
            <div class="input-group mb-3">
              <select style="width:100%;" class="form-control" id="employee_selection" name="employee" ></select>
            </div>
          </div>
        </div>
        <div class="col-6">
          <button class="btn btn-primary btn-block">Select</button>
               
        </div>
      </div>
      </form>
      <?php endif; ?>
        </div>
      </div>


      <div class="card">
              <div class="card-header d-flex p-0">
              <ul class="nav nav-pills p-2">
                  <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Leave Application</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Instructions and Requirements</a></li>
                </ul>
                <h3 class="card-title p-3 ml-auto">             </h3>
               
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div id="calendar" style="max-height: 450px !important; "></div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                  <b>Application for any type of leave shall be made on this Form and to be accomplished at least in duplicate with documentary requirements, as follows:</b><br><br>

   
    <div class="row">
        <div class="col-md-6">
          <div style="padding-left: 10px;">
            <ul class="list-group list-unstyled">
                <li>
                    <b>1. Vacation leave</b>
                    <ul>
                    <li>
                        It shall be filed five (5) days in advance, whenever possible, of the effective date of such leave. Vacation leave within the Philippines or abroad shall be indicated in the form for purposes of securing travel authority and completing clearance from money and work accountabilities.
                    </li>
                  </ul>
                </li>
                <li>
                <b>2. Mandatory/Forced leave</b>
                    <ul>
                      <li>
                        Annual five-day vacation leave shall be forfeited if not taken during the year. In case the scheduled leave has been canceled in the exigency of the service by the head of the agency, it shall no longer be deducted from the accumulated vacation leave. Availment of one (1) day or more Vacation Leave (VL) shall be considered for complying the mandatory/forced leave subject to the conditions under Section 25, Rule XVI of the Omnibus Rules Implementing E.O. No. 292.
                      </li>
                    </ul>
                </li>
                <li>
                <b>3. Sick leave</b>
                    <ul>
                        <li>It shall be filed immediately upon employee's return from such leave.</li>
                        <li>If filed in advance or exceeding five (5) days, application shall be accompanied by a medical certificate. In case medical consultation was not availed of, an affidavit should be executed by an applicant.</li>
                    </ul>
                </li>
                <li>
                <b> 4. Maternity leave - 105 days</b>
                    <ul>
                        <li>Proof of pregnancy e.g. ultrasound, doctor’s certificate on the expected date of delivery</li>
                        <li>Accomplished Notice of Allocation of Maternity Leave Credits (CS Form No. 6a), if needed</li>
                        <li>Seconded female employees shall enjoy maternity leave with full pay in the recipient agency.</li>
                    </ul>
                </li>
                <li>
                <b>5. Paternity leave - 7 days</b>
                    <ul>
                      <li>Proof of child’s delivery e.g. birth certificate, medical certificate and marriage contract</li>
                    </ul>
                </li>
                <li>
                <b>6. Special Privilege leave - 3 days</b>
                    <ul>
                      <li>
                        It shall be filed/approved for at least one (1) week prior to availment, except on emergency cases. Special privilege leave within the Philippines or abroad shall be indicated in the form for purposes of securing travel authority and completing clearance from money and work accountabilities.
                      </li>
                    </ul>
                </li>
                <li>
                <b>7. Solo Parent leave - 7 days</b>
                    <ul>
                      <li>
                        It shall be filed in advance or whenever possible five (5) days before going on such leave with updated Solo Parent Identification Card.
                        </li>
                    </ul>
                </li>
                <li>
                <b>8. Study leave - up to 6 months</b>
                    <ul>
                        <li>Shall meet the agency’s internal requirements, if any;</li>
                        <li>Contract between the agency head or authorized representative and the employee concerned.</li>
                    </ul>
                </li>
                <li>
                <b>9. VAWC leave - 10 days</b>
                    <ul>
                        <li>It shall be filed in advance or immediately upon the woman employee’s return from such leave.</li>
                        <li>It shall be accompanied by any of the following supporting documents:</li>
                        <ul>
                            <li>Barangay Protection Order (BPO) obtained from the barangay;</li>
                            <li>Temporary/Permanent Protection Order (TPO/PPO) obtained from the court;</li>
                            <li>If the protection order is not yet issued by the barangay or the court, a certification issued by the Punong Barangay/Kagawad or Prosecutor or the Clerk of Court that the application for the BPO, TPO or PPO has been filed with the said office shall be sufficient to support the application for the ten-day leave; or</li>
                            <li>d.	In the absence of the BPO/TPO/PPO or the certification, a police report specifying the details of the occurrence of violence on the victim and a medical certificate may be considered, at the discretion of the immediate supervisor of the woman employee concerned. </li>
                        </ul>
                    </ul>
                </li>
            </ul>
            </div>
        </div>
        <div class="col-6">

        <ul class="list-group list-unstyled">
    <!-- ... (previous list items) ... -->

    <li>
        <b>10. Rehabilitation leave* – up to 6 months</b>
        <ul>
            <li>
                Application shall be made within one (1) week from the time of the accident except when a longer period is warranted.
            </li>
            <li>
                Letter request supported by relevant reports such as the police report, if any,
            </li>
            <li>
                Medical certificate on the nature of the injuries, the course of treatment involved, and the need to undergo rest, recuperation, and rehabilitation, as the case may be.
            </li>
            <li>
                Written concurrence of a government physician should be obtained relative to the recommendation for rehabilitation if the attending physician is a private practitioner, particularly on the duration of the period of rehabilitation.
            </li>
        </ul>
    </li>

    <li>
        <b>11. Special leave benefits for women* – up to 2 months</b>
        <ul>
            <li>
                The application may be filed in advance, that is, at least five (5) days prior to the scheduled date of the gynecological surgery that will be undergone by the employee. In case of emergency, the application for special leave shall be filed immediately upon employee’s return but during confinement the agency shall be notified of said surgery.
            </li>
            <li>
                The application shall be accompanied by a medical certificate filled out by the proper medical authorities, e.g. the attending surgeon accompanied by a clinical summary reflecting the gynecological disorder which shall be addressed or was addressed by the said surgery; the histopathological report; the operative technique used for the surgery; the duration of the surgery including the peri-operative period (period of confinement around surgery); as well as the employee's estimated period of recuperation for the same.
            </li>
        </ul>
    </li>

    <li>
        <b>12. Special Emergency (Calamity) leave – up to 5 days</b>
        <ul>
            <li>
                The special emergency leave can be applied for a maximum of five (5) straight working days or staggered basis within thirty (30) days from the actual occurrence of the natural calamity/disaster. Said privilege shall be enjoyed once a year, not in every instance of calamity or disaster.
            </li>
            <li>
                The head of office shall take full responsibility for the grant of special emergency leave and verification of the employee’s eligibility to be granted thereof. Said verification shall include: validation of place of residence based on the latest available records of the affected employee; verification that the place of residence is covered in the declaration of calamity area by the proper government agency; and such other proofs as may be necessary.
            </li>
        </ul>
    </li>

    <li>
        <b>13. Monetization of leave credits</b>
        <p>
            Application for monetization of fifty percent (50%) or more of the accumulated leave credits shall be accompanied by a letter request to the head of the agency stating the valid and justifiable reasons.
        </p>
    </li>

    <li>
        <b>14. Terminal leave*</b>
        <p>
            Proof of employee’s resignation or retirement or separation from the service.
        </p>
    </li>

    <li>
        <b>15. Adoption Leave</b>
        <ul>
            <li>
                Application for adoption leave shall be filed with an authenticated copy of the Pre-Adoptive Placement Authority issued by the Department of Social Welfare and Development (DSWD).
            </li>
        </ul>
    </li>

</ul>

            <!-- Additional content for the right column if needed -->
        </div>
    </div>




                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>








        
      </div>
    </div>


    
    </section>
</div>
   
  </div>
  <?php require "layouts/footer.php"; ?>
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
<script src="AdminLTE_new/plugins/moment/moment.min.js"></script>
<script src="AdminLTE_new/plugins/fullcalendar/main.js"></script>



<script>

$('#employee_selection').select2({
    minimumInputLength: 3,
    placeholder: "Search by Biometric ID, First Name, Last Name",
    ajax: {
        url: 'ajax_aoemployees',
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


// Updated calculateDays function
function calculateDays(holidays) {
  // console.log(holidays);
  var fromDate = $('#from_date').val();
  var toDate = $('#to_date').val();
  var fromTime = $('#from_time').val();
  var toTime = $('#to_time').val();
  var leaveType = $('#leaveType').val(); // Replace with your actual way of getting the leave type





  // alert(leaveType);
  var skippedDates = [];
  // Check if both "From Date" and "To Date" are selected
  if (fromDate && toDate) {
    var currentDate = moment(fromDate);
    var endDate = moment(toDate);
    var numberOfDays = 0;

    while (currentDate <= endDate) {
      // Check if it's a working day for SICK leave and not a holiday
      
      if(isHoliday(currentDate, holidays) != false){
        skippedDates.push(isHoliday(currentDate, holidays));
      }

    var allowedLeaveTypes = ['SICK LEAVE', 'VACATION LEAVE', 'SPECIAL PRIVELEGE LEAVE', 'PATERNITY LEAVE'];

    if ($.inArray(leaveType, allowedLeaveTypes) !== -1) {
      if(currentDate.isoWeekday() <= 5 && !isHoliday(currentDate, holidays)){
        numberOfDays++;
      }
    }
    else{
      numberOfDays++;
    }


      currentDate = currentDate.add(1, 'days');
    }
    // console.log(numberOfDays);

    if(fromDate == toDate){
          if(fromTime == toTime){
            numberOfDays = numberOfDays - 0.5;
          }
        }
        else{
          if(fromTime == toTime){
            numberOfDays = numberOfDays - 0.5;
          }
          if(fromTime == "AFTERNOON" && toTime == "MORNING"){
              numberOfDays = numberOfDays - 1;
          }
        }

    // console.log(skippedDates);

    var allowedLeaveTypes = ['SICK LEAVE', 'VACATION LEAVE', 'SPECIAL PRIVELEGE LEAVE', 'PATERNITY LEAVE'];

if ($.inArray(leaveType, allowedLeaveTypes) !== -1) {
    if (skippedDates.length !== 0) {
        // Assuming skippedDates is an array of holiday names
        var formattedList = "<p>Skipped Dates</p><ul>";
        $.each(skippedDates, function(index, holiday) {
            formattedList += "<li>" + holiday + "</li>";
        });
        formattedList += "</ul>";

        // Now you can do something with the formatted list, for example, append it to a div
        $('#holidayDiv').html(formattedList);
        $('#holidayDiv').css({
            'padding': '10px',
            'margin-bottom': '10px',
        });
    } else {
        $('#holidayDiv').html("");
        $('#holidayDiv').css({
            'padding': '0px',
        });
    }
} else {
    // If leaveType is not one of the allowed types, hide or clear #holidayDiv
    $('#holidayDiv').html("");
    $('#holidayDiv').css({
        'padding': '0px',
    });
}

    




    $('#number_of_days').val(numberOfDays);
  }

  updateLeaveBalance();
}



function updateLeaveBalance() {
  var sickLeaveCredits = <?php echo($sick_leave); ?>;
  var vacationLeaveCredits = <?php echo($vacation_leave); ?>;
  var specialLeaveCredits = <?php echo($special_leave); ?>;
  var numberOfDays = parseFloat($('#number_of_days').val());
  var withoutPayDays = 0;

  if ($('#leaveType').val() === 'SICK LEAVE') {
    if (numberOfDays <= sickLeaveCredits) {
      sickLeaveCredits -= numberOfDays;
    } else if (numberOfDays <= (sickLeaveCredits + vacationLeaveCredits)) {
      vacationLeaveCredits -= (numberOfDays - sickLeaveCredits);
      sickLeaveCredits = 0;
    } else {
      var remainingDays = numberOfDays - (sickLeaveCredits + vacationLeaveCredits);
      sickLeaveCredits = 0;
      vacationLeaveCredits = 0;
      withoutPayDays = remainingDays;
    }
  } else if ($('#leaveType').val() === 'VACATION LEAVE') {
    if (numberOfDays <= vacationLeaveCredits) {
      vacationLeaveCredits -= numberOfDays;
    } else {
      var remainingDays = numberOfDays - vacationLeaveCredits;
      vacationLeaveCredits = 0;
      withoutPayDays = remainingDays;
    }
  }

  else if ($('#leaveType').val() === 'SPECIAL PRIVELEGE LEAVE') {
    if (numberOfDays <= specialLeaveCredits) {
      specialLeaveCredits -= numberOfDays;
    } else {
      var remainingDays = numberOfDays - specialLeaveCredits;
      specialLeaveCredits = 0;
      withoutPayDays = remainingDays;
    }
  }

  var withPay = numberOfDays - withoutPayDays;
  var withoutPay = withoutPayDays;

  if($('#leaveType').val() === 'VACATION LEAVE'){
    $('#leave_balance').val(vacationLeaveCredits + ' VL');
  }
  if($('#leaveType').val() === 'SPECIAL PRIVELEGE LEAVE'){
    $('#leave_balance').val(specialLeaveCredits + ' SPL');
  }
  else if ($('#leaveType').val() === 'SICK LEAVE'){
    $('#leave_balance').val(sickLeaveCredits + ' SL / ' + vacationLeaveCredits + ' VL');
  }

  
  $('#withorwithoutpay').val(withPay + ' / ' + withoutPay);
}







</script>
  <!-- <script src="AdminLTE_new/dist/js/adminlte.min.js"></script> -->
  <script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (https://fullcalendar.io/docs/event-object)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    // new Draggable(containerEl, {
    //   itemSelector: '.external-event',
    //   eventData: function(eventEl) {
    //     return {
    //       title: eventEl.innerText,
    //       backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
    //       borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
    //       textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
    //     };
    //   }
    // });
    $(document).ready(function() {
  var calendarEl = $('#calendar'); // Replace with the actual ID of your calendar element

  var calendar = new FullCalendar.Calendar(calendarEl[0], {
    headerToolbar: {
      right: 'prev,next today',
      left: 'title',
      // right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    themeSystem: 'bootstrap',
    dateClick: function(info) {
      $('#applyLeave #from_date').val(info.dateStr);
      $('#applyLeave #to_date').val(info.dateStr);
      $('#applyLeave').modal('show');
      fetchHolidaysAndCalculateDays();
    },
    // editable: true,
    // droppable: true,
    drop: function(info) {
      // Your drop logic here
    }
  });

  // Fetch events from your API using jQuery
  $.ajax({
    url: 'apply_leave', // Replace with your actual API endpoint
    method: 'POST',
    dataType: 'json',
    data: {
      action: 'fillDate',
    },
    success: function(data) {
      // Add the fetched events to the calendar
      calendar.addEventSource(data);
      getHolidaysFromServer();
    },
    error: function(error) {
      console.error('Error fetching events:', error);
    }
  });

  $.ajax({
    url: 'apply_leave', // Replace with your actual API endpoint
    method: 'POST',
    dataType: 'json',
    data: {
      action: 'fetchLeaveCalendar',
    },
    success: function(data) {
      calendar.addEventSource(data);

      calendar.setOption('eventClick', function (info) {
        // alert("awit");
        
        var event = info.event;
        var row = event.extendedProps;
        console.log(row);
                Swal.fire({
                title: "Choose Action",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "DOWNLOAD",
                denyButtonText: `DELETE`
              }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
                  var postData = {
                      action: 'print_leave',
                      leave_id: row.leaveId,
                      // ... add more fields as needed
                  };
                    console.log(postData);
                    $.ajax({
                    type: 'post',
                    url: 'leave_summary',
                    data: postData,
                    success: function (results) {
                    var o = jQuery.parseJSON(results);
                    if(o.status === "success"){
                      window.open(o.link, "_blank");
                      Swal.close();
                }
                },
            });
            
                } else if (result.isDenied) {


                  Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
                  var postData = {
                      action: 'deleteLeave',
                      leave_id: row.leaveId,
                      // ... add more fields as needed
                  };
                    console.log(postData);
                    $.ajax({
                    type: 'post',
                    url: 'leave_summary',
                    data: postData,
                    success: function (results) {
                    var o = jQuery.parseJSON(results);
                    if(o.status === "success"){
                      location.reload();
                      Swal.close();
                }
                },
            });





                }
              });
           
        });



      getHolidaysFromServer();
    },
    error: function(error) {
      console.error('Error fetching events:', error);
    }
  });

  calendar.render();
});
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    // Color chooser button
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      // Save color
      currColor = $(this).css('color')
      // Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      // Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      // Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.text(val)
      $('#external-events').prepend(event)

      // Add draggable funtionality
      ini_events(event)

      // Remove event from text input
      $('#new-event').val('')
    })
  })

  function getHolidaysFromServer() {
  $.ajax({
    url: 'holidays', // Replace with your actual API endpoint
    method: 'POST',
    dataType: 'json',
    data: {
      action: 'get_holidays',
    },
    success: function(holidays) {
      // Call the calculateDays function with the fetched holidays
      calculateDays(holidays);
    },
    error: function(error) {
      console.error('Error fetching holidays:', error);
    }
  });
}

$('#from_date, #to_date, #from_time, #to_time,#leaveType').on('change', function() {
  fromTime = $('#from_time').val();
  from_date = $('#from_date').val();
  to_date = $('#to_date').val();


  if(from_date == to_date){
    if (fromTime === 'AFTERNOON') {
      $('#to_time option[value="MORNING"]').prop('disabled', true);
    } else {
      $('#to_time option[value="MORNING"]').prop('disabled', false);
    }
  }

  else{
    $('#to_time option[value="MORNING"]').prop('disabled', false);
  }
  fetchHolidaysAndCalculateDays();
});

function fetchHolidaysAndCalculateDays() {
  // Fetch holidays from the server
  getHolidaysFromServer()
    .then(function(holidays) {
      // Calculate days using fetched holidays
      calculateDays(holidays);
    })
    .catch(function(error) {
      console.error('Error fetching holidays:', error);
    });
}

function isHoliday(date, holidays) {
    // Check if holidays is defined before calling includes
    const matchingHoliday = holidays.find(holiday => holiday.date === date.format('YYYY-MM-DD'));

    if (matchingHoliday) {
        // alert(matchingHoliday.name);
        return matchingHoliday.name;
    }
    else{
      // $('#holidayDiv').empty();
      return false;
    }
}

</script>
  <?php require "layouts/footer_end.php";
?>
