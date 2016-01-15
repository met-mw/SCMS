<?php
namespace App\Modules\Frames\Views\Admin;


use App\Views\Admin\MainList\ViewTableBody as ViewMasterTableBody;

class ViewTableBody extends ViewMasterTableBody {

    public function currentRender() {
        ?>
        <tbody>
        <? foreach ($this->data as $fileName): ?>
            <tr>
                <? foreach (array_keys($this->columns) as $field): ?>
                    <? if (isset($this->fieldDecorations[$field])): ?>
                        <td>
                            <?
                            $this->fieldDecorations[$field]->bindData($fileName);
                            $this->fieldDecorations[$field]->render()
                            ?>
                        </td>
                    <? else: ?>
                        <td><?= $fileName ?></td>
                    <? endif; ?>
                <? endforeach; ?>
                <? if (!empty($this->actions)): ?>
                    <td style="width: 80px;">
                        <? foreach ($this->actions as $action): ?>
                            <a href="<?= $action['url'] ?>?name=<?= $fileName ?>">
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