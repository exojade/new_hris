<?php

    /* -------------------------------------------------
		City Administrators Office - I.T. Section
	--------------------------------------------------- */

    require_once("constants.php");
    include 'phpqrcode/qrlib.php';
    // include 'php-weasyprint/pdf.php';
    include('fpdi/FPDI_Protection.php');
    include('fpdf_merge/fpdf_merge.php');

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function generateLeaveID($department){
        $prefix = "LEAVE";
        $department = query("select * from tbldepartment where Deptid = ?", $department);
        $yearPart = date("Y");
        $monthPart = date("m");

        $maxExistingID = query("SELECT MAX(SUBSTRING(leaveId, -5)) as maxID 
        FROM leave_employee l WHERE department = ? AND l.year = ? AND l.month = ?", $department[0]["Deptid"], $yearPart, $monthPart);
        
        $nextIncrement = ($maxExistingID[0]["maxID"] != "") ? (int)$maxExistingID[0]["maxID"] + 1 : 1;
        // dump($nextIncrement);
        $incrementPart = sprintf('%05d', $nextIncrement);
        $leaveID = $prefix . $department[0]["DeptCode"] . $yearPart . $monthPart . $incrementPart;
        return $leaveID;

    }

    function checkRole($arrayRole, $desiredRole){
        $exists = 0;
        foreach ($arrayRole as $role) {
            if ($role['role'] === $desiredRole) {
                $exists = 1;
                break;
            }
        }
        return $exists;
    }



     function datePDF($dateString) {
        if($dateString != ""):
        $dateTime = new DateTime($dateString);
        return $dateTime->format("m/d/Y");
        else:
            return "PRESENT";
        endif;
    }


    function datePDFCOE($dateString) {
        if($dateString != ""):
        $dateTime = new DateTime($dateString);
        return $dateTime->format("M d, Y");
        else:
            return "PRESENT";
        endif;
    }


     function convertDateFromAccess($dateString) {
        // Create a DateTime object from the input date string
        $dateTime = new DateTime($dateString);
    
        // Format the date as "Y-m-d"
        $formattedDate = $dateTime->format("Y-m-d");
    
        // Return the formatted date
        return $formattedDate;
    }


    function datetoAccess($dateString) {
        $dateTime = new DateTime($dateString);
        return $dateTime->format("Y/m/d");
    }

    

    function get_month_array(){
        $Month = [];
        $Month[0]["Full"] = "January";
        $Month[0]["month"] = "01";

        $Month[1]["Full"] = "February";
        $Month[1]["month"] = "02";

        $Month[2]["Full"] = "March";
        $Month[2]["month"] = "03";

        $Month[3]["Full"] = "April";
        $Month[3]["month"] = "04";

        $Month[4]["Full"] = "May";
        $Month[4]["month"] = "05";

        $Month[5]["Full"] = "June";
        $Month[5]["month"] = "06";

        $Month[6]["Full"] = "July";
        $Month[6]["month"] = "07";

        $Month[7]["Full"] = "August";
        $Month[7]["month"] = "08";

        $Month[8]["Full"] = "September";
        $Month[8]["month"] = "09";

        $Month[9]["Full"] = "October";
        $Month[9]["month"] = "10";

        $Month[10]["Full"] = "November";
        $Month[10]["month"] = "11";

        $Month[11]["Full"] = "December";
        $Month[11]["month"] = "12";

        return $Month;
    }

    function to_full_month($month){
        $date = "Y-".$month."-d";
        $new = date("F", strtotime(date($date)));
        return($new);
    }


    function generate_barcode(){
        $year = date("Ym");
        $number = rand(0,9999);
// echo(str_pad($number,4,0,STR_PAD_LEFT));
        $barcode = $year.str_pad($number,4,0,STR_PAD_LEFT);
        return $barcode;

    }



    function getWorkingDays($startDate,$endDate,$holidays){
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
    
        $days = ($endDate - $startDate) / 86400 + 1;
    
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);
    
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);
    
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            if ($the_first_day_of_week == 7) {
                $no_remaining_days--;
    
                if ($the_last_day_of_week == 6) {
                    $no_remaining_days--;
                }
            }
            else {
                $no_remaining_days -= 2;
            }
        }
    
       $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
          $workingDays += $no_remaining_days;
        }
    
        foreach($holidays as $holiday){
            $time_stamp=strtotime($holiday);
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                $workingDays--;
        }
        return $workingDays;
    }

    function gsis_ps($salary,$lwop,$days){
        $lwop = to_amount($lwop);
        $retirement_share = number_format((float)$salary * 0.07 * ($days - $lwop) / $days, 2, '.','');
        $life_share = number_format((float)$salary * 0.02, 2, '.','');
        $life_gov_share = number_format((float)$salary * 0.02 * ($lwop / $days), 2, '.','');
        $personal_share = $retirement_share + $life_share + $life_gov_share;
        // dump($personal_share);
        return $personal_share;
    }

    function gsis_gs($salary,$lwop,$days){
        $lwop = to_amount($lwop);
        $retirement_share = number_format((float)$salary * 0.1 * ($days - $lwop) / $days, 2, '.','');
       
        $life_gov_share = number_format((float)$salary * 0.02 * ($days - $lwop) / $days, 2, '.','');
        $government_share = $retirement_share  + $life_gov_share;
        // dump($personal_share);
        return $government_share;
    }




     function consolidate_attendance($filepath_dat){
        // dump($_POST);
        $bool = 0;
        $q = $filepath_dat;
        if ($q !== ""){
            $foldername = "file_folder/attlogs";
            $attendanceData = file_get_contents(getcwd() . "/file_folder/attlogs/" . $q);
            $arrAttendance = explode("\r\n", $attendanceData);
            $i = 1;
            $j = 1;
            $inserts = array();
            $queryFormat = '("%s","%s","%s","%s","%s")';
        foreach ($arrAttendance as $attendance) {
          $arrAttendanceLine = explode("\t", trim($attendance));
        //   dump($arrAttendanceLine);
          if ($attendance == NULL)
              continue;
          else
          {
          $fingerid = $arrAttendanceLine[0];
          $timeLog = $arrAttendanceLine[1];
          $deviceid = $arrAttendanceLine[2];
          $inout = $arrAttendanceLine[3];
          $timelog = explode(" ", trim($timeLog));
          $timelogDate = $timelog[0];
          $timelogTime = $timelog[1];
          $timelogdater = explode("-", trim($timelogDate));
          $year = $timelogdater[0];
          $month = $timelogdater[1];
          $day = $timelogdater[2];
          $inserts[] = sprintf( $queryFormat, $deviceid, $fingerid, $timelogDate, $timelogTime , $inout);
          }
        }
        $query = implode( ",", $inserts );
        $query_string = 'insert INTO tblbiologs(Deviceid, Fingerid, Date, Time, OutIn) VALUES '.$query;
        // dump($query_string);
        try {
            query($query_string);
            $value = "public/attlogs/" . $q;
            $files = glob($value);
            foreach($files as $file){
              if(is_file($file))
                unlink($file);
            }


            $bool = 1;
            return $bool;
            // $res_arr = [
            //     "message" => "Success",
            //     "status" => "success",
            //     "link" => "bio_logs",
            //     ];
            //     echo json_encode($res_arr); exit();


          }

          catch(Exception $e) {
            $bool = 0;
            return $bool;
            // $res_arr = [
            //     "message" => "'Message: ' .$e->getMessage();",
            //     "status" => "success",
            //     "link" => "bio_logs",
            //     ];
            //     echo json_encode($res_arr); exit();

          }


        $res_arr = [
            "message" => "<h1>" . $i . " lines have been added <br> ".$j." not added</h1>",
            "status" => "success",
            "link" => "bio_logs",
            ];
            echo json_encode($res_arr); exit();
            // $hint = "<h1>" . $i . " lines have been added <br> ".$j." not added</h1>";
    }

     }

     function isweekend($date){
        $date = strtotime($date);
        $date = date("l", $date);
        $date = strtoupper($date);

        if($date == "SATURDAY" || $date == "SUNDAY") {
            return $date;
        } else {
            return "false";
        }
    }


    function mail_action_dtrs($date_from, $date_to){

        // $_COOKIE = 
        $date_from = date("F d Y", strtotime($date_from));
        $date_to = date("F d Y", strtotime($date_to));

        $Emails = [];
        $emails = query("select * from tbldepartment group by email_address");
        foreach($emails as $e):
             if (filter_var($e["email_address"], FILTER_VALIDATE_EMAIL))
                    $Emails[] = $e["email_address"];
        endforeach;
        foreach($Emails as $e):
            echo($e . ";");
        endforeach;
        exit();
        // dump($Emails);

        // $Emails = [];
        // $Emails[] = "tradebryant@gmail.com";
        // $Emails[] = "imgeanican@gmail.com";


        $mail = new PHPMailer();
        $site_options = query("select * from site_options");
        $s = $site_options[0];
         $message = "<html>
         <body>
         Good day!
        <br><br>
         Respectfully forwarded to your end the DTR for ".$date_from." - ".$date_to."
         <br><br>
         Please open the link below . Use the password that was given to you for the access to the files. Kindly wait if there are no files available yet. We will update once the files are available., Thank you! 
         <br><br><br>".$s["google_full_id"]."
         </body>
     </html>";
         try {
                 $mail->isSMTP();
                 $mail->SMTPAuth = true;
                 $mail->SMTPSecure = "ssl";
                 $mail->Host = "smtp.gmail.com";
                 $mail->Port = "465";
                //  $mail->addAttachment($attachment);
                 $mail->isHTML();
                 $mail->Username = $s["email_from"];
                 $mail->Password = $s["email_password"];
                 $mail->SetFrom("no-reply@hrmis.panabocity.gov.ph");
                 $mail->Subject = "GOOGLE LINK FOR DTR and TIMESHEET";
                 $mail->Body = $message;
                 $mail->AddAddress("tradebryant@gmail.com");
                 foreach($Emails  as $email){
                    $mail->AddCC($email);  
                  }

                //   dump($mail);
                 if (!$mail->send())
                 $status = $mail->ErrorInfo;
                 else
                 $status = "success";

                //  dump($status);
                return $status;
               } catch (phpmailerException $e) {
                    $status = $mail->ErrorInfo;
                    return $status;
               } catch (Exception $e) {
                    $status = $mail->ErrorInfo;
                    return $status;
               }
     }

     function mail_action($email, $message, $attachment){
        $email = xss_cleaner($email);
         $mail = new PHPMailer();
         $site_options = query("select * from site_options");
        $s = $site_options[0];
         $status = "success";
         try {
                 $mail->isSMTP();
                 $mail->SMTPAuth = true;
                 $mail->SMTPSecure = "ssl";
                 $mail->Host = "smtp.gmail.com";
                 $mail->Port = "465";
                 $mail->addAttachment($attachment);
                 $mail->isHTML();

                //  $mail->Username = "chrmopanabo.payroll@gmail.com";
                //  $mail->Password = "mlgmynsxugehkhbx";
                 $mail->Username = $s["email_from"];
                 $mail->Password = $s["email_password"];
                 $mail->SetFrom("no-reply@hrmis.panabocity.gov.ph");
                 $mail->Subject = "HRMIS DTR/Timesheet";
                 $mail->Body = $message;
                 $mail->AddAddress($email);
                //  dump($mail);
                 //$mail->Send();
                 if (!$mail->send())
                 $status = $mail->ErrorInfo;
                 else
                 $status = "success";

                //  dump($status);
                return $status;
               } catch (phpmailerException $e) {
                    $status = $mail->ErrorInfo;
                    return $status;
               } catch (Exception $e) {
                    $status = $mail->ErrorInfo;
                    return $status;
               }
     }

     function get_minutes_difference($time_start, $time_end, $date){

        $time_start = $date . " " .$time_start . ":00";
        $time_end = $date . " " .$time_end . ":00";
       
        $from_time = strtotime($time_start); 
        $to_time = strtotime($time_end); 
        
        $minutes = round(abs($from_time - $to_time) / 60,2);
        // dump($minutes);
        return $minutes;

     }



     function pdfEncrypt ($origFile, $password, $destFile){
        $pdf = new FPDI_Protection();
        $pdf->FPDF('P');
        $pagecount = $pdf->setSourceFile($origFile);
        for ($loop = 1; $loop <= $pagecount; $loop++) {
            $tplidx = $pdf->importPage($loop);
            $pdf->addPage();
            $pdf->useTemplate($tplidx);
        }
        
        // protect the new pdf file, and allow no printing, copy etc and leave only reading allowed
        $pdf->SetProtection(array("print"),$password);
        $pdf->SetProtection(array(),"master");
        $pdf->Output($destFile, 'F');
        
        return $destFile;
        }


        function pdfEncrypt_array ($the_files, $password, $destFile, $orientation){
            $pdf = new FPDI_Protection();
            $pdf->FPDF($orientation);
            // dump($password);
            
            foreach($the_files as $f):
                $pagecount = $pdf->setSourceFile($f);
                // copy all pages from the old unprotected pdf in the new one
                for ($loop = 1; $loop <= $pagecount; $loop++) {
                    $tplidx = $pdf->importPage($loop);
                    $pdf->addPage();
                    $pdf->useTemplate($tplidx);
                }
                    unlink($f);
            endforeach;
            $pdf->SetProtection(array("print"),$password, "!1234#");
            $pdf->Output($destFile, 'F');
            return $destFile;
            }


    function to_peso($number){
        // dump("awit");
        if($number == "" || empty($number))
        return 0;
        else{
            $float_number = floatval($number);
            return(number_format($float_number, 2, '.', ','));
        }
      
    }

    function first_quincena($net_pay){
        $step1 = ($net_pay) / 2;
        $truncatedStep1 = floor($step1); // Truncate the result of step 1

        // Calculate the result of the fractional part of step 1
        $fractionalPart = ($step1 - $truncatedStep1);

        // Calculate the final result using the formula
        $result = $truncatedStep1 + ($fractionalPart * 2);
        return $result;
    }

    // function array_comma($array){
    //     $string = implode(", ", $array);
    //     return $string;
    // }



    function to_amount($number){
        return(round($number, 2));
    }

    function to_month($month){
        $monthNum  = $month;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        return $monthName;
    }

    function philhealth($salary){
        if($salary <= 10000):
            return 400;
        elseif($salary > 10000 && $salary <= 80000):
            $ph = $salary * .04;
            return $ph;
        elseif($salary>80000):
            return 3200;
        endif;
    }

    function rata($allowance, $days){
        $rata = 0;
        if($days >= 17):
            $rata = $allowance;
        elseif($days >= 12 && $days < 17):
            $rata = to_amount($allowance) * 0.75;
        elseif($days < 12 && $days >= 6):
            $rata = to_amount($allowance) * .5;
        elseif($days < 6 && $days >=1):
            $rata = to_amount($allowance) * 0.25;
        else:
            $rata = 0;
        endif;
// dump($rata);
        return $rata;

    }

    function salary($salary, $salary_id, $salary_grade, $salary_step, $salary_class){
        $Salary = [];
        foreach($salary as $row):
            $Salary[$row["schedule"]][$row["salary_grade"]][$row["step"]] = $row;
        endforeach;
        // dump($Salary);
      
        $my_salary = $Salary[$salary_class][$salary_grade][$salary_step]["salary"];
        // dump($my_salary);

        return $my_salary;
    }


    function to_findes_amount($amount){
        $amount = round($amount, 2);
        $amount = number_format((float)$amount, 2, '.', '');
        $p =  str_replace(".","",$amount);
        $p = sprintf("%015d",$p);
        $final_amount = $p;
        return $final_amount;
    }

    function scan_dir_better($dir) {
        $ignored = array('.', '..', '.svn', '.htaccess'); // -- ignore these file names
        $files = array(); //----------------------------------- create an empty files array to play with
        foreach (scandir($dir) as $file) {
            if ($file[0] === '.') continue; //----------------- ignores all files starting with '.'
            if (in_array($file, $ignored)) continue; //-------- ignores all files given in $ignored
            $files[$file] = filemtime($dir . '/' . $file); //-- add to files list
        }
        arsort($files); //------------------------------------- sort file values (creation timestamps)
        $files = array_keys($files); //------------------------ get all files after sorting
        return ($files) ? $files : false;
    }


    function add_log($activity, $action, $employee_id){
        $log_id = create_trackid("LOG");
        $ip = getIPAddress(); 
        $date = date("Y-m-d G:i:s");
        
        if (query("insert INTO system_logs (logs_id, action, logs_date, employee_id , ip_address, timestamp, activity) 
					VALUES(?,?,?,?,?,?,?)", 
				$log_id, $action, $date, $employee_id, $ip, time(), $activity) === false)
				{
					// apologize("Sorry, that username has already been taken!");
                }
    }


    function the_base_url(){
        $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http";
			$host = $_SERVER["HTTP_HOST"];
			$base_url = $protocol . "://" . $host;
            return $base_url;
    }

    function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    function array_month(){
        $months = array(
            array("month" => "01", "month_name" => "January"),
            array("month" => "02", "month_name" => "February"),
            array("month" => "03", "month_name" => "March"),
            array("month" => "04", "month_name" => "April"),
            array("month" => "05", "month_name" => "May"),
            array("month" => "06", "month_name" => "June"),
            array("month" => "07", "month_name" => "July"),
            array("month" => "08", "month_name" => "August"),
            array("month" => "09", "month_name" => "September"),
            array("month" => "10", "month_name" => "October"),
            array("month" => "11", "month_name" => "November"),
            array("month" => "12", "month_name" => "December")
        );

        return $months;
    }

  

    
    

    function convert_number_to_words($number) {
        $hyphen      = '-';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );
      
        if (!is_numeric($number)) {
            return false;
        }
      
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
     
        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }
      
        $string = $fraction = null;
      
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
      
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }
      
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
      
        return $string;
    }
     




    function date_explode($date){

        $date = explode('/', $date);
        $month = $date[0];
        $day   = $date[1];
        $year  = $date[2];
        $newdate = $year . "-" . $month . "-" . $day;
        return($newdate);

    }

    function getIPAddress() {  
         if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                    $ip = $_SERVER['HTTP_CLIENT_IP'];  
            }  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
         }  
        else{  
                 $ip = $_SERVER['REMOTE_ADDR'];  
         }  
         return $ip;  
    }  

    function xss_cleaner($input_str) {
        $return_str = str_replace( array('<',';','|','&','>',"'",'"',')','('), array('&lt;','&#58;','&#124;','&#38;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
        $return_str = str_ireplace( '%3Cscript', '', $return_str );
        return $return_str;
    }


    function getUpperPost($keepVar = false){
        $return_array = array();
        /* Edited on 4/1/2015 */
        foreach($_POST as $postKey => $postVar){
            $return_array[$postKey] = strtoupper($postVar);
        }
        if($keepVar){
            $_POST = $return_array;
        }else{
            return $return_array;
        }
    }

    function dump($variable)
    {
        require("dump.php");
        exit;
    }
	
	function dumper($variable)
    {
        require("../../templates/dump.php");
        exit;
    }
	
	function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
	}

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */

     function check_MFO($performance_serialized){
        // dump($performance_serialized);
        $performance = unserialize($performance_serialized);
        $bool = 0;
        for($i=1;$i<=5;$i++){
            if($performance[$i] != ""){
                $bool = 1;
                break;
            }
        }
        return $bool;
     }

     function get_latest_record($employee_id){

        $service_record = query("select * from tblemployee_service_record where employee_id = ?
                                    order by from_date desc", $employee_id);
        return($service_record[0]);
     }


    function changeCount($dat)
    {
    $dat = str_replace(")", "", $dat);
    $dat = explode(".", $dat);
    $d = "";
    foreach ($dat as $a) {
        $a = str_replace(' ', '', $a);
        if ($a) {
        if (is_numeric($a)) {
            if ($a < 10 && strlen($a) == 1) {
            $d .= "0" . $a . ".";
            } else {
            $d .= $a . ".";
            }
        } else {
            $d .= $a . ".";
        }
        }
    }
    return $d;
    }



    function get_salary($plantilla_id){
        $plantilla = query("
        select * from tbl_plantilla p
        left join tblposition pos
        on pos.Positionid = p.position_id
        where p.tbl_id = ?", $plantilla_id);
        $plantilla = $plantilla[0];
        $salary_schedule = query("select * from tbl_salary_sched ss
            left join tbl_salary_grade sg
            on sg.salary_schedule_id = ss.salary_schedule_id
            where ss.schedule = ? and sg.salary_grade = ? and step = ?", $plantilla["salary_schedule"], $plantilla["SGRate"], $plantilla["step"]);
            // dump($salary_schedule);
        $salary_schedule = $salary_schedule[0];
        return ($salary_schedule["salary"]);
    }



    function customSort($a, $b) {
        // Compare department first
        $departmentComparison = strcmp($a['department'], $b['department']);
        if ($departmentComparison != 0) {
            return $departmentComparison;
        }
        
        // If department is equal, compare active_date
        return strcmp($a['active_date'], $b['active_date']);
    }
    
    // Sort the array using custom comparison function



    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
				$handle->exec("set names utf8");
				$handle->exec("set character_set_results='utf8'");
				$handle->exec("set collation_connection='utf8'");
				$handle->exec("set character_set_client='utf8'");
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        $results = $statement->execute($parameters);

        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    function formatDate($dateString, $format = 'Y, F d') {
        $dateTime = new DateTime($dateString);
        return $dateTime->format($format);
    }

    function readableDate($dateString, $format = 'F d, Y') {
        $dateTime = new DateTime($dateString);
        return $dateTime->format($format);
    }

    function query_access(/* $sql [, ... ] */)
        {
            // SQL statement
            $sql = func_get_arg(0);
        
            // parameters, if any
            $parameters = array_slice(func_get_args(), 1);
        
            // try to connect to database
            static $handle;
            if (!isset($handle)) {
                try {
                    $handle = new PDO("odbc:Service_Record"); // Replace YourDSNName with the DSN you set up
                    $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    // $handle->exec("SET NAMES 'UTF8'");
                } catch (Exception $e) {
                    trigger_error($e->getMessage(), E_USER_ERROR);
                    exit;
                }
            }
        
            $statement = $handle->prepare($sql);
            if ($statement === false) {
                trigger_error($handle->errorInfo()[2], E_USER_ERROR);
                exit;
            }
        
            $results = $statement->execute($parameters);
        
            if ($results !== false) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

    function logout()
    {
        $_SESSION["hris"] = [];
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }
		unset($_SESSION["hris"]);
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new DateInterval('P1D');
    
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
    
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
    
        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }
    
        return $array;
    }

    function get_leave_balance_not_accummulated($leave_type, $employee){
        $solo_parent_leave = 7;
        $special_leave = 3;
        if($leave_type == "SPECIAL PRIVELEGE LEAVE"){
            $leave = query("select sum(WorkingDays) leaver from tbleave_employee
            where Employeeid = ? and leave_application_type = 'SPECIAL PRIVELEGE LEAVE' and year = ?", $employee, date("Y"));
            $special_leave = $special_leave - $leave[0]["leaver"];

            return($special_leave);
        }

        else if ($leave_type == "SOLO PARENT LEAVE"){
            $leave = query("select sum(WorkingDays) leaver from tbleave_employee
            where Employeeid = ? and leave_application_type = 'SOLO PARENT LEAVE' and year = ?", $employee, date("Y"));
            $solo_parent_leave = $solo_parent_leave - $leave[0]["leaver"];

            return($solo_parent_leave);
        }
    }


    function get_leave_balance($leave_type, $employee){

        $initial = query("select * from leave_initial where employee_id = ?", $employee);
        if($leave_type == "SICK LEAVE"):
            $sick_leave = query("select sum(sl_credits) as sick_leave from leave_employee where employee_id = ?",  $employee);
            $earned_leave = query("select sum(sick_leave) as sick_leave from leave_earned where employee_id = ?", $employee);
            $initial_sick_leave = 0;
            if(!empty($initial)):
                $initial_sick_leave = $initial[0]["sick_leave"];
            endif;  
            $balance = $initial_sick_leave - $sick_leave[0]["sick_leave"] + $earned_leave[0]["sick_leave"];

        elseif($leave_type == "VACATION LEAVE"):
            $vacation_leave = query("select sum(vl_credits) as vacation_leave from leave_employee where employee_id = ?",  $employee);
            $earned_leave = query("select sum(vacation_leave) as vacation_leave from leave_earned where employee_id = ?", $employee);
            $initial_vacation_leave = 0;
            if(!empty($initial)):
                $initial_vacation_leave = $initial[0]["vacation_leave"];
            endif; 
            $balance = $initial_vacation_leave - $vacation_leave[0]["vacation_leave"] + $earned_leave[0]["vacation_leave"];
        elseif($leave_type == "SPECIAL PRIVELEGE LEAVE"):
            $initial_special_leave = 3;
            $leave_special = query("select sum(special_leave_credits) as special_leave from leave_employee where employee_id = ?
                                            and year = ?",  $employee, date("Y"));
            $balance = $initial_special_leave - $leave_special[0]["special_leave"];
        endif;
        return $balance;
      
    }


  

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
		
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
			
			
        }
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
			
        }

        else
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
			
        }
		
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [], $app)
    {
		
            if($app == "pds"):
                extract($values);
                require("layouts/header.php");
                require("layouts/pds_sidebar.php");
                require("$template");
                require("layouts/footer.php");
            elseif($app=="attendance"):
                extract($values);
                require("layouts/header.php");
                require("layouts/attendance_sidebar.php");
                require("$template");
                require("layouts/new_footer.php");
            elseif($app=="plantilla"):
                    extract($values);
                    require("layouts/header.php");
                    require("layouts/plantilla_sidebar.php");
                    require("$template");
                    require("layouts/new_footer.php");
            elseif($app=="payroll"):
                extract($values);
                require("layouts/header.php");
                require("layouts/payroll_sidebar.php");
                require("$template");
                require("layouts/new_footer.php");
            elseif($app=="records"):
                extract($values);
                require("layouts/header.php");
                require("layouts/records_sidebar.php");
                require("$template");
                require("layouts/new_footer.php");

            elseif($app=="setup"):
                    extract($values);
                    require("layouts/header.php");
                    require("layouts/setup_sidebar.php");
                    require("$template");
                    require("layouts/new_footer.php");
            elseif($app=="leave"):
                extract($values);
                require("layouts/header.php");
                require("layouts/leave_sidebar.php");
                require("$template");
                require("layouts/new_footer.php");
            elseif($app=="spms"):
                extract($values);
                require("layouts/header.php");
                require("layouts/spms_sidebar.php");
                require("$template");
                require("layouts/new_footer.php");


            endif;


            
        // }

        // else err
        // else
        // {
            // trigger_error("Invalid template: $template", E_USER_ERROR);
        // }
    }

    function renderview($template, $values = []) {
        extract($values);
        require("$template");
    }
	
	function check_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	
	function super_unique($array,$key)
	{
		$temp_array = array();
		foreach ($array as &$v) {
		if (!isset($temp_array[$v[$key]]))
		$temp_array[$v[$key]] =& $v;
			}
		$array = array_values($temp_array);
		return $array;
	}
	
	header('content-type:text/html;charset=utf-8');
	
	


?>
