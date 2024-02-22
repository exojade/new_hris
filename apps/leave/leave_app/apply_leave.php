<?php

// dump(checkRole($_SESSION["hris"]["roles"],"AO LEAVE"));

// dump($_SESSION);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "datatable"):
            // print_r($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

          // dump($_REQUEST);
          $where = " where 1=1";
          if(isset($_REQUEST["jobType"])):
              if($_REQUEST["jobType"] != ""):
                  $where = $where . " and JobType = '".$_REQUEST["jobType"]."'";
              endif;
          endif;
          $department = query("select * from tbldepartment");
          if(isset($_REQUEST["depId"])):
              if($_REQUEST["depId"] != ""):
                  // $department = query("select * from tbldepartment where Deptid = ?", $_REQUEST["depId"]);
                  $where = $where . " and Deptid = '".$_REQUEST["depId"]."'"; 
              else:
                  $department = query("select * from tbldepartment");
              endif;
          else:
              $department = query("select * from tbldepartment");
          endif;

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



          $query_string = "select * from tblemployee" . $where;
            $sql = query($query_string);
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
                $employees = query($query_string);
            }
            else{

              $where = $where . "
              and (concat(LastName, ', ', FirstName) like '%".$search."%' or
              concat(FirstName, ' ', LastName) like '%".$search."%' or
              Fingerid like '%".$search."%' or 
              HRID like '%".$search."%')
              ";


                $query_string = "
                    select * from tblemployee
                    ".$where."
                    order by LastName ASC
                    limit ".$limit." offset ".$offset."
                ";
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




                if($row["active_status"] == 0)
                    $employees[$i]["active_status"] = '<p class="text-danger text-center" >NOT ACTIVE</p>';
                else
                    $employees[$i]["active_status"] = '<p class="text-success text-center" >ACTIVE</p>';


                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_employees),
                "iTotalDisplayRecords" => count($all_employees),
                "aaData" => $employees
            );
            echo json_encode($json_data);

        elseif($_POST["action"] == "fillDate"):
          // dump($_POST);
          $holidays = query("select * from holidays");
          // dump($holidays);
          $events = [];
          foreach($holidays as $row):
            $row["backgroundColor"] = '#0073b7';
            $row['allDay'] = true;
            $row['start'] = $row["holiday_date"];
            $row['end'] = $row["holiday_date"];
            $row['title'] = $row["name"];
            $events[] = $row;
          endforeach;

        elseif($_POST["action"] == "fetchLeaveCalendar"):
            // dump($_POST);

        if(checkRole($_SESSION["hris"]["roles"],"AO LEAVE")):
            $leave = query("select l.*, concat(FirstName, ' ', LastName) as employeeName from leave_employee l
                            left join tblemployee e
                            on e.Employeeid = l.employee_id
                            where department = ?", $_SESSION["hris"]["departmentAssignment"]);
        else:
            $leave = query("select l.*, concat(FirstName, ' ', LastName) as employeeName from leave_employee l
                            left join tblemployee e
                            on e.Employeeid = l.employee_id
                                where employee_id = ?", $_SESSION["hris"]["employee_id"]);
        endif;
        $events = [];
        foreach($leave as $row):
            $row["backgroundColor"] = '#89043D';
            $row['allDay'] = true;
            $row['start'] = $row["from_date"];
            $row['end'] = date('Y-m-d', strtotime($row["to_date"]. ' + 1 day'));
            $row['title'] = $row["employeeName"] . "(" . $row["leave_type"] . ")";
            // $row['url'] = 'your_redirect_url.php';
            $row['editable'] = 1;
            $events[] = $row;
        endforeach;



        elseif($_POST["action"] == "applyLeave"):
            // dump($_POST);
            $employee = query("select * from tblemployee e
            left join tblposition p
            on p.Positionid = e.Positionid
            where Employeeid = ?", $_POST["employee_id"]);
            $employee = $employee[0];

            $salary = query("SELECT * FROM tbl_salary_sched ss
                LEFT JOIN tbl_salary_grade  sg
                ON sg.salary_schedule_id = ss.salary_schedule_id
                WHERE ss.status = 'active'");
            $salary = salary($salary,"",$employee["salary_grade"],$employee["salary_step"],$employee["salary_class"]);

            $numberOfDays = $_POST["daysCovered"];
            $leaveID = generateLeaveID($employee["DeptAssignment"]);
            // dump($leaveID);

            // get_leave_balance($_POST["leaveType"], );
            if($_POST["leaveType"] == "SICK LEAVE"):
                $sickLeaveCredits = get_leave_balance("SICK LEAVE", $_POST["employee_id"]);
                $vacationLeaveCredits = get_leave_balance("VACATION LEAVE", $_POST["employee_id"]);
                $initialsickLeaveCredits = $sickLeaveCredits;
                $initialvacationLeaveCredits = $vacationLeaveCredits;
                $withoutPay = 0;
                $withPay = 0;
                // dump($vacationLeaveCredits);
                if ($numberOfDays <= $sickLeaveCredits) {
                    $sickLeaveCredits -= $numberOfDays;
                  } else if ($numberOfDays <= ($sickLeaveCredits + $vacationLeaveCredits)) {
                    $vacationLeaveCredits -= ($numberOfDays - $sickLeaveCredits);
                    $sickLeaveCredits = 0;
                  } else {
                    $remainingDays = $numberOfDays - ($sickLeaveCredits + $vacationLeaveCredits);
                    $sickLeaveCredits = 0;
                    $vacationLeaveCredits = 0;
                    $withoutPay = $remainingDays;
                  }

                 


                $withPay = $numberOfDays - $withoutPay;
                $sickLeaveCredits = $initialsickLeaveCredits - $sickLeaveCredits;
                $vacationLeaveCredits = $initialvacationLeaveCredits - $vacationLeaveCredits;

            elseif($_POST["leaveType"] == "VACATION LEAVE"):


                $sickLeaveCredits = get_leave_balance("SICK LEAVE", $_POST["employee_id"]);
                $vacationLeaveCredits = get_leave_balance("VACATION LEAVE", $_POST["employee_id"]);
                $initialsickLeaveCredits = $sickLeaveCredits;
                $initialvacationLeaveCredits = $vacationLeaveCredits;
                $withoutPay = 0;
                $withPay = 0;

                if ($numberOfDays <= $vacationLeaveCredits) {
                    $vacationLeaveCredits -= $numberOfDays;
                  } else {
                    $remainingDays = $numberOfDays - $vacationLeaveCredits;
                    $vacationLeaveCredits = 0;
                    $withoutPay = $remainingDays;
                  }

                $withPay = $numberOfDays - $withoutPay;
                $sickLeaveCredits = $initialsickLeaveCredits - $sickLeaveCredits;
                $vacationLeaveCredits = $initialvacationLeaveCredits - $vacationLeaveCredits;
            endif;
            
              

                if (
                    query(
                        "insert INTO leave_employee (
                            leaveId, 
                            employee_id, 
                            from_date, 
                            to_date, 
                            days, 
                            date_filed, 
                            year, 
                            month, 
                            leave_type, 
                            sl_credits, 
                            vl_credits, 
                            with_pay, 
                            without_pay, 
                            status,
                            asSLBalance,
                            asVLBalance,
                            department,
                            salary,
                            position,
                            from_time,
                            to_time
                        ) VALUES (
                            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
                        )",
                        $leaveID, 
                        $_POST["employee_id"],
                        $_POST["from_date"],
                        $_POST["to_date"],
                        $_POST["daysCovered"],
                        date("Y-m-d"),
                        date("Y"),
                        date("m"),
                        $_POST["leaveType"],
                        $sickLeaveCredits,
                        $vacationLeaveCredits,
                        $withPay,
                        $withoutPay,
                        "DONE",
                        $initialsickLeaveCredits,
                        $initialvacationLeaveCredits,
                        $employee["DeptAssignment"],
                        $salary,
                        $employee["Positionid"],
                        $_POST["from_time"],
                        $_POST["to_time"],
                    ) === false
                ) {
                    $res_arr = [
                        "status" => "failed",
                        "title" => "Failed",
                        "message" => "Cannot Encode Leave! Contact Tech Support",
                        "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();
                }
                
                $res_arr = [
                    "status" => "success",
                    "title" => "Success",
                    "message" => "Leave Application done Successfully!",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
            


        elseif($_POST["action"] == "redirectLeave"):
            // dump($_POST);
            $res_arr = [
                "status" => "success",
                "title" => "Success",
                "message" => "Leave Application done Successfully!",
                "link" => "apply_leave?employee=".$_POST["employee"],
                ];
                echo json_encode($res_arr); exit();

       



        endif;

        header('Content-Type: application/json');
        echo json_encode($events);
 
      


    }
    else
    {

        // dump("yawa");

        if(isset($_GET["action"])):
         
        else:


            if(isset($_GET["employee"])):
                $employee_id = $_GET["employee"];
            else:
                $employee_id = $_SESSION["hris"]["employee_id"];
            endif;

            $initial = query("select * from leave_initial where employee_id = ?", $employee_id);
            $leave_sl_vl = query("select sum(sl_credits) as sick_leave, sum(vl_credits) as vacation_leave from leave_employee where employee_id = ?", $employee_id);
            $leave_special_solo = query("select sum(special_leave_credits) as special_leave, sum(solo_parent_leave_credits) as solo_parent_leave from leave_employee where employee_id = ?
                                            and year = ?",  $employee_id, date("Y"));
            $earned_sl_vl = query("select sum(sick_leave) as sick_leave, sum(vacation_leave) as vacation_leave from leave_earned where employee_id = ?", $employee_id);
            // $earned_special_solo = query("select sum(special_leave) as special_leave, sum(solo_parent_leave) as solo_parent_leave
            //                                 from leave_earned where year = ? and employee_id = ?", date("Y"),$_SESSION["hris"]["employee_id"]);
            
            $initial_sick_leave = 0;
            $initial_vacation_leave = 0;
            $initial_special_leave = 3;
            $initial_solo_parent_leave = 7;

            if(!empty($initial)):
                $initial_sick_leave = $initial[0]["sick_leave"];
                $initial_vacation_leave = $initial[0]["vacation_leave"];
            endif;  

            $sick_leave = $initial_sick_leave - $leave_sl_vl[0]["sick_leave"] + $earned_sl_vl[0]["sick_leave"];
            $vacation_leave = $initial_vacation_leave - $leave_sl_vl[0]["vacation_leave"] + $earned_sl_vl[0]["vacation_leave"];
            $special_leave = $initial_special_leave - $leave_special_solo[0]["special_leave"];
            $solo_parent_leave = $initial_solo_parent_leave - $leave_special_solo[0]["solo_parent_leave"];
            // dump($vacation_leave);
            render("apps/leave/leave_app/apply_leave_form.php", 
                [
                  "title" => "Apply Leave",
                  "sick_leave" => $sick_leave,
                  "vacation_leave" => $vacation_leave,
                  "special_leave" => $special_leave,
                  "solo_parent_leave" => $solo_parent_leave,
              ],"leave");
        endif;
    }
?>