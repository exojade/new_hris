
<?php   
// dump($_REQUEST);
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST["action"] == "print_payslip"):
            // dump($_POST);
            // dump($_POST);
            if($_POST["category"] == "" || $_POST["option"] == ""):
                $load[] = array('result' => 'failed', 'message' => 'Select Filter!');
                $json = array('info' => $load);
                echo json_encode($json);
                exit();
            endif;
            if($_POST["category"] == "department"):
                $sql = query("select * from site_options");
                $url = $sql[0]["new_site_url"];
                $actual = query(
                    "select p.*, e.FirstName, e.LastName, g.barcode, d.DeptCode from payroll_actual p 
                            left join tblemployee e
                            on e.Employeeid = p.employee_id
                            left join payroll_group g
                            on g.payroll_id = p.payroll_id
                            left join tbldepartment d
                            on d.Deptid = e.Deptid
                            where p.year=? and p.month = ? and department_assigned = ?
                            and g.payroll_type = 'SALARY'
                            and g.payroll_status = 'done'
                            order by e.LastName, e.FirstName",
                            $_POST["year"], $_POST["month"], $_POST["option"]
                    );
                    // dump($_POST);
                if(empty($actual)):
                    $load[] = array('result' => 'failed', 'message' => 'Empty Array! No Payslip on requested filter...');
                    $json = array('info' => $load);
                    echo json_encode($json);
                    exit();
                endif;

              

      
                    $department = query("select * from tbldepartment where Deptid = ?", $_POST["option"]);
                    $department = $department[0]["DeptCode"];

                    $options = urlencode(serialize($_POST));
                    // dump($options);
                    $webpath = $url . "/payslip?action=payslip_pdf&options=".$options;
                    // dump($webpath);
                    $filename = "PAYSLIP_PERMANENT_".$department."_".$_POST["month"] ."-".$_POST["year"];
                    // dump($webpath);
                
                
                $path = "file_folder/payslip/".$filename.".pdf";
                $exec = '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe" -O portrait --image-dpi 300 "'.$webpath.'" '.$path.'';
                // dump($exec);
                exec($exec);
                $load[] = array('path'=>$path, 'filename' => $filename, 'result' => 'success');
                $json = array('info' => $load);
                echo json_encode($json);

            else:

                $actual = query(
                    "select p.*, e.FirstName, e.LastName, g.barcode, d.DeptCode from payroll_actual p 
                            left join tblemployee e
                            on e.Employeeid = p.employee_id
                            left join payroll_group g
                            on g.payroll_id = p.payroll_id
                            left join tbldepartment d
                            on d.Deptid = e.Deptid
                            where p.year=? and p.month = ? and p.employee_id = ?
                            and g.payroll_type = 'SALARY'
                            and g.payroll_status = 'done'
                            order by e.LastName, e.FirstName",
                            $_POST["year"], $_POST["month"], $_POST["option"]
                    );
                    // dump($_POST);

                $sql = query("select * from site_options");
                $url = $sql[0]["new_site_url"];
                if(empty($actual)):
                    $load[] = array('result' => 'failed', 'message' => 'Empty Array! No Payslip on requested filter...');
                    $json = array('info' => $load);
                    echo json_encode($json);
                    exit();
                endif;
                $options = urlencode(serialize($_POST));
                // dump($options);
                $webpath = $url . "/payslip?action=payslip_pdf&options=".$options;
                $filename = "PAYSLIP_PERMANENT_".$actual[0]["LastName"].$actual[0]["FirstName"]."_".$_POST["month"] ."-".$_POST["year"];
                $filename = str_replace(' ', '', $filename);
                // dump($webpath);
            $path = "file_folder/payslip/".$filename.".pdf";
            $exec = '"C:/Program Files/wkhtmltopdf/bin/wkhtmltopdf.exe" -O portrait --image-dpi 300 "'.$webpath.'" '.$path.'';
            exec($exec);
            $load[] = array('path'=>$path, 'filename' => $filename, 'result' => 'success');
            $json = array('info' => $load);
            echo json_encode($json);
                


            endif;
        endif;
    
    }
    else
    {
        if(isset($_GET["action"])):
            if($_GET["action"] == "payslip_pdf"):
                renderview("apps/payroll/payslip_app/payslip_pdf.php", 
                [
                ]);

            endif;
                
           
          
        else:
            $month = array_month();
            // dump($month);
            render("apps/payroll/payslip_app/payslip_generator.php", 
                ["title" => "PAYSLIP GENERATOR",
                 "month" => $month,
                //  "department" => $department,
                
                
                ],"payroll");
        endif;
       


        
    }
?>