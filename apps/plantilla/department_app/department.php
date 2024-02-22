<?php   

// dump($_REQUEST);
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "department_list_datatable"):

            if($_POST["action"] == "department_list_datatable"){
                // dump($_REQUEST);
                $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
                $where = " where active_status = 1";
                if(isset($_REQUEST["job"])){
                    if($_REQUEST["job"] != "")
                        if($_REQUEST["job"] != "all")
                        $where = $where . " and JobType = '".$_REQUEST["job"]."'";
                        else
                        $where = " where 1=1";
                }
                else
                    $where = $where . " and JobType = 'PERMANENT'";
    
    
                // dump($_REQUEST);
                if($_REQUEST["with_salary"] == "with"){
                    if($_REQUEST["job"] == "JOB ORDER" || $_REQUEST["job"] == "HONORARIUM"){
                        $where = $where . " and (salary != '' or salary is not null)";
                    }
                    else{
                        $where = $where . " and (salary_grade != '' or salary_grade is not null)
                                            and (salary_step != '' or salary_grade is not null)";
                    }
                }
    
    
                if($_REQUEST["with_salary"] == "without"){
                    if($_REQUEST["job"] == "JOB ORDER" || $_REQUEST["job"] == "HONORARIUM"){
                        $where = $where . " and (salary = '' or salary is null)";
                    }
                    else{
                        $where = $where . " and (salary_grade = '' or salary_grade is null)
                                            and (salary_step = '' or salary_grade is null)";
                    }
                }
    
    
                $offset = $_POST["start"];
                $limit = $_POST["length"];
                $search = $_POST["search"]["value"];
    
                $where_dept = $where_dept . " and Deptid = '".$_REQUEST["department_id"]."'";
                if(isset($_REQUEST["job"])){
                    if($_REQUEST["job"] != "")
                        if($_REQUEST["job"] != "all")
                        $where_dept = $where_dept . " and Deptid = '".$_REQUEST["department_id"]."'";
                        else
                        $where_dept = "";
                }
                // dump($_REQUEST);
    
                $Department = [];
                $department = query("select * from tbldepartment");
                foreach($department as $d):
                    $Department[$d["Deptid"]] = $d;
                endforeach;
    
                $sql = query("select * from tblemployee" .  $where  . $where_dept);
                $all_data = $sql;
                $data = query("select e.Employeeid, e.FirstName, e.LastName, e.NameExtension, e.MiddleName,
                                    e.Fingerid, e.salary_grade, e.salary_step, e.salary, e.salary_class, e.lbp_number,
                                    e.Deptid, e.Gender,
                                    p.PositionName from tblemployee e
                                    left join tblposition p
                                    on e.Positionid = p.Positionid
                                    " .  $where  . $where_dept .  " 
                                    order by LastName asc, FirstName asc 
                                    ");
                // dump($employees);
    
                $SalaryGrade = [];
                $salary_grade = query("select * from tbl_salary_sched ss
                                        left join tbl_salary_grade sg
                                        on sg.salary_schedule_id = ss.salary_schedule_id");
                foreach($salary_grade as $sg):
                    $SalaryGrade[$sg["schedule"]][$sg["salary_grade"]][$sg["step"]] = $sg;
                endforeach;
                // dump($SalaryGrade);
    
            
                if($search == ""){
                    $query_string = "select e.Employeeid, e.FirstName, e.LastName, e.NameExtension, e.MiddleName,
                    e.Deptid, e.Gender,
                    e.Fingerid, e.salary_grade, e.salary_step, e.salary, e.salary_class, e.JobType, e.lbp_number, 
                    p.PositionName as position from tblemployee e
                    left join tblposition p
                    on e.Positionid = p.Positionid
                    " .  $where  . $where_dept . "
                    order by LastName asc, FirstName asc 
                    limit ".$limit." offset ".$offset." ";
                    // dump($query_string);
                    $data = query($query_string);
                }
                else{
                    $query_string = "
                    select e.Employeeid, e.FirstName, e.LastName, e.NameExtension, e.MiddleName,
                    e.Deptid, e.Gender,
                    e.Fingerid, e.salary_grade, e.salary_step, e.salary, e.salary_class, e.lbp_number,
                    p.PositionName as position from tblemployee e
                    left join tblposition p
                    on e.Positionid = p.Positionid
                    " .  $where  . $where_dept . "
                    and (concat(e.LastName, ' ' , e.NameExtension , ', ', e.FirstName, ' ' ,e.MiddleName) like '%".$search."%'
                    or p.PositionName like '%".$search."%'
                    )
                    order by LastName asc, FirstName asc 
                        limit ".$limit." offset ".$offset."
                    ";
                    $data = query($query_string);
                }
                $i=0;
                foreach($data as $row):
                    // $data[$i]["action"] = $data[$i]["position"];
                    $data[$i]["fullname"] = $row["LastName"] . " " . $row["NameExtension"] . ", " . $row["FirstName"];
                    $data[$i]["gender"] = $row["Gender"];
    
                    $data[$i]["action"] = '
                    <a data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#modal_edit_employees" class="btn  btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="employees?employee_id='.$row["Employeeid"].'&action=information"" class="btn btn-flat btn-xs btn-success"><i class="fa fa-eye"></i></a>
                    <a data-id="'.$row["Employeeid"].'" data-toggle="modal" data-target="#modal_update_payroll" class="btn btn-xs btn-flat btn-info"><i class="fa fa-money-bill"></i></a>
                    ';
                    $data[$i]["dept"] = $Department[$row["Deptid"]]["DeptCode"];
                    if($row["JobType"] == "JOB ORDER" || $row["HONORARIUM"])
                        $data[$i]["salary"] = to_peso($row["salary"]);
                    else{
                        $data[$i]["salary"] = to_peso($SalaryGrade[$row["salary_class"]][$row["salary_grade"]][$row["salary_step"]]["salary"]);
                    }
                 $i++;
                endforeach;
                
                $json_data = array(
                    "draw" => $draw + 1,
                    "iTotalRecords" => count($all_data),
                    "iTotalDisplayRecords" => count($all_data),
                    "aaData" => $data
                );
                echo json_encode($json_data);
            }






        endif;

        
        
    
    }
    else
    {
        if(isset($_GET["action"])):
            if($_GET["action"] == "view"):
                // dump($_GET);
                $department = query("select * from tbldepartment where Deptid = ?", $_GET["id"]);
                $department = $department[0];

                $all = query("select count(*) as count from tblemployee");
                $all = $all[0]["count"];


                $permanent = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'PERMANENT' and active_status = 1", $_GET["id"]);
                $permanent = $permanent[0]["count"];
                
                $coterminous = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'COTERMINOUS' and active_status = 1", $_GET["id"]);
                $coterminous = $coterminous[0]["count"];
                $casual = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'CASUAL' and active_status = 1", $_GET["id"]);
                $casual = $casual[0]["count"];
                $job_order = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'JOB ORDER' and active_status = 1", $_GET["id"]);
                $job_order = $job_order[0]["count"];
                $honorarium = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'HONORARIUM' and active_status = 1", $_GET["id"]);
                $honorarium = $honorarium[0]["count"];
                $elective = query("select count(*) as count from tblemployee where Deptid = ? and JobType = 'ELECTIVE' and active_status = 1", $_GET["id"]);
                $elective = $elective[0]["count"];



                render("apps/plantilla/department_app/department_view.php", 
                [
                    "title" => $department["DeptCode"],
                    "all" => $all,
                    "department" => $department,
                    "permanent" => $permanent,
                    "coterminous" => $coterminous,
                    "casual" => $casual,
                    "job_order" => $job_order,
                    "honorarium" => $honorarium,
                    "elective" => $elective,
                ],"plantilla");

            endif;
        else:
            $department = query("select * from tbldepartment order by Deptid");
            render("apps/plantilla/department_app/department_list.php", 
                ["title" => "DEPARTMENT",
                 "department" => $department,
                ],"plantilla");
        endif;
       


        
    }
?>