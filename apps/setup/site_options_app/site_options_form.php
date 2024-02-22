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
        Site Options
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



    <div class="row">
      <div class="col-md-6">
        <div class="card">
        <!-- <div class="card-header"> </div> -->
          <div class="card-body">
            <form class="generic_form_trigger" data-url="siteoptions" autocomplete="off">
              <input type="hidden" name="action" value="updateSiteOptions">
              <div class="form-group">
                  <label>Site Url</label>
                  <input required type="text" placeholder="---" value="<?php echo($site_options["site_url"]); ?>" name="site_url"  class="form-control">
                </div>
                <div class="form-group">
                  <label>Google Client ID</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["google_clientID"]); ?>" name="google_clientID"  class="form-control">
                </div>
                <div class="form-group">
                  <label>Google Client Secret</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["google_clientSecret"]); ?>" name="google_clientSecret"  class="form-control">
                </div>
                <div class="form-group">
                  <label>Google Redirect URI</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["google_redirectUri"]); ?>" name="google_redirectUri"  class="form-control">
                </div>
                <div class="form-group">
                  <label>Google Folder ID</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["google_folder_id"]); ?>" name="google_folder_id"  class="form-control">
                </div>

                <div class="form-group">
                  <label>Google Folder Link</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["google_full_id"]); ?>" name="google_full_id"  class="form-control">
                </div>

                <div class="row">
                  <div class="col-md-6">
                  <div class="form-group">
                  <label>From Email</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["email_from"]); ?>" name="email_from"  class="form-control">
                </div>

                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                  <label>Email Secret Password</label>
                  <input  type="text" placeholder="---" value="<?php echo($site_options["email_password"]); ?>" name="email_password"  class="form-control">
                </div>

                  </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>


              

            </form>
          
          </div>
        </div>
      </div>
      <div class="col-md-6">
        
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



    var datatable = 
            $('#datatable').DataTable({
                // "searching": false,
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
                    'url':'holidays',
                     'type': "POST",
                     "data": function (data){
                        data.action = "datatable";
                     }
                },
                'columns': [
                    { data: 'update', "orderable": false },
                    { data: 'delete', "orderable": false },
                    { data: 'name', "orderable": false },
                    { data: 'holiday_date', "orderable": false },
                    { data: 'day', "orderable": false },
                    { data: 'year', "orderable": false },
                    { data: 'type', "orderable": false },
                
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

function filter() {
            var year = $('#holiday_year').val();
        
      
            datatable.ajax.url('holidays?action=datatable&year=' + year).load();
        }


  </script>


  <?php
	// render footer 2
	require("layouts/footer_end.php");
  ?>