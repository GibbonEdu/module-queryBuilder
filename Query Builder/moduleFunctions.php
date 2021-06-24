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

//Returns are array with illeagel SQL keywords
function getIllegals($allowCommands = false)
{
    $illegals = [
        'USE',
        'SHOW DATABASES',
        'SHOW TABLES',
        'DESCRIBE',
        'SHOW FIELDS FROM',
        'SHOW COLUMNS FROM',
        'SHOW INDEX FROM',
        'SET PASSWORD',
        'CREATE TABLE',
        'DROP TABLE',
        'ALTER TABLE',
        'CREATE INDEX',
        'LOAD DATA LOCAL INFILE',
        'GRANT USAGE ON',
        'GRANT SELECT ON',
        'GRANT ALL ON',
        'FLUSH PRIVILEGES',
        'REVOKE ALL ON',
    ];

    if (!$allowCommands) {
        $illegals[] = 'UPDATE';
        $illegals[] = 'DELETE';
        $illegals[] = 'DELETE FROM';
        $illegals[] = 'INSERT';
        $illegals[] = 'INSERT INTO';
    }

    return $illegals;
}

function getAutocompletions($pdo)
{
    $databaseName = $pdo->selectOne('select database()');
    
    $fields = [];
    $tables = $pdo->select("SHOW TABLES")->fetchAll();

    foreach ($tables as $table) {
        $tableName = $table['Tables_in_'.$databaseName];
        $tableFields = $pdo->select("SHOW COLUMNS FROM ".$table['Tables_in_'.$databaseName])->fetchAll();
        $fields[] = $tableName;
        
        foreach ($tableFields as $field) {
            $fields[] = $tableName.'.'.$field['Field'];
        }
    }

    return $fields;
}
