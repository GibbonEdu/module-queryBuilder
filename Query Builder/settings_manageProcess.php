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

use Gibbon\Domain\System\SettingGateway;

include '../../gibbon.php';

$URL = $session->get('absoluteURL').'/index.php?q=/modules/Query Builder/settings_manage.php';

if (isActionAccessible($guid, $connection2, '/modules/Query Builder/settings_manage.php') == false) {
    $URL .= '&return=error0';
    header("Location: {$URL}");
    exit;
} else {
    // Proceed!
    $partialFail = false;

    $settingGateway = $container->get(SettingGateway::class);
    $settingsToUpdate = [
        'Query Builder' => [
            'exportDefaultFileType',
        ]
    ];

    foreach ($settingsToUpdate as $scope => $settings) {
        foreach ($settings as $name) {
            $value = $_POST[$name] ?? null;
            if (empty($value)) continue;

            $updated = $settingGateway->updateSettingByScope($scope, $name, $value);
            $partialFail &= !$updated;
        }
    }

    $URL .= $partialFail
        ? '&return=error2'
        : '&return=success0';
    header("Location: {$URL}");
}
