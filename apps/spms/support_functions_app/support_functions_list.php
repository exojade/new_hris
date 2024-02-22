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
        <div class="col">
            <h1>Support Functions</h1>
        </div>
        <div class="col-auto">
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addSupport">Add Support Function</a>
        </div>
    </div>
</section>
    <section class="content">

    <div class="modal fade" id="updateSupportModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header bg-info">
              <h4 class="modal-title">Update Support Function</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form class="generic_form_trigger" autocomplete="off" data-url="support_functions">
                <input type="hidden" name="action" value="updateSupport">
                <div class="fetch"></div>
                <br>
            <button class="btn btn-info btn-block" type="submit">Save</button>
            </form>
            </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addSupport">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
          <div class="modal-header bg-info">
              <h4 class="modal-title">Add Support Function</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form class="generic_form_trigger" data-id="support_functions">
              <input type="hidden" name="action" value="addSupportFunction">
                <div class="form-group">
                  <label>MFO</label>
                  <input required type="text" name="mfo" class="form-control" placeholder="Enter ...">
                </div>

                <div class="form-group">
                  <label>Success Indicator</label>
                  <input required type="text" name="success_indicator" class="form-control" placeholder="Enter ...">
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <h4 class="text-center">Quantity</h4>
                      <?php for($i=5;$i>0;$i--): ?>
                      <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                      <span class="input-group-text"> <?php echo($i); ?></span>
                      </div>
                     
                        <input id="quantity_<?php echo($i); ?>" name="quantity_<?php echo($i); ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php endfor; ?>
                    </div>
                    <div class="col-md-4">
                      <h4 class="text-center">Quality</h4>
                      <?php for($i=5;$i>0;$i--): ?>
                      <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                      <span class="input-group-text"> <?php echo($i); ?></span>
                      </div>
                        <input id="quality_<?php echo($i); ?>" name="quality_<?php echo($i); ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php endfor; ?>
                    </div>
                    <div class="col-md-4">
                      <h4 class="text-center">Timeliness</h4>
                      <?php for($i=5;$i>0;$i--): ?>
                      <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                      <span class="input-group-text"> <?php echo($i); ?></span>
                      </div>
                        <input id="timeliness_<?php echo($i); ?>" name="timeliness_<?php echo($i); ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php endfor; ?>
                    </div>
                </div>
                <br>
                <br>
                <button class="btn btn-info btn-flat" type="submit">Submit</button>
            </form>
            </div>
          </div>
        </div>
      </div>


    <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <th>Action</th>
                        <th width="15%">MFO</th>
                        <th width="20%">Success Indicators</th>
                        <th>Quality</th>
                        <th>Efficiency</th>
                        <th>Timeliness</th>
                    </thead>
                    <tbody>
                    <?php foreach($supportFunctions as $row): 
                        $quantity = unserialize($row["quantity"]);
                        $quality = unserialize($row["quality"]);
                        $timeliness = unserialize($row["timeliness"]);
                        
                        ?>
                        <tr>
                            <td><a href="#" data-toggle="modal" data-target="#updateSupportModal" data-id="<?php echo($row["suppFunc_id"]); ?>" class="btn btn-success btn-sm btn-block">Update</a>
                            <form style="margin-top:8px;" class="generic_form_trigger" data-url="support_functions">
                                <input type="hidden" name="action" value="deleteSupportFunction">
                                <input type="hidden" name="id" value="<?php echo($row["suppFunc_id"]); ?>">

                                <button class="btn btn-sm btn-danger btn-block">Delete</button>
                            </form>
                            <td><?php echo($row["mfo"]); ?></td>
                            <td><?php echo($row["success_indicator"]); ?></td>
                            <td>
                                <?php
                                    for($i=5;$i>0;$i--):
                                        echo("<p style='margin-bottom:0px;'><b>" .$i . " - </b>" . $quantity[$i] . "</p>");
                                    endfor;
                                ?>
                            </td>
                            <td>
                                <?php
                                    for($i=5;$i>0;$i--):
                                        echo("<p style='margin-bottom:0px;'><b>" .$i . " - </b>" . $quality[$i] . "</p>");
                                    endfor;
                                ?>
                            </td>
                            <td>
                                <?php
                                    for($i=5;$i>0;$i--):
                                        echo("<p style='margin-bottom:0px;'><b>" .$i . " - </b>" . $timeliness[$i] . "</p>");
                                    endfor;
                                ?>
                            </td>
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
    $('#updateSupportModal').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).data('id');
        Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
        $.ajax({
            type : 'post',
            url : 'support_functions', //Here you will fetch records 
            data: {
                id: id ,
                action: "updateSupportModal"
            },
            success : function(results){
                var o = jQuery.parseJSON(results);
                $('#updateSupportModal .fetch').html(o.data);
                swal.close();
                // $(".select2").select2();//Show fetched data from database
            }
        });
     });

</script>

  <?php
	require("layouts/footer_end.php");
  ?>