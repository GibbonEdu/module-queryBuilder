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



if (isActionAccessible($guid, $connection2, "/modules/Query Builder/queries_duplicate.php")==FALSE) {
	//Acess denied
	print "<div class='error'>" ;
		print _("You do not have access to this action.") ;
	print "</div>" ;
}
else {
	//Proceed!
	print "<div class='trail'>" ;
	print "<div class='trailHead'><a href='" . $session->get("absoluteURL") . "'>" . _("Home") . "</a> > <a href='" . $session->get("absoluteURL") . "/index.php?q=/modules/" . getModuleName($_GET["q"]) . "/" . getModuleEntry($_GET["q"], $connection2, $guid) . "'>" . getModuleName($_GET["q"]) . "</a> > <a href='" . $session->get("absoluteURL") . "/index.php?q=/modules/" . getModuleName($_GET["q"]) . "/queries.php'>" . _('Manage Queries') . "</a> > </div><div class='trailEnd'>" . _('Duplicate Query') . "</div>" ;
	print "</div>" ;
	
	if (isset($_GET["updateReturn"])) { $updateReturn=$_GET["updateReturn"] ; } else { $updateReturn="" ; }
	$updateReturnMessage="" ;
	$class="error" ;
	if (!($updateReturn=="")) {
		if ($updateReturn=="fail0") {
			$updateReturnMessage=_("Your request failed because you do not have access to this action.") ;	
		}
		else if ($updateReturn=="fail1") {
			$updateReturnMessage=_("Your request failed because your inputs were invalid.") ;	
		}
		else if ($updateReturn=="fail2") {
			$updateReturnMessage=_("Your request failed due to a database error.") ;	
		}
		else if ($updateReturn=="fail3") {
			$updateReturnMessage=_("Your request failed because your inputs were invalid.") ;	
		}
		else if ($updateReturn=="fail4") {
			$updateReturnMessage=_("Your request failed because your inputs were invalid.") ;	
		}
		else if ($updateReturn=="success0") {
			$updateReturnMessage=_("Your request was completed successfully.") ;	
			$class="success" ;
		}
		print "<div class='$class'>" ;
			print $updateReturnMessage;
		print "</div>" ;
	} 
	
	$search=NULL ;
	if (isset($_GET["search"])) {
		$search=$_GET["search"] ;
	}
	if ($search!="") {
		print "<div class='linkTop'>" ;
			print "<a href='" . $session->get("absoluteURL") . "/index.php?q=/modules/Query Builder/queries.php&search=$search'>" . _('Back to Search Results') . "</a>" ;
		print "</div>" ;
	}
	
	//Check if school year specified
	$queryBuilderQueryID=$_GET["queryBuilderQueryID"] ;
	if ($queryBuilderQueryID=="") {
		print "<div class='error'>" ;
			print _("You have not specified one or more required parameters.") ;
		print "</div>" ;
	}
	else {
		$data=array("queryBuilderQueryID"=>$queryBuilderQueryID); 
		$sql="SELECT * FROM queryBuilderQuery WHERE queryBuilderQueryID=:queryBuilderQueryID" ;
		$error = "<div class='error'>\n" . $e->getMessage() . "\n</div>\n" ; 

		$pdo->executeQuery($data, $sql);
		
		if ($result->rowCount()!=1) {
			print "<div class='error'>" ;
				print _("The specified record cannot be found.") ;
			print "</div>" ;
		}
		else {
			//Let's go!
			$row=$result->fetch() ;
			?>
			<form method="post" action="<?php print $session->get("absoluteURL") . "/modules/" . $session->get("module") . "/queries_duplicateProcess.php?queryBuilderQueryID=$queryBuilderQueryID&search=$search" ?>">
				<table class='smallIntBorder' cellspacing='0' style="width: 100%">	
					<tr>
						<td> 
							<b><?php print _('New Name') ?> *</b><br/>
						</td>
						<td class="right">
							<input name="name" id="name" maxlength=255 value="<?php print htmlPrep($row["name"]) ?>" type="text" style="width: 300px">
							<script type="text/javascript">
								var name=new LiveValidation('name');
								name.add(Validate.Presence);
							 </script> 
						</td>
					</tr>
					<tr>
						<td> 
							<b><?php print _('Type') ?> *</b><br/>
							<span style="font-size: 90%"><i></i></span>
						</td>
						<td class="right">
							<select name="type" id="type" style="width: 302px">
								<option value="Personal"><?php print _('Personal') ?></option>
								<option value="School"><?php print _('School') ?></option>
							</select>
						</td>
					</tr>	
					<tr>
						<td>
							<span style="font-size: 90%"><i>* <?php print _("denotes a required field") ; ?></i></span>
						</td>
						<td class="right">
							<input type="hidden" name="address" value="<?php print $session->get("address") ?>">
							<input type="submit" value="<?php print _("Submit") ; ?>">
						</td>
					</tr>
				</table>
			</form>
			<?php
		}
	}
}
?>