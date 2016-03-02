<?php
namespace App\Views\Admin\Entities;


use App\Views\Admin\MainList\ViewTableBody as ViewMasterTableBody;
use SORM\Entity;

class ViewTableBody extends ViewMasterTableBody {

    public function currentRender() {
        $groupActions = [];
        foreach ($this->actions as $action) {
            if ($action['group']) {
                $groupActions[] = $action;
            }
        }

        ?>
        <tbody>
        <? /** @var Entity $entity */ foreach ($this->data as $entity): ?>
            <tr>
                <td>
                    <input type="checkbox"/>
                </td>
                <? foreach (array_keys($this->columns) as $field): ?>
                    <? if (isset($this->fieldDecorations[$field])): ?>
                        <td>
                            <?
                            $this->fieldDecorations[$field]->bindData($entity);
                            $this->fieldDecorations[$field]->render()
                            ?>
                        </td>
                    <? else: ?>
                        <td><?= $entity->{$field} ?></td>
                    <? endif; ?>
                <? endforeach; ?>
                <? if (!empty($this->actions)): ?>
                <td style="width: 80px;">
                    <? foreach ($this->actions as $action): ?>
                        <a href="<?= $action['url'] ?>?pk=<?= $entity->getPrimaryKey() ?>"><span class="glyphicon <?= $action['icon'] ?><?= (' ' . implode(' ', $action['classes'])) ?>" title="<?= $action['name'] ?>"></span></a>
                    <? endforeach; ?>
                </td>
                <? endif; ?>
            </tr>
        <? endforeach; ?>
            <tr>
                <td>
                <input type="checkbox"/>
                </td>
                <td colspan="<?= (count($this->columns) + 1) ?>">
                    <? if (count($groupActions)): ?>
                    <? foreach ($groupActions as $action): ?>
                        <a href="<?= $action['url'] ?>?pk=<?= $entity->getPrimaryKey() ?>">
                            <span class="glyphicon <?= $action['icon'] ?><?= (' ' . implode(' ', $action['classes'])) ?>" title="<?= $action['name'] ?>"></span>
                        </a>
                    <? endforeach; ?>
                    <? else: ?>
                        Групповых операций не предусмотрено.
                    <? endif; ?>
                </td>
            </tr>
        </tbody>
        <?
    }

}