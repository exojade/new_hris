
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
           if ($_POST["action"] == "serviceDatatable"):
                // dump($_POST);
    
                // dump($_REQUEST);
                $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
                $offset = $_POST["start"];
                $limit = $_POST["length"];
                $search = $_POST["search"]["value"];
                if ($limit == -1) {
                    // Set a large number to fetch all records
                    $limit = 999999;
                }
    
                $where = " 1=1";
    
                $limiter_string = "limit " . $limit . " offset " . $offset;

                $year = date("Y");

                if(isset($_REQUEST["year"])):
                  if($_REQUEST["year"] != ""):
                    $year = $_REQUEST["year"];
                  endif;
                endif;



    
    
            
    
                $emp = query("select * from tblemployee");
                $Emp = [];
                foreach($emp as $row):
                    $Emp[$row["Employeeid"]] = $row;
                endforeach;
    
                $dep = query("select * from tbldepartment");
                $Dep = [];
                foreach($dep as $row):
                    $Dep[$row["Deptid"]] = $row;
                endforeach;
    
                $query_string = "
                    SELECT 
                    employee_id, 
                    calculate_milestone(employee_id, calculate_overall_service(employee_id, DATE), YEAR(CURDATE())) AS next_milestone_year,
                    calculate_overall_service(employee_id, DATE) AS years_service,
                    MAX(CASE WHEN STATUS = 'Active' THEN DATE END) AS active_date
                    FROM employee_continuous_year cy
                    WHERE ".$year." = calculate_milestone(employee_id, calculate_overall_service(employee_id, DATE), YEAR(CURDATE()))
                    GROUP BY employee_id
                ".$limiter_string."";
                // dump($query_string);
                $data = query($query_string);
                // dump($data);
    
                $query_string = "select 
                employee_id, 
                calculate_milestone(employee_id, calculate_overall_service(employee_id, DATE), YEAR(CURDATE())) AS next_milestone_year,
                calculate_overall_service(employee_id, DATE) AS years_service,
                MAX(CASE WHEN STATUS = 'Active' THEN DATE END) AS active_date
                FROM employee_continuous_year cy
                WHERE ".$year." = calculate_milestone(employee_id, calculate_overall_service(employee_id, DATE), YEAR(CURDATE()))
                GROUP BY employee_id";
                $all_data = query($query_string);
       
                $i=0;
                foreach($data as $row):
                    $employee = $Emp[$row["employee_id"]];
                    $data[$i]["employee"] = $employee["LastName"] . ", " . $employee["FirstName"];
                    $data[$i]["department"] = $Dep[$employee["Deptid"]]["DeptCode"];
                    $data[$i]["length_service"] = $row["years_service"];
                    $i++;
                endforeach;
               

                usort($data, 'customSort');
        // dump($data);
                $json_data = array(
                    "draw" => $draw + 1,
                    "iTotalRecords" => count($all_data),
                    "iTotalDisplayRecords" => count($all_data),
                    "aaData" => $data
                );
                echo json_encode($json_data);








        endif;
    }
    else
    {
        if(isset($_GET["action"])):
            
            
        else:
            render("apps/records/service_awardees_app/service_awardees_form.php", 
                ["title" => "SERVICE AWARDEES",
               
                ],"records");
        endif;
       


        
    }
?>