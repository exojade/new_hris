
<?php   
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        if($_POST["action"] == "datatable"):
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

            $where = " where 1=1";
            


            if(isset($_REQUEST["year"])):
                if($_REQUEST["year"] != ""):
                    $where = $where . " and year = '".$_REQUEST["year"]."'";
                endif;
            endif;


            $query_string = "select * from holidays " . $where;
            $sql = query($query_string);
            $all_data = $sql;
            if($search == ""){
                $query_string = "select * from holidays
                                ".$where."
                                order by holiday_date DESC 
                                limit ".$limit." offset ".$offset." ";
                // dump($query_string);
                $data = query($query_string);
                // dump($employees);
            }
            else{

              $where = $where . "
                and name like '%".$search."%'
              ";


                $query_string = "
                    select * from holidays
                    ".$where."
                    order by holiday_date ASC
                    limit ".$limit." offset ".$offset."
                ";
                // dump($query_string);
                $data = query($query_string);
                $query_string = "
                        select * from holidays
                        ".$where."
                        order by holiday_date ASC
                ";
                $all_data = query($query_string);
            }

          
            $i=0;
            foreach($data as $row):


                $timestamp = strtotime($row["holiday_date"]);
                $data[$i]["day"] = date("l", $timestamp);
                $data[$i]["holiday_date"] = date("F d, Y", $timestamp);
             
         
           
                $data[$i]["update"] = '
                <a data-id="'.$row["holiday_id"].'" data-toggle="modal" data-target="#modalUpdate" class="btn btn-sm btn-block btn-warning"><i class="fas fa-edit"></i></a>
                ';
                $data[$i]["delete"] = '
                <form class="generic_form_trigger" data-url="holidays">
                    <input type="hidden" name="action" value="deleteHoliday">
                    <input type="hidden" name="holiday_id" value="'.$row["holiday_id"].'">
                    <button class="btn btn-sm btn-block btn-danger"><i class="fas fa-trash"></i></button>
                </form>
                ';
                $i++;
            endforeach;
   
            $json_data = array(
                "draw" => $draw + 1,
                "iTotalRecords" => count($all_data),
                "iTotalDisplayRecords" => count($all_data),
                "aaData" => $data
            );
            echo json_encode($json_data);


        elseif($_POST["action"] == "addHoliday"):
            // dump($_POST);

            $dateString = $_POST["date"];
            $timestamp = strtotime($dateString);
            $year = date("Y", $timestamp);

            $query_string = "
            INSERT INTO holidays (holiday_date, name, year, type)
            VALUES ('" . $_POST["date"] . "', '" . $_POST["name"] . "', '" . $year . "', '" . $_POST["type"] . "')
            ";
            query($query_string);

            $res_arr = [
                "message" => "Success adding Holiday",
                "status" => "success",
                "link" => "refresh",
                ];
                echo json_encode($res_arr); exit();

        elseif($_POST["action"] == "get_holidays"):
            $Holidays = [];
            $holidays = query("SELECT CONCAT(name, ' (', DAYNAME(holiday_date), ', ', DATE_FORMAT(holiday_date, '%M %d, %Y'), ')') AS name, holiday_date
            FROM holidays");
            $i = 0;
            foreach($holidays as $row):
                $Holidays[$i]["date"] = $row["holiday_date"];
                $Holidays[$i]["name"] = $row["name"];
                $i++;
            endforeach;
            header('Content-Type: application/json');
            // Output the events as JSON
            echo json_encode($Holidays);



        endif;
    }
    else
    {
        if(isset($_GET["action"])):
            
        else:
            render("apps/setup/holidays_app/holidays_form.php", 
                ["title" => "HOLIDAYS",
                //  "position" => $position,
                ],"setup");
        endif;
       


        
    }
?>