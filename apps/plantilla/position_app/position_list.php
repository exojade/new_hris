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
        Position
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
      .table > tbody > tr > td {
     vertical-align: middle;
}
textarea {
            resize: none;
        }
    </style>


    <div class="modal fade" id="modalUpdate">
      <div class="modal-dialog modal-xl">
        <div class="modal-content ">
          <div class="modal-header bg-primary">
            <h3 class="modal-title text-center">Update Position</h3>
          </div>
          <div class="modal-body">
                <form class="generic_form_trigger" data-url="position" autocomplete="off">
                  <input type="hidden" name="action" value="update">
                  <div class="fetch-data"></div> 
                <div class="box-footer">
                  <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                  <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modalUpdate">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="generic_form_trigger" data-url="position" autocomplete="off">
                  <input type="hidden" name="action" value="update">
                  <div class="fetch-data"></div> 
                <div class="box-footer">
                  <button class=" btn btn-primary btn-flat pull-right" data-dismiss="modal" aria-label="Close">Close</button>
                  <button type="submit" class="btn btn-primary btn-flat pull-right">Submit</button>
                </div>
            </form>
          </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


    <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
              <div class="table-responsive">
                    <table class="table table-bordered" id="position_datatable" style="width:100%">
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th>Position</th>
                              <th>Code</th>
                              <th>Functional Title</th>
                              <th>Count</th>
                              <th>SG</th>
                              <th>Education</th>
                              <th>Duty</th>
                              <th>Eligibility</th>
                              <th>Experience</th>
                              <th>Training</th>
                              <th>Category</th>
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
<script src="AdminLTE_new/dist/js/adminlte.min.js"></script>

<script>



    var datatable = 
            $('#position_datatable').DataTable({
                // "searching": false,
                "pageLength": 10,
                language: {
                    searchPlaceholder: "Enter Filter"
                },
                "bLengthChange": false,
                "ordering": false,
                // "info":     false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                
                'ajax': {
                    'url':'position',
                     'type': "POST",
                     "data": function (data){
                        data.action = "position_datatable";
                     }
                },
                'columns': [
                    { data: 'delete', "orderable": false },
                    { data: 'update', "orderable": false },
                    { data: 'PositionName', "orderable": false },
                    { data: 'PositionCode', "orderable": false },
                    { data: 'Functional_Title', "orderable": false  },
                    { data: 'count', "orderable": false  },
                    { data: 'SGRate', "orderable": false  },
                    { data: 'EducationRequirement', "orderable": false  },
                    { data: 'Duty', "orderable": false  },
                    { data: 'Eligibility', "orderable": false  },
                    { data: 'ExperienceRequirement', "orderable": false  },
                    { data: 'TrainingRequirement', "orderable": false  },
                    { data: 'Category', "orderable": false  },
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
});


  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>