<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "datatable"):
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = 10;
            $search = $_POST["search"]["value"];
            $sql = query("select * from tblemployee");
            $department = query("select * from tbldepartment");
            $Department = [];
            foreach($department as $d):
                $Department[$d["Deptid"]] = $d;
            endforeach;

            $Users=[];
            $users = query("select * from tblusers");
            foreach($users as $u):
                $Users[$u["Employeeid"]] = $u;
            endforeach; 



            $position = query("select * from tblposition");
            $Position = [];
            foreach($position as $p):
                $Position[$p["Positionid"]] = $p;
            endforeach;
            $all_employees = $sql;
            if($search == ""){
                $query_string = "select * from tblemployee
                                order by LastName ASC
                                    limit ".$limit." offset ".$offset." ";
                $employees = query($query_string);
            }
            else{
                $query_string = "
                    select * from tblemployee
                    where 
                    concat(LastName, ', ', FirstName) like '%".$search."%' or
                    concat(FirstName, ' ', LastName) like '%".$search."%' or
                    Fingerid like '%".$search."%' or 
                    HRID like '%".$search."%'
                    order by LastName ASC
                    limit ".$limit." offset ".$offset."
                ";
                $employees = query($query_string);
                $query_string = "
                        select * from tblemployee
                        where 
                        concat(LastName, ', ', FirstName) like '%".$search."%' or
                        concat(FirstName, ' ', LastName) like '%".$search."%' or
                        Fingerid like '%".$search."%' or 
                        HRID like '%".$search."%'
                        order by LastName ASC
                ";
                $all_employees = query($query_string);
            }
            $i=0;
            foreach($employees as $row):
                // $employees[$i]["action"] = '
                // <div class="btn-group">
                //   <button type="button" class="btn btn-default btn-flat" dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog" style="margin-right: 20px;"></i><i class="fa fa-caret-down"></i></button>
                //   <ul class="dropdown-menu" role="menu">
                //     <li><a href="employees?employee_id='.$row["Employeeid"].'&action=information">Information</a></li>
                //     <li><a href="viewemployee?id='.$row["Employeeid"].'">Schedule</a></li>
                //   </ul>
                // </div>';



                $employees[$i]["role"] = '
                <a data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#modal_role" class="btn btn-primary btn-block"><i class="fas fa-user"></i></a>
                ';
                $employees[$i]["password"] = '
                <a data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#modal_role" class="btn btn-danger btn-block"><i class="fas fa-key"></i></a>
                ';
                $employees[$i]["fingerprint"] = '
                <a data-id="'.$row["Employeeid"].'" data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#createEnrollment" class="btn btn-warning btn-block enroll_modal"><i class="fas fa-fingerprint"></i></a>
                ';

                $employees[$i]["verify"] = '
                <a data-id="'.$row["Employeeid"].'" data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#verifyFinger" class="btn btn-success btn-block enroll_modal"><i class="fas fa-fingerprint"></i></a>
                ';
            
            //   $bids[$i]["Title"] = "<a href='#' data-toggle='modal' data-id='".$row['ReferenceNumber']."' data-target='#modal-specific-bids'>".$row["Title"]."</a>";
            
                $employees[$i]["id"] = $row["Fingerid"];
                $employees[$i]["name"] = $row["LastName"] . ", " . $row["FirstName"];
                $employees[$i]["username"] = "";
                if(isset($Users[$row["Employeeid"]]))
                    $employees[$i]["username"] = $Users[$row["Employeeid"]]["username"];
                $employees[$i]["biometric_id"] = $row["Fingerid"];
                $employees[$i]["hr_id"] = $row["HRID"];
                if($row["Deptid"] != "")
                $employees[$i]["department"] = $Department[$row["Deptid"]]["DeptCode"];
                else
                $employees[$i]["department"] = "-";

                if($row["DeptAssignment"] != "")
                $employees[$i]["department_assigned"] = $Department[$row["DeptAssignment"]]["DeptCode"];
                else
                $employees[$i]["department_assigned"] = "-";

                if($row["Positionid"] != "")
                $employees[$i]["position"] = $Position[$row["Positionid"]]["PositionName"];
                else
                $employees[$i]["position"] = "-";


                if($row["active_status"] == 0)
                    $employees[$i]["active_status"] = '<p class="text-danger text-center" style="padding:5px;">NOT ACTIVE</p>';
                else
                    $employees[$i]["active_status"] = '<p class="text-success text-center" style="padding:5px;">ACTIVE</p>';


                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_employees),
                "iTotalDisplayRecords" => count($all_employees),
                "aaData" => $employees
            );
            echo json_encode($json_data);


        elseif($_POST["action"] == "modal_role"):

            $roles = query("select * from roles");
            $Roles = [];
            foreach($roles as $row):
                if ($row['parentID'] === NULL) {
                    // Parent role
                    $Roles[$row['roleID']] = [
                        'parentRole' => $row['role'],
                        'subRoles' => [],
                    ];
                } else {
                    // Subrole
                    $Roles[$row['parentID']]['subRoles'][] = $row['roleID'];
                }
            endforeach;

            $NameRole = [];
            foreach($roles as $row):
                $NameRole[$row["roleID"]] = $row;
            endforeach;


            // dump($Roles);
            $my_role = query("select * from tblusers where Employeeid = ?", $_POST["employee_id"]);
          
            if(empty($my_role)):
                $res_arr = [
                    "message" => "This employee doesnt have an account",
                    "title" => "Failed",
                    "status" => "failed",
                    "link" => "datatable",
                    ];
                    echo json_encode($res_arr); exit();
            endif;

            $my_role = unserialize($my_role[0]["roles"]);
            // dump($my_role);
            $MyRole = [];
            if(!empty($my_role)):
                foreach($my_role as $row):
                    $MyRole[$row] = $row;
                endforeach;
            endif;

            // dump($Roles);
            $hint = '<input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">';
            foreach($Roles as $role):
                $hint = $hint . "<h3>".$role["parentRole"]."</h3>";
                // if(!empty($role["subRoles"])):
                    $hint = $hint . '<div class="row">';
                foreach($role["subRoles"] as $row2):

                    if(isset($MyRole[$row2])):
                        $hint = $hint . '
                        <div class="col-md-6">
                            <div class="icheck-primary">
                                <input type="checkbox" name="'.$row2.'" checked data-bootstrap-switch>
                                <label for="checkboxPrimary3">
                                    '.$NameRole[$row2]["role"].'
                                </label>
                            </div>
                        </div>
                        ';
                    else:
                        $hint = $hint . '
                        <div class="col-md-6">
                            <div class="icheck-primary">
                                <input type="checkbox" name="'.$row2.'" data-bootstrap-switch>
                                <label for="checkboxPrimary3">
                                    '.$NameRole[$row2]["role"].'
                                </label>
                            </div>
                        </div>
                        ';
                    endif;

                    
                endforeach;
                $hint = $hint . '</div>';
            // endif;
            endforeach;
       

      
    

            $res_arr = [
                "status" => "success",
                "html" => $hint,
                ];
                echo json_encode($res_arr); exit();


            echo($hint);

            elseif($_POST["action"] == "updateRoles"):
                // dump($_POST);

                $Roles = [];
                $roles = query("select * from roles");
              
                $myRoles=[];
                foreach($roles as $row):
                    if(isset($_POST[$row["roleID"]])):
                        $myRoles[] = $row["roleID"];
                    endif;
                endforeach;

                $myRoles = serialize($myRoles);
                query("update tblusers set roles = ? where Employeeid = ?", $myRoles, $_POST["employee_id"]);
                $res_arr = [
                    "status" => "success",
                    "title" => "Success",
                    "message" => "Success on Updating Roles",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();




            elseif($_POST["action"] == "modal_update_payroll"):
                // dump($_POST);
            $employees = query("select payroll_method,JobType, salary_class, salary_grade, salary, salary_step, lbp_number, sss_personal, hdmf_personal, witholding_tax from tblemployee where Employeeid = ?", $_POST["employee_id"]);
            $e = $employees[0];
            if($e["JobType"] == "PERMANENT" || $e["JobType"] == "COTERMINOUS" || $e["JobType"] == "CASUAL" || $e["JobType"] == "ELECTIVE"){
            // dump($e);
            $salary_scheds = query("select * from tbl_salary_sched");
            $hint = '
            <input type="hidden" name="action" value="update_payroll_employee">
            <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
            <input type="hidden" name="options" value="not_jo">
            <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Salary Grade</label>
                      <input required value="'.$e["salary_grade"].'" name="salary_grade" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Salary Step</label>
                      <input required value="'.$e["salary_step"].'" name="salary_step" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-12">
                  <div class="form-group">
                  <label>Salary Class</label>
                  <input required value="'.$e["salary_class"].'" name="salary_class" type="text" class="form-control" placeholder="Enter ...">
                </div>
                  </div>
                  <div class="col-md-6">
                  <div class="form-group">
                  <label>Payroll Method</label>
                  <select class="form-control" name="payroll_method">
                    <option value="'.$e["payroll_method"].'">'.$e["payroll_method"].'</option>
                    <option value="ATM">ATM</option>
                    <option value="ATM">OVER-THE-COUNTER</option>
                  </select>
                </div>
                  </div>
                
                  <div class="col-md-6">
                  <div class="form-group">
                  <label>Land Bank Account Number (for PACS)</label>
                  <input required value="'.$e["lbp_number"].'" name="lbp_number" type="number" class="form-control" placeholder="Enter ...">
                </div>
                  </div>


                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Witholding Tax</label>
                            <input step=0.01 value="'.$e["witholding_tax"].'" name="wtax" type="number" class="form-control" placeholder="Enter ...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SSS Personal</label>
                            <input step=0.01 value="'.$e["sss_personal"].'" name="sss_personal" type="number" class="form-control" placeholder="Enter ...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF Personal </label>
                            <input step=0.01 value="'.$e["hdmf_personal"].'" name="hdmf_personal" type="number" class="form-control" placeholder="Enter ...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HDMF MP2</label>
                            <input step=0.01 value="'.$e["hdmf_mp2"].'" name="hdmf_mp2" type="number" class="form-control" placeholder="Enter ...">
                        </div>
                    </div>
                </div>
            <script>';

            if($payroll_settings != ""){
                if($gsis_government != 0)
                $hint = $hint . '$("#gsis_gov").prop("checked", true);';
                if($phic_government != 0)
                $hint = $hint . '$("#phic_gov").prop("checked", true);';
                if($phic_personal != 0)
                $hint = $hint . '$("#phic_personal").prop("checked", true);';
                if($hdmf_government != 0)
                $hint = $hint . '$("#hdmf_government").prop("checked", true);';
            }

            // $(".ios8-switch").prop("checked", true);

            $hint = $hint . '
            </script>
                
                ';
            }

            else if($e["JobType"] == "JOB ORDER" || $e["JobType"] == "HONORARIUM"){
                $hint = '
            <input type="hidden" name="action" value="update_payroll_employee">
            <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
            <input type="hidden" name="options" value="jo">
            <div class="box-body">
            
           
                <div class="row">
                <div class="col-md-12">
                <div class="form-group">
                  <label>Salary</label>
                <input required value="'.$e["salary"].'" name="salary" type="number" step="0.01" class="form-control" placeholder="---">
                </div>
                </div>
                 

                  <div class="col-md-12">
                  <div class="form-group">
                  <label>Land Bank Account Number (for PACS)</label>
                  <input value="'.$e["lbp_number"].'" name="lbp_number" type="number" class="form-control" placeholder="Enter ...">
                </div>
                  </div>
                </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>HDMF Personal </label>
                        <input step=0.01 value="'.$e["hdmf_personal"].'" name="hdmf_personal" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>SSS Personal</label>
                        <input step=0.01 value="'.$e["sss_personal"].'" name="sss_personal" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Witholding Tax</label>
                        <input step=0.01 value="'.$e["witholding_tax"].'" name="wtax" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                </div>
            </div>

            <script>';

            if($payroll_settings != ""){
                if($gsis_government != 0)
                $hint = $hint . '$("#gsis_gov").prop("checked", true);';
                if($phic_government != 0)
                $hint = $hint . '$("#phic_gov").prop("checked", true);';
                if($phic_personal != 0)
                $hint = $hint . '$("#phic_personal").prop("checked", true);';
                if($hdmf_government != 0)
                $hint = $hint . '$("#hdmf_government").prop("checked", true);';
            }

            // $(".ios8-switch").prop("checked", true);

            $hint = $hint . '
            </script>
                
                ';
            }
            echo($hint);

        endif;

 
      


    }
    else
    {

        // dump("yawa");

        if(isset($_GET["action"])):
         
        else:
            render("apps/setup/users_app/users_list.php", 
                ["title" => "Users",],"setup");

        endif;
       


        
    }
?>