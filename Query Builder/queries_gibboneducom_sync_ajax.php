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



//Gibbon system-wide includes
require_once "../../Gibbon.php" ;

//New PDO DB connection
$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();
//Module includes
include $session->get("absolutePath") . "/modules/" . $session->get("module") . "/moduleFunctions.php" ;

//Setup variables
$gibboneduComOrganisationName=$_POST["gibboneduComOrganisationName"] ;
$gibboneduComOrganisationKey=$_POST["gibboneduComOrganisationKey"] ;
$service=$_POST["service"] ;
$queries=json_decode($_POST["queries"], true) ;


if (count($queries)<1) { //We have a problem, report it.
	print "fail" ;
}
else { //Success, let's write them to the database.
	//But first let's remove all of the gibbonedu.com old queries
	$data=array(); 
	$sql="DELETE FROM queryBuilderQuery WHERE type='gibbonedu.com'" ;
	$result = $pdo->executeQuery($data, $sql);
	
	//Now let's get them in
	for ($i=0; $i<count($queries); $i++) {
		$data=array("queryID"=>$queries[$i]["queryID"], "name"=>$queries[$i]["name"], "category"=>$queries[$i]["category"], "description"=>$queries[$i]["description"], "query"=>$queries[$i]["query"]); 
		$sql="INSERT INTO queryBuilderQuery SET type='gibbonedu.com', queryID=:queryID, name=:name, category=:category, description=:description, query=:query" ;
		$result = $pdo->executeQuery($data, $sql, 'here {message}');
	}
}


?>