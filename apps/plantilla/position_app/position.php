
<?php   
// $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// $path = parse_url($currentUrl, PHP_URL_PATH);
// $lastWord = basename($path);

// dump($lastWord); 
// dump($_REQUEST);
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if($_POST["action"] == "position_datatable"):
            // dump($_POST);

            // print_r($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = 10;
            $search = $_POST["search"]["value"];
            $data = query("select * from tblposition");
            $all_data = $data;


            $position = query("
            SELECT Positionid, COUNT(*) AS count FROM tblemployee
            GROUP BY Positionid");

            $Position = [];
            foreach($position as $row):
                $Position[$row["Positionid"]] = $row;
            endforeach;


            if($search == ""){
                $query_string = "select * from tblposition
                                order by PositionName ASC
                                    limit ".$limit." offset ".$offset." ";
                $data = query($query_string);
            }
            else{
                $query_string = "
                    select * from tblposition
                    where 
                    PositionName like '%".$search."%' or
                    PositionCode like '%".$search."%' or
                    Functional_Title like '%".$search."%'
                    order by PositionName ASC
                    limit ".$limit." offset ".$offset."
                ";
                $data = query($query_string);
                $query_string = "
                    select * from tblposition
                    where 
                    PositionName like '%".$search."%' or
                    PositionCode like '%".$search."%' or
                    Functional_Title like '%".$search."%'
                    order by PositionName ASC
                ";
                $all_data = query($query_string);
            }
            $i=0;
            foreach($data as $row):
           
                $data[$i]["update"] = '
                <a data-id="'.$row["Positionid"].'" data-toggle="modal" data-target="#modalUpdate" class="btn btn-sm btn-block btn-warning"><i class="fas fa-edit"></i></a>
                ';
                $data[$i]["delete"] = '
                <form class="generic_form_trigger" data-url="position">
                    <input type="hidden" name="action" value="deletePosition">
                    <input type="hidden" name="Positionid" value="'.$row["Positionid"].'">
                    <button class="btn btn-sm btn-block btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                ';
                $data[$i]["count"] = 0;
                if(isset($Position[$row["Positionid"]])):
                    $data[$i]["count"] = $Position[$row["Positionid"]]["count"];
                endif;
                
                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            echo json_encode($json_data);

    elseif($_POST["action"] == "modalUpdate"):
        // dump($_POST);

        $position = query("select * from tblposition where Positionid = ?", $_POST["position_id"]);
        $position = $position[0];

        $message = "";

        $message = $message . '
                 <input type="hidden" name="position_id" value="'.$_POST["position_id"].'">
                 <div class="form-group">
                    <label for="exampleInputEmail1">Position Name</label>
                    <input required name="PositionName" type="text" value="'.$position["PositionName"].'" class="form-control" placeholder="Enter email">
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Functional Title</label>
                            <input type="text" name="Functional_Title" value="'.$position["Functional_Title"].'" class="form-control"  placeholder="---">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Position Code</label>
                            <input required type="text" name="PositionCode" value="'.$position["PositionCode"].'" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select requried name="category" class="form-control">
                                <option selected value="'.$position["Category"].'">'.$position["Category"].'</option>
                                <option value="Administrative">Administrative</option>
                                <option value="Key Position">Key Position</option>
                                <option value="Technical">Technical</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Salary Grade</label>
                            <input required type="number" name="SGRate" value="'.$position["SGRate"].'" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Position Level</label>
                            <input type="number" value="'.$position["PosLevel"].'" class="form-control" id="exampleInputEmail1" placeholder="---">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Education</label>
                            <textarea name="EducationRequirement" class="form-control" rows="3" placeholder="Enter ...">'.$position["EducationRequirement"].'</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Experience</label>
                            <textarea name="ExperienceRequirement" class="form-control" rows="3" placeholder="Enter ...">'.$position["ExperienceRequirement"].'</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Training</label>
                            <textarea name="TrainingRequirement" class="form-control" rows="3" placeholder="Enter ...">'.$position["TrainingRequirement"].'</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Eligibility</label>
                            <textarea name="Eligibility" class="form-control" rows="3" placeholder="Enter ...">'.$position["Eligibility"].'</textarea>
                        </div>
                    </div>
                  </div>
        ';

        echo($message);

    elseif($_POST["action"] == "update"):
        // dump($_POST);
        query("update tblposition set PositionName = ?, Functional_Title = ?, PositionCode = ?, Category = ?,
                        SGRate = ?, EducationRequirement = ?, ExperienceRequirement = ?, TrainingRequirement = ?,
                        Eligibility = ? where Positionid = ?",
                        $_POST["PositionName"], $_POST["Functional_Title"], $_POST["PositionCode"], $_POST["category"],
                        $_POST["SGRate"], $_POST["EducationRequirement"], $_POST["ExperienceRequirement"], $_POST["TrainingRequirement"],
                        $_POST["Eligibility"], $_POST["position_id"]
                    );
        $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Updating",
            "option" => "theFunctions",
            "theFunctions" => "
            $('#modalUpdate').modal('hide');
            function filter() {
                var currentPage = datatable.page();
                var currentLength = datatable.page.len();
                datatable.ajax.url('position?action=position_datatable').load();
                datatable.page(currentPage).page.len(currentLength).draw('page');
            }
            filter();
            ",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();
            

        
    elseif($_POST["action"] == "deletePosition"):
     
        // $result = query("delete from tblposition where Positionid = ?", $_POST["Positionid"]);


        if (query("delete from tblposition where Positionid = ?", $_POST["Positionid"]) === false)
        {
            $res_arr = [
                "status" => "failed",
                "title" => "Failed",
                "message" => "There are employees on this position! Remove the position on that employee then you can delete this position!",
                // "theFunctions" => "filter('someJob')",
                // "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
                ];
                echo json_encode($res_arr); exit();
        }
        $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Deleting Position",
            "option" => "theFunctions",
            "theFunctions" => "
            function filter() {
        
                var currentPage = datatable.page();
                var currentLength = datatable.page.len();
                datatable.ajax.url('position?action=position_datatable').load();
                datatable.page(currentPage).page.len(currentLength).draw('page');
            }
            filter();
            
            ",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();

    
      


        endif;
    }
    else
    {
        if(isset($_GET["action"])):
            
        else:
            $position = query("select * from tblposition order by PositionName");
            render("apps/plantilla/position_app/position_list.php", 
                ["title" => "POSITION",
                 "position" => $position,
                ],"plantilla");
        endif;
       


        
    }
?>