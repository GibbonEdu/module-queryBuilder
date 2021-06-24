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
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;
use Gibbon\Module\QueryBuilder\Domain\QueryGateway;

$page->breadcrumbs->add(__('Manage Commands'));

if (isActionAccessible($guid, $connection2, '/modules/Query Builder/commands.php') == false) {
    // Access denied
    $page->addError(__('You do not have access to this action.'));
} else {
    $highestAction = getHighestGroupedAction($guid, $_GET['q'], $connection2);
    if (empty($highestAction)) {
        $page->addError(__('You do not have access to this action.'));
        return;
    }

    $search = isset($_GET['search'])? $_GET['search'] : '';

    // CRITERIA
    $queryGateway = $container->get(QueryGateway::class);
    $criteria = $queryGateway->newQueryCriteria(true)
        ->searchBy($queryGateway->getSearchableColumns(), $search)
        ->sortBy(['category', 'gibbonPersonID', 'name'])
        ->pageSize(100)
        ->fromPOST();

    $form = Form::create('search', $_SESSION[$guid]['absoluteURL'].'/index.php', 'get');
    $form->setTitle(__('Search'));
    $form->setClass('noIntBorder fullWidth');

    $form->addHiddenValue('q', '/modules/'.$_SESSION[$guid]['module'].'/queries.php');

    $row = $form->addRow();
        $row->addLabel('search', __('Search For'))->description(__m('Command name and category.'));
        $row->addTextField('search')->setValue($criteria->getSearchText());

    $row = $form->addRow();
        $row->addSearchSubmit($gibbon->session, __('Clear Search'));

    echo $form->getOutput();

    // QUERY
    $queries = $queryGateway->queryQueries($criteria, $_SESSION[$guid]['gibbonPersonID']);

    $table = DataTable::createPaginated('queriesManage', $criteria);
    $table->setTitle(__('Queries'));

    if ($highestAction == 'Manage Queries_viewEditAll') {
        $table->addHeaderAction('add', __('Add'))
            ->setURL('/modules/Query Builder/queries_add.php')
            ->addParam('search', $criteria->getSearchText(true))
            ->addParam('sidebar', 'false')
            ->displayLabel();
    }

    $table->modifyRows(function ($query, $row) {
        if ($query['active'] != 'Y') $row->addClass('error');
        return $row;
    });

    // COLUMNS
    $table->addColumn('type', __('Type'))
        ->format(function ($query) {
            return !is_null($query['queryID'])? 'gibbonedu.com' : $query['type'];
        });
    $table->addColumn('category', __('Category'));
    $table->addColumn('name', __('Name'));
    $table->addColumn('active', __('Active'))
          ->format(Format::using('yesNo', 'active'));

    // ACTIONS
    $table->addActionColumn()
        ->addParam('queryBuilderQueryID')
        ->addParam('search', $criteria->getSearchText(true))
        ->format(function ($query, $actions) use ($highestAction, $guid) {

            if ($highestAction == 'Manage Queries_viewEditAll') {
                if (($query['type'] == 'Personal' && $query['gibbonPersonID'] == $_SESSION[$guid]['gibbonPersonID']) || $query['type'] == 'School') {
                    $actions->addAction('edit', __('Edit Record'))
                        ->setURL('/modules/Query Builder/queries_edit.php')
                        ->addParam('sidebar', 'false');

                    $actions->addAction('delete', __('Delete Record'))
                        ->setURL('/modules/Query Builder/queries_delete.php');
                }

                $actions->addAction('duplicate', __('Duplicate'))
                    ->setURL('/modules/Query Builder/queries_duplicate.php')
                    ->setIcon('copy');
            }

            $actions->addAction('run', __('Run Query'))
                ->setURL('/modules/Query Builder/queries_run.php')
                ->addParam('sidebar', 'false')
                ->setIcon('run');
        });

    echo $table->render($queries);
}
