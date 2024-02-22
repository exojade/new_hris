<?php   
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory}; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
// dump($_REQUEST); asdasdasdasdasdasdasddddd
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "modal_leave"):
            $leave = query("select lwop, leave_days from payroll_actual where payroll_id = ?
                            and employee_id = ?", $_POST["payroll_id"], $_POST["employee_id"]);
            $hint = "";
            $hint = $hint . '
            <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
            <input type="hidden" name="payroll_id" value="'.$_POST["payroll_id"].'">
            <div class="form-group">
                <label for="exampleInputPassword1">Leave with Pay</label>
                <input value="'.$leave[0]["leave_days"].'" type="number" name="leave_days" step="0.01" class="form-control" placeholder="---">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Leave without Pay</label>
                <input value="'.$leave[0]["lwop"].'" type="number" name="lwop" step="0.01" class="form-control" placeholder="---">
            </div>
            ';
            echo($hint);

        elseif($_POST["action"] == "delete_payroll"):
            // dump($_POST);
            query("delete from payroll_group where payroll_id = ?", $_POST["payroll_id"]);
            query("delete from payroll_actual where payroll_id = ?", $_POST["payroll_id"]);
            $res_arr = [
                "message" => "Successfully Added",
                "status" => "success",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "modal_employee"):
            

            $employee = query("select * from tblemployee where Employeeid = ?", $_POST["employee_id"]);
            $salsched = query("select sg.salary_grade_id, sg.salary_grade, sg.step, s.schedule, sg.salary FROM tbl_salary_sched s LEFT JOIN tbl_salary_grade sg
            ON sg.salary_schedule_id = s.salary_schedule_id
            WHERE s.status = 'active'");
            $e = $employee[0];

            $settings = query("select * from payroll_yearend_settings");


            $payroll_actual = query("select * from payroll_yearend where employee_id = ?
                                        and payroll_id=?", $_POST["employee_id"], $_POST["payroll_id"]);
            $pa = $payroll_actual[0];
            // dump($pa);



                $hint = "";
                $hint = $hint . '
                <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
                <input type="hidden" name="payroll_id" value="'.$_POST["payroll_id"].'">
                <div class="form-group">
                  <label>Salary</label>
                  <select name="salary" class="form-control" id="salary_select" style="width: 100%;">
                    ';
                    foreach($salsched as $row):
                        // dump($e);
                        if($row["salary_grade"] == $e["salary_grade"] && $row["step"] == $e["salary_step"] && $row["schedule"] == $e["salary_class"]):
                            $hint = $hint . '<option selected value="'.$row["salary_grade_id"].'">'.$row["salary"].' (SG: '.$row["salary_grade"].' Step '.$row["step"].' '.$row["schedule"].') ' . '</option>';
                        else:
                            $hint = $hint . '<option value="'.$row["salary_grade_id"].'">'.$row["salary"]. ' (SG: '.$row["salary_grade"].' Step '.$row["step"].' '.$row["schedule"].') ' . '</option>';
                        endif;
                    endforeach;
                    $hint = $hint . '
                  </select>
                </div>
                

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Separation Length of Service</label>
                            <select name="separated_length" class="form-control" id="salary_select" style="width: 100%;">
                            ';
                            if($pa["deduction_type"] == 'SEPARATED'):
                                $hint = $hint . '<option selected value="'.$pa["length_of_service"].'">'.$pa["length_of_service"] . '</option>';
                                $hint = $hint . '<option selected value="">None</option>';
                            else:
                                $hint = $hint . '<option selected value="">None</option>';
                            endif;
                            
                                foreach($settings as $row):
                                    // dump($e);
                                    if($row["type"] == "SEPARATED"):
                                        $hint = $hint . '<option value="'.$row["length_service"].'">'.$row["length_service"] . '</option>';
                                    endif;
                                endforeach;
                                $hint = $hint . '
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Pro-Rated Length of Service</label>
                            <select name="pro_rated_length" class="form-control" id="salary_select" style="width: 100%;">
                            ';
                            if($pa["deduction_type"] == 'PRO-RATED'):
                                $hint = $hint . '<option selected value="'.$pa["length_of_service"].'">'.$pa["length_of_service"] . '</option>';
                                $hint = $hint . '<option selected value="">None</option>';
                            else:
                                $hint = $hint . '<option selected value="">None</option>';
                            endif;
                            
                                foreach($settings as $row):
                                    // dump($e);
                                    if($row["type"] == "PRO-RATED"):
                                        $hint = $hint . '<option value="'.$row["length_service"].'">'.$row["length_service"] . '</option>';
                                    endif;
                                endforeach;
                                $hint = $hint . '
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputPassword1">ATM Payroll</label>
                            <input name="lbp_number" value="'.$e["lbp_number"].'" type="number" class="form-control" placeholder="---">
                        </div>
                    </div>
                </div>
                <br>
                <script>
               
                </script>
                ';
                echo($hint);
            
        elseif($_POST["action"] == "update_employee"):
            
            if(!empty($_POST["separated_length"]) && !empty($_POST["pro_rated_length"])):
                $res_arr = [
                    "message" => "You can only choose pro-rated or separated!",
                    "status" => "failed",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
            endif;
            $settings = [];
            $deduction_type = "";
            if(!empty($_POST["separated_length"])):
                $settings = query("select * from payroll_yearend_settings where length_service = ? and type = 'SEPARATED'", $_POST["separated_length"]);
                $deduction_type = "SEPARATED";
            endif;

            if(!empty($_POST["pro_rated_length"])):
                $settings = query("select * from payroll_yearend_settings where length_service = ? and type = 'PRO-RATED'", $_POST["pro_rated_length"]);
                $deduction_type = "PRO-RATED";
            endif;

            $percentage = "";
            $length = "";
            if(!empty($settings)):
                $percentage = $settings[0]["percentage"];
                $length = $settings[0]["length_service"];
            endif;

            

            $salsched = query("select sg.salary_grade, sg.step, s.schedule from tbl_salary_sched s
                                left join tbl_salary_grade sg
                                on sg.salary_schedule_id = s.salary_schedule_id
                                where sg.salary_grade_id = ?
                                ", $_POST["salary"]);
            $salary=$salsched[0];
            query("update tblemployee set 
                    salary_grade = '".$salary["salary_grade"]."', 
                    salary_step = '".$salary["step"]."', 
                    salary_class = '".$salary["schedule"]."', 
                    lbp_number = '".$_POST["lbp_number"]."'
                    where Employeeid= '".$_POST["employee_id"]."'
                    ");
            query("update payroll_yearend set deduction_type = ?, length_of_service = ?, percentage = ? where
                    employee_id = ? and payroll_id = ?", $deduction_type, $length, $percentage,$_POST["employee_id"], $_POST["payroll_id"]);
            
                $res_arr = [
                "message" => "Successfully Added",
                "status" => "success",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();
        
        elseif($_POST["action"] == "new_payroll"):
                $job_type = serialize($_POST["job_type"]);
                // dump($_POST);
                $payroll_id = create_trackid("PAYROLL");
                $query_string = "insert into payroll_group 
                (payroll_id,barcode,year,month,department_fund,employment_status,
                remarks,payroll_type,payroll_design,date_created, time_created
                ) 
				VALUES(
                        '".$payroll_id."',
                        '".$_POST["barcode"]."',
                        '".$_POST["year"]."',
                        '11',
                        '".$_POST["department"]."',
                        '".$job_type."',
                        '".$_POST["remarks"]."',
                        'YEAR END',
                        'special',
                        '".date("Y-m-d")."',
                        '".date("h:i:s a")."'
                        )";
                        // dump($query_string);
                if (query($query_string
                ) === false)
				{
					$res_arr = [
                        "message" => "Employee already been created payroll on this month and year!",
                        "status" => "failed",
                        // "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();
				}
                $res_arr = [
                    "message" => "Successfully Added",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "update_leave"):
            // dump($_POST);
            $pg = query("select * from payroll_group where payroll_id = ?", $_POST["payroll_id"]);
            query("update payroll_actual set leave_days = ?, lwop = ?
                    where payroll_id = ? and employee_id = ?",
                    $_POST["leave_days"], $_POST["lwop"],
                    $_POST["payroll_id"], $_POST["employee_id"]);
            $res_arr = [
                "message" => "Successfully Processed",
                "status" => "success",
                "link" => "payroll_permanent?action=barcode&barcode=".$pg[0]["barcode"],
                ];
                echo json_encode($res_arr); exit();
        elseif($_POST["action"] == "modal_gs"):
            // dump($_POST);

            $hint = '
                  <table class="table table-bordered">
                    <thead>
                        <th>Share</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>GSIS</td>
                            <td>'.to_peso($_POST["gsis_gs"]).'</td>
                        </tr>
                        <tr>
                            <td>Philhealth</td>
                            <td>'.to_peso($_POST["phic_gs"]).'</td>
                        </tr>
                        <tr>
                            <td>HDMF (PAGIBIG)</td>
                            <td>'.to_peso($_POST["hdmf_gs"]).'</td>
                        </tr>
                        <tr>
                            <td>ECC</td>
                            <td>'.to_peso($_POST["ecc"]).'</td>
                        </tr>
                        <tr>
                            <th><b>TOTAL</b></th>
                            <th>'.to_peso($_POST["ecc"] + $_POST["hdmf_gs"] + $_POST["phic_gs"] + $_POST["gsis_gs"]).'</th>
                        </tr>
                    </tbody>    

                  </table>
            ';
            echo($hint);
            elseif($_POST["action"] == "modal_ps"):
            // dump($_POST);
            $mandatory = query("select m.* FROM tblemployee e LEFT JOIN
            employee_mandatory m
            ON m.employee_id = e.Employeeid
            WHERE e.Employeeid = ?", $_POST["employee_id"]);
            
            $hint = '
            <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
            <table class="table table-bordered">
                <thead>
                    <th>Share</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                    <tr>
                        <td>GSIS</td>
                        <td>'.to_peso($_POST["gsis_ps"]).'</td>
                    </tr>
                    <tr>
                        <td>PHIC</td>
                        <td>'.to_peso($_POST["phic_ps"]).'</td>
                    </tr>
                    <tr>
                        <td>HDMF (Pagibig)</td>
                        <td><input name="hdmf_ps" value="'.$mandatory[0]["hdmf_ps"].'" type="number" step="0.01" min="100" class="form-control" placeholder="100"></td>
                    </tr>
                    <tr>
                        <td>SSS</td>
                        <td><input name="sss_ps" value="'.$mandatory[0]["sss_ps"].'" type="number" step="0.01" class="form-control" placeholder="---"></td>
                    </tr>
                    <tr>
                        <td>HDMF MP2</td>
                        <td><input name="hdmf_mp2" value="'.$mandatory[0]["hdmf_mp2"].'" type="number" step="0.01" class="form-control" placeholder="---"></td>
                    </tr>
                    <tr>
                        <td>Witholding Tax</td>
                        <td><input name="witholding_tax" value="'.$mandatory[0]["witholding_tax"].'" type="number" step="0.01" class="form-control" placeholder="---"></td>
                    </tr>
                </tbody>
            </table>
            
            ';
            echo($hint);
            
            // if()
            elseif($_POST["action"] == "update_ps"):
                // dump($_POST);
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
            
            elseif($_POST["action"] == "modal_others"):
                // dump($_POST);
                $lenders = query("select * from lenders_management order by loan_name ASC");
                $loans_employee = query("select * from loans_management where employee_id = ?", $_POST["employee_id"]);
                $LoansEmployee = [];
                foreach($loans_employee as $row):
                    $LoansEmployee[$row["lenders_id"]] = $row;
                endforeach;

                $hint = '
                <input type="hidden" name="employee_id" value="'.$_POST["employee_id"].'">
                <table class="table table-bordered" id="loans_datatable">
                    <thead>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                ';
                foreach($lenders as $row):
                    $hint = $hint . '
                    <tr>
                    <td>'.$row["loan_name"].'</td>
                    <td><input value="'.$LoansEmployee[$row["lenders_id"]]["loans_amount"].'" type="number" step="0.01" name="loan_amount_'.$row["lenders_id"].'" class="form-control" placeholder="'.$row["lender"].'"></td>
                    <td><input value="'.$LoansEmployee[$row["lenders_id"]]["from_date"].'" type="date"  name="from_date_'.$row["lenders_id"].'" class="form-control" placeholder="'.$row["lender"].'"></td>
                    <td><input value="'.$LoansEmployee[$row["lenders_id"]]["to_date"].'" type="date"  name="to_date_'.$row["lenders_id"].'" class="form-control" placeholder="'.$row["lender"].'"></td>
                    <td>
                    <select class="form-control" name="active_status_'.$row["lenders_id"].'">
                    ';
                         if(!isset($LoansEmployee[$row["lenders_id"]])):
                            $hint = $hint . '<option selected value="inactive">🔴 inactive</option>
                                             <option value="active">🟢 active</option>
                            '; 
                        else:
                              if($LoansEmployee[$row["lenders_id"]]["active_status"] == "active"):
                                $hint = $hint . '<option value="inactive">🔴 inactive</option>
                                                 <option selected value="active">🟢 active</option>';
                              else:
                                $hint = $hint . '<option selected value="inactive">🔴 inactive</option>
                                                 <option value="active">🟢 active</option>
                                ';
                            endif;
                        endif;
                    $hint = $hint.'
                    </select>
                    </td>
                  </tr>
                  ';
                endforeach;
                $hint = $hint . '
                </tbody>
                </table>
                ';
                echo($hint);
            elseif($_POST["action"] == "save_loan"):
            dump($_POST);
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
            $query = implode( ",", $inserts );
            $query_string = 'insert into loans_management(loans_id, loan_title, employee_id, loans_amount, from_date, to_date, active_status ,lenders_id) VALUES '.$query;
            query($query_string);
            $res_arr = [
                "message" => "Successfully Processed",
                "status" => "success",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();
            elseif($_POST["action"] == "delete_employee"):
                // dump($_POST);
                query("delete from payroll_yearend where employee_id = ?
                        and payroll_id = ?", $_POST["employee_id"], $_POST["payroll_id"]);
                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
            elseif($_POST["action"] == "add_all_employees"):
                // dump($_POST);
                $pg = query("select * from payroll_group where barcode = ?", $_POST["barcode"]);
                $pg = $pg[0];
                $Pa = [];
                $pa = query("select employee_id from payroll_yearend where year = ?", $pg["year"]);
                foreach($pa as $row):
                    $Pa[$row["employee_id"]] = $row;
                endforeach;

                $job_type = unserialize($pg["employment_status"]);
                $job_type = "('" . implode("','", $job_type) . "')";

                $employees = query("select Employeeid from tblemployee where Deptid = ? and JobType in ".$job_type." and active_status = 1", $pg["department_fund"]);
                $Include = [];
                foreach($employees as $row):
                    if(!isset($Pa[$row["Employeeid"]])):
                        $Include[] = $row["Employeeid"];
                    endif; 
                endforeach;
                // dump($Include);
                $in = "('" . implode("','",$Include) . "')";
                $inserts = array();
                $queryFormat = '("%s","%s","%s")';
               if(!empty($Include)):
                foreach($Include as $row):
                    $inserts[] = sprintf( 
                        $queryFormat, 
                        $pg["payroll_id"], $row, $pg["year"]
                    );
                endforeach;
                $query = implode( ",", $inserts );
                query('insert into payroll_yearend(payroll_id, employee_id, year) VALUES '.$query);
               endif;
               
                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();

            elseif($_POST["action"] == "add_employee"):
                // dump($_POST);
                $pg = query("select * from payroll_group where barcode = ?", $_POST["barcode"]);
                $pg = $pg[0];
                $query_string = "insert into payroll_yearend 
                (payroll_id,employee_id,year) 
				VALUES(
                        '".$pg["payroll_id"]."',
                        '".$_POST["employee"]."',
                        '".$pg["year"]."'
                        )";
                        // dump($query_string);
                if (query($query_string
                ) === false)
				{
					$res_arr = [
                        "message" => "Employee already been created payroll on this month and year!",
                        "status" => "failed",
                        // "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();
				}
                $res_arr = [
                    "message" => "Successfully Added",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
                elseif($_POST["action"] == "save_payroll"):
                    $Emp = [];
                    $pg = query("select * from payroll_group where barcode = ?", $_POST["barcode"]);
                    $pa = query("
                    select pa.*,
         
                    e.Employeeid, e.Fingerid,
                    e.salary_grade, e.salary_step, e.salary_class, e.salary_id,
                    e.Positionid, e.lbp_number,
                    p.Positionid, p.PositionName
                    from payroll_yearend pa 
                    left join tblemployee e
                    on pa.employee_id = e.Employeeid
                    left join tblposition p
                    on p.Positionid = e.Positionid
                    where pa.payroll_id = ?
                    order by e.LastName ASC, e.FirstName ASC
                    ", $pg[0]["payroll_id"]);
                    foreach($pa as $row):
                        $Emp[] = $row["Employeeid"];
                    endforeach;
                    $Emp = implode( "','", $Emp );
                    $Emp = "('" . $Emp . "')";
                    $pg = $pg[0];
                  
         

                    $salary = query("select * from tbl_salary_sched sched
                                    left join tbl_salary_grade sg
                                    on sg.salary_schedule_id = sched.salary_schedule_id
                                    where sched.status = 'active'");
                                    
                    
                    $queryFormat = "(
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                        '%s','%s','%s','%s','%s','%s','%s','%s','%s'
                        )"; // 14

                    foreach($pa as $row):
                        $my_salary = salary($salary, $row["salary_id"], $row["salary_grade"], $row["salary_step"], $row["salary_class"]);
                        $cash_gift = 5000;
                        $accrued_bonus = $my_salary;
                        $accrued_cash_gift = $cash_gift;

                        if($row["deduction_type"] == "SEPARATED"):
                            $settings = query("select * from payroll_yearend_settings where length_service = ? and type = 'SEPARATED'", $row["length_of_service"]);
                            $accrued_bonus = $my_salary * $row["percentage"];
                            $accrued_cash_gift = $cash_gift * $row["percentage"];
                            $length_of_service = $row["length_of_service"];
                            $percentage = $row["percentage"] * 100;
                            $percentage = $percentage . "%";
                          elseif($row["deduction_type"] == "PRO-RATED"):
                            $settings = query("select * from payroll_yearend_settings where length_service = ? and type = 'PRO-RATED'", $row["length_of_service"]);
                            $accrued_bonus = 0;
                            $accrued_cash_gift = $cash_gift * $row["percentage"];
                            $length_of_service = $row["length_of_service"];
                            $percentage = $row["percentage"] * 100;
                            $percentage = $percentage . "%";
                          endif;






                        $inserts[] = sprintf( 
                            $queryFormat, 
                            $row["payroll_id"], $row["year"], $row["employee_id"], $row["Fingerid"],
                            $pg["department_fund"], $row["Positionid"], $row["PositionName"],$row["lbp_number"],
                            $accrued_bonus, $cash_gift, $row["salary_id"], $row["salary_class"],
                            $row["salary_grade"],$row["salary_step"], $row["length_of_service"], $row["percentage"], $row["deduction_type"],$my_salary,$accrued_cash_gift
                        );
                    endforeach;
                    
                    $query = implode( ",", $inserts );
                    $query_string = 'insert into payroll_yearend
                    (
                        payroll_id, year, employee_id, finger_id,
                        department, position_id, position_title,
                        lbp_number, year_end_bonus, cash_gift,
                        salary_sched_id,salary_class,
                        salary_grade,salary_step, length_of_service,percentage,deduction_type, salary, accrued_cash_gift
                    ) VALUES ' . $query;
                    query("delete from payroll_yearend where payroll_id = ?", $pg["payroll_id"]);
                    query($query_string);
                    query("update payroll_group set payroll_status = 'done'
                        where payroll_id = ?", $pg["payroll_id"]);
                        $res_arr = [
                            "message" => "Successfully Added",
                            "status" => "success",
                            "link" => "year_end?action=done&barcode=".$pg["barcode"],
                            ];
                            echo json_encode($res_arr); exit();
                    
              

                    elseif($_POST["action"] == "revert_payroll"):
                        query("update payroll_group set payroll_status = '' where barcode = ?", $_POST["barcode"]);
                        $res_arr = [
                            "message" => "Successfully Added",
                            "status" => "success",
                            "link" => "year_end?action=barcode&barcode=".$_POST["barcode"],
                            ];
                            echo json_encode($res_arr); exit();

                    elseif($_POST["action"] == "modal_gs_done"):
                    $pa=query("select government_deductions from payroll_actual where payroll_id = ? and employee_id = ?",
                                $_POST["payroll_id"], $_POST["employee_id"]);
                    $government = unserialize($pa[0]["government_deductions"]);
                    $hint = '
                    <table class="table table-bordered">
                      <thead>
                          <th>Share</th>
                          <th>Amount</th>
                      </thead>
                      <tbody>
                      ';
                    $total = 0;
                    foreach($government as $g):
                        $total = $total + to_amount($g["amount"]);
                        $hint = $hint . '
                        <tr>
                            <td>'.$g["title"].'</td>
                            <td>'.to_peso($g["amount"]).'</td>
                        </tr>
                        ';
                    endforeach;
                    $hint = $hint . '
                    <tr>
                        <th>Total</th>
                        <th>'.to_peso($total).'</th>
                    </tr>
                    ';
                      $hint = $hint . '
                      </tbody>    
                    </table>
              ';
              echo($hint);


              elseif($_POST["action"] == "modal_ps_done"):
            //   dump($_POST);
                $pa=query("select personal_deductions from payroll_actual where payroll_id = ? and employee_id = ?",
                            $_POST["payroll_id"], $_POST["employee_id"]);
                $personal = unserialize($pa[0]["personal_deductions"]);
                $hint = '
                <table class="table table-bordered">
                  <thead>
                      <th>Deductions</th>
                      <th>Amount</th>
                  </thead>
                  <tbody>
                  ';
                $total = 0;
                foreach($personal as $p):
                    $total = $total + to_amount($p["amount"]);
                    $hint = $hint . '
                    <tr>
                        <td>'.$p["title"].'</td>
                        <td>'.to_peso($p["amount"]).'</td>
                    </tr>
                    ';
                endforeach;
                $hint = $hint . '
                <tr>
                    <th>Total</th>
                    <th>'.to_peso($total).'</th>
                </tr>
                ';
                  $hint = $hint . '
                  </tbody>    
                </table>
                ';
                echo($hint);


                elseif($_POST["action"] == "modal_others_done"):
                    //   dump($_POST);
                        $pa=query("select other_deductions from payroll_actual where payroll_id = ? and employee_id = ?",
                                    $_POST["payroll_id"], $_POST["employee_id"]);
                        $others = unserialize($pa[0]["other_deductions"]);
                        $hint = '
                        <table class="table table-bordered">
                          <thead>
                              <th>Share</th>
                              <th>Amount</th>
                          </thead>
                          <tbody>
                          ';
                        $total = 0;
                        foreach($others as $o):
                            $total = $total + to_amount($o["amount"]);
                            $hint = $hint . '
                            <tr>
                                <td>'.$o["title"].'</td>
                                <td>'.to_peso($o["amount"]).'</td>
                            </tr>
                            ';
                        endforeach;
                        $hint = $hint . '
                        <tr>
                            <th>Total</th>
                            <th>'.to_peso($total).'</th>
                        </tr>
                        ';
                          $hint = $hint . '
                          </tbody>    
                        </table>
                        ';
                        echo($hint);


                    elseif($_POST["action"] == "pacs"):
                        // dump($_POST);
                        $pg = query("select payroll_id from payroll_group where barcode = ?", $_POST["barcode"]);
                        $employees = query("select pa.*, e.FirstName, e.LastName, e.MiddleName from tblemployee e
                                            left join payroll_yearend pa
                                            on pa.employee_id = e.Employeeid
                                            where payroll_id = ?
                                            order by LastName, FirstName
                                            ", $pg[0]["payroll_id"]);
                        $fileLocation = "file_folder/pacs/" . $_POST["barcode"] . $_POST["pacs"] . ".txt";
                        $file = fopen($fileLocation,"w");
                        $my_string = "";
                        foreach($employees as $row):
                            // dump($row);
                        $string = $row["lbp_number"] . $row["LastName"] . ", " . $row["FirstName"] . " " . $row["MiddleName"][0] . ".";
                            $amount = to_findes_amount($row["year_end_bonus"] + $row["accrued_cash_gift"]);
                        // $amount = to_findes_amount($f["net"]);
                        $string = str_pad($string, 50, " ", STR_PAD_RIGHT) . $amount . $string[0] . $string[1] . $string[2] . "00001";
                        $string = str_pad($string, 80, " ", STR_PAD_RIGHT);
                        $string = $string . "\n";
                        $my_string = $my_string . $string;
                        $my_string = str_replace('Ñ','N',$my_string);
                        $my_string = str_replace('-',' ',$my_string);
                        endforeach;

                        fwrite($file,$my_string);
                        fclose($file);
                        $res_arr = [
                            "message" => "Successfully Added",
                            "status" => "success",
                            "option" => "new_tab",
                            "link" => $fileLocation,
                            ];
                            echo json_encode($res_arr); exit();

                        elseif($_POST["action"] == "generate_excel"):
                            // dump($_POST);
                            $pg = query("select pg.*, d.DeptCode, d.DeptName from payroll_group pg
                            left join tbldepartment d
                            on d.Deptid = pg.department_fund
                            where pg.barcode = ?", $_POST["barcode"]);
                            $pg = $pg[0];
                            // dump($pg);
                            $department = query("select * from tbldepartment where Deptid = ?", $pg["department_fund"]);
                            $d = $department[0];
                            $payroll_actual = query("select e.Employeeid, Gender, concat(e.LastName, ' ', e.NameExtension, ', ', e.FirstName) as fullname, p.* from payroll_yearend p
                                                        left join tblemployee e
                                                        on e.Employeeid = p.employee_id
                                                        where payroll_id = ?
                                                        order by LastName asc, FirstName asc", $pg["payroll_id"]);
                            // dump($payroll_actual);
                            if($_POST["form_type"] == "standard"):
                                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("file_folder/payroll/year_end_default.xlsx");
                            elseif($_POST["form_type"] == "otc"):

                            elseif($_POST["form_type"] == "separated"):
                                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("file_folder/payroll/year_end_separated.xlsx");
                            elseif($_POST["form_type"] == "pro-rated"):
                                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("file_folder/payroll/year_end_prorated.xlsx");
                            endif;
                            $sheet = $spreadsheet->getActiveSheet();
                            $start_row = 16;
                            $beginner_row = $start_row;
                            $number = 1;
                            // dump($payroll);
                            $month = date("F", strtotime($pg["year"] . "-" . $pg["month"] ."-" . "01" ));
                            $end_date = date("t", strtotime($pg["year"] . "-" . $pg["month"] ."-" . "01" ));
                            $year = date("Y", strtotime($pg["year"] . "-" . $pg["month"] ."-" . "01" ));
                            $date_prepared = date("F d, Y", strtotime($pg["date_created"]));
                            $Rows=[];

                            $sheet->setCellValue("A6", "For the year " . $year);
                            $sheet->setCellValue("B9", $d["DeptCode"]);
                     
                            // $sheet->setCellValue("AA8", $payroll["time_saved"]);
                            // $sheet->setCellValue("Z11", $month);
                            // $sheet->setCellValue("AA11", $month);
                            // $sheet->setCellValue("Z12", "1-15");
                            // $sheet->setCellValue("AA12", "16-".$end_date);
                            // dump($payroll_actual);
                            foreach($payroll_actual as $p):
                                // dump($p);
                                
                                if($_POST["form_type"] == "standard"):
                                    $employee_start_row = $start_row;
                                    $sheet->insertNewRowBefore($start_row);
                                    $sheet->setCellValue('A'.$start_row, $number);
                                    $sheet->setCellValue('B'.$start_row, $p["fullname"]);
                                    $male = $p["Gender"] == "MALE" ? 1 : "";
                                    $female = $p["Gender"] == "FEMALE" ? 1 : "";
                                    $sheet->setCellValue('C'.$start_row, $male);
                                    $sheet->setCellValue('D'.$start_row, $female);
                                    $sheet->setCellValue('E'.$start_row, $p["position_title"]);
                                    $sheet->setCellValue('F'.$start_row ,$p["finger_id"]);
                                    $sheet->setCellValue('G'.$start_row ,$p["year_end_bonus"]);
                                    $sheet->setCellValue('J'.$start_row ,$p["accrued_cash_gift"]);
                                    $sheet->setCellValue('K'.$start_row ,"=G".$start_row."+J".$start_row."");
                                    $sheet->setCellValue('S'.$start_row ,"=SUM(P".$start_row.":P".$start_row.",R".$start_row.":R".$start_row.")");
                                    $sheet->setCellValue('T'.$start_row ,"=K".$start_row."-S".$start_row."");
                                elseif($_POST["form_type"] == "otc"):
    
                                elseif($_POST["form_type"] == "separated"):
                                    $percentage = $p["percentage"];
                                    // $percentage = $percentage . "%";
                                    $employee_start_row = $start_row;
                                    $sheet->insertNewRowBefore($start_row);
                                    $sheet->setCellValue('A'.$start_row, $number);
                                    $sheet->setCellValue('B'.$start_row, $p["fullname"]);
                                    $male = $p["Gender"] == "MALE" ? 1 : "";
                                    $female = $p["Gender"] == "FEMALE" ? 1 : "";
                                    $sheet->setCellValue('C'.$start_row, $male);
                                    $sheet->setCellValue('D'.$start_row, $female);
                                    $sheet->setCellValue('E'.$start_row, $p["position_title"]);
                                    $sheet->setCellValue('G'.$start_row ,$p["salary"]);
                                    $sheet->setCellValue('H'.$start_row ,$p["cash_gift"]);
                                    $sheet->setCellValue('I'.$start_row ,$p["length_of_service"]);
                                    $sheet->setCellValue('J'.$start_row ,$percentage);
                                    $sheet->setCellValue('K'.$start_row ,"=ROUND(G".$start_row."*J".$start_row.",2)");
                                    $sheet->setCellValue('L'.$start_row ,"=ROUND(H".$start_row."*J".$start_row.",2)");
                                    $sheet->setCellValue('Q'.$start_row ,"=K".$start_row."+L".$start_row."");
                                    $sheet->setCellValue('S'.$start_row ,"=Q".$start_row."");
                                    $sheet->setCellValue('T'.$start_row ,$number);
                                elseif($_POST["form_type"] == "pro-rated"):
                                    $percentage = $p["percentage"];
                                    // $percentage = $percentage . "%";
                                    $employee_start_row = $start_row;
                                    $sheet->insertNewRowBefore($start_row);
                                    $sheet->setCellValue('A'.$start_row, $number);
                                    $sheet->setCellValue('B'.$start_row, $p["fullname"]);
                                    $male = $p["Gender"] == "MALE" ? 1 : "";
                                    $female = $p["Gender"] == "FEMALE" ? 1 : "";
                                    $sheet->setCellValue('C'.$start_row, $male);
                                    $sheet->setCellValue('D'.$start_row, $female);
                                    $sheet->setCellValue('E'.$start_row, $p["position_title"]);
                                    // $sheet->setCellValue('G'.$start_row ,$p["salary"]);
                                    $sheet->setCellValue('H'.$start_row ,$p["cash_gift"]);
                                    $sheet->setCellValue('I'.$start_row ,$p["length_of_service"]);
                                    $sheet->setCellValue('J'.$start_row ,$percentage);
                                    // $sheet->setCellValue('K'.$start_row ,"=ROUND(G".$start_row."*J".$start_row.",2)");
                                    $sheet->setCellValue('L'.$start_row ,"=ROUND(H".$start_row."*J".$start_row.",2)");
                                    $sheet->setCellValue('Q'.$start_row ,"=K".$start_row."+L".$start_row."");
                                    $sheet->setCellValue('S'.$start_row ,"=Q".$start_row."");
                                    // $sheet->setCellValue('T'.$start_row ,$number);
                                endif;

                       
                  
                                
                                
                                
                                //other deductions end

                                
                                $Row[$p["Employeeid"]]["start_row"] = $employee_start_row;
                                $Row[$p["Employeeid"]]["end_row"] = $start_row;

                                // $letters = array(
                                //     "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
                                //     "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
                                //     "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD",
                                //     "AE", "AF", "AG", "AH"
                                // );

                                // foreach($letters as $l):
                                //     $cell = $l .  $employee_start_row;
                                //     $spreadsheet
                                //     ->getActiveSheet()
                                //     ->getStyle($cell)
                                //     ->getBorders()
                                //     ->getTop()
                                //     ->setBorderStyle(Border::BORDER_THIN)
                                //     ->setColor(new Color('000'));
                                // endforeach;
                              


                                

                                

                              
                                // $excel->getActiveSheet()->getRowDimension(1)->setRowHeight(-1);
                                $sheet->getRowDimension($start_row)->setRowHeight(-1); 
                                $sheet->getRowDimension($start_row)->setRowHeight(30); 
                               
                                
                                
                                $start_row++;
                                $number++;
                                
                            endforeach;
                            // dump($payroll_actual);
                            // $ending_row = $start_row;
                            // $ender = $ending_row-1;

                            // // $sheet->setCellValue("=D".$beginner_row);
                            // $sheet->setCellValue('D'.($ending_row) ,"=SUM(D".$beginner_row.":D".$ender.")");
                            // $sheet->setCellValue('E'.($ending_row) ,"=SUM(E".$beginner_row.":E".$ender.")");
                            // // $sheet->setCellValue('F'.($ending_row) ,"=SUM(F".$beginner_row.":F".$ender.")");
                            // $sheet->setCellValue('G'.($ending_row) ,"=SUM(G".$beginner_row.":G".$ender.")");
                            // $sheet->setCellValue('H'.($ending_row) ,"=SUM(H".$beginner_row.":H".$ender.")");
                            // $sheet->setCellValue('I'.($ending_row) ,"=SUM(I".$beginner_row.":I".$ender.")");
                            // $sheet->setCellValue('J'.($ending_row) ,"=SUM(J".$beginner_row.":J".$ender.")");
                            // $sheet->setCellValue('K'.($ending_row) ,"=SUM(K".$beginner_row.":K".$ender.")");
                            // $sheet->setCellValue('L'.($ending_row) ,"=SUM(L".$beginner_row.":L".$ender.")");
                            // $sheet->setCellValue('M'.($ending_row) ,"=SUM(M".$beginner_row.":M".$ender.")");
                            // $sheet->setCellValue('S'.($ending_row) ,"=SUM(S".$beginner_row.":S".$ender.")");
                            // $sheet->setCellValue('T'.($ending_row) ,"=SUM(T".$beginner_row.":T".$ender.")");
                            // $sheet->setCellValue('U'.($ending_row) ,"=SUM(U".$beginner_row.":U".$ender.")");
                            // $sheet->setCellValue('W'.($ending_row) ,"=SUM(W".$beginner_row.":W".$ender.")");
                            // $sheet->setCellValue('X'.($ending_row) ,"=SUM(X".$beginner_row.":X".$ender.")");
                            // $sheet->setCellValue('Y'.($ending_row) ,"=SUM(Y".$beginner_row.":Y".$ender.")");
                            // $sheet->setCellValue('Z'.($ending_row) ,"=SUM(Z".$beginner_row.":Z".$ender.")");
                            // $sheet->setCellValue('AE'.($ending_row) ,"=SUM(AE".$beginner_row.":AE".$ender.")");
                            // $sheet->setCellValue('AF'.($ending_row) ,"=SUM(AF".$beginner_row.":AF".$ender.")");
                            // $sheet->setCellValue('AG'.($ending_row) ,"=SUM(AG".$beginner_row.":AG".$ender.")");
                            // $sheet->setCellValue('AH'.($ending_row) ,"=SUM(AH".$beginner_row.":AH".$ender.")");
                            // $sheet->removeRow(14);
                            // $sheet->removeRow(13);
                            


                            

                            $sheet->removeRow(15);



                            $writer = new Xlsx($spreadsheet);
                            $filename = $pg["DeptCode"]. "-" . $pg["barcode"] . ".xlsx";
                            $path = 'file_folder/payroll/'.$filename;


                            $writer->save($path);
                            $res_arr = [
                                "message" => "Successfully Added",
                                "status" => "success",
                                "option" => "new_tab",
                                "link" => $path,
                                ];
                                echo json_encode($res_arr); exit();
                                            
                        


        endif;

        
        
    
    }
    else
    {
        if(isset($_GET["action"])):
            if($_GET["action"] == "barcode"):
                // dump($_GET);
                $pg = query("select * from payroll_group where barcode = ?", $_GET["barcode"]);
                // dump($pg);
                $pa = query("select * from payroll_yearend pa
                                left join tblemployee emp
                                on emp.Employeeid = pa.employee_id
                                where pa.payroll_id = ?
                                order by emp.LastName ASC, emp.FirstName ASC", $pg[0]["payroll_id"]);
                // dump($pa);
                $salary = query("select * from tbl_salary_sched sched
                                left join tbl_salary_grade grade
                                on grade.salary_schedule_id = sched.salary_schedule_id");
                $Salary = [];
                foreach($salary as $row):
                    $Salary[$row["schedule"]][$row["salary_grade"]][$row["step"]]["salary"] = $row["salary"];
                endforeach;
                // dump($Lenders);
                // dump($pa);
                // $burial = query("select * from pchgea_burial order by year desc, month desc");
                render("apps/payroll/payroll_special_app/year_end/year_end_barcode.php", 
                [
                    "title" => "Loans Management", 
                    "payroll_group" => $pg, 
                    "payroll_actual" => $pa, 
                    "Salary" => $Salary, 
                ],"payroll");
                // elseif($_GET["action"] == "mandatory_employee"):
                //     $employee = query("select Employeeid, Fingerid, concat(LastName, ', ', FirstName, ' ' , NameExtension) as name from tblemployee
                //                         where Employeeid = ?", $_GET["id"]);
                //     $employee = $employee[0];
                //     $mandatory = query("select * from employee_mandatory where employee_id = ?", $_GET["id"]);
                //     $mandatory = $mandatory[0];
                //     render("apps/payroll/mandatory_deductions_app/mandatory_employee.php", 
                //     [
                //         "title" => "Mandatory Employee", 
                //         "employee" => $employee, 
                //         "mandatory" => $mandatory, 
                
                //     ],"payroll");
                elseif($_GET["action"] == "done"):
                    $pg = query("select * from payroll_group where barcode = ?", $_GET["barcode"]);
                    $pg = $pg[0];
                    $payroll_actual = query("
                    select pa.*, e.FirstName, e.LastName from payroll_yearend pa
                    left join tblemployee e
                    on pa.employee_id = e.Employeeid
                    where payroll_id = ?
                    order by e.LastName ASC, e.FirstName ASC
                    ", $pg["payroll_id"]);
                    // dump($payroll_actual);
                    render("apps/payroll/payroll_special_app/year_end/year_end_done.php", 
                    [
                        "title" => "Payroll Done", 
                        "payroll_actual" => $payroll_actual, 
                        "pg" => $pg, 
                    ],"payroll");
            endif;
        else:

            $department = query("select * from tbldepartment");
            $payroll_group = query("select pg.*, d.DeptCode from payroll_group pg
                                    left join tbldepartment d
                                    on d.Deptid = pg.department_fund
                                    where 
                                    payroll_design = 'special' and payroll_type = 'YEAR END'
                                    order by year desc");
            // dump($payroll_group);
            render("apps/payroll/payroll_special_app/year_end/year_end_list.php", 
                ["title" => "YEAR END PAYROLL",
                 "payroll_group" => $payroll_group,
                 "department" => $department,
                ],"payroll");
        endif;
       


        
    }
?>