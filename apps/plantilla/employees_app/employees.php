<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "datatable"):
            // dump($_REQUEST);

            // print_r($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

            $search = filter_var($search, FILTER_SANITIZE_STRING);

          if(isset($_REQUEST['jobType'])) {
              $jobTypeArray = explode(',', $_REQUEST['jobType']);
              $jobTypeArray = "'" . implode("','", $jobTypeArray) . "'";
          }
          
          // Process depId
          if(isset($_REQUEST['depId'])) {
              $depIdArray = explode(',', $_REQUEST['depId']);
              $depIdArray = "'" . implode("','", $depIdArray) . "'";
          }



          // dump($_REQUEST);
          $where = " where 1=1";
          if(isset($_REQUEST["jobType"])):
              if($_REQUEST["jobType"] != ""):
                  $where = $where . " and JobType in (".$jobTypeArray.")";
              endif;
          endif;
          $department = query("select * from tbldepartment");
          if(isset($_REQUEST["depId"])):
              if($_REQUEST["depId"] != ""):
                  // $department = query("select * from tbldepartment where Deptid = ?", $_REQUEST["depId"]);
                  $where = $where . " and Deptid in (".$depIdArray.")";
              else:
                  $department = query("select * from tbldepartment");
              endif;
          else:
              $department = query("select * from tbldepartment");
          endif;
          // dump($where);

          $Department = [];
          foreach($department as $d):
              $Department[$d["Deptid"]] = $d;
          endforeach;

          if(isset($_REQUEST["activeStatus"])):
            if($_REQUEST["activeStatus"] != ""):
              $where = $where . " and active_status = ".$_REQUEST["activeStatus"]."";
            endif;
          endif;

          // dump($where);


          $Continuous = [];
          $continuous = query("SELECT 
          employee_id, 
          COUNT(*) AS row_count,
          MAX(CASE WHEN status = 'active' THEN date END) AS active_date
          FROM 
              employee_continuous_year
          GROUP BY 
              employee_id");

          foreach($continuous as $row):
            $Continuous[$row["employee_id"]] = $row;
          endforeach;


          $Promotion = [];
          $promotion = query("SELECT 
          employee_id, 
          COUNT(*) AS row_count,
          MAX(CASE WHEN status = 'active' THEN promotion_date END) AS active_date
          FROM 
              employee_promotion_year
          GROUP BY 
              employee_id");

          foreach($promotion as $row):
            $Promotion[$row["employee_id"]] = $row;
          endforeach;

          // dump($Promotion);



          $query_string = "select * from tblemployee" . $where;
          // dump($query_string);
            $sql = query($query_string);
            // dump($sql);
            // $department = query("select * from tbldepartment");
            
            $position = query("select * from tblposition");
            $Position = [];
            foreach($position as $p):
                $Position[$p["Positionid"]] = $p;
            endforeach;
            $all_employees = $sql;
            if($search == ""){
                $query_string = "select * from tblemployee
                                ".$where."
                                order by LastName ASC 
                                    limit ".$limit." offset ".$offset." ";
                // dump($query_string);
                $employees = query($query_string);
                // dump($employees);
            }
            else{

              $where = $where . "
              and (concat(LastName, ', ', FirstName) like '%".$search."%' or
              concat(FirstName, ' ', LastName) like '%".$search."%' or
              Fingerid like '%".$search."%' or 
              HRID like '%".$search."%')
              ";
              // dump($where);


                $query_string = "
                    select * from tblemployee
                    ".$where."
                    order by LastName ASC
                    limit ".$limit." offset ".$offset."
                ";
                // dump($query_string);
                $employees = query($query_string);
                $query_string = "
                        select * from tblemployee
                        ".$where."
                        order by LastName ASC
                ";
                $all_employees = query($query_string);
            }


            $salary = query("select * from tbl_salary_sched ss
                            left join tbl_salary_grade sg
                            on sg.salary_schedule_id = ss.salary_schedule_id
                            where ss.status = 'active'");
                            // dump($salary);


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


                // $employees[$i]["last_promotion"] = '<a href="google.com" class="btn btn-primary btn-sm btn-block">'.$row["last_promotion"].'</a>';



                // $employees[$i]["action"] = '
                // <a data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#modal_edit_employees" class="btn btn-sm btn-block btn-warning"><i class="fas fa-edit"></i></a>
                
                // ';
            
            //   $bids[$i]["Title"] = "<a href='#' data-toggle='modal' data-id='".$row['ReferenceNumber']."' data-target='#modal-specific-bids'>".$row["Title"]."</a>";
            
                $employees[$i]["name"] = $row["LastName"] . ", " . $row["FirstName"];
                $employees[$i]["biometric_id"] = $row["Fingerid"];
                $employees[$i]["lbp_number"] = $row["lbp_number"];
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

                $employees[$i]["continuous"] = "";
                if(isset($Continuous[$row["Employeeid"]])):
                  if($Continuous[$row["Employeeid"]]["row_count"] == 1):
                    $employees[$i]["continuous"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalContinuous' class='btn btn-primary btn-sm btn-block'>".$Continuous[$row["Employeeid"]]["active_date"]."</a>";
                  else:
                    $employees[$i]["continuous"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalContinuous' class='btn btn-warning btn-sm btn-block'>".$Continuous[$row["Employeeid"]]["active_date"]."</a>";
                  endif;
                else:
                  $employees[$i]["continuous"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalContinuous' class='btn btn-info btn-sm btn-block'>Add Continuous</a>";
                endif;


                $employees[$i]["promotion"] = "";
                if(isset($Promotion[$row["Employeeid"]])):
                  if($Promotion[$row["Employeeid"]]["row_count"] == 1):
                    $employees[$i]["promotion"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalPromotion' class='btn btn-primary btn-sm btn-block'>".$Promotion[$row["Employeeid"]]["active_date"]."</a>";
                  else:
                    $employees[$i]["promotion"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalPromotion' class='btn btn-warning btn-sm btn-block'>".$Promotion[$row["Employeeid"]]["active_date"]."</a>";
                  endif;

                else:
                  $employees[$i]["promotion"] = "<a href='#' data-id='".$row["Employeeid"]."' data-toggle='modal' data-target='#modalPromotion' class='btn btn-info btn-sm btn-block'>Add Promotion</a>";
                endif;  



                $employees[$i]["salary_grade"] = "-";
                $employees[$i]["salary_step"] = "-";
                $employees[$i]["salary_class"] = "-";

                if($row["JobType"] == "JOB ORDER" || $row["JobType"] == "HONORARIUM"):
                  $employees[$i]["salary"] = to_peso($row["salary"]);
                else:
                  $employees[$i]["salary"] = to_peso(salary($salary, "", $row["salary_grade"], $row["salary_step"], $row["salary_class"]));
                  $employees[$i]["salary_grade"] = $row["salary_grade"];
                  $employees[$i]["salary_step"] = $row["salary_step"];
                  $employees[$i]["salary_class"] = $row["salary_class"];
                endif;
                // dump($employee[$i]["salary"]);




                // if($row["active_status"] == 0)
                //     $employees[$i]["active_status"] = '<p class="text-danger text-center" >NOT ACTIVE</p>';
                // else
                //     $employees[$i]["active_status"] = '<p class="text-success text-center" >ACTIVE</p>';


                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_employees),
                "iTotalDisplayRecords" => count($all_employees),
                "aaData" => $employees
            );
            echo json_encode($json_data);

        elseif($_POST["action"] == "add_employee"):
          // dump($_POST);


          $sql = query("select * from tblemployee where Fingerid = ?", $_POST["biometric_number"]);
          if(!empty($sql)){
              $res_arr = [
                  "result" => "failed",
                  "title" => "Failed",
                  "message" => "Biometric Number already registered to " . $sql[0]["LastName"] . ", " . $sql[0]["FirstName"],
                  ];
                  echo json_encode($res_arr); exit();
          }
          $employee_id = create_trackid("EMP-");
          if (query("insert INTO tblemployee (Employeeid, active_status, Fingerid, FirstName, MiddleName, LastName, NameExtension, 
                      Deptid, DeptAssignment, GroupName, JobType, print_remarks
                      ) 
          VALUES(?,1,?,?,?,?,?,?,?,?,?,?)", 
          $employee_id, $_POST["biometric_number"] , $_POST["first_name"], $_POST["middle_name"], $_POST["last_name"], $_POST["suffix"],
                  $_POST["department"], $_POST["department"], $_POST["group"], $_POST["employment"], $_POST["print_remarks"]) === false)
          {
              echo("not_success");
          }

          $res_arr = [
              "status" => "success",
              "title" => "Success",
              "message" => "Success on Register Employee",
              "link" => "refresh",
              ];
              echo json_encode($res_arr); exit();


        elseif($_POST["action"] == "updateEmployee"):
          

          $salary = "";
          $sg = "";
          $step = "";
          $class = "";

          if($_POST["employment"] == "JOB ORDER" || $_POST["employment"] == 'HONORARIUM'):
            $salary = $_POST["salary"];
            query("update tblemployee set salary_option = ? where Employeeid = ?", $_POST["salary_option"], $_POST["employee_id"]);
          else:
            if($_POST["salary"] != ""):
              $salary = query("select * from tbl_salary_grade sg 
                                left join tbl_salary_sched ss on ss.salary_schedule_id = sg.salary_schedule_id
                                where sg.salary_grade_id = ?", $_POST["salary"]);
              // dump($salary);
              $sg = $salary[0]["salary_grade"];
              $step = $salary[0]["step"];
              $class = $salary[0]["schedule"];
            endif;
          endif;

          $query_string = "update tblemployee set
          active_status = '".$_POST["active_status"]."',
          Fingerid = '".$_POST["biometric_number"]."',
          Fingerid = '".$_POST["biometric_number"]."',
          BirthDate = '".$_POST["birthdate"]."',
          FirstName = '".$_POST["first_name"]."',
          MiddleName = '".$_POST["middle_name"]."',
          LastName = '".$_POST["last_name"]."',
          NameExtension = '".$_POST["suffix"]."',
          Gender = '".$_POST["gender"]."',
          JobType = '".$_POST["employment"]."',
          Positionid = '".$_POST["position"]."',
          lbp_number = '".$_POST["lbp_number"]."',
          Deptid = '".$_POST["department_fund"]."',
          DeptAssignment = '".$_POST["department"]."',
          GroupName = '".$_POST["group"]."',
          print_remarks = '".$_POST["print_remarks"]."',
          original_appointment = '".$_POST["original_appointment"]."',
          date_hired = '".$_POST["date_hired"]."',
          salary = '".$salary."',
          salary_class = '".$class."',
          salary_grade = '".$sg."',
          salary_step = '".$step."'
            where Employeeid = '".$_POST["employee_id"]."'
  ";
  // dump($query_string);
          query($query_string);


          $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Updating Employee",
            "option" => "theFunctions",
            "theFunctions" => "
            function filter2() {
              var jobtypeData = $('#job_type').select2('data');
              var depData = $('#department_select').select2('data');
              var activeStatusData = $('#active_status').select2('data');
      
              var jobType ='';
              var depId ='';
              var activeStatus ='';
              if (jobtypeData[0])
                  jobType = jobtypeData[0].id;
              if (depData[0])
                  depId = depData[0].id;
              if (activeStatusData[0])
                  activeStatus = activeStatusData[0].id;

              var datatable = $('#employees-datatable').DataTable();
              var rowToUpdate = datatable.row(".$_POST['rowIndex'].");
              var rowIndex = rowToUpdate.index();
              rowToUpdate.node().style.backgroundColor = '#FFFFCC';
          
              // else{
              var currentPage = datatable.page();
              var currentLength = datatable.page.len();
              datatable.ajax.url('employees?action=datatable&jobType=' + jobType + '&depId=' + depId + '&activeStatus=' + activeStatus).load();
              datatable.page(currentPage).page.len(currentLength).draw('page');
              closeAllModals();
              rowToUpdate.node().css('background-color', '#FFFFCC').delay(10000).fadeOut(5000, function() {
                // Callback function after fading out
                // You can perform additional actions here if needed
            });

                // datatable.ajax.url('employees?action=position_datatable').load();
            }
            
            filter2();
            ",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "deleteContinuous"):
          // dump($_POST);s
          query("delete from employee_continuous_year where id = ?", $_POST["id"]);
          $res_arr = [
            "status" => "success",
            ];
            echo json_encode($res_arr); exit();
          

          $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Updating Employee",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "deletePromotion"):
          query("delete from employee_promotion_year where id = ?", $_POST["id"]);
          $res_arr = [
            "status" => "success",
            ];
            echo json_encode($res_arr); exit();


        elseif($_POST["action"] == "updateContinuous"):
          // dump($_POST);


          if($_POST["id"] != ""):
            query("update employee_continuous_year set date = ?,
                    status = ?, note = ? where id = ?
                    ", $_POST["date"], $_POST["status"], $_POST["note"], $_POST["id"]);
            else:
              if (query("insert INTO employee_continuous_year (employee_id, date, status, note) 
                    VALUES(?,?,?,?)", 
                    $_POST["employee_id"], $_POST["date"] , $_POST["status"], $_POST["note"]) === false)
                    {
                        echo("not_success");
                    }
            endif;
            
          
  
  
            
  
            $res_arr = [
              "status" => "success",
              "title" => "Success",
              ];
              echo json_encode($res_arr); exit();
          



          

          $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Updating Employee",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();


          elseif($_POST["action"] == "updatePromotion"):
              // dump($_POST);

              if($_POST["id"] != ""):
              query("update employee_promotion_year set promotion_date = ?,
                      status = ?, note = ? where id = ?
                      ", $_POST["promotion_date"], $_POST["status"], $_POST["note"], $_POST["id"]);
              else:
                if (query("insert INTO employee_promotion_year (employee_id, promotion_date, status, note) 
                      VALUES(?,?,?,?)", 
                      $_POST["employee_id"], $_POST["promotion_date"] , $_POST["status"], $_POST["note"]) === false)
                      {
                          echo("not_success");
                      }
              endif;
              
            
    
    
              
    
              $res_arr = [
                "status" => "success",
                "title" => "Success",
                ];
                echo json_encode($res_arr); exit();


        elseif($_POST["action"] == "modalContinuous"):
          $continuous = query("select * from employee_continuous_year where
                                employee_id = ? order by date desc", $_POST["employee_id"]);
          // dump($continuous);
          $html = "";


          if (empty($continuous)) {
            // If $continuous is empty, provide an empty form
            $html .= '<form autocomplete="off" class="form_continuous" style="display: inline;">';
            $html .= '<input type="hidden" name="employee_id" value="' . $row["employee_id"] . '" >';
            $html .= '<div class="row">';
            $html .= '<div class="col-2">';
            $html .= '<div class="form-group"><input name="date" type="date" value=""  class="form-control" placeholder="Enter email"></div>';
            $html .= '</div>';
            $html .= '<div class="col-6">';
            $html .= '<div class="form-group"><input name="note" type="text" value="" class="form-control" placeholder="Enter Note Here"></div>';
            $html .= '</div>';
            $html .= '<div class="col-2">';
            $html .= '<select class="form-control" name="status">';
            $html .= '<option value="INACTIVE">INACTIVE</option>';
            $html .= '<option value="ACTIVE">ACTIVE</option>';
            $html .= '</select>';
            $html .= '</div>';
            $html .= '<div class="col-1">';
            $html .= '<button type="submit" name="actionButton" value="Save" class="btn btn-success btn-block">Save</button>';
            $html .= '</div>';
            $html .= ' <div class="col-1">';
            $html .= '<a href="#" onclick="resetFormContinuous(this)" class="btn btn-danger btn-block">Delete</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</form>';
        }

        else{

          foreach ($continuous as $row):
            $html .= '<form autocomplete="off" class="form_continuous" style="display: inline;">';
            $html .= '<input type="hidden" name="employee_id" value="' . $row["employee_id"] . '" >';
            $html .= '<input type="hidden" name="id" value="' . $row["id"] . '" >';
            $html .= '<input type="hidden" name="action" value="updateContinuous" >';

            $html .= '<div class="row">';
            $html .= ' <div class="col-2">';
            $html .= '<div class="form-group"><input name="date" type="date" value="' . $row["date"] . '"  class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div>';
            $html .= '</div>';
            $html .= '<div class="col-6">';
            $html .= '<div class="form-group"><input name="note" type="text" value="' . $row["note"] . '" class="form-control" id="exampleInputEmail1" placeholder="Enter Note Here"></div>';
            $html .= '</div>';
            $html .= '<div class="col-2">';
            $html .= '
                    <select class="form-control" name="status">
                      <option value="'.$row["status"].'">'.$row["status"].'</option>
                      <option value="INACTIVE">INACTIVE</option>
                      <option value="ACTIVE">ACTIVE</option>
                    </select>
            ';
            $html .= '</div>';
            $html .= ' <div class="col-1">';
            $html .= '<button type="submit" name="actionButton" value="Save" class="btn btn-success btn-block">Save</button>';
            $html .= '</div>';
            $html .= ' <div class="col-1">';
            $html .= '<a href="#" onclick="deleteContinuous(\''.$row["id"].'\', \''.$row["employee_id"].'\')" class="btn btn-danger btn-block">Delete</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</form>';

        endforeach;

        }
          
          

        $html .= '<button type="button" class="btn btn-info mt-2" onclick="duplicateRowContinuous(this)">Add Row</button>';

          echo($html);



          elseif($_POST["action"] == "modalPromotion"):
            $continuous = query("select * from employee_promotion_year where
                                  employee_id = ? order by promotion_date desc", $_POST["employee_id"]);
            // dump($continuous);
            $html = "";


            if (empty($continuous)) {
              // If $continuous is empty, provide an empty form
              $html .= '<form autocomplete="off" class="form_promotion" style="display: inline;">';
              $html .= '<input type="hidden" name="employee_id" value="' . $_POST["employee_id"] . '" >';
              $html .= '<div class="row">';
              $html .= '<div class="col-2">';
              $html .= '<div class="form-group"><input name="promotion_date" type="date" value=""  class="form-control" placeholder="Enter email"></div>';
              $html .= '</div>';
              $html .= '<div class="col-6">';
              $html .= '<div class="form-group"><input name="note" type="text" value="" class="form-control" placeholder="Enter Note Here"></div>';
              $html .= '</div>';
              $html .= '<div class="col-2">';
              $html .= '<select class="form-control" name="status">';
              $html .= '<option value="INACTIVE">INACTIVE</option>';
              $html .= '<option value="ACTIVE">ACTIVE</option>';
              $html .= '</select>';
              $html .= '</div>';
              $html .= '<div class="col-1">';
              $html .= '<button type="submit" name="actionButton" value="Save" class="btn btn-success btn-block">Save</button>';
              $html .= '</div>';
              $html .= ' <div class="col-1">';
              $html .= '<a href="#" onclick="resetFormPromotion(this)" class="btn btn-danger btn-block">Delete</a>';
              $html .= '</div>';
              $html .= '</div>';
              $html .= '</form>';
          }

          else{
            foreach ($continuous as $row):
              $html .= '<form autocomplete="off" class="form_promotion" style="display: inline;">';
              $html .= '<input type="hidden" name="employee_id" value="' . $row["employee_id"] . '" >';
              $html .= '<input type="hidden" name="id" value="' . $row["id"] . '" >';
              $html .= '<input type="hidden" name="action" value="updatePromotion" >';
  
              $html .= '<div class="row">';
              $html .= ' <div class="col-2">';
              $html .= '<div class="form-group"><input name="promotion_date" type="date" value="' . $row["promotion_date"] . '"  class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div>';
              $html .= '</div>';
              $html .= '<div class="col-6">';
              $html .= '<div class="form-group"><input name="note" type="text" value="' . $row["note"] . '" class="form-control" id="exampleInputEmail1" placeholder="Enter Note Here"></div>';
              $html .= '</div>';
              $html .= '<div class="col-2">';
              $html .= '
                      <select class="form-control" name="status">
                        <option value="'.$row["status"].'">'.$row["status"].'</option>
                        <option value="INACTIVE">INACTIVE</option>
                        <option value="ACTIVE">ACTIVE</option>
                      </select>
              ';
              $html .= '</div>';
              $html .= ' <div class="col-1">';
              $html .= '<button type="submit" name="actionButton" value="Save" class="btn btn-success btn-block">Save</button>';
              $html .= '</div>';
              $html .= ' <div class="col-1">';
              $html .= '<a href="#" onclick="deletePromotion(\''.$row["id"].'\', \''.$row["employee_id"].'\')" class="btn btn-danger btn-block">Delete</a>';
              $html .= '</div>';
              $html .= '</div>';
              $html .= '</form>';
  
          endforeach;
          }


            
            
  
          $html .= '<button type="button" class="btn btn-info mt-2" onclick="duplicateRowPromotion(this)">Add Row</button>';
  
            echo($html);

        


        elseif($_POST["action"] == "modal_edit_employees"):

            
            // dump($_POST);

            $department = query("select * from tbldepartment");
            $group = query("select * from tbl_group");

            $position = query("select * from tblposition");
            $salsched = query("select sg.salary_grade_id, sg.salary_grade, sg.step, s.schedule, sg.salary FROM tbl_salary_sched s LEFT JOIN tbl_salary_grade sg
                ON sg.salary_schedule_id = s.salary_schedule_id
                WHERE s.status = 'active'");

            $employees = query("select * from tblemployee where Employeeid = ?", $_POST["employee_id"]);
            // dump($employees);
            $e = $employees[0];
            // dump($e["Gender"]);
            $hint = '
            <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
            <input type="hidden" name="rowIndex" value="'.$_POST["rowIndex"].'">
                  <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Biometric Number</label>
                      <input required value="'.$e["Fingerid"].'" name="biometric_number" type="number" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Active Status</label>
                      <select name="active_status" required class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="'.$e["active_status"].'">'.$e["active_status"].'</option>
                            <option value="1">1</option>
                            <option value="0">0</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Birth Date</label>
                      <input value="'.$e["BirthDate"].'" name="birthdate" type="date" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>First Name</label>
                      <input required value="'.$e["FirstName"].'" name="first_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Last Name</label>
                      <input required value="'.$e["LastName"].'" name="last_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Middle Name</label>
                      <input value="'.$e["MiddleName"].'" name="middle_name" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Suffix</label>
                      <input value="'.$e["NameExtension"].'" name="suffix" type="text" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">';
                                
                                if(!empty($e["Gender"])){
                                    $hint = $hint . '<option value="'.$e["Gender"].'">'.$e["Gender"].'</option>';
                                }    
                                else{
                                    $hint = $hint . '<option value="" selected disabled>Please select Gender</option>';
                                }
                                $hint = $hint .'<option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                    <div class="form-group">
                        <label>Employment Status</label>
                        <select required name="employment" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="'.$e["JobType"].'">'.$e["JobType"].'</option>
                            <option value="JOB ORDER">JOB ORDER</option>
                            <option value="HONORARIUM">HONORARIUM</option>
                            <option value="CASUAL">CASUAL</option>
                            <option value="COTERMINOUS">COTERMINOUS</option>
                            <option value="ELECTIVE">ELECTIVE</option>
                            <option value="PERMANENT">PERMANENT</option>
                        </select>
                    </div>
                  </div>


                  <div class="col-md-4">
                    <div class="form-group">
                        <label>Position</label>
                        <select name="position" id="position_select" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="">Please select Position</option>';
                            foreach($position as $p):
                                if($p["Positionid"] == $e["Positionid"]){
                                    $hint = $hint . '<option selected value="'.$p["Positionid"].'">'.$p["PositionName"].'</option>';
                                }
                                else{
                                    $hint = $hint . '<option value="'.$p["Positionid"].'">'.$p["PositionName"].'</option>';
                                }
                            endforeach;
                            $hint = $hint . '
                        </select>
                    </div>
                  </div>
                </div>
                

                <div class="row">
                <div class="col-md-6">';


                if($e["JobType"] == "JOB ORDER" || $e["JobType"] == "HONORARIUM"):
                  $hint = $hint . '

                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label>Salary</label>
                        <input value="'.$e["salary"].'" name="salary" type="text" class="form-control" placeholder="Enter ...">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label>Salary Type</label>
                          <select class="form-control" name="salary_option">
                          ';
                          if($e["salary_option"] == ""):
                            $hint .= '<option value="" selected disabled>Please Select Type</option>';
                          else:
                            $hint .= '<option selected value="'.$e["salary_option"].'">'.$e["salary_option"].'</option>';
                          endif;
                          $hint .='
                            <option value="DAILY">DAILY</option>
                            <option value="HOURLY">HOURLY</option>
                          </select>
                      </div>
                    </div>
                  </div>
                  
                  ';
                else:
                  $hint = $hint . '
                  <div class="form-group">
                    <label>Salary</label>
                    <select name="salary" id="salary_select" class="form-control select2" style="width: 100%;">
                    <option value="" disabled selected>Please select SG</option>
                    ';
                    
                    foreach($salsched as $row):
                          // dump($e);
                          if($row["salary_grade"] == $e["salary_grade"] && $row["step"] == $e["salary_step"] && $row["schedule"] == $e["salary_class"]): 
                              $hint = $hint . '<option selected value="'.$row["salary_grade_id"].'">'.$row["salary"] . " SG: " . $row["salary_grade"] . " Step " . $row["step"] . " " . $row["schedule"].'</option>';
                        else:
                          $hint = $hint . '<option  value="'.$row["salary_grade_id"].'">'.$row["salary"] . " SG: " . $row["salary_grade"] . " Step " . $row["step"] . " " . $row["schedule"].'</option>';
                  endif; 
                      endforeach;
                    $hint = $hint . '
                    </select>
                  </div>';
                endif;


                $hint = $hint . '</div>';
                

                $hint = $hint . '
                <div class="col-md-6">
                  <div class="form-group">
                    <label>PACS / LBP Number</label>
                    <input value="'.$e["lbp_number"].'" name="lbp_number" type="number" class="form-control" placeholder="Enter ...">
                  </div>
                </div>




                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Department Asssign</label>
                        <select name="department" required class="form-control select2" style="width: 100%;">
                            <option selected="selected" disabled value="">Please Select Department</option>
                ';
               foreach($department as $d): 

                if($d["Deptid"] == $e["DeptAssignment"]){
                    $hint = $hint . '<option selected value="'.$d["Deptid"].'">'.$d["DeptCode"] . " - " . $d["DeptName"].'</option>';
                }
                else{
                    $hint = $hint . '<option value="'.$d["Deptid"].'">'.$d["DeptCode"] . " - " . $d["DeptName"].'</option>';
                }
                endforeach;
                $hint = $hint . '
                        </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Department Fund</label>
                        <select name="department_fund" required class="form-control select2" style="width: 100%;">
                            <option selected="selected" disabled value="">Please Select Department</option>
                ';
               foreach($department as $d): 

                if($d["Deptid"] == $e["Deptid"]){
                    $hint = $hint . '<option selected value="'.$d["Deptid"].'">'.$d["DeptCode"] . " - " . $d["DeptName"].'</option>';
                }
                else{
                    $hint = $hint . '<option value="'.$d["Deptid"].'">'.$d["DeptCode"] . " - " . $d["DeptName"].'</option>';
                }
                endforeach;
                $hint = $hint . '
                        </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Group</label>
                        <select name="group" class="form-control select2" style="width: 100%;">
                            <option selected="selected" value="">Please select Group</option>';
                foreach($group as $g):

                    if($g["group_id"] == $e["GroupName"]){
                        $hint = $hint . '<option selected value="'.$g["group_id"].'">'.$g["group_name"].'</option>';
                    }
                    else{
                        $hint = $hint . '<option value="'.$g["group_id"].'">'.$g["group_name"].'</option>';
                    }
                endforeach;
                
                $hint = $hint . '
                   
                        </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Print Remarks</label>
                        <select name="print_remarks" class="form-control select2" style="width: 100%;">
                            <option selected value="'.$e["print_remarks"].'">'.$e["print_remarks"].'</option>
                            <option value="DTR">DTR</option>
                            <option value="TIMESHEET">TIMESHEET</option>
                            <option value="BOTH">BOTH</option>
                            <option value="NONE">NONE</option>
                            ';
                $hint = $hint . '
                        </select>
                    </div>
                  </div>

                  


                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Original Appointment</label>
                        <input value="'.$e["original_appointment"].'" name="original_appointment" type="date" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                        <label>Date Hired</label>
                        <input value="'.$e["date_hired"].'" name="date_hired" type="date" class="form-control" placeholder="Enter ...">
                    </div>
                  </div>
                </div>
            
            ';
            echo($hint);
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

          $department = query("select * from tbldepartment");
            $group = query("select * from tbl_group");
            render("apps/plantilla/employees_app/employees_form.php", 
                [
                  "title" => "Employees",
                  "navbar" => "collapse",
                  "group" => $group,
                  "department" => $department,
              
              ],"records");

        endif;
       


        
    }
?>