<?php
namespace App\Modules\Modules\Views\Admin\TreeList;


use App\Views\Admin\MainList\ViewTableBody as ViewMasterTableBody;

class ViewTableBody extends ViewMasterTableBody {

    public function currentRender() {
        ?>
        <tbody>
        <? /** @var Entity $entity */ foreach ($this->data as $entity): ?>
            <tr>
                <? foreach (array_keys($this->columns) as $field): ?>
                    <? if (isset($this->fieldDecorations[$field])): ?>
                        <td>
                            <?
                            $this->fieldDecorations[$field]->entity = $entity;
                            $this->fieldDecorations[$field]->render()
                            ?>
                        </td>
                    <? else: ?>
                        <td><?= $entity->{$field} ?></td>
                    <? endif; ?>
                <? endforeach; ?>
                <? if (!empty($this->actions)): ?>
                    <td>
                        <? foreach ($this->actions as $action): ?>
                            <a href="<?= $action['url'] ?>?pk=<?= $entity->getPrimaryKey() ?>">
                                <span class="glyphicon <?= $action['icon'] ?>" title="<?= $action['name'] ?>"></span>
                            </a>
                        <? endforeach; ?>
                    </td>
                <? endif; ?>
            </tr>
        <? endforeach; ?>
        </tbody>
        <?
    }

}