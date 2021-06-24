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

use Gibbon\Forms\Form;
use Gibbon\Services\Format;
use Gibbon\Module\QueryBuilder\Forms\BindValues;
use Gibbon\Module\QueryBuilder\Domain\QueryGateway;

// Module includes
include __DIR__.'/moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Query Builder/commands_add.php') == false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    // Proceed!
    $page->breadcrumbs
        ->add(__('Manage Commands'), 'commands.php')
        ->add(__('Add Command'));

    $queryGateway = $container->get(QueryGateway::class);
    
    if (isset($_GET['editID'])) {
        $page->return->setEditLink($session->get('absoluteURL').'/index.php?q=/modules/Query Builder/commands_edit.php&queryBuilderQueryID='.$_GET['editID'].'&search='.$_GET['search'].'&sidebar=false');
    }

    $search = $_GET['search'] ?? '';
    if ($search != '') {
        echo "<div class='linkTop'>";
        echo "<a href='".$session->get('absoluteURL')."/index.php?q=/modules/Query Builder/commands.php&search=$search'>".__('Back to Search Results').'</a>';
        echo '</div>';
    }

    $form = Form::create('queryBuilder', $session->get('absoluteURL').'/modules/'.$session->get('module').'/commands_addProcess.php?search='.$search);

    $form->setDescription(Format::alert(__('Commands are SQL statements that can update or delete records in your database. Be careful when creating and editing commands, as these queries can make destructive changes to your data. <b>Always backup your database before working with commands</b>.'), 'warning'));

    $form->addHiddenValue('address', $session->get('address'));

    $form->addHeaderAction('help', __('Help'))
        ->setURL('/modules/Query Builder/queries_help_full.php')
        ->setIcon('help')
        ->addClass('underline')
        ->displayLabel()
        ->modalWindow();

    $types = [
        'Personal' => __('Personal'),
        'School' => __('School'),
    ];
    $row = $form->addRow();
        $row->addLabel('type', __('Type'));
        $row->addSelect('type')->fromArray($types)->required();

    $row = $form->addRow();
        $row->addLabel('name', __('Name'));
        $row->addTextField('name')->maxLength(255)->required();

    $categories = $queryGateway->selectCategoriesByPerson($session->get('gibbonPersonID'))->fetchAll(\PDO::FETCH_COLUMN, 0);
    $row = $form->addRow();
        $row->addLabel('category', __('Category'));
        $row->addTextField('category')->required()->maxLength(100)->autocomplete($categories);

    $row = $form->addRow();
        $row->addLabel('active', __('Active'));
        $row->addYesNo('active')->required();

    $actions = $queryGateway->selectActionListByPerson($session->get('gibbonPersonID'));
    $row = $form->addRow();
        $row->addLabel('moduleActionName', __('Limit Access'))->description(__('Only people with the selected permission can run this query.'));
        $row->addSelect('moduleActionName')->fromResults($actions, 'groupBy')->required()->placeholder();
            
    $row = $form->addRow();
        $row->addLabel('description', __('Description'));
        $row->addTextArea('description')->setRows(8);

    $col = $form->addRow()->addColumn();
        $col->addLabel('query', __('Command'));
        $col->addCodeEditor('query')
            ->setMode('mysql')
            ->autocomplete(getAutocompletions($pdo))
            ->required();

    $bindValues = new BindValues($form->getFactory(), 'bindValues', [], $session);
    $form->addRow()->addElement($bindValues);

    $row = $form->addRow();
        $row->addFooter();
        $row->addSubmit();

    echo $form->getOutput();
}
