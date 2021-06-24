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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//This file describes the module, including database tables

//Basic variables
$name = 'Query Builder';
$description = 'A module to provide SQL queries for pulling data out of Gibbon and exporting it to Excel.';
$entryURL = 'queries.php';
$type = 'Additional';
$category = 'Admin';
$version = '2.00.00';
$author = 'Ross Parker';
$url = 'http://rossparker.org';

//Module tables & gibbonSettings entries
$moduleTables[0] = "CREATE TABLE `queryBuilderQuery` (`queryBuilderQueryID` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT, `type` enum('gibbonedu.com','Personal','School') NOT NULL DEFAULT 'gibbonedu.com', `scope` varchar(30) NOT NULL DEFAULT 'Core', `name` varchar(255) NOT NULL, `category` varchar(50) NOT NULL, `moduleName` VARCHAR(30) NULL DEFAULT NULL, `actionName` VARCHAR(50) NULL DEFAULT NULL, `description` text NOT NULL,  `query` text NOT NULL, `bindValues` TEXT NULL DEFAULT NULL, `active` enum('Y','N') NOT NULL DEFAULT 'Y',  `queryID` int(10) unsigned zerofill DEFAULT NULL COMMENT 'If based on a gibbonedu.org query.',  `gibbonPersonID` int(10) unsigned zerofill DEFAULT NULL,  PRIMARY KEY (`queryBuilderQueryID`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";

//gibbonSettings entries
$gibbonSetting[0] = "INSERT INTO `gibbonSetting` (`scope` ,`name` ,`nameDisplay` ,`description` ,`value`) VALUES ('Query Builder', 'exportDefaultFileType', 'Default Export File Type', '', 'Excel2007');";

//Action rows
$actionRows[] = [
    'name' => 'Manage Queries_viewEditAll',
    'precedence' => '1',
    'category' => 'Queries',
    'description' => 'Allows a user to register with gibbonedu.org to gain access to managed queries.',
    'URLList' => 'queries.php, queries_add.php, queries_edit.php, queries_duplicate.php, queries_delete.php, queries_run.php, queries_sync.php, queries_help_full.php',
    'entryURL' => 'queries.php',
    'defaultPermissionAdmin' => 'Y',
    'defaultPermissionTeacher' => 'N',
    'defaultPermissionStudent' => 'N',
    'defaultPermissionParent' => 'N',
    'defaultPermissionSupport' => 'N',
    'categoryPermissionStaff' => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent' => 'N',
    'categoryPermissionOther' => 'N',
];

$actionRows[] = [
    'name' => 'Manage Settings',
    'precedence' => '0',
    'category' => 'Queries',
    'description' => 'Allows a privileged user to manage Query Builder settings.',
    'URLList' => 'settings_manage.php',
    'entryURL' => 'settings_manage.php',
    'defaultPermissionAdmin' => 'Y',
    'defaultPermissionTeacher' => 'N',
    'defaultPermissionStudent' => 'N',
    'defaultPermissionParent' => 'N',
    'defaultPermissionSupport' => 'N',
    'categoryPermissionStaff' => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent' => 'N',
    'categoryPermissionOther' => 'N',
];

$actionRows[] = [
    'name' => 'Manage Queries_run',
    'precedence' => '0',
    'category' => 'Queries',
    'description' => 'Allows a user to run queries but not add or edit them.',
    'URLList' => 'queries.php, queries_run.php',
    'entryURL' => 'queries.php',
    'defaultPermissionAdmin' => 'N',
    'defaultPermissionTeacher' => 'N',
    'defaultPermissionStudent' => 'N',
    'defaultPermissionParent' => 'N',
    'defaultPermissionSupport' => 'N',
    'categoryPermissionStaff' => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent' => 'N',
    'categoryPermissionOther' => 'N',
];

$actionRows[] = [
    'name' => 'Manage Commands_viewEditAll',
    'precedence' => '1',
    'category' => 'Commands',
    'description' => 'Allows a user to run and edit all commands.',
    'URLList' => 'commands.php, commands_add.php, commands_edit.php, commands_duplicate.php, commands_delete.php, commands_run.php',
    'entryURL' => 'commands.php',
    'defaultPermissionAdmin' => 'Y',
    'defaultPermissionTeacher' => 'N',
    'defaultPermissionStudent' => 'N',
    'defaultPermissionParent' => 'N',
    'defaultPermissionSupport' => 'N',
    'categoryPermissionStaff' => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent' => 'N',
    'categoryPermissionOther' => 'N',
];

$actionRows[] = [
    'name' => 'Manage Commands_run',
    'precedence' => '0',
    'category' => 'Commands',
    'description' => 'Allows a user to run commands but not add or edit them.',
    'URLList' => 'commands.php, commands_run.php',
    'entryURL' => 'commands.php',
    'defaultPermissionAdmin' => 'N',
    'defaultPermissionTeacher' => 'N',
    'defaultPermissionStudent' => 'N',
    'defaultPermissionParent' => 'N',
    'defaultPermissionSupport' => 'N',
    'categoryPermissionStaff' => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent' => 'N',
    'categoryPermissionOther' => 'N',
];
