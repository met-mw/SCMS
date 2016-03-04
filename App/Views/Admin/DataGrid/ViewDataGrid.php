<?php


namespace App\Views\Admin\DataGrid;


use App\Views\Admin\ViewPagination;
use SFramework\Classes\DataGrid;
use SFramework\Classes\View;

class ViewDataGrid extends View {

    /** @var DataGrid */
    public $dataGrid;

    public function currentRender() {
        $pagerView = new ViewPagination();

        $this->dataGrid->preparePager();
        $this->dataGrid->fillPager($pagerView);

        ?>
        <div class="panel panel-info">
            <div class="panel-heading"><?= $this->dataGrid->getCaption() ?></div>
            <div class="panel-body">
                <p><?= $this->dataGrid->getDescription() ?></p>
            </div>
        </div>
        <div class="table-responsive">
            <form method="get" class="form-inline">
                <fieldset>
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <? if ($this->dataGrid->hasGroupActions()): ?>
                                <th>
                                    <input type="checkbox" />
                                </th>
                            <? endif; ?>
                            <? foreach ($this->dataGrid->getHeaders() as $header): ?>
                                <th <?= $header->buildAttributes() ?>>
                                    <?= $header->getDisplayName() ?>
                                </th>
                            <? endforeach; ?>
                            <? if (sizeof($this->dataGrid->getActions()) > 0): ?>
                            <th class="text-center">
                                <span class="glyphicon glyphicon-menu-hamburger" title="Действия"></span>
                            </th>
                            <? endif; ?>
                        </tr>
                        <? if ($this->dataGrid->hasFiltered()): ?>
                            <tr class="warning top">
                                <? if ($this->dataGrid->hasGroupActions()): ?>
                                    <th></th>
                                <? endif; ?>
                                <? foreach ($this->dataGrid->getHeaders() as $header): ?>
                                    <th>
                                        <input class="form-control input-sm" style="width: 100%;" name="filter-<?= $header->getKey() ?>" id="filter-<?= $header->getKey() ?>" type="text" placeholder="" value="<?= $header->getFilterValue() ?>">
                                    </th>
                                <? endforeach; ?>
                                <th style="width: 40px;">
                                    <button name="filter" type="submit" class="btn btn-success input-sm" title="Фильтровать"><span class="glyphicon glyphicon-filter"></span></button>
                                </th>
                            </tr>
                        <? endif; ?>
                        </thead>
                        <tbody>
                        <? foreach ($this->dataGrid->getData() as $row): ?>
                            <tr>
                                <? if ($this->dataGrid->hasGroupActions()): ?>
                                    <th>
                                        <input name="selected-<?= $this->dataGrid->getKey() ?>" type="checkbox" />
                                    </th>
                                <? endif; ?>
                                <? foreach ($this->dataGrid->getHeaders() as $header): ?>
                                    <td<?= ' ' . $header->buildValueAttributes() ?>><?= $header->decorate($row[$header->getKey()]) ?></td>
                                <? endforeach; ?>
                                <td>
                                    <? foreach ($this->dataGrid->getActions() as $action): ?>
                                        <a name="action-<?= $action->getName() ?>-<?= $row[$this->dataGrid->getKey()] ?>" href="<?= $action->buildURI($row[$this->dataGrid->getKey()]) ?>"><span class="<?= $action->buildClasses() ?>" title="<?= $action->getTitle() ?>"><?= $action->getDisplayName() ?></span></a>
                                    <? endforeach; ?>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                        <tfoot>
                        <? if ($this->dataGrid->hasGroupActions()): ?>
                        <tr>
                            <td colspan="<?= (count($this->dataGrid->getHeaders()) + ($this->dataGrid->hasGroupActions() ? 2 : 1)) ?>">
                                Групповые операции:&nbsp;
                                <? foreach ($this->dataGrid->getGroupActions() as $action): ?>
                                    <button name="action-<?= $action->getName() ?>-selected" formmethod="post" type="submit" formaction="<?= $action->buildURI() ?>" >
                                        <span class="<?= $action->buildClasses() ?>" title="<?= $action->getTitle() ?>"><?= $action->getDisplayName() ?></span>
                                    </button>
                                <? endforeach; ?>
                            </td>
                        </tr>
                        <? endif; ?>
                        <tr>
                            <td colspan="<?= (count($this->dataGrid->getHeaders()) + ($this->dataGrid->hasGroupActions() ? 2 : 1)) ?>" class="text-left">
                                <div class="form-group">
                                    <select name="items-per-page" class="form-control input-sm" style="width: 100px;">
                                        <option<?= $this->dataGrid->getItemsPerPage() == 5 ? ' selected="selected"' : '' ?>>5</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 10 ? ' selected="selected"' : '' ?>>10</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 20 ? ' selected="selected"' : '' ?>>20</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 50 ? ' selected="selected"' : '' ?>>50</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 100 ? ' selected="selected"' : '' ?>>100</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 500 ? ' selected="selected"' : '' ?>>500</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 1000 ? ' selected="selected"' : '' ?>>1000</option>
                                    </select>
                                    <button name="filter" type="submit" class="btn btn-success input-sm">Отобразить</button>
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </form>
        </div>
        <?
        $pagerView->render();
    }

} 