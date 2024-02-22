
<?php   
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if($_POST["action"] == "datatable"):
            // dump($_POST);

            // print_r($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

            if ($limit == -1) {
                // Set a large number to fetch all records
                $limit = 999999;
            }


            

            

            $where = " where 1=1";
            


            if(isset($_REQUEST["empId"])):
                if($_REQUEST["empId"] != ""):
                    $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee where Employeeid = ?", $_REQUEST["empId"]);
                    $where = $where . " and (incumbent = '".$_REQUEST["empId"]."' or vice = '".$_REQUEST["empId"]."')";
                else:
                    $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
                endif;
                
            else:
                $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
            endif;

            if(isset($_REQUEST["depId"])):
                if($_REQUEST["depId"] != ""):
                    $department = query("select * from tbldepartment where Deptid = ?", $_REQUEST["depId"]);
                    $where = $where . " and department_id = '".$_REQUEST["depId"]."'"; 
                else:
                    $department = query("select * from tbldepartment");
                endif;
            else:
                $department = query("select * from tbldepartment");
            endif;

            if(isset($_REQUEST["posId"])):
                if($_REQUEST["posId"] != ""):
                $position = query("select * from tblposition where Positionid = ?", $_REQUEST["posId"]);
                $where = $where . " and position_id = '".$_REQUEST["posId"]."'"; 
                else:
                    $position = query("select * from tblposition");
                endif;
            else:
                $position = query("select * from tblposition");
            endif;


            $Department = [];
            // $department = query("select * from tbldepartment");
            foreach($department as $row):
                $Department[$row["Deptid"]] = $row;
            endforeach;

            $Position = [];
            // $position = query("select * from tblposition");
            foreach($position as $row):
                $Position[$row["Positionid"]] = $row;
            endforeach;

            $Appointment = [];
            $appointment = query("select * from appointment");
            foreach($appointment as $row):
                $Appointment[$row["appointment_id"]] = $row;
            endforeach;

            $Employee = [];
            foreach($employee as $row):
                $Employee[$row["Employeeid"]] = $row;
            endforeach;


            $salary = query("select * from tbl_salary_sched ss
                                left join tbl_salary_grade sg
                                on sg.salary_schedule_id = ss.salary_schedule_id
                                where ss.status = 'active'");


            // dump($where);

            $data = query("select * from plantilla 
                           ".$where."
                           order by plantilla_id asc
                           limit ".$limit." offset ".$offset." 
                                ");
            $all_data = query("select * from plantilla 
            ".$where."
                 ");
        
          
            $i=0;
            foreach($data as $row):
                // dump($row);
                $data[$i]["department"] = $Department[$row["department_id"]]["DeptCode"];
                $data[$i]["position"] = $Position[$row["position_id"]]["PositionName"];
                $data[$i]["SGRate"] = $Position[$row["position_id"]]["SGRate"];
                $data[$i]["salary_schedule"] = $row["salary_schedule"];


                // dump($Appointment[$row["incumbent"]]);
                if($row["incumbent"] != ""):
                    $incumbent = $Employee[$row["incumbent"]];
                    $data[$i]["incumbent"] = $incumbent["FirstName"] . " " . $incumbent["LastName"];
                else:
                    $data[$i]["incumbent"] = '
                    <div class="btn-group">
                        <a href="#" data-target="#addAppointmentModalOld" data-toggle="modal" data-id="'.$row["plantilla_id"].'" class="btn btn-sm btn-info">OLD</a>
                        <a href="#" data-target="#addAppointmentModal" data-toggle="modal" data-id="'.$row["plantilla_id"].'" class="btn btn-sm btn-success">NEW</a>
                    </div>';
                endif;

                if($row["vice"] != ""):
                    $vice = $Employee[$row["vice"]];
                    $data[$i]["vice"] = $vice["FirstName"] . " " . $vice["LastName"];
                else:
                    $data[$i]["vice"] = "";
                    $data[$i]["reason_vacancy"] = "";
                endif;
                // $data[$i]["functional_title"] = $Position[$row["position_id"]]["Functional_Title"];
                // $department = $Department[$row["department_id"]]["DeptCode"];
                // $position = $Position[$row["position"]]
         
              
                $i++;
            endforeach;
            // dump($data);

            usort($data, function($a, $b) {
                return strcmp($a['incumbent'], $b['incumbent']);
            });
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            echo json_encode($json_data);

    elseif($_POST["action"] == "addPlantilla"):
        // dump($_POST);

        $position = query("select * from tblposition where Positionid = ?", $_POST["position"]);
        $position = $position[0];

        query("insert INTO plantilla (plantilla_id,department_id,salary_grade, status, plantilla_status, salary_class, position_id, position_level, section, unit) 
                        VALUES(?,?,?,?,?,?,?,?,?,?)", 
                        $_POST["item_number"],$_POST["department"],$position["SGRate"], $_POST["status"], $_POST["jobType"], $_POST["salarySchedule"],
                        $_POST["position"], $_POST["position_level"], $_POST["section"], $_POST["unit"]
                    );


        $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success in Adding Plantilla",
            "link" => "refresh",
            ];
            echo json_encode($res_arr); exit();



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
            $department = query("select * from tbldepartment");
            $department_unit = query("select * from department_unit");
            $department_section = query("select * from department_section");


            $employee = query("select Employeeid, concat(FirstName, ' ', MiddleName, ' ', LastName, ' ', NameExtension) as fullname from tblemployee where active_status = 1");


            render("apps/plantilla/plantilla_app/plantilla_list.php", 
                ["title" => "PLANTILLA",
                 "position" => $position,
                 "department" => $department,
                 "department_unit" => $department_unit,
                 "department_section" => $department_section,
                 "employee" => $employee,
                ],"plantilla");
        endif;
       


        
    }
?>