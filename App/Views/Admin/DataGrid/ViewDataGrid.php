<?php


namespace App\Views\Admin\DataGrid;


use SFramework\Classes\CoreFunctions;
use SFramework\Classes\DataGrid;
use SFramework\Classes\View;

class ViewDataGrid extends View {

    /** @var DataGrid */
    public $dataGrid;

    public function currentRender() {
        $pagerView = new ViewPagination();
        $pagerView->formName = $this->dataGrid->getName();
        $menuView = new ViewMenu();

        $this->dataGrid->preparePager();
        $this->dataGrid->fillPager($pagerView);

        $menuView->menu = $this->dataGrid->getMenu();
        $menuView->render();
        ?>
        <hr/>
        <div class="panel panel-info">
            <div class="panel-heading"><?= $this->dataGrid->getCaption() ?></div>
            <div class="panel-body">
                <p><?= $this->dataGrid->getDescription() ?></p>
            </div>
        </div>
        <div class="table-responsive">
            <form action="<?= $this->dataGrid->getAction() ?>" class="form-inline s-datagrid" name="<?= $this->dataGrid->getName() ?>" id="<?= $this->dataGrid->getName() ?>">
                <fieldset>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <? if ($this->dataGrid->hasGroupActions()): ?>
                                <th style="width: 30px;">
                                    <span class="glyphicon glyphicon-check"></span>
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
                                        <input class="form-control input-sm" style="width: 100%;" name="<?= $this->dataGrid->getName() ?>-filter-<?= $header->getKey() ?>" id="<?= $this->dataGrid->getName() ?>-filter-<?= $header->getKey() ?>" type="text" placeholder="" value="<?= $header->getFilterValue() ?>">
                                    </th>
                                <? endforeach; ?>
                                <th style="width: 40px;">
                                    <button name="<?= $this->dataGrid->getName() ?>-filter" type="submit" class="btn btn-info btn-sm" formmethod="get" title="Фильтровать"><span class="glyphicon glyphicon-filter"></span></button>
                                </th>
                            </tr>
                        <? endif; ?>
                        </thead>
                        <tbody>
                        <? foreach ($this->dataGrid->getData() as $row): ?>
                            <tr>
                                <? if ($this->dataGrid->hasGroupActions()): ?>
                                    <th>
                                        <input name="<?= $this->dataGrid->getName() ?>-checked-row-flag-<?= $row[$this->dataGrid->getKey()] ?>" type="checkbox" />
                                    </th>
                                <? endif; ?>
                                <? foreach ($this->dataGrid->getHeaders() as $header): ?>
                                    <td<?= ' ' . $header->buildValueAttributes() ?>><?= $header->decorate($row[$header->getKey()], $row) ?></td>
                                <? endforeach; ?>
                                <td>
                                    <? foreach ($this->dataGrid->getActions() as $action): ?>
                                        <a name="<?= $this->dataGrid->getName() ?>-action-<?= $action->getName() ?>-<?= $this->dataGrid->getKey() ?>" href="<?= CoreFunctions::addGETParamToURI($action->getURI(), $action->getParamName(), $row[$this->dataGrid->getKey()]) ?>"><span <?= $action->buildAttributes() ?> title="<?= $action->getTitle() ?>"><?= $action->getDisplayName() ?></span></a>
                                    <? endforeach; ?>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                        <tfoot>
                        <? if ($this->dataGrid->hasGroupActions()): ?>
                        <tr>
                            <td>
                                <input type="checkbox" id="<?= $this->dataGrid->getName() ?>-group-action-all-check" name="<?= $this->dataGrid->getName() ?>-group-action-all-check""/>
                            </td>
                            <td colspan="<?= count($this->dataGrid->getHeaders()) + 1 ?>">
                                Групповые операции:&nbsp;
                                <? foreach ($this->dataGrid->getGroupActions() as $action): ?>
                                    <button name="<?= $this->dataGrid->getName() ?>-action-group-<?= $action->getName() ?>" id="<?= $this->dataGrid->getName() ?>-group-action-<?= $action->getName() ?>" formmethod="post" type="submit" formaction="<?= $action->buildGroupURI() ?>" title="<?= $action->getTitle() ?>">
                                        <span <?= $action->buildAttributes() ?>><?= $action->getDisplayName() ?></span>
                                    </button>
                                <? endforeach; ?>
                            </td>
                        </tr>
                        <? endif; ?>
                        <tr>
                            <td colspan="<?= (count($this->dataGrid->getHeaders()) + ($this->dataGrid->hasGroupActions() ? 2 : 1)) ?>" class="text-left">
                                <div class="form-group">
                                    <select name="<?= $this->dataGrid->getName() ?>-items-per-page" id="<?= $this->dataGrid->getName() ?>-items-per-page" class="form-control input-sm" style="width: 80px;">
                                        <option<?= $this->dataGrid->getItemsPerPage() == 5 ? ' selected="selected"' : '' ?>>5</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 10 ? ' selected="selected"' : '' ?>>10</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 20 ? ' selected="selected"' : '' ?>>20</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 50 ? ' selected="selected"' : '' ?>>50</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 100 ? ' selected="selected"' : '' ?>>100</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 500 ? ' selected="selected"' : '' ?>>500</option>
                                        <option<?= $this->dataGrid->getItemsPerPage() == 1000 ? ' selected="selected"' : '' ?>>1000</option>
                                    </select>
                                    <button name="<?= $this->dataGrid->getName() ?>-set-items-per-page" type="submit" class="btn btn-info btn-sm" title="Отобразить" formmethod="get"><span class="glyphicon glyphicon-ok"></span></button>
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </fieldset>
                <? foreach ($this->dataGrid->getHiddenFields() as $name => $value): ?>
                    <input type="hidden" name="<?= $name ?>" value="<?= $value ?>"/>
                <? endforeach; ?>
            </form>
        </div>
        <?
        $pagerView->render();
    }

}