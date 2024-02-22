
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "updateSupportModal"):
            // dump($_POST);
            $support_function = query("select * from spms_support_functions where suppFunc_id = ?", $_POST["id"]);
                $supp = $support_function[0];
                $quantity = unserialize($support_function[0]["quantity"]);
                $quality = unserialize($support_function[0]["quality"]);
                $timeliness = unserialize($support_function[0]["timeliness"]);
                $hint = "";
                $hint = $hint . '
                <input type="hidden" name="function_id" value="'.$supp["suppFunc_id"].'">
                <div class="form-group">
                  <label>MFO</label>
                  <input required type="text" name="mfo" class="form-control" value="'.$supp["mfo"].'">
                </div>

                <div class="form-group">
                  <label>Success Indicator</label>
                  <input required type="text" name="success_indicator" class="form-control" value="'.$supp["success_indicator"].'">
                </div>
                ';

                $hint = $hint . '
                <div class="row">
                ';

                $hint = $hint . '<div class="col-md-4"> <h4 class="text-center">Quantity</h4>';
                for($i=5;$i>0;$i--):
                    $hint = $hint . '
               

                    <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">'.$i .''.'</span>
                        </div>
                        <input id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$quantity[$i].'" type="text" class="form-control">
                        </div>
                      </div>
                    ';
                endfor;
                $hint = $hint . '</div>';

                $hint = $hint . '<div class="col-md-4"> <h4 class="text-center">Quality</h4>';
                for($i=5;$i>0;$i--):

                    $hint = $hint . '
                    <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                      <span class="input-group-text">'.$i .''.'</span>
                      </div>
                        <input id="quality_'.$i.'" name="quality_'.$i.'" value="'.$quality[$i].'" type="text" class="form-control">
                        </div>
                      </div>
                    ';
                    

                endfor;
                $hint = $hint . '</div>';
                $hint = $hint . '<div class="col-md-4"> <h4 class="text-center">Timeliness</h4>';
                for($i=5;$i>0;$i--):

                    $hint = $hint . '
                    <div class="form-group" style="margin-bottom:0px;">
                      <div class="input-group">
                      <div class="input-group-prepend">
                      <span class="input-group-text">'.$i .''.'</span>
                      </div>
                        <input id="timeliness_'.$i.'" name="timeliness_'.$i.'" value="'.$timeliness[$i].'" type="text" class="form-control">
                        </div>
                      </div>
                    ';
                endfor;
                $hint = $hint . '</div>';
                $hint = $hint . '
                </div>
                ';


                $res_arr = [
                    "data" => $hint,
                    ];
                    echo json_encode($res_arr); exit();
                

                // echo($hint);
        elseif($_POST["action"] == "updateSupport"):
            // dump($_POST);
            $function_id = $_POST["function_id"];
            $array_quantity = [];
            $array_quality = [];
            $array_time = [];

            for($i=0;$i<=5;$i++):
                if($i==0){
                    array_push($array_quantity, "");
                    array_push($array_quality, "");
                    array_push($array_time, "");
                }
                else{
                    array_push($array_quantity, $_POST["quantity_".$i]);
                    array_push($array_quality, $_POST["quality_".$i]);
                    array_push($array_time, $_POST["timeliness_".$i]);
                }
            endfor;
            $quantity = (serialize($array_quantity));
            $quality = (serialize($array_quality));
            $timeliness = (serialize($array_time));
            // dump($_POST);
            // dump($timeliness);
            query("delete from spms_support_functions where suppFunc_id = ?", $_POST["function_id"]);
            $query_string = "
                    insert INTO spms_support_functions (suppFunc_id, mfo, success_indicator, quantity, quality, timeliness, status, type)
                        VALUES('".$function_id."','".$_POST["mfo"]."','".$_POST["success_indicator"]."','".$quantity."','".$quality."','".$timeliness."','active','".$_POST["type"]."')
                    ";
            query($query_string);

            $res_arr = [
                "status" => "success",
                "title" => "Success",
                "message" => "Success",
                "link" => "refresh",
                ];
            echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "addSupportFunction"):
            // dump($_POST);

            $function_id = create_trackid("SuppFunc-");
            $array_quantity = [];
            $array_quality = [];
            $array_time = [];

            for($i=0;$i<=5;$i++):
                if($i==0){
                    array_push($array_quantity, "");
                    array_push($array_quality, "");
                    array_push($array_time, "");
                }
                else{
                    array_push($array_quantity, $_POST["quantity_".$i]);
                    array_push($array_quality, $_POST["quality_".$i]);
                    array_push($array_time, $_POST["timeliness_".$i]);
                }
            endfor;

           



            $quantity = (serialize($array_quantity));
            $quality = (serialize($array_quality));
            $timeliness = (serialize($array_time));
            // dump($_POST);
            // dump($timeliness);

            $query_string = "
                    insert INTO spms_support_functions (suppFunc_id, mfo, success_indicator, quantity, quality, timeliness, status, type)
                        VALUES('".$function_id."','".$_POST["mfo"]."','".$_POST["success_indicator"]."','".$quantity."','".$quality."','".$timeliness."','active','".$_POST["type"]."')
                    ";

            query($query_string);
            $res_arr = [
                "status" => "success",
                "title" => "Success",
                "message" => "Success",
                "link" => "refresh",
                // "html" => '<a href="#">View or Print '.$transaction_id.'</a>'
                ];
            echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "deleteSupportFunction"):
            query("delete from spms_support_functions where suppFunc_id = ?", $_POST["id"]);
            $res_arr = [
                "status" => "success",
                "title" => "Success",
                "message" => "Success",
                "link" => "refresh",
                ];
            echo json_encode($res_arr); exit();



        endif;  
    

    }
    else
    {
        if(isset($_GET["action"])):
            
        else:

            $supportFunctions = query("select * from spms_support_functions");




            render("apps/spms/support_functions_app/support_functions_list.php", 
                ["title" => "SPMS - SUPPORT FUNCTIONS",
                 "supportFunctions" => $supportFunctions,
                ],"spms");
        endif;
       


        
    }
?>