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

$config = new Gibbon\config();
$guid = $config->get('guid');

//New PDO DB connection
$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();
@session_start() ;

//Set timezone from session variable
date_default_timezone_set($_SESSION[$guid]["timezone"]);

$queryBuilderQueryID=$_GET["queryBuilderQueryID"] ;
$query=$_POST["query"] ;

$URL=$_SESSION[$guid]["absoluteURL"] . "/index.php" ;

if (isActionAccessible($guid, $connection2, "/modules/Query Builder/queries_run.php")==FALSE) {
	print "<div class='error'>"; 
		print _("Your request failed because you do not have access to this action.") ;
	print "</div>" ; 

}
else {
	$excel = new Gibbon\Excel("queryBuilderExport.xlsx");

	if ($queryBuilderQueryID=="" OR $query=="") {
		print "<div class='error'>"; 
			print _("You have not specified one or more required parameters.") ;
		print "</div>" ; 
	}
	else {
		$data=array("queryBuilderQueryID"=>$queryBuilderQueryID, "gibbonPersonID"=>$_SESSION[$guid]["gibbonPersonID"]); 
		$sql="SELECT * FROM queryBuilderQuery WHERE queryBuilderQueryID=:queryBuilderQueryID AND (gibbonPersonID=:gibbonPersonID OR NOT type='Personal') AND active='Y'" ;
		$error = "<div class='error'>\n"._("Your request failed due to a database error.")."\n</div>" ; 

		$result = $pdo->executeQuery($data, $sql, $error);

		if ($result->rowCount()<1) {
			print "<div class='error'>"; 
				print _("The selected record does not exist, or you do not have access to it.") ;
			print "</div>" ; 
		}
		else {
			$data = array();
			$error =  "<div class='error'\n>" . _("Your request failed due to a database error.") . "\n</div>" ; 

			$result = $pdo->executeQuery($data, $query, $error);

			if ($result->rowCount()<1) {
				print "<div class='warning'>Your query has returned 0 rows.</div>" ; 
			}
			else {
				
				$excel->setActiveSheetIndex(0);
				
				for ($i=0; $i<$result->columnCount(); $i++) {
					$col=$result->getColumnMeta($i);
					if ($col["name"]!="password" AND $col["name"]!="passwordStrong" AND $col["name"]!="passwordStrongSalt" AND $col["table"]!="gibbonStaffContract" AND $col["table"]!="gibbonStaffApplicationForm" AND $col["table"]!="gibbonStaffApplicationFormFile") {
						$excel->getActiveSheet()->setCellValueByColumnAndRow($i, 1, $col["name"]);
					}
				}
				$r = 2;
				while ($row=$result->fetch()) {
					if (intval($row['gibbonPersonID']) > 0 )
					{
						for ($i=0; $i<$result->columnCount(); $i++) {
							$col=$result->getColumnMeta($i);		
							if ($col["name"]!="password" AND $col["name"]!="passwordStrong" AND $col["name"]!="passwordStrongSalt" AND $col["table"]!="gibbonStaffContract" AND $col["table"]!="gibbonStaffApplicationForm" AND $col["table"]!="gibbonStaffApplicationFormFile") {
								$excel->getActiveSheet()->setCellValueByColumnAndRow($i, $r, $row[$col["name"]]);
							}
						}
						$r++;
					}
				}
			}
		}
	}
	$excel->exportWorksheet();
}
?>