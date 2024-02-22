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
        Service Awardees
      </h1>
  </section>
    <section class="content">
    <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-2">
              <input class="form-control" type="number" id="year" value="<?php echo(date("Y")); ?>">
            </div>
            <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select style="width:100%;" class="form-control" multiple id="department_select" name="department[]"></select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <button onclick="filter();" class="btn btn-primary">Filter</button>
              <button onclick="print();" class="btn btn-success">Print</button>
            </div>
          </div>

     
        </div>
            <div class="card-body">
              <table class="table table-bordered" id="serviceDatatable">
                <thead>
                  <th>Employee</th>
                  <th>Department</th>
                  <th>Years</th>
                  <th>Continuous Year</th>
                </thead>
              </table>
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
<script>
          var serviceDatatable = 
            $('#serviceDatatable').DataTable({
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
                    'url':'service_awardees',
                     'type': "POST",
                     "data": function (data){
                        data.action = "serviceDatatable";
                     }
                },
                'columns': [
                    { data: 'employee', "orderable": false },
                    { data: 'department', "orderable": false },
                    { data: 'length_service', "orderable": false },
                    { data: 'active_date', "orderable": false },
                ],
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



    function filter() {
    var depData = $('#department_select').select2('data');
    var year = $('#year').val();

    var depIds = [];


    depData.forEach(function(option) {
        depIds.push(option.id);
    });


    // Constructing URL based on selected options
    var url = 'service_awardees?action=serviceDatatable';
    if (depIds.length > 0) {
        url += '&depId=' + depIds.join(',');
    }
    if (year) {
        url += '&year=' + year;
    }

    // Load data with constructed URL
    console.log(url);
    serviceDatatable.ajax.url(url).load();
}


  </script>
  <?php
	require("layouts/footer_end.php");
  ?>