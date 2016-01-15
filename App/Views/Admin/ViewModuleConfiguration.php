<?php
namespace App\Views\Admin;


use App\Models\ModuleSetting;
use SFramework\Classes\View;
use SORM\Entity;

class ViewModuleConfiguration extends View {

    /** @var array['setting' => ModuleSetting, 'list' => Entity[], 'value' => mixed] */
    public $settings;

    public function currentRender() {
        ?>
        <div class="thumbnail">
        <h3>Настройки модуля</h3>
        <?
        if (count($this->settings) == 0):
            ?>
            Модуль не выбран, настройки не требуются.
            <?
        else:
            foreach ($this->settings as $settingsData) {
                /** @var ModuleSetting $setting */
                $setting = $settingsData['setting'];
                /** @var Entity[] $list */
                $list = $settingsData['list'];
                $value = $settingsData['value'];
                ?>
                <div class="control-group">
                    <label><?= $setting->alias ?></label>
                    <div class="controls">
                        <?
                        if ($setting->entity != '') {
                            ?>
                            <select name="<?= $setting->parameter ?>">
                                <option title="Не выбрано" value="0" <?= (is_null($value) ? 'selected="selected"' : '') ?>>Не выбрано</option>
                                <? foreach ($list as $entity): ?>
                                    <option title="<?= $entity->description ?>" value="<?= $entity->getPrimaryKey() ?>" <?= (!is_null($value) && $entity->getPrimaryKey() == $value ? 'selected="selected"' : '') ?>><?= $entity->name ?></option>
                                <? endforeach; ?>
                            </select>
                        <?
                        } else {
                            ?>
                            <input type="text" name="<?= $setting->parameter ?>" value="<?= $value ?>" />
                        <?
                        }
                        ?>
                    </div>
                    <span class="help-block"><?= $setting->description ?></span>
                </div>
            <?
            }
        endif;
        ?>
        </div>
    <?
    }

}