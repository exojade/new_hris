<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    //   dump($_POST);
        $rows = query("select * from tblusers
						where username = ?", $_POST["username"]);
        if (count($rows) == 1)
        {
            $row = $rows[0];
			if (crypt($_POST["password"], $row["hash"]) == $row["hash"]){
				$employee_name = query("select * from tblemployee e
										left join tbldepartment d
										on d.Deptid = e.Deptid where Employeeid = ?", $row["Employeeid"]);
				$employee_name = $employee_name[0];
				// dump($employee_name);

				$Roles = [];
				$roles = query("select * from roles");
				foreach($roles as $role):
					$Roles[$role["roleID"]] = $role;
				endforeach;
				$MyRoles = [];
				$my_roles = unserialize($row["roles"]);
				// dump($my_roles);
				$i = 0;
				foreach($my_roles as $row2):
					$MyRoles[$i]["roleID"] = $row2;
					$MyRoles[$i]["role"] = $Roles[$row2]["role"];
					$MyRoles[$i]["parentID"] = $Roles[$row2]["parentID"];
					$MyRoles[$i]["parent"] = $Roles[$MyRoles[$i]["parentID"]]["role"];
					$i++;
				endforeach;
				// dump($MyRoles);




				$Roles = [];
				if(empty($rows[0]["roles"]))
					$Roles["employee"] = "";
				else{

					$roles = explode(',',$rows[0]["roles"]);
					foreach($roles as $r):
						$Roles[$r] = "";
					endforeach;
				}
				$_SESSION["hris"] = [
					"uname" => $employee_name["FirstName"],
					"fullname" => $employee_name["FirstName"] . " " . $employee_name["LastName"],
					"roles" => $MyRoles,
					"department" => $employee_name["Deptid"],
					"departmentAssignment" => $employee_name["DeptAssignment"],
					"department_name" => $employee_name["DeptName"],
					"department_code" => $employee_name["DeptCode"],
					"employee_id" => $employee_name["Employeeid"],
					"defaultPass" => $row["defaultPass"],
					// "fullname" => $employee_name[0]["FirstName"] . " " .  $employee_name[0]["LastName"],
					"application" => "hris"
				];
				$activity = $employee_name["Employeeid"] . " logged in to the system";
				$action = "LOGIN";
				add_log($activity, $action, $employee_name["Employeeid"]);
				echo("proceed");
            }
			else {

				$activity = "SOMEONE entered username: " . $_POST["username"] . " and password: " . $_POST["password"];
				$action = "LOGIN FAILED";
				add_log($activity, $action, "000");
				echo("proceed");

				echo("WRONG USERNAME / PASSWORD");
			}
		}
		else {

				$activity = "SOMEONE entered username: " . $_POST["username"] . " and password: " . $_POST["password"];
				$action = "LOGIN FAILED";
				add_log($activity, $action, "000");

				
				echo("WRONG USERNAME / PASSWORD");
		}  
    }
    else
    {
        renderview("public/login_system/loginform.php", 
		[
			"employees" => "Log In"
		]);
    }
?>