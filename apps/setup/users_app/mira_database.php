<?php   

// // $Temp = [];
// $temp = query("select * from temp_employees");

// // $temp = utf8_encode($temp);
// $emp = query("select * from tblemployee");

// // $Emp = [];
// // foreach($emp as $row):
// // $Emp[$row["Employeeid"]] = $row;
// // endforeach;
// function replaceSpecialCharacters($inputString) {
//     // Replace 'Ñ' with 'N'
//     $inputString = str_replace('Ñ', 'N', $inputString);

//     // Replace '?' with 'N'
//     $inputString = str_replace('?', 'N', $inputString);

//     return $inputString;
// }


// foreach ($emp as $empData) {
//     $empLastName = replaceSpecialCharacters(strtoupper($empData['LastName']));
//     $empFirstName = replaceSpecialCharacters(strtoupper($empData['FirstName']));

//     foreach ($temp as $tempData) {
//         $tempLastName = replaceSpecialCharacters(strtoupper($tempData['lastname']));
//         $tempFirstName = replaceSpecialCharacters(strtoupper($tempData['firstname']));

//         // Check if the LastName and FirstName in $Emp exist in $temp
//         if (strpos($tempLastName, $empLastName) !== false && strpos($tempFirstName, $empFirstName) !== false) {
//             // Match found, do something


//             query("update temp_employees set employee_id = ? where id = ?", $empData["Employeeid"], $tempData["id"]);

//         }
//     }
// }
// dump("done");


// $Emp = [];
// $emp = query("select * from tblemployee");
// foreach($Emp as $row):
// $Emp[$row["Employeeid"]] = $row;
// endforeach;

// $temp = query("select * from temp_employees");


// $queryFormatContinuous = "('%s','%s','%s')";
// $inserts_continuous = array();

// $queryFormatPromotion = "('%s','%s','%s')";
// $insertsPromotion = array();


// foreach($temp as $row):




//     if($temp["continuous_year"] != ""):
//         query("insert into employee_continuous_year (employee_id, date, status)
//                 values ('".$_POST["employee"]."', '".$temp["continuous_year"]."', 'INACTIVE')
//                 ");
//     endif;

//     if($temp["new_year_started"] != ""):
//         query("insert into employee_continuous_year (employee_id, date, status)
//                 values ('".$_POST["employee"]."', '".$temp["new_year_started"]."', 'INACTIVE')
//                 ");
//     endif;

//     query("update employee_continuous_year
//     SET status = 'ACTIVE'
//     WHERE employee_id = '".$_POST["employee"]."'
//       AND date = (SELECT MAX(date) FROM employee_continuous_year WHERE employee_id = '".$_POST["employee"]."')");


//     if($temp["latest_promotion"] != ""):
//         query("insert into employee_promotion_year (employee_id, promotion_date, status)
//                 values ('".$_POST["employee"]."', '".$temp["latest_promotion"]."', 'INACTIVE')
//                 ");
//     endif;

//     if($temp["new_latest_promotion"] != ""):
//         query("insert into employee_promotion_year (employee_id, promotion_date, status)
//                 values ('".$_POST["employee"]."', '".$temp["new_latest_promotion"]."', 'INACTIVE')
//                 ");
//     endif;

    // if($row["employee_id"] != ""):
//         if($row["continuous_year"] != ""):
//             $inserts_continuous[] = sprintf( $queryFormatContinuous, $row["employee_id"], $row["continuous_year"], "INACTIVE");
//         endif;
//         if($row["new_year_started"] != ""):
//             $inserts_continuous[] = sprintf( $queryFormatContinuous, $row["employee_id"], $row["new_year_started"], "INACTIVE");
//         endif;


//         if($row["latest_promotion"] != ""):
//             $insertsPromotion[] = sprintf( $queryFormatPromotion, $row["employee_id"], $row["latest_promotion"], "INACTIVE");
//         endif;
//         if($row["new_latest_promotion"] != ""):
//             $insertsPromotion[] = sprintf( $queryFormatPromotion, $row["employee_id"], $row["new_latest_promotion"], "INACTIVE");
//         endif;

//         // query("update");


//         $string_query = '
//                 update tblemployee
//                 set 
//                 date_hired = "'.$row["date_hired"].'",
//                 original_appointment = "'.$row["original_appointment"].'",
//                 date_started = "'.$row["continuous_year"].'",
//                 last_promotion = "'.$row["latest_promotion"].'",
//                 BirthDate = "'.$row["birthday"].'",
//                 BirthPlace = "'.$row["place_birth"].'",
//                 Address = "'.$row["address"].'",
//                 CellphoneNumber = "'.$row["contact"].'",
//                 email_address = "'.$row["email"].'",
//                 GSIS = "'.$row["gsis"].'",
//                 Pagibig = "'.$row["pagibig"].'",
//                 PHIC = "'.$row["philhealth"].'",
//                 SSS = "'.$row["sss_number"].'",
//                 BloodType = "'.$row["blood_type"].'"
//                 where Employeeid = "'.$row["employee_id"].'";
//             ';

//             echo("<pre>");
//             echo($string_query);
//             echo("<br>");
//             echo("</pre>");

//             // query($string_query);
//     endif;
// endforeach;


// $query = implode( ",", $inserts_continuous );
// $query_string = "insert into employee_continuous_year
// (employee_id, date, status) 
// VALUES " . $query;
// query($query_string);


// $query = implode( ",", $insertsPromotion );
// $query_string = "insert into employee_promotion_year
// (employee_id, promotion_date, status) 
// VALUES " . $query;
// query($query_string);


// $query = "
// UPDATE employee_promotion_year
// SET status = 'ACTIVE'
// WHERE (employee_id, promotion_date) IN (
//     SELECT employee_id, MAX(promotion_date) AS max_promotion_date
//     FROM employee_promotion_year
//     GROUP BY employee_id
// )";
// query($query);


// $query = "
// UPDATE employee_continuous_year
// SET status = 'ACTIVE'
// WHERE (employee_id, date) IN (
//     SELECT employee_id, MAX(date) AS max_promotion_date
//     FROM employee_continuous_year
//     GROUP BY employee_id
// )";
// query($query);

// dump("done");















if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST["action"] == "datatable"):
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];
            $sql = query("select * from temp_employees");

            if($search == ""){
                $query_string = "select * from temp_employees
                                order by lastname ASC
                                    limit ".$limit." offset ".$offset." ";
                $data = query($query_string);
                $all_data = query("select * from temp_employees");
            }
            else{
                $query_string = "
                    select * from temp_employees
                    where 
                    concat(lastname, ', ', firstname) like '%".$search."%' or
                    concat(firstname, ' ', lastname) like '%".$search."%'
                    order by lastname ASC
                    limit ".$limit." offset ".$offset."
                ";
                $data = query($query_string);
                $query_string = "
                        select * from temp_employees
                        where 
                        concat(lastname, ', ', firstname) like '%".$search."%' or
                        concat(firstname, ' ', lastname) like '%".$search."%'
                        order by lastname ASC
                ";
                $all_data = query($query_string);
            }
            $i=0;
            foreach($data as $row):

                $data[$i]["action"] = '<a href="" data-target="#transferModal" data-id="'.$row["id"].'" data-toggle="modal" class="btn btn-sm btn-primary btn-block">Transfer</a>';
                $data[$i]["name"] = $row["lastname"] . ", " . $row["firstname"];
           

                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            echo json_encode($json_data);

        elseif($_POST["action"] == "update_employee"):
            // dump($_POST);
            $temp = query("select * from temp_employees where id = ?", $_POST["id"]);
            $temp = $temp[0];
            // dump($temp);
            $string_query = '
                update tblemployee
                set 
                date_hired = "'.$temp["date_hired"].'",
                original_appointment = "'.$temp["original_appointment"].'",
                date_started = "'.$temp["continuous_year"].'",
                last_promotion = "'.$temp["latest_promotion"].'",
                BirthDate = "'.$temp["birthday"].'",
                BirthPlace = "'.$temp["place_birth"].'",
                Address = "'.$temp["address"].'",
                CellphoneNumber = "'.$temp["contact"].'",
                email_address = "'.$temp["email"].'",
                GSIS = "'.$temp["gsis"].'",
                Pagibig = "'.$temp["pagibig"].'",
                PHIC = "'.$temp["philhealth"].'",
                SSS = "'.$temp["sss_number"].'",
                BloodType = "'.$temp["blood_type"].'"
                where Employeeid = "'.$_POST["employee"].'"
            ';

            if($temp["continuous_year"] != ""):
                query("insert into employee_continuous_year (employee_id, date, status)
                        values ('".$_POST["employee"]."', '".$temp["continuous_year"]."', 'INACTIVE')
                        ");
            endif;

            if($temp["new_year_started"] != ""):
                query("insert into employee_continuous_year (employee_id, date, status)
                        values ('".$_POST["employee"]."', '".$temp["new_year_started"]."', 'INACTIVE')
                        ");
            endif;

            query("update employee_continuous_year
            SET status = 'ACTIVE'
            WHERE employee_id = '".$_POST["employee"]."'
              AND date = (SELECT MAX(date) FROM employee_continuous_year WHERE employee_id = '".$_POST["employee"]."')");


            if($temp["latest_promotion"] != ""):
                query("insert into employee_promotion_year (employee_id, promotion_date, status)
                        values ('".$_POST["employee"]."', '".$temp["latest_promotion"]."', 'INACTIVE')
                        ");
            endif;

            if($temp["new_latest_promotion"] != ""):
                query("insert into employee_promotion_year (employee_id, promotion_date, status)
                        values ('".$_POST["employee"]."', '".$temp["new_latest_promotion"]."', 'INACTIVE')
                        ");
            endif;

            query("update employee_promotion_year
            SET status = 'ACTIVE'
            WHERE employee_id = '".$_POST["employee"]."'
              AND promotion_date = (SELECT MAX(promotion_date) FROM employee_promotion_year WHERE employee_id = '".$_POST["employee"]."')");
            
            


            // dump($string_query);
            query($string_query);
            query("update temp_employees set employee_id = ? where id = ?", $_POST["employee"], $_POST["id"]);


            


          $res_arr = [
            "status" => "success",
            "title" => "Success",
            "message" => "Success on Updating Employee",
            "option" => "theFunctions",
            "theFunctions" => "
            function filter2() {
              var datatable = $('#employees-datatable').DataTable();
              var rowToUpdate = datatable.row(".$_POST['rowIndex'].");
              var rowIndex = rowToUpdate.index();
              rowToUpdate.node().style.backgroundColor = '#FFFFCC';
          
              // else{
              var currentPage = datatable.page();
              var currentLength = datatable.page.len();
              datatable.ajax.url('mira_database?action=datatable).load();
              datatable.page(currentPage).page.len(currentLength).draw('page');
              closeAllModals();
              rowToUpdate.node().css('background-color', '#FFFFCC').delay(10000).fadeOut(5000, function() {
            });
            }
            
            filter2();
            ",
            "link" => "plantilla_profile?action=details&employee_id=".$_POST["employee_id"],
            ];
            echo json_encode($res_arr); exit();

    

      






            elseif($_POST["action"] == "upload_records"):
                    // dump($_FILES);

        $inserts = array();
        $queryFormat = "('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                         '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                         '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',
                         '%s','%s','%s','%s','%s','%s')";
        $fileName = $_FILES["logzips"]["tmp_name"];
        if ($_FILES["logzips"]["size"] > 0) {
            $file = fopen($fileName, "r");
            $i = 0;
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                // dump($column);
               
                    $Education = [];
                    $i = 0;
                    if($column[20] != ""):
                        $Education[$i]["course"] = $column[20];
                        $Education[$i]["highest_level"] = $column[21];
                        $Education[$i]["year_graduated"] = $column[22];
                        $i++;
                    endif;
                    if($column[23] != ""):
                        $Education[$i]["course"] = $column[23];
                        $Education[$i]["highest_level"] = $column[24];
                        $Education[$i]["year_graduated"] = $column[25];
                        $i++;

                    endif;
                    if($column[26] != ""):
                        $Education[$i]["course"] = $column[26];
                        $Education[$i]["highest_level"] = $column[27];
                        $Education[$i]["year_graduated"] = $column[28];
                        $i++;
                    endif;
                    $Education = serialize($Education);


                    $Graduate = [];
                    $i = 0;
                    if($column[29] != ""):
                        $Graduate[$i]["graduate"] = $column[29];
                        $Graduate[$i]["highest_level"] = $column[30];
                        $Graduate[$i]["year_graduated"] = $column[31];
                        $i++;
                    endif;
                    if($column[32] != ""):
                        $Graduate[$i]["graduate"] = $column[32];
                        $Graduate[$i]["highest_level"] = $column[33];
                        $Graduate[$i]["year_graduated"] = $column[34];
                        $i++;

                    endif;
                    if($column[35] != ""):
                        $Graduate[$i]["graduate"] = $column[35];
                        $Graduate[$i]["highest_level"] = $column[36];
                        $Graduate[$i]["year_graduated"] = $column[37];
                        $i++;
                    endif;
                    $Graduate = serialize($Graduate);

                    $i = 0;
                    $Vocational= [];
                    if($column[38] != ""):
                        $Vocational[$i]["vocational"] = $column[38];
                        $Vocational[$i]["highest_level"] = $column[39];
                        $Vocational[$i]["year_graduated"] = $column[40];
                        $i++;
                    endif;
                    if($column[41] != ""):
                        $Vocational[$i]["graduate"] = $column[41];
                        $Vocational[$i]["highest_level"] = $column[42];
                        $Vocational[$i]["year_graduated"] = $column[43];
                        $i++;

                    endif;
                    $Vocational = serialize($Vocational);
                    
                    $column[8] = !empty($column[8]) ? DateTime::createFromFormat('m/d/Y', $column[8])->format('Y-m-d') : '';
                    $column[9] = !empty($column[9]) ? DateTime::createFromFormat('m/d/Y', $column[9])->format('Y-m-d') : '';
                    $column[10] = !empty($column[10]) ? DateTime::createFromFormat('m/d/Y', $column[10])->format('Y-m-d') : '';
                    $column[11] = !empty($column[11]) ? DateTime::createFromFormat('m/d/Y', $column[11])->format('Y-m-d') : '';
                    $column[12] = !empty($column[12]) ? DateTime::createFromFormat('m/d/Y', $column[12])->format('Y-m-d') : '';
                    $column[13] = !empty($column[13]) ? DateTime::createFromFormat('m/d/Y', $column[13])->format('Y-m-d') : '';
                    $column[18] = !empty($column[18]) ? DateTime::createFromFormat('m/d/Y', $column[18])->format('Y-m-d') : '';

                    
                    $inserts[] = sprintf( 
                        $queryFormat, 
                            $column[0], $column[1], $column[3], $column[4], $column[5],$column[6],$column[7],$column[8],
                            $column[9], $column[10], $column[11], $column[12], $column[13],
                            $column[14], $column[15], $column[16], $column[18], $column[19],$Education,$Graduate,$Vocational,$column[44],$column[45],
                            $column[46], $column[47], $column[48], $column[49], $column[50],$column[51],$column[52],$column[53],$column[54],$column[55],
                            $column[56], $column[57], $column[58]
                    
                    );
          
              
            }
                $query = implode( ",", $inserts );
                $query_string = "insert INTO temp_employees
                (lastname, firstname, position, job_type, office, gender_male, gender_female, date_hired,
                 original_appointment, continuous_year, new_year_started, latest_promotion, new_latest_promotion,
                 eligibility, salary_grade, step, birthday, place_birth, education, graduates, vocational, address, contact,
                 email, skills, indigenous, disability, solo_parent, philhealth, gsis, pagibig, tin_number, sss_number,
                 civil_status, children, blood_type)
                VALUES " . $query;
                query($query_string);
                $res_arr = [
                    "message" => "Successfully Uploaded CSV",
                    "status" => "success",
                    "link" => "refresh",
                    ];
                    echo json_encode($res_arr); exit();
                }


        endif;

 
      


    }
    else
    {
        if(isset($_GET["action"])):

        else:
            render("apps/setup/users_app/mira_database_list.php", 
                ["title" => "Users",],"setup");

        endif;
       


        
    }
?>