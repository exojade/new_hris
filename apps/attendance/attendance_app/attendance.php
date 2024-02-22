<?php   
// ini_set('memory_limit', '4G');
    include('apps/attendance/attendance_app/attendance_functions.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "process_attendance"):
            // dump($_POST);

        $from_date = $_POST["year_process"] . "-" . $_POST["month_process"] . "-" . "01";
        $to_date = date("Y-m-t", strtotime($from_date));

        $_POST["from_date"] = $from_date;
        $_POST["to_date"] = $to_date;

        // dump($to_date);

        if($from_date == "" || $to_date == ""){


            $res_arr = [
                "message" => "Please provide date to be processed",
                "status" => "error",
                "link" => "bio_logs",
                ];
                echo json_encode($res_arr); exit();

        }

        $Attendance = [];
        $string_query = "select * from tblbiologs where Date between '".$from_date."' and '".$to_date."' order by Date ASC, Time ASC";
$attendance = query($string_query);
// dump($string_query);
if(empty($attendance)){
    $res_arr = [
        "message" => "NO BIOLOGS TO BE PROCESSED",
        "status" => "failed",
        "link" => "bio_logs",
        ];
        echo json_encode($res_arr); exit();
}
$bio_id = query("select Fingerid from tblbiologs where Date between '".$from_date."' and '".$to_date."' group by Fingerid");
$Employees = [];
$employees = query("select * from tblemployee");
foreach($employees as $e):
    $Employees[$e["Fingerid"]] = $e;
endforeach;

// dump($Employees);


// $Dtras = [];
// $Dtras_Date = [];
// $dtras = query("select * from tblemployee_dtras");
// $dtras_date = query("select * from tblemployee_dtras_dates where date_info between ? and ? order by date_info ASC", $from_date, $to_date);
// foreach($dtras as $d):
// 	$Dtras[$d["dtras_id"]] = $d;
// endforeach;

// foreach($dtras_date as $d):
//     $fingerid = $Dtras[$d["dtras_id"]]["Fingerid"];
// 	$Dtras_Date[$fingerid][$d["date_info"]] = $d;
//     $Dtras_Date[$fingerid][$d["date_info"]]["Fingerid"] = $fingerid;
// 	$Dtras_Date[$fingerid][$d["date_info"]]["AMArrival"] = $Dtras[$d["dtras_id"]]["AMArrival"];
// 	$Dtras_Date[$fingerid][$d["date_info"]]["AMDeparture"] = $Dtras[$d["dtras_id"]]["AMDeparture"];
// 	$Dtras_Date[$fingerid][$d["date_info"]]["PMArrival"] = $Dtras[$d["dtras_id"]]["PMArrival"];
// 	$Dtras_Date[$fingerid][$d["date_info"]]["PMDeparture"] = $Dtras[$d["dtras_id"]]["PMDeparture"];
// endforeach;

// dump($Dtras_Date);

$Schedules = [];


// $schedules = query("select * from employee_schedule");
// foreach($schedules as $s):
//     $Schedules[$s["Fingerid"]][$s["day"]] = $s;
// endforeach;
// dump($Schedules);


//  dump($Schedules);
foreach($attendance as $a):
    $Attendance[$a["Fingerid"]][$a["Date"]][$a["Time"]] = $a;
endforeach;
// dump($Attendance);

//time regular
$am_in = "08:00";
$am_limit = "10:00";
$am_out = "12:00";
$am_out_limit = "12:30";
$pm_in = "13:00";
$pm_out = "17:00";
$pm_in_limit = "15:00";

$sample_time_array = [];
$employee_involved = [];
// dump($Attendance[$a["Fingerid"]]);
foreach($bio_id as $b):
    array_push($employee_involved, $b);
    foreach($Attendance[$b["Fingerid"]] as $value => $a):
        if(isset($Schedules[$b["Fingerid"]])):
            $FixedAttendance = schedule($Schedules[$b["Fingerid"]], $a);
        else:
            $FixedAttendance = regular_schedule($a);
        endif;

        // dump($FixedAttendance);

       


            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["Fingerid"] = $b["Fingerid"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["Date"] = $FixedAttendance[0]["date"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["time_in_am"] = $FixedAttendance[0]["time_in_am"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["time_out_am"] = $FixedAttendance[0]["time_out_am"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["time_in_pm"] = $FixedAttendance[0]["time_in_pm"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["time_out_pm"] = $FixedAttendance[0]["time_out_pm"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["overtime_in"] = $FixedAttendance[0]["overtime_in"];
            $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["overtime_out"] = $FixedAttendance[0]["overtime_out"];
            // dump($sample_time_array);
            
            // $sample_time_array[$finger_id][$date]["overtime_in"] = $overtime_in;
            // $sample_time_array[$finger_id][$date]["overtime_out"] = $overtime_out;
        //     if($time_in_am != ""){
        //         if($time_in_am > "08:00"){
        //             $number_lates++;
        //             $minutes_lates = $minutes_lates + get_minutes_difference("08:00", $time_in_am, $date);
        //         }
        //     }

        //     if($time_out_am != ""){
        //         if($time_out_am < "12:00"){
        //             $number_lates++;
        //             $minutes_lates = $minutes_lates + get_minutes_difference("12:00", $time_out_am, $date);
        //         }
        //     }

        //     if($time_in_pm != ""){
        //         if($time_in_pm > "13:00"){
        //             $number_lates++;
        //             $minutes_lates = $minutes_lates + get_minutes_difference("13:00", $time_in_pm, $date);
        //         }
        //     }

        //     if($time_out_pm != ""){
        //         if($time_out_pm < "17:00"){
        //             $number_lates++;
        //             $minutes_lates = $minutes_lates + get_minutes_difference("17:00", $time_out_pm, $date);
        //         }
        //     }
        $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["number_lates"] = $FixedAttendance[0]["number_lates"];
        $sample_time_array[$b["Fingerid"]][$FixedAttendance[0]["date"]]["minutes_lates"] = $FixedAttendance[0]["minutes_lates"];
    endforeach;
endforeach;

// dump($sample_time_array);

$inserts = array();
$queryFormat = '("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';
foreach($employee_involved as $e):
    foreach($sample_time_array[$e["Fingerid"]] as $s):
        if(isset($Employees[$s["Fingerid"]])){
            $inserts[] = sprintf( $queryFormat, $Employees[$s["Fingerid"]]["Employeeid"], $s["Date"], $s["time_in_am"], $s["time_out_am"] , $s["time_in_pm"], $s["time_out_pm"], 
                        $s["overtime_in"],$s["overtime_out"],
            $Employees[$s["Fingerid"]]["DeptAssignment"], $Employees[$s["Fingerid"]]["print_remarks"], $Employees[$s["Fingerid"]]["JobType"], $Employees[$s["Fingerid"]]["LastName"], $Employees[$s["Fingerid"]]["FirstName"], $s["Fingerid"],
            $s["number_lates"],$s["minutes_lates"]
        );
        }
    endforeach;
endforeach;

$query = implode( ",", $inserts );
query("delete from tblattendance where Date between '".$from_date."' and '".$to_date."'");


query('insert into tblattendance(Employeeid, Date, AMArrival, AMDeparture, PMArrival, PMDeparture, overtime_in, overtime_out,DeptAssignment, print_remarks, JobType, LastName, FirstName, Fingerid, number_lates, minutes_late) VALUES '.$query);
// query('insert into tblattendance(Employeeid, Date, AMArrival, AMDeparture, PMArrival, PMDeparture,DeptAssignment, print_remarks, JobType, LastName, FirstName, Fingerid, number_lates, minutes_late) VALUES '.$query);




$inserts = array();
        $queryFormat = '("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s")';
        $Dtras = [];
        $dtras = query("select * from tblemployee_dtras d
                        left join tblemployee_dtras_dates dd
                        on dd.dtras_id = d.dtras_id
                        where dd.date_info between ? and ?
                        ", $_POST["from_date"], $_POST["to_date"]);
        foreach($dtras as $d):
            $Dtras[$d["employee_id"]][$d["date_info"]] = $d;
        endforeach;


        $Attendance = [];
        $attendance = query("select * from tblattendance where Date between ? and ?", $_POST["from_date"], $_POST["to_date"]);
        foreach($attendance as $a):
            $Attendance[$a["Employeeid"]][$a["Date"]] = $a;
        endforeach;

        $for_delete = [];

        foreach($dtras as $d):
            if(isset($Attendance[$d["employee_id"]][$d["date_info"]])){

                $attendance = $Attendance[$d["employee_id"]][$d["date_info"]];
                $AMArrival = $d["AMArrival"] != "" ? $d["AMArrival"] : $attendance["AMArrival"];
                $AMDeparture = $d["AMDeparture"] != "" ? $d["AMDeparture"] : $attendance["AMDeparture"];
                $PMArrival = $d["PMArrival"] != "" ? $d["PMArrival"] : $attendance["PMArrival"];
                $PMDeparture = $d["PMDeparture"] != "" ? $d["PMDeparture"] : $attendance["PMDeparture"];
                
                $minutes_late =  $attendance["minutes_late"];
                $number_lates =  $attendance["number_lates"];
                // dump($PMDeparture);
                // if()

                $print_remarks = $Employees[$d["Fingerid"]]["print_remarks"];
                $dept_assignment = $Employees[$d["Fingerid"]]["DeptAssignment"];
                $job_type = $Employees[$d["Fingerid"]]["JobType"];
                $lastname = $Employees[$d["Fingerid"]]["LastName"];
                $firstname = $Employees[$d["Fingerid"]]["FirstName"];

                $for_delete[] = $Attendance[$d["employee_id"]][$d["date_info"]]["Attendanceid"];
                $inserts[] = sprintf( $queryFormat, $d["employee_id"], $d["date_info"], $AMArrival, $AMDeparture, $PMArrival, 
                $PMDeparture, $print_remarks, $dept_assignment, $job_type, $lastname, $firstname, $d["Fingerid"], $minutes_late, $number_lates);
            }
            else{

                $print_remarks = $Employees[$d["Fingerid"]]["print_remarks"];
                $dept_assignment = $Employees[$d["Fingerid"]]["DeptAssignment"];
                $job_type = $Employees[$d["Fingerid"]]["JobType"];
                $lastname = $Employees[$d["Fingerid"]]["LastName"];
                $firstname = $Employees[$d["Fingerid"]]["FirstName"];

                $AMArrival = $d["AMArrival"];
                $AMDeparture = $d["AMDeparture"];
                $PMArrival = $d["PMArrival"];
                $PMDeparture = $d["PMDeparture"];
                $inserts[] = sprintf( $queryFormat, $d["employee_id"], $d["date_info"], $AMArrival, $AMDeparture, $PMArrival, $PMDeparture, $print_remarks, 
                $dept_assignment, $job_type, $lastname, $firstname, $d["Fingerid"], $minutes_late, $number_lates);
            }
        endforeach;
        if(!empty($dtras)){
            $for_delete = "'" . implode("','", $for_delete) . "'";
            query("delete from tblattendance where Attendanceid in (".$for_delete.")");
    
            $query = implode( ",", $inserts );
            query('insert into tblattendance(Employeeid, Date, AMArrival, AMDeparture, PMArrival, PMDeparture, print_remarks, DeptAssignment, JobType, LastName, FirstName, Fingerid, number_lates, minutes_late) VALUES '.$query);
        }

        $res_arr = [
            "message" => "Successfully Processed",
            "status" => "success",
            "link" => "attendance",
            ];
            echo json_encode($res_arr); exit();
 
        elseif($_POST["action"] == "upload_bio"):
        $total_count = count($_FILES['logzips']['name']);
        for( $i=0 ; $i < $total_count ; $i++ ) {
            $filename = $_FILES["logzips"]["name"][$i];
            $source = $_FILES["logzips"]["tmp_name"][$i];
            $type = $_FILES["logzips"]["type"][$i];

            $name = explode(".", $filename);
            $rect = 0;
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed', 'application/octet-stream');
            foreach($accepted_types as $mime_type) {
                if($mime_type == $type) {
                    $rect = 1;
                    break;
                }
            }
            if ($rect != 1){
                $res_arr = [
                    "message" => "Must upload zip or .dat file for required DTR Logs",
                    "status" => "failed",
                    ];
                    echo json_encode($res_arr); exit();
            }
            if ($type=='application/octet-stream')
            {
                $target = "file_folder/attlogs/";
                $target = $target . basename( $_FILES['logzips']['name'][$i]);
                // dump($_FILES['logzips']['name']);
                if(move_uploaded_file($_FILES['logzips']['tmp_name'][$i], $target))
                     { 
                     copy($target, $target);
                        
                     }
            $bool = consolidate_attendance($_FILES['logzips']['name'][$i]);
            // $bool = 1;
            if($bool == 0){
                $res_arr = [
                    "message" => $_FILES['logzips']['name'][$i] . " is problematic",
                    "status" => "error",
                    "link" => "attendance",
                    ];
                    echo json_encode($res_arr); exit();
            }
            }
         }

         $res_arr = [
            "message" => "Successfully Uploaded and converted to RAW Attendance Data",
            "status" => "success",
            "link" => "attendance",
            ];
            echo json_encode($res_arr); exit();
        
            elseif($_POST["action"] == "fetch_option"):
                // dump($_POST);
        
        
                if($_POST["selected_option"] == "department"){
                    $department = query("select Deptid as option_id, concat(DeptCode, ' - ', DeptName) AS option_name from tbldepartment");
                    $res_arr = [
                        "option" => $department,
                        "status" => "success",
                        ];
                        echo json_encode($res_arr); exit();
                }
        
                if($_POST["selected_option"] == "group"){
                    $group = query("select group_id as option_id, concat(group_name) AS option_name from tbl_group");
                    $res_arr = [
                        "option" => $group,
                        "status" => "success",
                        ];
                        echo json_encode($res_arr); exit();
                }
                else{

                }

        elseif($_POST["action"] == "repair_logs"):

            $from_date = $_POST["year"] . "-" . $_POST["month"] . "-01";
            $to_date = $_POST["year"] . "-" . $_POST["month"] . "-31";

            $queryFormat = '("%s","%s","%s","%s","%s","%s","%s","%s","%s")';
            $inserts = array();
            // dump($_POST);
            $Biologs = [];   
            $biologs = query("select * from tblbiologs where Date between ? and ?", $from_date, $to_date);    
            foreach($biologs as $row):
                $Biologs[$row["Fingerid"]][$row["Date"]][$row["Time"]] = $row;
            endforeach;

            $bio=[];
           
            foreach($Biologs as $fingerid):
                foreach($fingerid as $date):
                    foreach($date as $time):
                        $bio[] = $time;
                        // dump($time);
                    endforeach;
                endforeach;
            endforeach;
            // dump($bio);
            // dump(count($bio));






            foreach($bio as $row):
                $inserts[] = sprintf( $queryFormat, $row["Deviceid"], $row["Fingerid"], $row["Date"], $row["Time"] , $row["OutIn"], $row["Month"], 
                                        $row["Day"], $row["Year"], $row["id"]
                    );
            endforeach;
            
            $query = implode( ",", $inserts );
            query("delete from tblbiologs where date between ? and ?", $from_date, $to_date);
            query('insert into tblbiologs(Deviceid, Fingerid, Date, Time, OutIn, Month, Day, Year,id) VALUES '.$query);

            $res_arr = [
                "message" => "Successfully repaired BioLogs",
                "status" => "success",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();

            

        elseif($_POST["action"] == "print_attendance"):

            // dump($_POST);
                    $report = $_POST["report"];
                    $from_date = $_POST["from_date"];
                    $to_date = $_POST["to_date"];
                    $report = $_POST["report"];
                    $sql = query("select * from site_options");
                    $url = $sql[0]["new_site_url"];

                    if($_POST["category"] == "department"){
                        
                        if($_POST["report"] == "dtr"):
                            $dep = query("select * from tbldepartment where Deptid = ?", $_POST["option"]);
                            if($_POST["emp_status"] == 0):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH') group by Employeeid order by LastName ASC, FirstName ASC ");
                            elseif($_POST["emp_status"] == 1):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH') and JobType in ('PERMANENT','COTERMINOUS') group by Employeeid order by LastName ASC, FirstName ASC ");
                            elseif($_POST["emp_status"] == 2):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH') and JobType in ('CASUAL','JOB ORDER', 'HONORARIUM') group by Employeeid order by LastName ASC, FirstName ASC ");
                            endif;
    
                            $the_filename = $dep[0]["DeptCode"];
                            $counter = count($counter);

                        else:

                            $dep = query("select * from tbldepartment where Deptid = ?", $_POST["option"]);
                            if($_POST["emp_status"] == 0):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH', 'TIMESHEET') group by Employeeid order by LastName ASC, FirstName ASC ");
                            elseif($_POST["emp_status"] == 1):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH', 'TIMESHEET') and JobType in ('PERMANENT','COTERMINOUS') group by Employeeid order by LastName ASC, FirstName ASC ");
                            elseif($_POST["emp_status"] == 2):
                                $counter = query("select Employeeid from tblattendance where DeptAssignment = '".$_POST["option"]."' and print_remarks in ('DTR', 'BOTH', 'TIMESHEET') and JobType in ('CASUAL','JOB ORDER', 'HONORARIUM') group by Employeeid order by LastName ASC, FirstName ASC ");
                            endif;
    
                            $the_filename = $dep[0]["DeptCode"];
                            $counter = count($counter);


                        endif;
                        


                       
                    }

                   

                    else if ($_POST["category"] == "employee"){
                        $counter = query("select count(Employeeid) as count, DeptAssignment, LastName, FirstName from tblemployee where Employeeid = ?", $_POST["option"]);
                        $dep = query("select * from tbldepartment where Deptid = ?", $counter[0]["DeptAssignment"]);
                        $the_filename = $counter[0]["LastName"]."_".$counter[0]["FirstName"]."_".$dep[0]["DeptCode"]."_IND";
                        $counter = $counter[0]["count"];
                    }

                    else if($_POST["category"] == "group"){
                        $dep = query("select * from tbl_group where group_id = ?", $_POST["option"]);
                        $counter = query("select count(Employeeid) as count from tblemployee where GroupName = ? and active_status = 1 order by LastName", $_POST["option"]);
                        $the_filename = $dep[0]["group_name"]."_GR";
                        $counter = $counter[0]["count"];
                        // dump($counter);


                    }

                    $serverName = $_SERVER['SERVER_NAME'];
                    if (strpos($serverName, 'localhost') !== false) {
                       $url = "http://localhost:81/hris";
                    }

                    // dump($counter);
                    $the_files = [];
                    try{
                        if($report == "dtr"){
                            // dump($_POST);
                            $the_filename = "DTR_".$the_filename;
                            $orientation = "P";
                            $offsetter=1;
                            while($counter >= 10){
                                $offsetter++;
                                $counter = $counter - 10;
                            }


                           


                            for($i=0; $i<$offsetter;$i++){
                                $the_offset = $i * 10;
                                $webpath = $url . "/attendance?action=generate_dtr&emp_status=".$_POST["emp_status"]."&category=".$_POST["category"]."&option=".$_POST["option"]."&from_date=".$from_date."&to_date=".$to_date."&offset=".$the_offset;
                            //    dump($webpath);
                                $filename = "TEMPDTR-".$_POST["category"]."-".$from_date."-".$to_date."_".$the_offset;
                                $path = "file_folder/DTR/".$filename.".pdf";
                                $exec = '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe" -L 1mm -B 1mm -T 1mm -R 1mm --disable-smart-shrinking --page-size A4 -O Portrait --image-quality 1 --dpi 72 "'.$webpath.'" '.$path.'';
                                // dump($exec);
                                // $origFile = 'book.pdf';
                                exec($exec);
                                $origFile = "file_folder/DTR/".$filename.".pdf";
                                array_push($the_files, $origFile);
                            }
                        }
                        else{
                            $the_filename = "TS_".$the_filename;
                            $orientation = "L";
                            $offsetter=1;
                            while($counter >= 50){
                                $offsetter++;
                                $counter = $counter - 50;
                            }
                            for($i=0; $i<$offsetter;$i++){
                                $the_offset = $i * 50;
                                $webpath = $url . "/attendance?action=generate_timesheet&emp_status=".$_POST["emp_status"]."&category=".$_POST["category"]."&option=".$_POST["option"]."&from_date=".$from_date."&to_date=".$to_date."&offset=".$the_offset;
                                // dump($webpath);
                                $filename = "TEMPTIMESHEET-".$_POST["category"]."-".$from_date."-".$to_date."_".$the_offset;
                                // dump($filename);
                                $path = "file_folder/DTR/".$filename.".pdf";
                                $exec = '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe"  -L 3mm -B 3mm -T 3mm -R 3mm --disable-smart-shrinking --page-size A4 -O Landscape --image-quality 1 --dpi 72 "'.$webpath.'" '.$path.'';
                                // dump($exec);
                                exec($exec);
                                $origFile = "file_folder/DTR/".$filename.".pdf";
                                array_push($the_files, $origFile);
                            }
                        }

                        // dump($the_filename);
                        $job_type = "";
                        if($_POST["category"] == "department"){
                            $job_type = "ALL";
                            if($_POST["emp_status"] == "1"):
                                $job_type = "_PERM_COTI_ELECTIVE";
                            elseif($_POST["emp_status"] == "2"):
                                $job_type = "_CASUAL_JO_HON";
                            endif;
                        }
                        
                        
                        $from = strtotime($from_date);
                        $to = strtotime($to_date);
                        $from = date("F_d_Y", $from);
                        $to = date("F_d_Y", $to);
                        $the_filename = $the_filename . "_" . $from . "_" . $to; 
                        $destFile ="file_folder/DTR/".$the_filename . $job_type .".pdf";
                        $password = $dep[0]["passcode"];
                        $email = $dep[0]["email_address"];
                        // dump($password);
                        pdfEncrypt_array($the_files, $password, $destFile, $orientation);
                        // dump();
                        $activity = $_SESSION["hris"]["fullname"] . " generated " . $destFile;
                        $action = "GENERATE PDF";
                        add_log($activity, $action, $_SESSION["hris"]["employee_id"]);
                        $load[] = array('path'=>$destFile, 'filename' => $destFile, 'result' => 'success', 'email'=> $email);
                        $json = array('info' => $load);
                        echo json_encode($json);
                        exit();
                        //continue outer try block code
                     }
                     catch (Exception $e){
                        $load[] = array('result' => 'failed', 'message' => $e->getMessage());
                        $json = array('info' => $load);
                        echo json_encode($json);
                        exit();
                     }

                     elseif($_POST["action"] == "upload_google_drive"):

                        $google->setAccessToken($_SESSION["hris"]['accessToken']);
                        $file_name = explode(",", $_POST["file_path"]);
                        $file_name = $file_name[0];

                        $whole = explode("/", $file_name);
                        $file_name  = $whole[count($whole) - 1];


                        // dump($file_name);
                        $service = new Google_Service_Drive($google);
                        $content = file_get_contents($_POST["file_path"]);
                        $mime = mime_content_type($_POST["file_path"]);
                        // dump($mime);
                        
                        $fileMetadata = new Google_Service_Drive_DriveFile(array(
                            //Set the Random Filename
                            'name' => $file_name,
                            //Set the Parent Folder
                            'parents' => array($_POST["file_id"]) // this is the folder id
                        ));
                        // dump($fileMetadata);
                        try{
                            // $service->files->delete($file['id']);
                            $newFile = $service->files->create(
                                $fileMetadata,
                                array(
                                    'data' => $content,
                                    'mimeType' => $mime,
                                    'fields' => 'id'
                                )
                            );
                            $res_arr = [
                                "message" => "Successfully Uploaded to Google Drive",
                                "status" => "success",
                                "link" => "not_refresh",
                                ];
                                echo json_encode($res_arr); exit();
                        } catch(Exception $e){
                            $res_arr = [
                                "message" => $e->getMessage(),
                                "status" => "failed",
                                "link" => "not_refresh",
                                ];
                                echo json_encode($res_arr); exit();
                            // header("location:details.php?error=true&msg=".$e->getMessage());
                            // die;
                        }
                
                
        



        elseif($_POST["action"] == "google_drive"):
            
        if(!isset($_SESSION["hris"]["accessToken"])){

            $hint = "";
            $hint = $hint . "
            <a target='_blank' class='btn btn-primary' href='google_login'>Login To Google Drive</a>
            ";

            echo($hint);
        }

        else{
            // dump($_POST);
            $google->setAccessToken($_SESSION["hris"]['accessToken']);
            $service = new Google_Service_Drive($google);
            $site_options = query("select google_folder_id from site_options");
            $folderId = $site_options[0]["google_folder_id"];
            $sheetsList = $service->files->listFiles([
                'q' => "mimeType='application/vnd.google-apps.folder' and '".$folderId."' in parents and trashed=false",
                'fields' => 'nextPageToken, files(id, name)'
              ]);

              $hint = $hint . '
              <table class="table table-striped">';
             foreach($sheetsList as $sheet):
                $hint = $hint .'
                    <tr>
                    <td><div class="box-body box-profile">
                    <h5 class="profile-username">';
                    $hint = $hint . $sheet["name"]. '</h5>';
                    $hint = $hint . '</div></td><td>';
                    $hint = $hint . '<form class="generic_form_trigger" data-url="attendance">';
                    $hint = $hint . '
                    <input type="hidden" name="file_id" value="'.$sheet["id"].'">
                    <input type="hidden" name="action" value="upload_google_drive">
                    <input type="hidden" name="file_path" value="'.$_POST["file_name"].'">
                        <button type="submit" class="btn btn-social-icon btn-bitbucket"><i class="fa fa-upload"></i></button>
                    </form>
                    </td>
                </tr>';
                    endforeach;
                $hint = $hint .'</table>';


                $hint = $hint . "
                <script>
                $('.generic_form_trigger').submit(function(e) {
                    var form = $(this)[0];
                    var formData = new FormData(form);
                    var url = $(this).data('url');
                    e.preventDefault();
                    Swal.fire({
                    title: 'Do you want to submit the changes?',
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    }).then((result) => {
                      if (result.isConfirmed) {
                        Swal.fire({title: 'Please wait...', imageUrl: 'AdminLTE/dist/img/loader.gif', showConfirmButton: false});
                        $.ajax({
                                  type: 'post',
                                  url: url,
                                  data: formData,
                                  processData: false,
                                  contentType: false,
                                  success: function (results) {
                                  var o = jQuery.parseJSON(results);
                                  console.log(o);
                                  if(o.status === 'success') {
                                      Swal.close();
                                      Swal.fire({title: 'Submit success',
                                      text: o.message,
                                      icon: 'success',})
                                      .then(function () {
                  
                                        if(typeof(o.option) != 'undefined' && o.option !== null) {
                                            if(o.option == 'new_tab'){
                                              if(o.link == 'refresh')
                                              window.location.reload();
                                              else if(o.link == 'not_refresh')
                                                console.log('');
                                              else
                                                window.location.replace(o.link, '_blank');
                                            }
                                        }
                                        else{
                                          if(o.link == 'refresh')
                                          window.location.reload();
                                          else if(o.link == 'not_refresh')
                                            console.log('');
                                          else
                                            window.location.replace(o.link);
                  
                                        }
                  
                                        
                                      });
                                  }
                                  else {
                                      Swal.fire({
                                      title: 'Error!',
                                      text: o.message,
                                      icon:'error'
                                      });
                                      console.log(results);
                                  }
                                  },
                                  error: function(results) {
                                  console.log(results);
                                  swal('Error!', 'Unexpected error occur!', 'error');
                                  }
                              });
                      } else if (result.isDenied) {
                      
                      }
                    })
                      });
                </script>
                ";
            echo $hint;
        }


        elseif($_POST["action"] == "locator_datatable"):
            // dump($_REQUEST);

            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];

            if(isset($_REQUEST["employee"])):
                $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
                $where = " and employee_id = '".$_REQUEST["employee"]."'"; 
            else:
                $employee = query("select Employeeid, FirstName, LastName, Fingerid, MiddleName from tblemployee");
            endif;


            if(isset($_REQUEST["from"])):
                if($_REQUEST["from"] != "")
                    $where = $where . " and date_encoded >= '" . $_REQUEST["from"] . "'";
            endif;

            if(isset($_REQUEST["to"])):
                if($_REQUEST["to"] != "")
                    $where = $where . " and date_encoded <= '" . $_REQUEST["to"] . "'";
            endif;


            $Employees = [];
            foreach($employee as $row):
                $Employees[$row["Employeeid"]] = $row;
            endforeach;
            // $data = query("select * from tblemployee_dtras");

            if($where != ""):
                $query_string = "select * from tblemployee_dtras
                             where 1=1 ".$where."
                             order by date_encoded DESC
                                limit ".$limit." offset ".$offset." ";
                // dump($query_string);
                $data = query($query_string);
                $all_data = query("select * from tblemployee_dtras
                where 1=1 ".$where."
                order by date_encoded DESC");
                // $all_data = $data;
            else:
                $query_string = "select * from tblemployee_dtras
                             where 1=1
                             order by date_encoded DESC
                                limit ".$limit." offset ".$offset." ";
                                // dump($query_string);
                $data = query($query_string);
                $all_data = query("select * from tblemployee_dtras");
                // $all_data = $data;
            endif;
            $i=0;
            foreach($data as $row):
                $data[$i]["name"] = $Employees[$row["employee_id"]]["FirstName"] . " " . $Employees[$row["employee_id"]]["LastName"];
                $data[$i]["edit"] = "
                            <a href='javascript:void(0);' onclick='awit(\"".$row["dtras_id"]."\")' class='btn btn-primary btn-flat btn-warning'><i class='fa fa-solid fa-edit'></i></a>
                            ";
                $data[$i]["delete"] = "
                            <a href='javascript:void(0);' onclick='delete_dtras(\"".$row["dtras_id"]."\")' class='btn btn-danger btn-flat'><i class='fa fa-trash'></i></a>
                            ";
                $i++;
            endforeach;
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            echo json_encode($json_data);


            elseif($_POST["action"] == "delete_dtras"):
                query("delete from tblemployee_dtras where dtras_id = ?", $_POST["dtras_id"]);
                query("delete from tblemployee_dtras_dates where dtras_id = ?", $_POST["dtras_id"]);
                $res_arr = [
                            "message" => "Success",
                            "status" => "success",
                            "link" => "attendance?action=locator_slip",
                            ];
                            echo json_encode($res_arr); exit();



            elseif($_POST["action"] == "newDTRAS"):
                // dump($_POST);


        $employee = query("select * from tblemployee where Employeeid = ?", $_POST["employee"]);
        $queryFormat = "('%s','%s','%s')";
        $inserts = array();
        $dtras_id = create_trackid("DTRAS-");
        $date_encoded = date("Y-m-d");
        $date = explode (",", $_POST["date_included"]); 
        foreach($date as $d):
            $dtras_date_id = create_trackid("DTRASDATE-");
            $inserts[] = sprintf( $queryFormat, $dtras_date_id, $dtras_id, $d);
        endforeach;
        // dump($date);

        $query = implode( ",", $inserts );
        $query_string = "insert into tblemployee_dtras_dates
            (dtras_date_id, dtras_id, date_info)
            VALUES " . $query;
            //  dump($query_string);
        query($query_string);
        query("insert INTO tblemployee_dtras (dtras_id, employee_id, reason, date_encoded, AMDeparture, PMArrival, Fingerid, Type)
                    VALUES(?,?,?,?,?,?,?,?)", 
                $dtras_id, $_POST["employee"],  $_POST["reason"] , $date_encoded, $_POST["amout"], $_POST["pmin"],  $employee[0]["Fingerid"], $_POST["type"]);
        $res_arr = [
            "message" => "Success",
            "status" => "success",
            "link" => "refresh",
            ];
            echo json_encode($res_arr); exit();




            elseif($_POST["action"] == "download_lan"):
                $bio = query("select * from biometric_address where biometric_id = ?", $_POST["biometric_id"]);
                $site_options = query("select * from site_options");
                $ip_address = $bio[0]["ip_address"];
                $biometric_file_name = $bio[0]["biometric_name"] . date("Y-m-d") . ".dat";
                $password = $bio[0]["password"];
                $path = $site_options[0]["python_path"] . "basic_test.py";
                

                $serverName = $_SERVER['SERVER_NAME'];
                if (strpos($serverName, 'localhost') !== false) {
                    $path = 'C:\xampp7.3.29\htdocs\hris\lan_library' . "\basic_test.py";
                    $command = escapeshellcmd("python $path $ip_address $biometric_file_name $password");
                    // dump($command);
                } else {
                    $command = escapeshellcmd("C:\Users\Administrator\AppData\Local\Programs\Python\Python311\python $path $ip_address $biometric_file_name $password");
                }
                // dump($command);

                // $command = "C:\Users\Administrator\AppData\Local\Programs\Python\Python311\python --version";
                // dump($command);
        
                try {
                    // Your shell_exec() command goes here
                    // dump($command);
                    $result = shell_exec($command);
                    // dump($result);
                    if ($result === null) {
                        $res_arr = [
                            // "option" => $department,
                            "status" => "error",
                            "message" => 'Command execution failed.',
                            "link" => "bio_logs",
                        ];
                        echo json_encode($res_arr); exit();
                    }
                    consolidate_attendance($biometric_file_name);
                    $res_arr = [
                        // "option" => $department,
                        "status" => "success",
                        "message" => "Downloaded and successfully converted to Biometric Logs",
                        "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
                
                    // Process the result or do something with it
                    // echo "Command output: " . $result;
                } catch (Exception $e) {
                    // Handle the exception
                    $res_arr = [
                        // "option" => $department,
                        "status" => "error",
                        "message" => $e->getMessage(),
                        "link" => "bio_logs",
                    ];
                    echo json_encode($res_arr); exit();
                    // echo "Error: " . $e->getMessage();
                }
        
            elseif($_POST["action"] == "update_dtras"):
                $queryFormat = "('%s','%s','%s')";
                $inserts = array();
                $dtras = query("select e.Fingerid, d.* from tblemployee_dtras d 
                                left join tblemployee e
                                on d.employee_id = e.Employeeid
                                where d.dtras_id = ?", $_POST["dtras_id"]);
                $date = explode (",", $_POST["date_included"]); 
                foreach($date as $d):
                    $dtras_date_id = create_trackid("DTRASDATE-");
                    $inserts[] = sprintf( $queryFormat, $dtras_date_id, $_POST["dtras_id"], $d);
                endforeach;
                $query = implode( ",", $inserts );
                // dump($_POST);   
                query("delete from tblemployee_dtras_dates where dtras_id = ?", $_POST["dtras_id"]);
                $query_string = "insert into tblemployee_dtras_dates
                    (dtras_date_id, dtras_id, date_info)
                    VALUES " . $query;
                query($query_string);
        
                query("update tblemployee_dtras
                        set reason = ?, AMArrival = ?, AMDeparture = ?, PMArrival = ?, PMDeparture = ?, Fingerid = ?, Type = ?
                        where dtras_id = ?", $_POST["reason"], $_POST["am_in"], $_POST["am_out"],
                        $_POST["pm_in"], $_POST["pm_out"], $dtras[0]["Fingerid"], $_POST["dtras_id"], $_POST["type"]);
               
                            redirect("attendance?action=locator_slip");
                elseif($_POST["action"] == "upload_dtras"):
                    // dump($_FILES);
        $Employee = [];
        $employee = query("select * from tblemployee");
        foreach($employee as $e):
            $Employee[$e["Fingerid"]] = $e;
        endforeach;
        $inserts = array();
        $queryFormat = "('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";
        $inserts_temp = array();
        $queryFormat_temp = "('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";

        $inserts_dates = array();
        $queryFormat_dates = "('%s','%s','%s')";

        $fileName = $_FILES["logzips"]["tmp_name"];
        if ($_FILES["logzips"]["size"] > 0) {
            $file = fopen($fileName, "r");
            $i = 0;
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                // dump($column[0]);
                if(isset($Employee[$column[0]])){

                    $dtras_id = create_trackid("DTRAS-");
                    $date_array = explode(",", $column[7]);
                    foreach($date_array as $date):
                        $dtras_date_id = create_trackid("DTRASDATE-");
                        $date = str_replace(" ","",$date);
                        $sec = strtotime($date); 
                        $date = date("Y-m-d", $sec);
                        $inserts_dates[] = sprintf(
                            $queryFormat_dates, $dtras_date_id, $dtras_id,$date);
                    endforeach;

                    $filed = explode(" ",$column[6]);
                    $filed = $filed[0];
                    $sec = strtotime($filed); 
                    $filed = date("Y-m-d", $sec);


                     // $dtras = query("select * from tblemployee_dtras");
                    // foreach($dtras as $row):
                    //     $date = $row["date_encoded"];
                    //     $sec = strtotime($date); 
                    //     $newdate = date("Y-m-d", $sec);
                    //     query("update tblemployee_dtras set date_encoded = ? where dtras_id = ? and employee_id = ?", 
                    //             $newdate, $row["dtras_id"], $row["employee_id"]);
                    // endforeach;





                    $time_in = "";
                    $time_out = "";
                    if($column[8] != "")
                        $time_in = date("h:i",strtotime($column[8]));
                    if($column[9] != "")
                        $time_out = date("h:i",strtotime($column[9]));
                    
                    
                    $inserts[] = sprintf( 
                        $queryFormat, $dtras_id, $Employee[$column[0]]["Employeeid"], $column[10], $filed, "", $time_in, $time_out, "", $column[0], "OFFICIAL");
                }
                else{
                    $inserts_temp[] = sprintf(
                        $queryFormat_temp, $column[0], $column[4], $column[6], $column[7], $column[8], $column[9], $column[10], $column[1], $column[2], $column[3]);
                }
            }
            $query = implode( ",", $inserts );
                $query_string = "insert into tblemployee_dtras
                (dtras_id, employee_id, reason,date_encoded,AMArrival,AMDeparture,PMArrival,PMDeparture, Fingerid, Type) 
                VALUES " . $query;
                // dump($query_string);
                
                query($query_string);
//  dump($query_string);


                $query = implode( ",", $inserts_dates );
                $query_string = "insert into tblemployee_dtras_dates
                (dtras_date_id, dtras_id, date_info) 
                VALUES " . $query;
                
                query($query_string);
                // dump($inserts_temp);
                // dump($query_string);
                if(!empty($inserts_temp)){
                    $query = implode( ",", $inserts_temp );
                $query_string = "insert into temp_dtras_table
                (Fingerid, control_number, date_filed,date_included,time_out,time_in,reason,FirstName,MiddleName,LastName) 
                VALUES " . $query;
                
                query($query_string);
                $res_arr = [
                    "message" => "Successfully Uploaded and converted to RAW Attendance Data",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
                }


                $res_arr = [
                    "message" => "Successfully Uploaded and converted to RAW Attendance Data",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
                }

              
            
            
                    // $output = shell_exec($command);
                    // if(file_exists("public/attlogs/".$biometric_file_name)):
                    //     consolidate_attendance($biometric_file_name);
                    //     $res_arr = [
                    //         // "option" => $department,
                    //         "status" => "success",
                    //         "message" => "Downloaded and successfully converted to Biometric Logs",
                    //         "link" => "bio_logs",
                    //     ];
                    //     echo json_encode($res_arr); exit();
                    // else:
                    //     $res_arr = [
                    //         // "option" => $department,
                    //         "status" => "error",
                    //         "message" => "The Biometric Cannot be reached. Contact IT",
                    //         "link" => "bio_logs",
                    //     ];
                    //     echo json_encode($res_arr); exit();
                    // endif;
               








        endif;
      


    }
    else
    {
        // dump($_GET);

        if(isset($_GET["action"])):
            if($_GET["action"] == "generate_dtr"):
                renderview("apps/attendance/attendance_app/generate_dtr_form.php", 
                [
                ]);
            elseif($_GET["action"] == "generate_timesheet"):
                renderview("apps/attendance/attendance_app/generate_timesheet_form.php", 
                [
                ]);
            elseif($_GET["action"] == "locator_slip"):


                // query("select * from tblemployee_dtras where date_encoded between '2023-09-01' and '2023-10-02'");
                // query("delete from tblemployee_dtras d, tblemployee_dtras_dates dd 
                //     left join dd.dtras_id = d.dtras_id
                
                //  where dd.date_encoded between '2023-09-01' and '2023-10-02'");



                    // $dtras = query("select * from tblemployee_dtras");
                    // foreach($dtras as $row):
                    //     $date = $row["date_encoded"];
                    //     $sec = strtotime($date); 
                    //     $newdate = date("Y-m-d", $sec);
                    //     query("update tblemployee_dtras set date_encoded = ? where dtras_id = ? and employee_id = ?", 
                    //             $newdate, $row["dtras_id"], $row["employee_id"]);
                    // endforeach;





                render("apps/attendance/attendance_app/locator_slip.php", 
                ["title" => "Locator Slip",],"attendance");
            elseif ($_GET["action"] == "modal_edit_dtras"):
                    $dtras = query("select e.FirstName, e.LastName, d.* from tblemployee_dtras d 
                                left join tblemployee e
                                on d.employee_id = e.Employeeid
                                where d.dtras_id = ?", $_GET["dtras_id"]);
                    $dtras = $dtras[0];
                    $name = $dtras["FirstName"] . " " . $dtras["LastName"];
                    // dump($dtras);
                    $dtras_dates = query("select date_info from tblemployee_dtras_dates where dtras_id = ?", $_GET["dtras_id"]);
                    foreach($dtras_dates as $d):
                        $inserts[] = $d["date_info"];
                    endforeach;
                    $dates = implode( ",", $inserts );
                    // dump($dates);

                  
                    renderview("apps/attendance/attendance_app/edit_dtras_form.php", 
                    [
                        "dtras" => $dtras,
                        "name" => $name,
                        "dtras_dates" => $dtras_dates,
                        "dates" => $dates,
                    ]);
                
            endif;
        else:

            $biometric_machines = query("select * from biometric_address");
            render("apps/attendance/attendance_app/attendance_form.php", 
                [
                    "title" => "Attendance",
                    "biometric_machines" => $biometric_machines,
            ],"attendance");

        endif;
       


        
    }
?>