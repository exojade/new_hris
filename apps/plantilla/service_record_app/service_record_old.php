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
                $data=query_access("select * from SRecord where EmpID = ? order by FromDate desc", $_REQUEST["employee"]);
                $all_data=query_access("select * from SRecord where EmpID = ?", $_REQUEST["employee"]);
                $employee = query_access("select * from Employee where EmpID = ?", $_REQUEST["employee"]);
                $Employees = [];
                foreach($employee as $row):
                    $Employees[$row["EmpID"]] = $row;
                endforeach;

                $Stat = [];
                $stat = query_access("select * from Status");
                foreach($stat as $row):
                    $Stat[$row["StatID"]] = $row;
                endforeach;


                $Designation = [];
                $designation = query_access("select * from Designation");
                foreach($designation as $row):
                    $Designation[$row["DesigID"]] = $row;
                endforeach;


                $Branch = [];
                $branch = query_access("select * from Branch");
                foreach($branch as $row):
                    $Branch[$row["BranchID"]] = $row;
                endforeach;


                $Assignment = [];
                $assignment = query_access("select * from Assignment");
                foreach($assignment as $row):
                    $Assignment[$row["AssignID"]] = $row;
                endforeach;
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
                $data[$i]["employee_name"] = $Employees[$row["EmpID"]]["Givenname"] . " " . $Employees[$row["EmpID"]]["Middlename"] . " " . $Employees[$row["EmpID"]]["Surname"] . " " . $Employees[$row["EmpID"]]["Appellation"];
                $data[$i]["employee_name"] =utf8_encode($data[$i]["employee_name"]);
                
                
                $data[$i]["update"] = "
                            <a href='#' data-id='".$row["SRID"]."' data-toggle='modal' data-target='#modalUpdateServiceRecord' class='btn btn-primary btn-flat btn-warning'><i class='fa fa-solid fa-edit'></i></a>
                            ";
                $data[$i]["delete"] = '
                
                    <form class="generic_form_trigger_datatable" data-url="service_record">
                        <input type="hidden" name="action" value="deleteRecord">
                        <input type="hidden" name="SRID" value="'.$row["SRID"].'">
                        <button class="btn btn-danger btn-flat"><i class="fa fa-solid fa-trash"></i></button>
                    </form>
                ';
               
                $data[$i]["from"] = formatDate($row["FromDate"]);
                if($row["ToDate"] == ""):
                    $data[$i]["to"] = "PRESENT";
                else:
                    $data[$i]["to"] = formatDate($row["ToDate"]);
                endif;
                $data[$i]["designation"] = $Designation[$row["DesigID"]]["DesigName"];
                $data[$i]["status"] = $Stat[$row["StatID"]]["StatName"];
                $data[$i]["assignment"] = $Assignment[$row["AssignID"]]["AssignName"];
                $data[$i]["branch"] = $Branch[$row["BranchID"]]["BranchName"];
                $data[$i]["bms"] = to_peso($row["MonthlySalary"]);
                $data[$i]["remarks"] = $row["Remarks"];
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
            query_access("delete from SRecord where SRID = ?", $_POST["SRID"]);
            $res_arr = [
                "message" => "Successfully Deleted",
                "status" => "success",
                "link" => "datatable",
                ];
                echo json_encode($res_arr); exit();
        elseif($_POST["action"] == "modalUpdateServiceRecord"):
            

            $branch = query_access("select * from Branch");
            $designation = query_access("select * from Designation");
            $assignment = query_access("select * from Assignment");
            $status = query_access("select * from Status");


            $service_record = query_access("SELECT s.*, e.Givenname & ' ' & e.Middlename & ' ' & e.Surname & ' ' & e.Appellation AS fullname
            FROM SRecord s
            LEFT JOIN Employee e ON e.EmpID = s.EmpID
            WHERE SRID = ?", $_POST["id"]);
            
            $message = "";
            $message = $message . '
                  <input type="hidden" name="SRID" value="'.$_POST["id"].'">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Employee</label>
                    <input type="text" disabled value="'.$service_record[0]["fullname"].'" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
            ';

            $message = $message . '<div class="row">';
                $message = $message . '<div class="col-md-3">';
                    $message = $message . '
                    <div class="form-group">
                        <label>Designation</label>
                        <select name="designation" required class="form-control">
                          ';  
                        foreach($designation as $row):
                            if($row["DesigID"] == $service_record[0]["DesigID"]):
                                $message = $message . '<option selected value="'.$row["DesigID"].'">'.$row["DesigName"].'</option>';
                            else:
                                $message = $message . '<option value="'.$row["DesigID"].'">'.$row["DesigName"].'</option>';
                            endif; 
                        endforeach;
                        $message= $message .'</select>
                      </div>
                      </div>
                    ';
                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required class="form-control">
                      ';  
                    foreach($status as $row):
                        if($row["StatID"] == $service_record[0]["StatID"]):
                            $message = $message . '<option selected value="'.$row["StatID"].'">'.$row["StatName"].'</option>';
                        else:
                            $message = $message . '<option value="'.$row["StatID"].'">'.$row["StatName"].'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';


                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Assignment</label>
                    <select name="assignment" required class="form-control">
                      ';  
                    foreach($assignment as $row):
                        if($row["AssignID"] == $service_record[0]["AssignID"]):
                            $message = $message . '<option selected value="'.$row["AssignID"].'">'.$row["AssignName"].'</option>';
                        else:
                            $message = $message . '<option value="'.$row["AssignID"].'">'.$row["AssignName"].'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';


                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <select name="branch" required class="form-control">
                      ';  
                    foreach($branch as $row):
                        if($row["BranchID"] == $service_record[0]["BranchID"]):
                            $message = $message . '<option selected value="'.$row["BranchID"].'">'.$row["BranchName"].'</option>';
                        else:
                            $message = $message . '<option value="'.$row["BranchID"].'">'.$row["BranchName"].'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';
            
                $message = $message . '</div>';


                $message = $message . '
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">From</label>
                            <input type="date" class="form-control" name="FromDate" value="'.convertDateFromAccess($service_record[0]["FromDate"]).'" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">To</label>
                            <input type="date" class="form-control" name="ToDate" value="'.convertDateFromAccess($service_record[0]["ToDate"]).'" placeholder="Enter email">
                        </div>
                    </div>
                </div>
                ';


                $message = $message . '
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Salary</label>
                            <input type="text" class="form-control" name="MonthlySalary" value="'.$service_record[0]["MonthlySalary"].'" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Remarks</label>
                            <input type="text" class="form-control" name="Remarks" value="'.$service_record[0]["Remarks"].'" placeholder="---">
                        </div>
                    </div>
                </div>
                ';



            echo($message);

            elseif($_POST["action"] == "updateServiceRecord"):
                // dump($_POST);

                if($_POST["FromDate"] != ""):
                    $_POST["FromDate"] = "#".datetoAccess($_POST["FromDate"])."#";
                endif;

                if($_POST["ToDate"] != ""):
                    $_POST["ToDate"] = "#".datetoAccess($_POST["ToDate"])."#";
                    $string_query = "
                update SRecord set 
                    DesigID = ".$_POST["designation"].",
                    AssignID = ".$_POST["assignment"].",
                    StatID = ".$_POST["status"].",
                    BranchID = ".$_POST["branch"].",
                    FromDate = ".$_POST["FromDate"].",
                    ToDate = ".$_POST["ToDate"].",
                    MonthlySalary = ".$_POST["MonthlySalary"].",
                    Remarks = '".$_POST["Remarks"]."'
                    where SRID = ".$_POST["SRID"]."
                ";
                else:
                    $_POST["ToDate"] = "NULL";
                    $string_query = "
                update SRecord set 
                    DesigID = ".$_POST["designation"].",
                    AssignID = ".$_POST["assignment"].",
                    StatID = ".$_POST["status"].",
                    BranchID = ".$_POST["branch"].",
                    FromDate = ".$_POST["FromDate"].",
                    ToDate = ".$_POST["ToDate"].",
                    MonthlySalary = ".$_POST["MonthlySalary"].",
                    Remarks = '".$_POST["Remarks"]."'
                    where SRID = ".$_POST["SRID"]."
                ";
                endif;

      
                // dump($string_query);


// dump($_POST);
                if (query_access($string_query) === false)
				{
					$res_arr = [
                        "message" => "To Date must be before From Date! Update Not Saved",
                        "status" => "failed",
                        "link" => "datatable",
                        ];
                        echo json_encode($res_arr); exit();
				}
              

                $res_arr = [
                    "message" => "Successfully Added",
                    "status" => "success",
                    "link" => "datatable",
                    ];
                    echo json_encode($res_arr); exit();
        elseif($_POST["action"] == "addServiceRecord"):
            // dump($_POST);

            $check_emp = query_access("select * from Employee where EmpID = ?", $_POST["EmpID"]);
            if(empty($check_emp)):
                $employee = query("select * from tblemployee where Fingerid = ?", $_POST["EmpID"]);
                dump($employee);
            endif;



       
            if($_POST["FromDate"] != ""):
                $_POST["FromDate"] = "#".datetoAccess($_POST["FromDate"])."#";
            endif;

            if($_POST["ToDate"] != ""):
                $_POST["ToDate"] = "#".datetoAccess($_POST["ToDate"])."#";
            else:
                $_POST["ToDate"] = "NULL";
            endif;

            $query_string = "
            insert into SRecord (EmpID, DesigID, AssignID, StatID, BranchID, FromDate, ToDate, MonthlySalary, Remarks, CreatedOn)
            values ('".$_POST["EmpID"]."', ".$_POST["designation"].", ".$_POST["assignment"].", ".$_POST["status"].", ".$_POST["branch"].",  
                        ".$_POST["FromDate"].", ".$_POST["ToDate"].", ".$_POST["MonthlySalary"].", '".$_POST["Remarks"]."',
                        #".datetoAccess(date("Y-m-d"))."#
                        )
            
            ";
            // dump($query_string);

            if (query_access($query_string) === false)
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

     

                $Stat = [];
                $stat = query_access("select * from Status");
                foreach($stat as $row):
                    $Stat[$row["StatID"]] = $row;
                endforeach;


                $Designation = [];
                $designation = query_access("select * from Designation");
                foreach($designation as $row):
                    $Designation[$row["DesigID"]] = $row;
                endforeach;


                $Branch = [];
                $branch = query_access("select * from Branch");
                foreach($branch as $row):
                    $Branch[$row["BranchID"]] = $row;
                endforeach;


                $Assignment = [];
                $assignment = query_access("select * from Assignment");
                foreach($assignment as $row):
                    $Assignment[$row["AssignID"]] = $row;
                endforeach;

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
                // dump($logo);


            $service_records = query_access("select * from SRecord sr
                                        left join Employee e
                                        on e.EmpID = sr.EmpID
                                        where sr.EmpID = ?
                                        order by SRID asc
                                        ", $_POST["employee"]);

            $employee = query_access("select * from Employee where EmpID = ?", $_POST["employee"]);
            $full_name = "";
            $id_no = "";
            $birthdate = "";
            $birth_place = "";

            $datenow =  date('jS') ." day of " . date("F, Y");
            if(!empty($employee)):
            $full_name = $employee[0]["Givenname"] . " " . $employee[0]["Middlename"] . " " . $employee[0]["Surname"] . " " . $employee[0]["Appellation"];
            $id_no = $employee[0]["EmpID"];
            $dateTime = new DateTime($employee[0]["DOB"]);
            $birthdate = $dateTime->format("F d, Y");
            $birth_place = $employee[0]["BirthPlace"];
            endif;

            $mpdf->defaultheaderline = 0;
            $mpdf->defaultfooterline = 0;
            $mpdf->defaultfooterline = 0;
                                        // dump($service_record);
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
                                            // $record["sr_is_per_session"] === 1 ? $record["sr_salary_rate"] = $record["sr_rate_on_schedule"] : "";
                                            $i++;
                                            $record["FromDate"] = datePDF($record["FromDate"]);
                                            $record["ToDate"] = datePDF($record["ToDate"]);
                                            $designation = $Designation[$record["DesigID"]]["DesigName"];
                                            $status = $Stat[$record["StatID"]]["StatName"];
                                            $assignment = $Assignment[$record["AssignID"]]["AssignName"];
                                            $branch = $Branch[$record["BranchID"]]["BranchName"];
                                            $salary = to_peso($record["MonthlySalary"]);
                                        
                                            $html .= <<< EOD
                                            <tr>
                                                <td>$i</td>
                                                <td>$record[FromDate]</td>
                                                <td>$record[ToDate]</td>
                                                <td>$designation</td>
                                                <td>$status</td>
                                                <td>$salary</td>
                                                <td>$assignment</td>
                                                <td>$branch</td>
                                                <td>$record[Remarks]</td>
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

// $mpdf->WriteHTML($html);

                                        endif;
// continue;
                                        // break;
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
                                        
                                            // dump($html);

                                        $mpdf->WriteHTML($html);
                                  
                               
                                        
                                    


                                        $mpdf->Output("resources/service_record.pdf", \Mpdf\Output\Destination::FILE);
            
            // $pdf = new Pdf('resources/service_record_pdf.pdf', [
            //     // 			'command' => 'C:/Program Files (x86)/PDFtk/bin/pdftk.exe',
            //     // // or on most Windows systems:(x86)\PDFtk\bin\pdftk.exe',
            //     // 'useExec' => true, 
            //     // // 'command' => 'C:\Program Files 
            //             ]);

            //     $result = [
            //         "Surname"    => strtoupper($service_record[0]["Surname"]),
            //         "Givenname"  => strtoupper($service_record[0]["Givenname"]),
            //         "Middlename" => strtoupper($service_record[0]["Middlename"]),
            //         "Birthdate"  => strtoupper($service_record[0]["DOB"]),
            //         "BirthPlace" => strtoupper($service_record[0]["BirthPlace"]),
            //         "EmpID" => strtoupper($service_record[0]["EmpID"]),
            //         "DateGenerated" => date("F d, Y"),
            //         // Add more non-repeating fields as needed...
            //     ];


            //     for ($index = 1; $index <= count($service_record); $index++) {
            //         $result["FromDate{$index}"]    = datePDF($service_record[$index-1]["FromDate"]);
            //         $result["ToDate{$index}"]      = datePDF($service_record[$index-1]["ToDate"]);
            //         $result["Designation{$index}"] = strtoupper($Designation[$service_record[$index-1]["DesigID"]]["DesigName"]);
            //         $result["Status{$index}"]      = strtoupper($Stat[$service_record[$index-1]["StatID"]]["StatName"]);
            //         $result["Salary{$index}"]      = to_peso($service_record[$index-1]["MonthlySalary"]);
            //         $result["assignment_{$index}"] = strtoupper($Assignment[$service_record[$index-1]["AssignID"]]["AssignName"]);
            //         $result["branch{$index}"]      = strtoupper($Branch[$service_record[$index-1]["BranchID"]]["BranchName"]);
            //         // $result["lawop{$index}"]       = strtoupper($service_record[$index]["BranchID"]);
            //         $result["remarks{$index}"]     = strtoupper($service_record[$index-1]["Remarks"]);
            //     }
            // $result = $pdf->fillForm($result)
                //   ->needAppearances()
                // ->flatten()
                // ->saveAs("resources/service_record.pdf");


                $res_arr = [
                    "message" => "Successfully Processed",
                    "status" => "success",
                    "link" => "resources/service_record.pdf",
                    "option" => "new_tab",
                    ];
                    echo json_encode($res_arr); exit();


    
            
                // dump("Done");
                // $filename = $burial_contract["contract_id"].".pdf";
                // $path = "reports/".$burial_contract["contract_id"].".pdf";
                // $load[] = array('path'=>$path, 'filename' => $filename, 'result' => 'success');
                // $json = array('info' => $load);
                // echo json_encode($json);


            dump($_POST);
                

        elseif($_POST["action"] == "modalAddServiceRecord"):

            $latest = query_access("select * from SRecord where EmpID = ? order by FromDate desc", $_POST["id"]);

            $Stat = [];
            $status = query_access("select * from Status");
            foreach($status as $row):
                $Stat[$row["StatID"]] = $row;
            endforeach;


            $Designation = [];
            $designation = query_access("select * from Designation");
            foreach($designation as $row):
                $Designation[$row["DesigID"]] = $row;
            endforeach;


            $Branch = [];
            $branch = query_access("select * from Branch");
            foreach($branch as $row):
                $Branch[$row["BranchID"]] = $row;
            endforeach;


            $Assignment = [];
            $assignment = query_access("select * from Assignment");
            foreach($assignment as $row):
                $Assignment[$row["AssignID"]] = $row;
            endforeach;

            $message = "";
            $message = $message . '
            <input type="hidden" name="EmpID" value="'.$_POST["id"].'">
            ';
            
            if(!empty($latest)):
            if($latest[0]["ToDate"] == ""):
                $res_arr = [
                    "title" => "Failed",
                    "status" => "failed",
                    "message" => "The Latest Service record has no End Date, please configure it first before adding new one!",
                    // "option" => "new_tab",
                    ];
                    echo json_encode($res_arr); exit();
            endif;

            $currentDate = $latest[0]["ToDate"]; // Replace this with your current date
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
            $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <select name="branch" required class="form-control">
                      ';  
                    foreach($branch as $row):
                        if($row["BranchID"] == $latest[0]["BranchID"]):
                            $message = $message . '<option selected value="'.$row["BranchID"].'">'.$row["BranchName"].'</option>';
                        else:
                            $message = $message . '<option value="'.$row["BranchID"].'">'.$row["BranchName"].'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';

        
                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Designation</label>
                    <select name="designation" required class="form-control">
                      ';  
                    foreach($designation as $row):
                        if($row["DesigID"] == $latest[0]["DesigID"]):
                            $message = $message . '<option selected value="'.$row["DesigID"].'">'.$row["DesigName"].'</option>';
                        else:
                            $message = $message . '<option value="'.$row["DesigID"].'">'.$row["DesigName"].'</option>';
                        endif; 
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';
            $message = $message . '<div class="col-md-3">';
            $message = $message . '
            <div class="form-group">
                <label>Status</label>
                <select name="status" required class="form-control">
                  ';  
                foreach($status as $row):
                    if($row["StatID"] == $latest[0]["StatID"]):
                        $message = $message . '<option selected value="'.$row["StatID"].'">'.$row["StatName"].'</option>';
                    else:
                        $message = $message . '<option value="'.$row["StatID"].'">'.$row["StatName"].'</option>';
                    endif; 
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';


            $message = $message . '<div class="col-md-3">';
            $message = $message . '
            <div class="form-group">
                <label>Assignment</label>
                <select name="assignment" required class="form-control">
                  ';  
                foreach($assignment as $row):
                    if($row["AssignID"] == $latest[0]["AssignID"]):
                        $message = $message . '<option selected value="'.$row["AssignID"].'">'.$row["AssignName"].'</option>';
                    else:
                        $message = $message . '<option value="'.$row["AssignID"].'">'.$row["AssignName"].'</option>';
                    endif; 
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Monthly Salary</label>
                <input type="text" name="MonthlySalary" class="form-control" value="'.$latest[0]["MonthlySalary"].'">
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
            $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Branch</label>
                    <select name="branch" required class="form-control">
                      ';  
                      $message = $message . '<option selected value="">Please select Branch</option>';
                    foreach($branch as $row):
                            $message = $message . '<option value="'.$row["BranchID"].'">'.$row["BranchName"].'</option>';
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';

        
                $message = $message . '<div class="col-md-3">';
                $message = $message . '
                <div class="form-group">
                    <label>Designation</label>
                    <select name="designation" required class="form-control">
                      ';  
                      $message = $message . '<option selected value="">Please select Designation</option>';
                    foreach($designation as $row):
                            $message = $message . '<option value="'.$row["DesigID"].'">'.$row["DesigName"].'</option>';
                    endforeach;
                    $message= $message .'</select>
                  </div>
                  </div>
                ';
            $message = $message . '<div class="col-md-3">';
            $message = $message . '
            <div class="form-group">
                <label>Status</label>
                <select name="status" required class="form-control">
                  ';  
                  $message = $message . '<option selected value="">Please select Status</option>';
                foreach($status as $row):
                        $message = $message . '<option value="'.$row["StatID"].'">'.$row["StatName"].'</option>';
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';


            $message = $message . '<div class="col-md-3">';
            $message = $message . '
            <div class="form-group">
                <label>Assignment</label>
                <select name="assignment" required class="form-control">
                  ';  
                $message = $message . '<option selected value="">Please select Assignment</option>';
                foreach($assignment as $row):
                        $message = $message . '<option value="'.$row["AssignID"].'">'.$row["AssignName"].'</option>';
                endforeach;
                $message= $message .'</select>
              </div>
              </div>
            ';

            $message = $message . '<div class="col-md-6">';
            $message = $message . '
            <div class="form-group">
                <label>Monthly Salary</label>
                <input type="number" step="0.01" name="MonthlySalary" class="form-control" value="">
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