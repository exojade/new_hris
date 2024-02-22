<?php   
// dump($_REQUEST);
use mikehaertl\pdftk\Pdf;  
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "datatable"):
            // dump($_POST);
            
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

            $where = " where 1=1";


            if(checkRole($_SESSION["hris"]["roles"], "AO LEAVE")):
                    $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName, DeptAssignment, Deptid from tblemployee where DeptAssignment = ?", $_SESSION["hris"]["departmentAssignment"]);
                    $where = $where . " and department = '".$_SESSION["hris"]["departmentAssignment"]."'";
            else:

                if(isset($_REQUEST["employee_id"])):
                    $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName, DeptAssignment, Deptid from tblemployee");
                    $where = $where . " and employee_id = '".$_REQUEST["employee_id"]."'";
                else:
                    $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName, DeptAssignment, Deptid from tblemployee");
                endif;
            endif;

            if(isset($_REQUEST["from"])):
                if($_REQUEST["from"] != "")
                    $where = $where . " and date_filed >= '" . $_REQUEST["from"] . "'";
            endif;

            if(isset($_REQUEST["to"])):
                if($_REQUEST["to"] != "")
                    $where = $where . " and date_filed <= '" . $_REQUEST["to"] . "'";
            endif;


            $Dept = [];
            $department = query("select * from tbldepartment");
            foreach($department as $row):
                $Dept[$row["Deptid"]] = $row;
            endforeach;


            $Employees = [];
            foreach($employee as $row):
                $Employees[$row["Employeeid"]] = $row;
            endforeach;
            // $data = query("select * from tblemployee_dtras");

    
                $query_string = "select * from leave_employee
                             ".$where."
                             order by date_filed DESC
                                limit ".$limit." offset ".$offset." ";
                // dump($query_string);
                $data = query($query_string);
                $all_data = query("select * from leave_employee
                    ".$where."
                order by date_filed DESC");
         
            $i=0;
            foreach($data as $row):
                $Employee = $Employees[$row["employee_id"]];
                // dump($Employee);
                $data[$i]["employee"] = $Employees[$row["employee_id"]]["FirstName"] . " " . $Employees[$row["employee_id"]]["LastName"];
                $data[$i]["view"] = '<a href="#" class="btn btn-primary btn-sm btn-block"><i class="fa fa-eye"></i></a>';
                $data[$i]["print"] = '
                                        <form class="generic_form_trigger" data-url="leave_summary">
                                            <input type="hidden" name="leave_id" value="'.$row["leaveId"].'">
                                            <input type="hidden" name="action" value="print_leave">
                                            <button type="submit" class="btn btn-success btn-sm btn-block"><i class="fa fa-print"></i></button>
                                        </form>
                                    ';
                $data[$i]["finger_id"] = $Employee["Fingerid"];
                $data[$i]["department"] = $Dept[$Employee["DeptAssignment"]]["DeptCode"];
                $i++;
            endforeach;


            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );

            echo json_encode($json_data); exit();


            elseif($_POST["action"] == "print_leave"):
                // dump($_POST);


                $leave = query("select l.*, e.FirstName, e.LastName, e.MiddleName, d.DeptCode,
                                e.salary_grade, e.salary_step, e.salary_class,
                                p.PositionName
                                from leave_employee l
                                left join tblemployee e
                                on e.Employeeid = l.employee_id
                                left join tbldepartment d
                                on d.Deptid = e.DeptAssignment
                                left join tblposition p
                                on p.Positionid = l.position
                                where l.leaveId = ?
                                ", $_POST["leave_id"]);

                $leave = $leave[0];

                if($leave["from_time"] == "MORNING"):
                    $leave["from_time"] = "AM";
                else:
                    $leave["from_time"] = "PM";
                endif;

                if($leave["to_time"] == "MORNING"):
                    $leave["to_time"] = "AM";
                else:
                    $leave["to_time"] = "PM";
                endif;

                if($leave["from_date"] == $leave["to_date"]):
                    if($leave["from_time"] != $leave["to_time"])
                    $dateIncluded = $leave["from_date"];
                    else{
                        if($leave["from_time"] == "AM")
                            $dateIncluded = $leave["from_date"] . " AM";
                        else
                            $dateIncluded = $leave["from_date"] . " PM";
                    }
                else:
                    $dateIncluded = $leave["from_date"] . " " . $leave["from_time"] . " - " . $leave["to_date"] . " " . $leave["to_time"];

                endif;  



                $pdf = new Pdf('resources/LEAVE2024.pdf');
                $result = $pdf->fillForm([
                "DeptAssign"    => $leave["DeptCode"],
                "lastname"    => $leave["LastName"],
                "firstname"    => $leave["FirstName"],
                "middlename"    => $leave["MiddleName"],
                "position"    => $leave["PositionName"],
                "salary"    => "P".to_peso($leave["salary"]),
                "vacationLeaveCheck" => ($leave["leave_type"] == 'VACATION LEAVE') ? 'Yes' : 'No',
                "forceLeaveCheck" => ($leave["leave_type"] == 'FORCED LEAVE') ? 'Yes' : 'No',
                "sickLeaveCheck" => ($leave["leave_type"] == 'SICK LEAVE') ? 'Yes' : 'No',
                "maternityLeaveCheck" => ($leave["leave_type"] == 'MATERNITY LEAVE') ? 'Yes' : 'No',
                "paternityLeaveCheck" => ($leave["leave_type"] == 'PATERNITY LEAVE') ? 'Yes' : 'No',
                "specialLeaveCheck" => ($leave["leave_type"] == 'SPECIAL PRIVELEGE LEAVE') ? 'Yes' : 'No',
                "soloParentLeaveCheck" => ($leave["leave_type"] == 'SOLO PARENT LEAVE') ? 'Yes' : 'No',
                "daysCovered"    => $leave["days"],
                "datesIncluded"    => $dateIncluded,
                "asOfDate"    => $leave["date_filed"],
                "asOfVL"    => $leave["asVLBalance"],
                "asOfSL"    => $leave["asSLBalance"],
                "vlDeduct"    => $leave["vl_credits"],
                "slDeduct"    => $leave["sl_credits"],
                "vlBalance"    => $leave["asVLBalance"] - $leave["vl_credits"],
                "slBalance"    => $leave["asSLBalance"] - $leave["sl_credits"],
                "withPay"    => $leave["with_pay"],
                "withOutPay"    => $leave["without_pay"],
                "dateFiled"    => readableDate($leave["date_filed"]),
                // "leaveID"    => $leave["leaveId"],
                ])
                ->flatten()
                ->saveAs("resources/leave/LEAVE.pdf");
                $filename = "LEAVE.pdf";
                $path = "resources/leave/".$filename;



                $qrTempDir = 'file_folder/resources';
                $filePath = $qrTempDir.'/'.uniqid();
                
                QRcode::png($leave["leaveId"], $filePath);
                $qrImage = file_get_contents($filePath);
                



                $mpdf = new \Mpdf\Mpdf();
                $leftMargin = 10;   // Adjust as needed
                $rightMargin = 10;  // Adjust as needed
                $topMargin = 10;    // Adjust as needed
                $bottomMargin = 10; // Adjust as needed

                $mpdf->SetMargins($leftMargin, $rightMargin, $topMargin, $bottomMargin);

                $pageCount = $mpdf->SetSourceFile($path);

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $mpdf->ImportPage($i);
                    $mpdf->AddPage();
                    $mpdf->UseTemplate($tplId);
                    
                    // Add image only on the first page
                    if ($i === 1) {
                        // $logo = $_SERVER['DOCUMENT_ROOT'] . "/hris/resources/bayawanLogo.png";
                        $mpdf->Image($filePath, 160, 10, 25, 0, 'PNG');
                    }
                }

                // Save the modified PDF
                $mpdf->Output($path, 'F');
                unlink($filePath);


                $res_arr = [
                "status" => "success",
                "title" => "Success",
                "option" => "new_tab",
                "message" => "Leave Application done Successfully!",
                "link" => $path,
                ];
                echo json_encode($res_arr); exit();


            elseif($_POST["action"] == "deleteLeave"):
                    // dump($_POST);
                    $leave = query("select * from leave_employee where leaveId = ?", $_POST["leave_id"]);
                    $leave=$leave[0];
                    if($leave["status"] != "DONE"):
                        $res_arr = [
                            "status" => "failed",
                            "title" => "Failed",
                            "option" => "new_tab",
                            "message" => "Leave already approved! Cannot be deleted!",
                            "link" => $path,
                            ];
                            echo json_encode($res_arr); exit();
                    endif;


                    query("delete from leave_employee where leaveId = ?", $_POST["leave_id"]);
                    $res_arr = [
                        "status" => "success",
                        "title" => "Success",
                        "option" => "new_tab",
                        "message" => "Deleted",
                        "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();


 


            endif;


 
      


    }
    else
    {
        if(isset($_GET["action"])):
         
        else:


            render("apps/leave/leave_summary_app/leave_summary_list.php", 
                [
                  "title" => "Leave Summary",
             
              ],"leave");

        endif;
        
    }
?>