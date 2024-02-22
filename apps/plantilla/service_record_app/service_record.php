<?php
use mikehaertl\pdftk\Pdf;   


    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "serviceRecordDatatable"):
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = 10;
            $search = $_POST["search"]["value"];
            // dump($_REQUEST);
            if(isset($_REQUEST["employee"])):
                // $data=query_access("select * from SRecord where EmpID = ? order by FromDate desc", $_REQUEST["employee"]);
                $all_data=query("select * from service_record where employee_id = ?", $_REQUEST["employee"]);
                $employee = query("select * from tblemployee where Employeeid = ?", $_REQUEST["employee"]);

                $data=query("select * from service_record where employee_id = ? order by from_date desc", $_REQUEST["employee"]);
                $Employees = [];
                foreach($employee as $row):
                    $Employees[$row["Employeeid"]] = $row;
                endforeach;

                // $Stat = [];
                // $stat = query_access("select * from Status");
                // foreach($stat as $row):
                //     $Stat[$row["StatID"]] = $row;
                // endforeach;


                // $Designation = [];
                // $designation = query_access("select * from Designation");
                // foreach($designation as $row):
                //     $Designation[$row["DesigID"]] = $row;
                // endforeach;


                // $Branch = [];
                // $branch = query_access("select * from Branch");
                // foreach($branch as $row):
                //     $Branch[$row["BranchID"]] = $row;
                // endforeach;


                // $Assignment = [];
                // $assignment = query_access("select * from Assignment");
                // foreach($assignment as $row):
                //     $Assignment[$row["AssignID"]] = $row;
                // endforeach;
                // $where = " and employee_id = '".$_REQUEST["employee"]."'"; 
            else:
                $data=query("select * from tblemployee where Fingerid = 99999999");
                $all_data=query("select * from tblemployee where Fingerid = 99999999");
                // $employee = query_access("select * from Employee");
            endif;

            // dump(count($all_data));


         

            if(!empty($data)):
            $i=0;
            foreach($data as $row):
                // dump($Employees[$row["EmpID"]]);
                // dump($data);
                $data[$i]["employee_name"] = $Employees[$row["employee_id"]]["LastName"] . " " . $Employees[$row["employee_id"]]["MiddleName"] . " " . $Employees[$row["employee_id"]]["LastName"] . " " . $Employees[$row["employee_id"]]["NameExtension"];
                $data[$i]["employee_name"] =utf8_encode($data[$i]["employee_name"]);
                
                
                $data[$i]["update"] = "
                            <a href='#' data-id='".$row["service_record_id"]."' data-toggle='modal' data-target='#modalUpdateServiceRecord' class='btn btn-primary btn-flat btn-warning'><i class='fa fa-solid fa-edit'></i></a>
                            ";
                $data[$i]["delete"] = '
                
                    <form class="generic_form_trigger_datatable" data-url="service_record">
                        <input type="hidden" name="action" value="deleteRecord">
                        <input type="hidden" name="service_record_id" value="'.$row["service_record_id"].'">
                        <button class="btn btn-danger btn-flat"><i class="fa fa-solid fa-trash"></i></button>
                    </form>
                ';
               
                $data[$i]["from"] = formatDate($row["from_date"]);
                if($row["to_date"] == ""):
                    $data[$i]["to"] = "PRESENT";
                else:
                    $data[$i]["to"] = formatDate($row["to_date"]);
                endif;
                // $data[$i]["designation"] = $Designation[$row["DesigID"]]["DesigName"];
                // $data[$i]["status"] = $Stat[$row["StatID"]]["StatName"];
                // $data[$i]["assignment"] = $Assignment[$row["AssignID"]]["AssignName"];
                // $data[$i]["branch"] = $Branch[$row["BranchID"]]["BranchName"];
                $data[$i]["bms"] = to_peso($row["salary"]);
                $data[$i]["remarks"] = $row["remarks"];
                $i++;
            endforeach;
            endif;
            
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            // dump(json_encode($json_data));
            // $encodedString = utf8_encode($accessFetchedString);
            echo json_encode($json_data);

        elseif($_POST["action"] == "deleteRecord"):
            query("delete from service_record where service_record_id = ?", $_POST["service_record_id"]);
            $res_arr = [
                "message" => "Successfully Deleted",
                "status" => "success",
                "link" => "datatable",
                ];
                echo json_encode($res_arr); exit();



        elseif($_POST["action"] == "modalUpdateServiceRecord"):
            
            // dump($_POST);
            // $branch = query_access("select * from Branch");
            // $designation = query_access("select * from Designation");
            // $assignment = query_access("select * from Assignment");
            // $status = query_access("select * from Status");
            $position = query("select * from tblposition");

            $service_record = query("SELECT sr.*, e.FirstName, CONCAT(e.FirstName, ' ', e.MiddleName, ' ', e.LastName, ' ', e.NameExtension) AS fullname 
            FROM service_record sr 
            LEFT JOIN tblemployee e ON e.Employeeid = sr.employee_id 
            WHERE sr.service_record_id = ?
            ", $_POST["id"]);
            // dump($service_record);
            $status = ["CASL", "CONT", "COTR", "ELEC", "EMER", "PERM", "HONORARIUM", "JOBORDER", "CASUAL", "CONTRACTUAL", "COTERMINOUS", "EMERGENCY", "PERMANENT"];

                

            // $service_record = query_access("SELECT s.*, e.Givenname & ' ' & e.Middlename & ' ' & e.Surname & ' ' & e.Appellation AS fullname
            // FROM SRecord s
            // LEFT JOIN Employee e ON e.EmpID = s.EmpID
            // WHERE SRID = ?", $_POST["id"]);
            
            $message = "";
            $message = $message . '
                  <input type="hidden" name="service_record_id" value="'.$_POST["id"].'">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Employee</label>
                    <input type="text" disabled value="'.$service_record[0]["fullname"].'" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
            ';

            $message = $message . '<div class="row">';
                $message = $message . '<div class="col-md-6">';
                    $message = $message . '
                    <div class="form-group">
                        <label>Designation</label>
                        <select style="width: 100% !important;" name="designation" id="designation_select"  class="form-control">
                        <option value="'.$service_record[0]["position"].'" selected>'.$service_record[0]["position"].'</option>
                        ';
                        foreach($position as $row):
                            $message = $message .'
                            <option value="'.$row["PositionName"].'">'.$row["PositionName"].'</option>';
                        endforeach;
                       $message = $message . '</select>
                      </div>
                      </div>
                    ';
                $message = $message . '<div class="col-md-6">';
                $message = $message . '
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required class="form-control">
                      ';  
                    foreach($status as $row):
                        if($row == $service_record[0]["status"]):
                            $message = $message . '<option selected value="'.$row.'">'.$row.'</option>';
                        else:
                            $message = $message . '<option value="'.$row.'">'.$row.'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';


                $message = $message . '<div class="col-md-6">';
                $message = $message . '
                <div class="form-group">
                    <label>Agency</label>
                    <input name="assignment" type="text" class="form-control" value="'.$service_record[0]["assignment"].'">
                  </div>
                  </div>
                ';


                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <input name="branch" type="text" class="form-control" value="'.$service_record[0]["branch"].'">
                  </div>
                </div>
                ';

                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Office</label>
                    <input placeholder="Enter Office Code" name="office_assignment" type="text" class="form-control" value="'.$service_record[0]["office_assignment"].'">
                  </div>
                </div>
                ';
            
                $message = $message . '</div>';


                $message = $message . '
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">From</label>
                            <input required type="date" class="form-control" name="from_date" value="'.$service_record[0]["from_date"].'" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">To</label>
                            <input type="date" class="form-control" name="to_date" value="'.$service_record[0]["to_date"].'" placeholder="Enter email">
                        </div>
                    </div>
                </div>
                ';


                $message = $message . '
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Salary</label>
                            <input type="text" class="form-control" name="MonthlySalary" value="'.$service_record[0]["salary"].'" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Remarks</label>
                            <input type="text" class="form-control" name="Remarks" value="'.$service_record[0]["remarks"].'" placeholder="---">
                        </div>
                    </div>
                </div>
                ';



            echo($message);

            elseif($_POST["action"] == "updateServiceRecord"):
                // dump($_POST);

                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];

                // Check if from_date is set and not empty
                if(!empty($from_date)) {
                    // Convert from_date to Unix timestamp
                    $from_timestamp = strtotime($from_date);

                    // Check if to_date is set and not empty
                    if (!empty($to_date)) {
                        // Convert to_date to Unix timestamp for comparison
                        $to_timestamp = strtotime($to_date);
                        
                        // Ensure to_date is not before from_date
                        if($to_timestamp < $from_timestamp) {
                            $res_arr = [
                                "message" => "To Date must be before From Date! Update Not Saved",
                                "status" => "failed",
                                "link" => "datatable",
                                ];
                                echo json_encode($res_arr); exit();
                            // You can handle this error scenario as per your requirement, like redirecting back with an error message
                        } else {
                            // Continue with your code logic here
                        }
                    } else {
                     
                    }
                } else {
                    $res_arr = [
                        "message" => "To Date must be before From Date! Update Not Saved",
                        "status" => "failed",
                        "link" => "datatable",
                        ];
                        echo json_encode($res_arr); exit();
                }

                $string_query = "update service_record set
                        position = '".$_POST["designation"]."',
                        assignment = '".$_POST["assignment"]."',
                        office_assignment = '".$_POST["office_assignment"]."',
                        status = '".$_POST["status"]."',
                        branch = '".$_POST["branch"]."',
                        from_date = '".$_POST["from_date"]."',
                        to_date = '".$_POST["to_date"]."',
                        salary = '".$_POST["MonthlySalary"]."',
                        remarks = '".$_POST["Remarks"]."'
                        where service_record_id = '".$_POST["service_record_id"]."'
                ";

      
                if (query($string_query) === false)
				{
					$res_arr = [
                        "message" => "To Date must be before From Date! Update Not Saved",
                        "status" => "failed",
                        "link" => "datatable",
                        ];
                        echo json_encode($res_arr); exit();
				}
              

                $res_arr = [
                    "message" => "Successfully Updated",
                    "status" => "success",
                    "link" => "datatable",
                    ];
                    echo json_encode($res_arr); exit();
        elseif($_POST["action"] == "addServiceRecord"):

            $employee = query("select * from tblemployee where Employeeid = ?", $_POST["EmpID"]);
            $employee=$employee[0];

            $query_string = "
            insert into service_record (employee_id, fingerid, position, assignment, status, branch, from_date, to_date, salary, remarks, office_assignment)
            values ('".$_POST["EmpID"]."', '".$employee["Fingerid"]."', '".$_POST["designation"]."', '".$_POST["assignment"]."', '".$_POST["status"]."',  
                        '".$_POST["branch"]."', '".$_POST["FromDate"]."', '".$_POST["ToDate"]."', '".$_POST["MonthlySalary"]."',
                        '".$_POST["Remarks"]."', '".$_POST["office_assignment"]."'
                        )
            ";

            if (query($query_string) === false)
				{
					$res_arr = [
                        "message" => "From Date already existed! Duplication of entry is prohibited!",
                        "status" => "failed",
                        "link" => "refresh",
                        ];
                        echo json_encode($res_arr); exit();
				}
            else;

            $res_arr = [
                "message" => "Success adding Service Record",
                "status" => "success",
                "link" => "datatable",
                ];
                echo json_encode($res_arr); exit();

                
        elseif($_POST["action"] == "print"):

            if($_POST["type_print"] == "SERVICE RECORD"):

                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8', 'format' => 'FOLIO-P',
                    'margin_top' => 15,
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_bottom' => 5,
                    'margin_footer' => 1,
                    'default_font' => 'helvetica'
                ]);

                $mpdf->showImageErrors = true;


            $logo = $_SERVER['DOCUMENT_ROOT'] . "/hris/resources/bayawanLogo.png";
 
            $service_records = query("select sr.*, e.Employeeid from service_record sr left join tblemployee e
                                        on e.Employeeid = sr.employee_id
                                        
                                        where sr.employee_id = ?
                                        and status not in ('HONORARIUM', 'JOBORDER')
                                        order by from_date asc", $_POST["employee"]);

            $employee = query("select * from tblemployee where Employeeid = ?", $_POST["employee"]);
            $full_name = "";
            $id_no = "";
            $birthdate = "";
            $birth_place = "";

            $datenow =  date('jS') ." day of " . date("F, Y");
            if(!empty($employee)):
            $full_name = $employee[0]["FirstName"] . " " . $employee[0]["MiddleName"] . " " . $employee[0]["LastName"] . " " . $employee[0]["NameExtension"];
            $id_no = $employee[0]["Fingerid"];
            $dateTime = new DateTime($employee[0]["BirthDate"]);
            $birthdate = $dateTime->format("F d, Y");
            $birth_place = $employee[0]["BirthPlace"];
            endif;

            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterline = 0;
            $mpdf->defaultfooterline = 0;
                                        $html = <<< EOD
                                        <style>
                                            .p-2 {
                                                padding: 3px;
                                            }
                                            .u {
                                                border-bottom: 1px solid black;
                                            }
                                            .nw {
                                                white-space:nowrap;
                                            }
                                            .w {
                                                width: 250;
                                            }
                                            th,td {
                                                font-size: 10px;
                                            }
                                            .tbl {
                                                width: 100%;
                                                border-collapse: collapse;
                                            }
                                            .tbl tr th {
                                                border: 1px inset grey;
                                            }
                                            .tbl tr td {
                                                border: 1px inset grey;
                                                padding: 3px;
                                            }
                                            .center {
                                                text-align: center;
                                            }
                                            .grey {
                                                background-color: lightgrey;
                                            }
                                        </style>
                                        
                                        <htmlpagefooter name="myFooter2">
                                            <table width="100%" style="border: none; font-size: 9px; font-weight: bold; font-style: italic;">
                                                <tr><td colspan="3" class="u"></td></tr>
                                                <tr>
                                                    <td width="33%">Printed on {DATE F d, Y}</td>
                                                    <td width="33%"></td>
                                                    <td width="33%" style="text-align:right;">Page {PAGENO} of {nbpg}</td>
                                                </tr>
                                            </table>
                                        </htmlpagefooter>
                                        <sethtmlpagefooter name="myFooter2" value="on" force="1" />
                                        <div style="position: fixed; left: 200px; top: 1px;">
                                            <img src="./resources/hr_logo.png" width="60">
                                        </div>
                                        <div style="text-align:center; width: 100%;">
                                            <p>Republic of the Philippines <br>
                                            Province of Davao del Norte <br>
                                            City of Panabo</p>
                                        </div>
                                        <h4 style="text-align: center; padding: 0px; margin: 0px;">SERVICE RECORD</h4>
                                        <table style="font-size: 12px; padding-top: 10px;">
                                            <tr>
                                                <td class="p-2 nw">NAME</td>
                                                <td class="p-2 nw">:</td>
                                                <td class="p-2 nw u w"><strong>$full_name</strong></td>
                                                <td class="p-2 nw">I.D. No</td>
                                                <td class="p-2 nw">:</td>
                                                <td class="p-2 nw u w"><strong>$id_no</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="p-2 nw">BIRTHDAY</td>
                                                <td class="p-2 nw">:</td>
                                                <td class="p-2 nw u w"><strong>$birthdate</strong></td>
                                                <td class="p-2 nw">BIRTH PLACE</td>
                                                <td class="p-2 nw">:</td>
                                                <td class="p-2 nw u w"><strong>$birth_place</strong></td>
                                            </tr>
                                        </table>
                                            <p style="text-indent: 5%; font-size: 12px;">This is to certify that the employee herein shows actually rendered service in this office as shown by the service record below, each line of which
                                            is supported by appointment and other papers actually issued by this Office and approved by authorities concerned.</p>
                                        <sethtmlpageheader name="myHeader" value="on" />
                                        
                                        <table class="tbl">
                                            <tr class="grey">
                                                <th rowspan="2">#</th>
                                                <th colspan="2">SERVICE<br>(inclusive dates)</th>
                                                <th colspan="3">RECORD OF APPOINTMENT</th>
                                                <th colspan="2">OFFICE / ENTITY / DIVISION</th>
                                                <th rowspan="2" width="25%">REMARKS</th>
                                            </tr>
                                            <tr class="grey">
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Designation</th>
                                                <th>Status</th>
                                                <th>Salary</th>
                                                <th>Station / Place of Assignment</th>
                                                <th>Branch</th>
                                            </tr>
                                        EOD;
                                            $i = 0;
                                        foreach ($service_records as $record) {
                                            $i++;
                                            $record["from_date"] = datePDF($record["from_date"]);
                                            $record["to_date"] = datePDF($record["to_date"]);
                                            $designation = $record["position"];
                                            $status = $record["status"];
                                            $assignment = $record["assignment"];
                                            $branch = $record["branch"];
                                            $salary = to_peso($record["salary"]);
                                        
                                            $html .= <<< EOD
                                            <tr>
                                                <td>$i</td>
                                                <td>$record[from_date]</td>
                                                <td>$record[to_date]</td>
                                                <td>$designation</td>
                                                <td>$status</td>
                                                <td>$salary</td>
                                                <td>$assignment</td>
                                                <td>$branch</td>
                                                <td>$record[remarks]</td>
                                            </tr>
                                        EOD;

                                        if($i == 34):

                                            $html .= <<< EOD
                                            <tr>
                                                <td  colspan="9" class="center grey">************************************************* NEXT PAGE *************************************************</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center"></td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong></strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"></td>
                                        </tr>
                                        <tr><td colspan="2" class="center">Issued in compliance with Executive Order No. 54, dated August 10, 1954 and in accordance with Circular No. 58, dated August 10, 1954 of the System.
                                        </td></tr>
                                        <tr><td colspan="2" class="center">Issued this $datenow at Panabo City, Davao del Norte, Philippines.
                                        </td></tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center">Certified Correct:</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong>JAN MARI G. CAFE, MBA</strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%">CGDH I - CHRMO</td>
                                        </tr>
                                        <tr><td colspan="2">
                                        </td></tr>
                                        </table>

                                        EOD;
                                        $mpdf->WriteHTML($html);
                                        $mpdf->AddPage();

                                        $html = <<< EOD
                                        <div style="position: fixed; left: 200px; top: 1px;">
                                        <img src="./resources/hr_logo.png" width="60">
                                        </div>
                                        <div style="text-align:center; width: 100%;">
                                        <p>Republic of the Philippines <br>
                                        Province of Davao del Norte <br>
                                        City of Panabo</p>
                                        </div>
                                        <h4 style="text-align: center; padding: 0px; margin: 0px;">SERVICE RECORD</h4>
                                        <table style="font-size: 12px; padding-top: 10px;">
                                        <tr>
                                            <td class="p-2 nw">NAME</td>
                                            <td class="p-2 nw">:</td>
                                            <td class="p-2 nw u w"><strong>$full_name</strong></td>
                                            <td class="p-2 nw">I.D. No</td>
                                            <td class="p-2 nw">:</td>
                                            <td class="p-2 nw u w"><strong>$id_no</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="p-2 nw">BIRTHDAY</td>
                                            <td class="p-2 nw">:</td>
                                            <td class="p-2 nw u w"><strong>$birthdate</strong></td>
                                            <td class="p-2 nw">BIRTH PLACE</td>
                                            <td class="p-2 nw">:</td>
                                            <td class="p-2 nw u w"><strong>$birth_place</strong></td>
                                        </tr>
                                        </table>
                                        <p style="text-indent: 5%; font-size: 12px;">This is to certify that the employee herein shows actually rendered service in this office as shown by the service record below, each line of which
                                        is supported by appointment and other papers actually issued by this Office and approved by authorities concerned.</p>
                                        <sethtmlpageheader name="myHeader" value="on" />
                                        <table class="tbl">
                                        <tr class="grey">
                                            <th rowspan="2">#</th>
                                            <th colspan="2">SERVICE<br>(inclusive dates)</th>
                                            <th colspan="3">RECORD OF APPOINTMENT</th>
                                            <th colspan="2">OFFICE / ENTITY / DIVISION</th>
                                            <th rowspan="2" width="25%">REMARKS</th>
                                        </tr>
                                        <tr class="grey">
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            <th>Salary</th>
                                            <th>Station / Place of Assignment</th>
                                            <th>Branch</th>
                                        </tr>
                                        EOD;

                                        endif;
                                        }
                                        $html .= <<< EOD
                                            <tr>
                                                <td  colspan="9" class="center grey">************************************************* nothing follows *************************************************</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                    <tr><td colspan="2" class="center"></td></tr>
                                    <tr>
                                        <td></td>
                                        <td class="center nw" width="50%"><strong></strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="center nw" width="50%"></td>
                                    </tr>
                                    <tr><td colspan="2" class="center">Issued in compliance with Executive Order No. 54, dated August 10, 1954 and in accordance with Circular No. 58, dated August 10, 1954 of the System.
                                    </td></tr>
                                    <tr><td colspan="2" class="center">Issued this $datenow at Panabo City, Davao del Norte, Philippines.
                                    </td></tr>
                                    </table>
                                    <table width="100%" style="padding-top: 20px;">
                                    <tr><td colspan="2" class="center">Certified Correct:</td></tr>
                                    <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                    <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                    <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                    <tr>
                                        <td></td>
                                        <td class="center nw" width="50%"><strong>JAN MARI G. CAFE, MBA</strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="center nw" width="50%">CGDH I - CHRMO</td>
                                    </tr>
                                    <tr><td colspan="2">
                                    </td></tr>
                                    </table>
                                    EOD;
                                        
                $mpdf->WriteHTML($html);
                                  
                $mpdf->Output("resources/service_record.pdf", \Mpdf\Output\Destination::FILE);

                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "resources/service_record.pdf",
                    "option" => "new_tab",
                    ];
                    echo json_encode($res_arr); exit();

            elseif($_POST["type_print"] == "COE"):
                // dump($_POST);
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8', 'format' => 'FOLIO-P',
                    'margin_top' => 15,
                    'margin_left' => 5,
                    'margin_right' => 5,
                    'margin_bottom' => 5,
                    'margin_footer' => 1,
                    'default_font' => 'helvetica'
                ]);

            $mpdf->showImageErrors = true;
          
            $salary = query("select * from tbl_salary_sched ss
                            left join tbl_salary_grade sg
                            on sg.salary_schedule_id = ss.salary_schedule_id
                            where ss.status = 'active'");

            $service_records = query("select sr.*, e.Employeeid from service_record sr left join tblemployee e
                                        on e.Employeeid = sr.employee_id
                                        where sr.employee_id = ?
                                        order by from_date desc", $_POST["employee"]);

            $employee = query("select e.*, p.PositionName, d.DeptName from tblemployee e left join tblposition p
            on p.Positionid = e.Positionid
            left join tbldepartment d
            on d.Deptid = e.Deptid
            
            where Employeeid = ?", $_POST["employee"]);
            
            


            // $salary = salary($);
            $full_name = "";
            $id_no = "";
            $birthdate = "";
            $birth_place = "";

            $datenow =  date('jS') ." day of " . date("F, Y");
            if(!empty($employee)):
            $full_name = $employee[0]["FirstName"] . " " . $employee[0]["MiddleName"] . " " . $employee[0]["LastName"] . " " . $employee[0]["NameExtension"];
            $id_no = $employee[0]["Fingerid"];
            $dateTime = new DateTime($employee[0]["BirthDate"]);
            $birthdate = $dateTime->format("F d, Y");
            $birth_place = $employee[0]["BirthPlace"];
            $position = $employee[0]["PositionName"];
            $department = $employee[0]["DeptName"];
            endif;

            $full_name = $_POST["name_prefix"] . $full_name;
            // dump($full_name);


            if($employee[0]["JobType"] != "JOB ORDER" || $employee[0]["JobType"] == "HONORARIUM"):
                $my_salary = to_peso(salary($salary, "", $employee[0]["salary_grade"], $employee[0]["salary_step"], $employee[0]["salary_class"]));
                // $salary_type = ""
                $message = "THIS IS TO CERTIFY that as per record in this Office, <b>$full_name</b> is employed by the City Government of Panabo with present appointment as <b>$position</b>
                at the <b>$department</b> with a daily rate of <b>₱$my_salary</b>";
                
            else:
                if($employee[0]["salary_option"] == "" || $employee[0]["salary_option"] == "DAILY"):
                    $message = "THIS IS TO CERTIFY that as per record in this Office, <b>$full_name</b> is employed by the City Government of Panabo with present appointment as <b>$position</b>
                    at the <b>$department</b> with a daily rate of <b>₱".to_peso($employee[0]["salary"])."</b>";
                else:
                    $message = "THIS IS TO CERTIFY that as per record in this Office, <b>$full_name</b> is employed by the City Government of Panabo with present appointment as <b>$position</b>
                    at the <b>$department</b> with a rate of <b>₱".to_peso($employee[0]["salary"])." / hr</b>.";
                endif;
            endif;

            $message2 = "";
            if($employee[0]["Gender"] == "FEMALE"):
                $message2.="Below is the inclusive period of her employment with the corresponding related information, to wit:";
            else:
                $message2.="Below is the inclusive period of his employment with the corresponding related information, to wit:";
            endif;

            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterline = 0;
            $mpdf->defaultfooterline = 0;
                                        $html = <<< EOD
                                        <style>
                                            .p-2 {
                                                padding: 3px;
                                            }
                                            .u {
                                                border-bottom: 1px solid black;
                                            }
                                            .nw {
                                                white-space:nowrap;
                                            }
                                            .w {
                                                width: 250;
                                            }
                                            th,td {
                                                font-size: 13px;
                                            }
                                            .tbl {
                                                width: 100%;
                                                border-collapse: collapse;
                                            }
                                            .tbl tr th {
                                                border: 1px inset grey;
                                            }
                                            .tbl tr td {
                                                border: 1px inset grey;
                                                padding: 3px;
                                            }
                                            .center {
                                                text-align: center;
                                            }
                                            .grey {
                                                background-color: lightgrey;
                                            }
                                        </style>
                                        <sethtmlpagefooter name="myFooter2" value="on" force="1" />
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <h4 style="text-align: center; padding: 0px; margin: 0px; font-size:30px;">CERTIFICATION</h4>
                                        <p style="text-indent: 5%; font-size: 15px; text-align: justify;">
                                        $message.
                                        </p>
                                        <p style="text-indent: 5%; font-size: 15px; text-align: justify;">
                                        $message2.
                                        </p>

                                       
                                        <sethtmlpageheader name="myHeader" value="on" />
                                        
                                        <table class="tbl">
                                            <tr class="grey">
                                                <th>Inclusive Period</th>
                                                <th>Designation</th>
                                                <th>Employment Status</th>
                                                <th>Office Assignment</th>
                                            </tr>
                                        EOD;
                                            $i = 0;
                                        foreach ($service_records as $record) {
                                            $i++;
                                            $record["from_date"] = datePDFCOE($record["from_date"]);
                                            $record["to_date"] = datePDFCOE($record["to_date"]);
                                            $designation = $record["position"];
                                            $status = $record["status"];
                                            $assignment = $record["assignment"];
                                            $office_assignment = $record["office_assignment"];
                                            $branch = $record["branch"];
                                            $salary = to_peso($record["salary"]);
                                        
                                            $html .= <<< EOD
                                            <tr>
                                                <td>$record[from_date] - $record[to_date]</td>
                                                <td>$designation</td>
                                                <td>$status</td>
                                                <td>$office_assignment</td>
                                            </tr>
                                        EOD;

                                        if($i == 34):
                                            $html .= <<< EOD
                                            <tr>
                                                <td  colspan="4" class="center grey">************************************************* NEXT PAGE *************************************************</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center"></td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong></strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"></td>
                                        </tr>
                                        <tr><td colspan="2" class="center">This certification is being issued upon the request of the above-mentioned employee for <b>$_POST[coe_input]</b>.
                                        </td></tr>
                                        <tr><td colspan="2" class="center">Issued this <b>$datenow</b> at Panabo City, Davao del Norte, Philippines.
                                        </td></tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center">Certified Correct:</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong>JAN MARI G. CAFE, MBA</strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%">CGDH I - CHRMO</td>
                                        </tr>
                                        <tr><td colspan="2">
                                        </td></tr>
                                        </table>
                                        EOD;
                                        $mpdf->WriteHTML($html);
                                        $mpdf->AddPage();

                                        $html = <<< EOD
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <h4 style="text-align: center; padding: 0px; margin: 0px;">CERTIFICATION</h4>
                                            <p style="text-indent: 5%; font-size: 12px;">
                                            $message.</p>
                                            <p style="text-indent: 5%; font-size: 12px;">
                                            $message2.
                                            </p>
                                        <sethtmlpageheader name="myHeader" value="on" />
                                        
                                        <table class="tbl">
                                            <tr class="grey">
                                                <th>Inclusive Period</th>
                                                <th>Designation</th>
                                                <th>Employment Status</th>
                                                <th>Office Assignment</th>
                                            </tr>
                                        EOD;

                                        endif;
                                        }
                                        $html .= <<< EOD
                                            <tr>
                                                <td  colspan="4" class="center grey">***************************** nothing follows *****************************</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center"></td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong></strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"></td>
                                        </tr>
                                        <tr><td colspan="2" class="center">This certification is being issued upon the request of the above-mentioned employee for <b>$_POST[coe_input]</b>.
                                        </td></tr>
                                        <tr><td colspan="2" class="center">Issued this $datenow at Panabo City, Davao del Norte, Philippines.
                                        </td></tr>
                                        </table>
                                        <table width="100%" style="padding-top: 20px;">
                                        <tr><td colspan="2" class="center">Certified Correct:</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr><td colspan="2" class="center">&nbsp;</td></tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%"><strong>JAN MARI G. CAFE, MBA</strong></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td class="center nw" width="50%">CGDH I - CHRMO</td>
                                        </tr>
                                        <tr><td colspan="2">
                                        </td></tr>
                                        </table>
                                        EOD;

                $mpdf->WriteHTML($html);

                $mpdf->Output("resources/coe.pdf", \Mpdf\Output\Destination::FILE);
            
                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "resources/coe.pdf",
                    "option" => "new_tab",
                    ];
                    echo json_encode($res_arr); exit();

            endif;
                

        elseif($_POST["action"] == "modalAddServiceRecord"):
            // dump($_POST);
            // $latest = query("select * from service_record where ");
            // $latest = query_access("select * from SRecord where Fingerid = ? order by FromDate desc", $_POST["id"]);
            $latest = query("select * from service_record where employee_id = ? order by from_date desc", $_POST["id"]);
            // dump($latest);
            // $Stat = [];
            // $status = query_access("select * from Status");
            // foreach($status as $row):
            //     $Stat[$row["StatID"]] = $row;
            // endforeach;


            $status = ["CASL", "CONT", "COTR", "ELEC", "EMER", "PERM", "HONORARIUM", "JOBORDER", "CASUAL", "CONTRACTUAL", "COTERMINOUS", "EMERGENCY", "PERMANENT"];


            $position = query("select * from tblposition");



            $message = "";
            $message = $message . '
            <input type="hidden" name="EmpID" value="'.$_POST["id"].'">
            ';
            
            if(!empty($latest)):
            // if($latest[0]["to_date"] == ""):
            //     $res_arr = [
            //         "title" => "Failed",
            //         "status" => "failed",
            //         "message" => "The Latest Service record has no End Date, please configure it first before adding new one!",
            //         // "option" => "new_tab",
            //         ];
            //         echo json_encode($res_arr); exit();
            // endif;

            $currentDate = $latest[0]["to_date"]; // Replace this with your current date
            $nextDay = date("Y-m-d", strtotime($currentDate . "+1 day"));
            
            // dump($latest[0]);
            $message = $message . '
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Date</label>
                        <input type="date" name="FromDate" value="'.$nextDay.'" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">To Date</label>
                        <input type="date" name="ToDate" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                </div>
            </div>
            ';
            $message = $message . '<div class="row">';
            

        
                $message = $message . '<div class="col-md-6">';
                $message = $message . '
                <div class="form-group">
                    <label>Designation</label>
                    <select style="width: 100%;" name="designation" id="designation_select2"  class="form-control">
                    <option selected value="'.$latest[0]["position"].'">'.$latest[0]["position"].'</option>
                    <option value="">NONE</option>
                    
                      ';  
                    foreach($position as $row):
                            $message = $message . '<option value="'.$row["PositionName"].'">'.$row["PositionName"].'</option>';
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';
            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Status</label>
                <select name="status" required class="form-control">
                  ';  
                foreach($status as $row):
                    if($row == $latest[0]["status"]):
                        $message = $message . '<option selected value="'.$row.'">'.$row.'</option>';
                    else:
                        $message = $message . '<option value="'.$row.'">'.$row.'</option>';
                    endif; 
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Agency</label>
                <input class="form-control" type="text" value="'.$latest[0]["assignment"].'" name="assignment">
              </div>
              </div>
            ';
            $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <input class="form-control" type="text" name="branch" value="'.$latest[0]["branch"].'">
                  </div>
                  </div>
                ';

                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Office</label>
                    <input placeholder="Enter Office Code" class="form-control" type="text" name="office_assignment" value="'.$latest[0]["office_assignment"].'">
                  </div>
                  </div>
                ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Monthly Salary</label>
                <input type="text" name="MonthlySalary" class="form-control" value="'.$latest[0]["salary"].'">
              </div>
              </div>
            ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Remarks</label>
                <input placeholder="Remarks" type="text" name="Remarks" class="form-control" >
              </div>
              </div>
            ';


      

            $message = $message . '</div>';

            $res_arr = [
                "title" => "Success",
                "status" => "success",
                "message" => $message,
                // "option" => "new_tab",
                ];
                echo json_encode($res_arr); exit();
            
            else:
                // dump($latest);

                $message = $message . '
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">From Date</label>
                        <input type="date" name="FromDate" value="" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">To Date</label>
                        <input type="date" name="ToDate" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                    </div>
                </div>
            </div>
            ';
            $message = $message . '<div class="row">';
            

        
                $message = $message . '<div class="col-md-6">';
                $message = $message . '
                <div class="form-group">
                    <label>Designation</label>
                    <select style="width:100%;" name="designation" id="designation_select2"  class="form-control">
                    <option value="" selected>NONE</option>
                      ';  
                      $message = $message . '<option selected disabled value="">Please select Designation</option>';
                    foreach($position as $row):
                            $message = $message . '<option value="'.$row["PositionName"].'">'.$row["PositionName"].'</option>';
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';
            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Status</label>
                <select name="status" required class="form-control">
                  ';  
                  $message = $message . '<option selected value="">Please select Status</option>';
                foreach($status as $row):
                        $message = $message . '<option value="'.$row.'">'.$row.'</option>';
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';

            


            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Agency</label>
                <input type="text" placeholder="CITY GOVERNMENT OF PANABO" name="assignment" class="form-control">
                  ';  
                
                $message= $message .'
              </div>
              </div>
            ';


            $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <input placeholder="LGU" type="text" name="branch" class="form-control">
                  </div>
                  </div>
                ';


                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Office</label>
                    <input placeholder="Enter Office Code" type="text" name="office_assignment" class="form-control">
                  </div>
                  </div>
                ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Monthly Salary</label>
                <input type="number" placeholder="Enter Salary" step="0.01" name="MonthlySalary" class="form-control" value="">
              </div>
              </div>
            ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Remarks</label>
                <input placeholder="Remarks" type="text" name="Remarks" class="form-control" >
              </div>
              </div>
            ';


      

            $message = $message . '</div>';

            $res_arr = [
                "title" => "Success",
                "status" => "success",
                "message" => $message,
                // "option" => "new_tab",
                ];
                echo json_encode($res_arr); exit();


            endif;


        endif;

        

 
      


    }
    else
    {

        // dump("yawa");

        if(isset($_GET["action"])):

          if($_GET["action"] == "details"):

          endif;
        else:

            

            render("apps/plantilla/service_record_app/service_record_details.php", 
                ["title" => "Service Record",],"records");
        endif;
        
    }
?>