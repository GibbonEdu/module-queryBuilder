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

@session_start() ;

//Gibbon system-wide includes
include "../../Gibbon.php" ;

//New PDO DB connection
$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();
//Module includes
include $_SESSION[$guid]["absolutePath"] . "/modules/" . $_SESSION[$guid]["module"] . "/moduleFunctions.php" ;

//Setup variables
$gibboneduComOrganisationName=$_GET["gibboneduComOrganisationName"] ;
$gibboneduComOrganisationKey=$_GET["gibboneduComOrganisationKey"] ;
$service=$_GET["service"] ;

//Remove all gibbonedu.com queries
$data=array(); 
$sql="DELETE FROM queryBuilderQuery WHERE type='gibbonedu.com'" ;
$result = $pdo->executeQuery($data, $sql);

?>