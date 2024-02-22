
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if($_POST["action"] == "stepIncrementDatatable"):
            // dump($_POST);

            // print_r($_REQUEST);
            $draw = isset($_POST["draw"]) ? $_POST["draw"] : 1;
            $offset = $_POST["start"];
            $limit = $_POST["length"];
            $search = $_POST["search"]["value"];
            if ($limit == -1) {
                // Set a large number to fetch all records
                $limit = 999999;
            }

            $where = " and 1=1";

            $limiter_string = "limit " . $limit . " offset " . $offset;


            $salary = query("select * from tbl_salary_sched ss
                            left join tbl_salary_grade sg
                            on sg.salary_schedule_id = ss.salary_schedule_id
                            where ss.status = 'active'");

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

            $query_string = "select
            employee_id,
            promotion_date,
            LEAST(FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) + 1, 8) AS step_count,
            DATE_ADD(promotion_date, INTERVAL FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) * 3 YEAR) AS step_increment_date
            FROM
                employee_promotion_year
            WHERE
                STATUS = 'ACTIVE'
                AND MOD(YEAR(CURDATE()) - YEAR(promotion_date), 3) = 0
                AND LEAST(FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) + 1, 8) <= 8
                ".$where." 
            ORDER BY
            step_increment_date asc
            ".$limiter_string."";
            // dump($query_string);
            $data = query($query_string);

            $query_string = "select
            employee_id,
            promotion_date,
            LEAST(FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) + 1, 8) AS step_count,
            DATE_ADD(promotion_date, INTERVAL FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) * 3 YEAR) AS step_increment_date
            FROM
                employee_promotion_year
            WHERE
                STATUS = 'ACTIVE'
                AND MOD(YEAR(CURDATE()) - YEAR(promotion_date), 3) = 0
                AND LEAST(FLOOR((YEAR(CURDATE()) - YEAR(promotion_date)) / 3) + 1, 8) <= 8
                ".$where."
            ORDER BY
            step_increment_date asc";
            $all_data = query($query_string);
   
            $i=0;
            foreach($data as $row):
                $employee = $Emp[$row["employee_id"]];
                $data[$i]["employee"] = $employee["LastName"] . ", " . $employee["FirstName"];
                $data[$i]["department"] = $Dep[$employee["Deptid"]]["DeptCode"];
                $data[$i]["latest_promotion"] = $row["promotion_date"];
                $data[$i]["salary_grade"] = $employee["salary_grade"];
                $data[$i]["salary_step"] = $row["step_increment_date"];
                $data[$i]["effectivity"] = $row["step_increment_date"];
                $data[$i]["previous"] = to_peso(salary($salary,"",$employee["salary_grade"],$employee["salary_step"],$employee["salary_class"]));
                $data[$i]["incremented"] = to_peso(salary($salary,"",$employee["salary_grade"],$row["step_count"],$employee["salary_class"]));
                $data[$i]["generate_nosa"] = "<a href='#' class='btn btn-sm btn-block btn-info'>Generate</a>";
                $i++;
            endforeach;
   
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
            render("apps/records/step_increment_app/step_increment_form.php", 
                ["title" => "STEP INCREMENT",
          
                ],"records");
        endif;
       


        
    }
?>