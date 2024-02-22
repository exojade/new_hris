<?php   
// dump($_REQUEST);
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        
      if($_POST["action"] == "employees_list"):
        // dump($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

            


            $where = " where active_status = 1 and JobType in ('PERMANENT', 'COTERMINOUS')";


            if(isset($_REQUEST["department"])):
                // $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
                $where = $where . " and Deptid = '".$_REQUEST["department"]."'"; 
            else:
                // $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
            endif;


            $sql = query("select * from tblemployee" . $where);
            $department = query("select * from tbldepartment");
            $Department = [];
            foreach($department as $d):
                $Department[$d["Deptid"]] = $d;
            endforeach;
            $position = query("select * from tblposition");
            $Position = [];
            foreach($position as $p):
                $Position[$p["Positionid"]] = $p;
            endforeach;

            $mandatory = query("select * from employee_mandatory");
            // dump($Salary);
            $Mandatory = [];
            foreach($mandatory as $row):
                $Mandatory[$row["employee_id"]] = $row;
            endforeach;



            $all_employees = $sql;
            if($search == ""){
                $query_string = "select * from tblemployee ". $where . "
                                order by LastName ASC
                                    limit ".$limit." offset ".$offset." ";
                $employees = query($query_string);
            }
            else{
                $query_string = "
                    select * from tblemployee
                    ".$where." and
                    (concat(LastName, ', ', FirstName) like '%".$search."%' or
                    concat(FirstName, ' ', LastName) like '%".$search."%' or
                    Fingerid like '%".$search."%' or 
                    HRID like '%".$search."%')
                    order by LastName ASC
                    limit ".$limit." offset ".$offset."
                ";
                // dump($query_string);
                $employees = query($query_string);
                $query_string = "
                        select * from tblemployee
                        ".$where." and 
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
                $employees[$i]["sss_ps"] = 0;
                $employees[$i]["hdmf_ps"] = 0;
                $employees[$i]["hdmf_mp2"] = 0;
                $employees[$i]["witholding_tax"] = 0;
                if(isset($Mandatory[$row["Employeeid"]])):
                    $employees[$i]["sss_ps"] = to_peso($Mandatory[$row["Employeeid"]]["sss_ps"]);
                    $employees[$i]["hdmf_ps"] = to_peso($Mandatory[$row["Employeeid"]]["hdmf_ps"]);
                    $employees[$i]["hdmf_mp2"] = to_peso($Mandatory[$row["Employeeid"]]["hdmf_mp2"]);
                    $employees[$i]["witholding_tax"] = to_peso($Mandatory[$row["Employeeid"]]["witholding_tax"]);
                endif;


             
                $employees[$i]["name"] = "<a target='_blank' href='payroll_employees?action=update_employee&id=".$row["Employeeid"]."'>".$row["LastName"] . ", " . $row["FirstName"] . "</a>";
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
                    $employees[$i]["active_status"] = '<p class="bg-red text-center" style="padding:5px;">NOT ACTIVE</p>';
                else
                    $employees[$i]["active_status"] = '<p class="bg-green text-center" style="padding:5px;">ACTIVE</p>';


                $i++;
            endforeach;
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_employees),
                "iTotalDisplayRecords" => count($all_employees),
                "aaData" => $employees
            );
            echo json_encode($json_data);
        
            elseif($_POST["action"] == "save_loan"):
                // dump($_POST);
                query("delete from loans_management where employee_id = ?", $_POST["employee_id"]);
                $inserts = array();
                $queryFormat = '("%s","%s","%s","%s","%s","%s","%s","%s")';
                $lenders = query("select * from lenders_management");
                foreach($lenders as $row):
                    if($_POST["loan_amount_".$row["lenders_id"]] != ""):
                        $loans_id = create_uuid("LOAN");
                        $inserts[] = sprintf( 
                            $queryFormat, 
                            $loans_id, $row["loan_name"], $_POST["employee_id"], 
                            $_POST["loan_amount_".$row["lenders_id"]],
                            $_POST["from_date_".$row["lenders_id"]],
                            $_POST["to_date_".$row["lenders_id"]],
                            $_POST["active_status_".$row["lenders_id"]],
                            $row["lenders_id"]
                        );
                    endif;
                endforeach;
                // dump($inserts);
                $query = implode( ",", $inserts );
                $query_string = 'insert into loans_management(loans_id, loan_title, employee_id, loans_amount, from_date, to_date, active_status ,lenders_id) VALUES '.$query;
                // dump($query_string);
                query($query_string);
                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
            elseif($_POST["action"] == "save_salary"):
                // dump($_POST);


                $salsched = query("select sg.salary_grade, sg.step, s.schedule from tbl_salary_sched s
                                left join tbl_salary_grade sg
                                on sg.salary_schedule_id = s.salary_schedule_id
                                where sg.salary_grade_id = ?
                                ", $_POST["salary"]);
            $salary=$salsched[0];
            // dump($salary);
            query("update tblemployee set 
                    representation_allowance = '".$_POST["representation_allowance"]."', 
                    travel_allowance = '".$_POST["travel_allowance"]."', 
                    salary_grade = '".$salary["salary_grade"]."', 
                    salary_step = '".$salary["step"]."', 
                    salary_class = '".$salary["schedule"]."', 
                    lbp_number = '".$_POST["lbp_number"]."'
                    where Employeeid= '".$_POST["employee_id"]."'
                    ");

                    $res_arr = [
                        "message" => "Successfully Added",
                        "status" => "success",
                        "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();

            elseif($_POST["action"] == "save_personal"):
                query("delete from employee_mandatory where employee_id = ?", $_POST["employee_id"]);
                $queryFormat = '("%s","%s","%s","%s","%s")';
                        $inserts[] = sprintf( 
                            $queryFormat, 
                            $_POST["employee_id"], $_POST["sss_ps"], $_POST["hdmf_ps"], 
                            $_POST["hdmf_mp2"], $_POST["witholding_tax"]
                        );
                $query = implode( ",", $inserts );
                query('insert into employee_mandatory(employee_id, sss_ps, hdmf_ps, hdmf_mp2, witholding_tax) VALUES '.$query);
                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();

            elseif($_POST["action"] == "save_pchgea_settings"):
                // dump($_POST);
                        query("update tblemployee set pchgea_dues = ?, pchgea_burial = ?
                                 where Employeeid = ?", $_POST["pchgea_dues"], $_POST["pchgea_burial"], $_POST["employee_id"]);
                         $res_arr = [
                             "message" => "Successfully Processed",
                             "status" => "success",
                             "link" => "refresh",
                             ];
                             echo json_encode($res_arr); exit();



        endif;
    }
    else
    {

        if(isset($_GET["action"])):
           if($_GET["action"] == "update_employee"):
            // dump($_GET);
                $employee = query("
                
                SELECT e.Employeeid AS employeeid, e.salary_grade, e.salary_step, e.salary_class,
                e.FirstName, e.LastName, e.pchgea_dues, e.pchgea_burial, e.communication_allowance,
                e.travel_allowance, e.representation_allowance, e.lbp_number,
                concat(e.FirstName, ' ', e.LastName) as name, e.Fingerid,
                m.* FROM tblemployee e LEFT JOIN employee_mandatory m
                ON m.employee_id = e.Employeeid WHERE e.Employeeid = ?
                ", $_GET["id"]);


                $salsched = query("select sg.salary_grade_id, sg.salary_grade, sg.step, s.schedule, sg.salary FROM tbl_salary_sched s LEFT JOIN tbl_salary_grade sg
                ON sg.salary_schedule_id = s.salary_schedule_id
                WHERE s.status = 'active'");
                // dump($employee);

                $lenders = query("select * from lenders_management order by lender ASC");
                $loans_employee = query("select * from loans_management where employee_id = ?", $_GET["id"]);

                $LoansEmployee = [];
                foreach($loans_employee as $row):
                    $LoansEmployee[$row["lenders_id"]] = $row;
                endforeach;


                    // $employee = query("select Employeeid, Fingerid, concat(LastName, ', ', FirstName, ' ' , NameExtension) as name from tblemployee
                    //                     where Employeeid = ?", $_GET["id"]);
                    // $employee = $employee[0];
                    // $mandatory = query("select * from employee_mandatory where employee_id = ?", $_GET["id"]);
                    // $mandatory = $mandatory[0];
                    $employee = $employee[0];
                    render("apps/payroll/employees_app/update_employee.php", 
                    [
                        "title" => "Update Employee", 
                        "lenders" => $lenders, 
                        "LoansEmployee" => $LoansEmployee, 
                        "employee" => $employee, 
                        "salsched" => $salsched, 
                
                    ],"payroll");
            endif;
        else:
            render("apps/payroll/employees_app/employees_list.php", 
                ["title" => "Employees",],"payroll");

        endif;
       


        
    }
?>