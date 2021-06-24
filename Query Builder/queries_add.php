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
use Gibbon\Module\QueryBuilder\Forms\BindValues;
use Gibbon\Module\QueryBuilder\Domain\QueryGateway;

// Module includes
include __DIR__.'/moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Query Builder/queries_add.php') == false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    // Proceed!
    $page->breadcrumbs
        ->add(__('Manage Queries'), 'queries.php')
        ->add(__('Add Query'));

    $queryGateway = $container->get(QueryGateway::class);
    
    if (isset($_GET['editID'])) {
        $page->return->setEditLink($session->get('absoluteURL').'/index.php?q=/modules/Query Builder/queries_edit.php&queryBuilderQueryID='.$_GET['editID'].'&search='.$_GET['search'].'&sidebar=false');
    }

    $search = $_GET['search'] ?? '';
    if ($search != '') {
        echo "<div class='linkTop'>";
        echo "<a href='".$session->get('absoluteURL')."/index.php?q=/modules/Query Builder/queries.php&search=$search'>".__('Back to Search Results').'</a>';
        echo '</div>';
    }

    $form = Form::create('queryBuilder', $session->get('absoluteURL').'/modules/'.$session->get('module').'/queries_addProcess.php?search='.$search);

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
        $row->addSelect('type')->fromArray($types)->isRequired();

    $row = $form->addRow();
        $row->addLabel('name', __('Name'));
        $row->addTextField('name')->maxLength(255)->isRequired();

    $categories = $queryGateway->selectCategoriesByPerson($session->get('gibbonPersonID'))->fetchAll(\PDO::FETCH_COLUMN, 0);
    $row = $form->addRow();
        $row->addLabel('category', __('Category'));
        $row->addTextField('category')->isRequired()->maxLength(100)->autocomplete($categories);

    $row = $form->addRow();
        $row->addLabel('active', __('Active'));
        $row->addYesNo('active')->isRequired();

    $actions = $queryGateway->selectActionListByPerson($session->get('gibbonPersonID'));
    $row = $form->addRow();
        $row->addLabel('moduleActionName', __('Limit Access'))->description(__('Only people with the selected permission can run this query.'));
        $row->addSelect('moduleActionName')->fromResults($actions, 'groupBy')->placeholder();
            
    $row = $form->addRow();
        $row->addLabel('description', __('Description'));
        $row->addTextArea('description')->setRows(8);

    $col = $form->addRow()->addColumn();
        $col->addLabel('query', __('Query'));
        $col->addCodeEditor('query')
            ->setMode('mysql')
            ->autocomplete(getAutocompletions($pdo))
            ->isRequired();

    $bindValues = new BindValues($form->getFactory(), 'bindValues', [], $session);
    $form->addRow()->addElement($bindValues);

    $row = $form->addRow();
        $row->addFooter();
        $row->addSubmit();

    echo $form->getOutput();
}
