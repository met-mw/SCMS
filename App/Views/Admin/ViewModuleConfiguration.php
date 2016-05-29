<?php
namespace App\Views\Admin;


use App\Models\Module;
use App\Models\ModuleSetting;
use SFramework\Classes\View;
use SORM\Entity;

class ViewModuleConfiguration extends View {

    /** @var array['type' => 1 | 2, 'setting' => ModuleSetting, 'list' => Entity[] | array, 'value' => mixed] */
    public $settings;

    public function currentRender() {
        if (count($this->settings) == 0):
            ?>
            Настройки не требуются.
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
                    <label for="<?= $setting->parameter ?>"><?= $setting->alias ?></label>
                    <? if ($settingsData['type'] != Module::TYPE_VALUE): ?>
                    <select class="form-control input-sm" name="<?= $setting->parameter ?>" id="<?= $setting->parameter ?>">
                        <option title="Не выбрано" value="0" <?= (is_null($value) ? 'selected="selected"' : '') ?>>Не выбрано</option>
                        <? if ($settingsData['type'] == Module::TYPE_ENTITY): ?>
                            <? foreach ($list as $entity): ?>
                                <option value="<?= $entity->getPrimaryKey() ?>" <?= (!is_null($value) && $entity->getPrimaryKey() == $value ? 'selected="selected"' : '') ?>><?= $entity->name ?></option>
                            <? endforeach; ?>
                        <? elseif ($settingsData['type'] == Module::TYPE_LIST): ?>
                            <? foreach ($list as $currentValue => $name): ?>
                                <option value="<?= $currentValue ?>" <?= (!is_null($currentValue) && $currentValue == $value ? 'selected="selected"' : '') ?>><?= $name ?></option>
                            <? endforeach; ?>
                        <? endif; ?>
                        </select>
                    <? else: ?>
                        <input class="form-control input-sm" type="text" name="<?= $setting->parameter ?>"  id="<?= $setting->parameter ?>" value="<?= $value ?>" />
                    <? endif; ?>
                    <span class="help-block"><?= $setting->description ?></span>
                </div>
            <?
            }
        endif;
    }

}