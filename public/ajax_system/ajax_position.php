<?php
	$search = $_REQUEST["q"];
	$sql = query("select concat(PositionName, ' (' , PositionCode, ') ' ) as supplier, Positionid as id from tblposition 
					where 
					PositionName like '%".$search."%' OR
					PositionCode like '%".$search."%' OR
					Functional_Title like '%".$search."%'
					");
	if($sql == null){
		$sql[0]["supplier"] = $search;
	}
	
					$json_data = array(
						"results" => $sql
					);
	
					echo json_encode($json_data);
?>
