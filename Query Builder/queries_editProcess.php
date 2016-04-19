<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

require_once "../../Gibbon.php" ;

//New PDO DB connection
$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();


//Set timezone from session variable
date_default_timezone_set($session->get("timezone"));

$search=NULL ;
if (isset($_GET["search"])) {
	$search=$_GET["search"] ;
}
$queryBuilderQueryID=$_GET["queryBuilderQueryID"] ;
$URL=$session->get("absoluteURL") . "/index.php?q=/modules/" . getModuleName($_POST["address"]) . "/queries_edit.php&queryBuilderQueryID=" . $queryBuilderQueryID . "&sidebar=false&search=$search" ;

if (isActionAccessible($guid, $connection2, "/modules/Query Builder/queries_edit.php")==FALSE) {
	//Fail 0
	$URL=$URL . "&updateReturn=fail0" ;
	header("Location: {$URL}");
}
else {
	//Proceed!
	//Check if school year specified
	if ($queryBuilderQueryID=="") {
		//Fail1
		$URL=$URL . "&updateReturn=fail1" ;
		header("Location: {$URL}");
	}
	else {
		$data=array("queryBuilderQueryID"=>$queryBuilderQueryID, "gibbonPersonID"=>$session->get("gibbonPersonID")); 
		$sql="SELECT * FROM queryBuilderQuery WHERE queryBuilderQueryID=:queryBuilderQueryID AND NOT type='gibbonedu.com' AND gibbonPersonID=:gibbonPersonID" ;
		$result = $pdo->executeQuery($data, $sql);
		if (! $pdo->getQuerySuccess())
		{ 
			//Fail2
			$URL=$URL . "&deleteReturn=fail2" ;
			header("Location: {$URL}");
			break ;
		}

		if ($result->rowCount()!=1) {
			//Fail 2
			$URL=$URL . "&updateReturn=fail2" ;
			header("Location: {$URL}");
		}
		else {
			//Validate Inputs
			$name=$_POST["name"] ;
			$category=$_POST["category"] ;
			$active=$_POST["active"] ;
			$description=$_POST["description"] ;
			$query=$_POST["query"] ;
			$gibbonPersonID=$session->get("gibbonPersonID") ;
	
			if ($name=="" OR $category=="" OR $active=="" OR $query=="") {
				//Fail 3
				$URL=$URL . "&updateReturn=fail3" ;
				header("Location: {$URL}");
			}
			else {
				//Write to database
				$data=array("name"=>$name, "category"=>$category, "active"=>$active, "description"=>$description, "query"=>$query, "queryBuilderQueryID"=>$queryBuilderQueryID); 
				$sql="UPDATE queryBuilderQuery SET name=:name, category=:category, active=:active, description=:description, query=:query WHERE queryBuilderQueryID=:queryBuilderQueryID" ;
				$result = $pdo->executeQuery($data, $sql);
				if (! $pdo->getQuerySuccess())
				{ 
					//Fail 2
					$URL=$URL . "&updateReturn=fail2" ;
					header("Location: {$URL}");
					break ;
				}

				//Success 0
				$URL=$URL . "&updateReturn=success0" ;
				header("Location: {$URL}");
			}
		}
	}
}
?>