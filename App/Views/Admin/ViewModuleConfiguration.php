<?php
namespace App\Views\Admin;


use App\Models\ModuleSetting;
use SFramework\Classes\View;
use SORM\Entity;

class ViewModuleConfiguration extends View {

    /** @var array['setting' => ModuleSetting, 'list' => Entity[], 'value' => mixed] */
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
                    <? if ($setting->entity != ''): ?>
                    <select class="form-control input-sm" name="<?= $setting->parameter ?>" id="<?= $setting->parameter ?>">
                        <option title="Не выбрано" value="0" <?= (is_null($value) ? 'selected="selected"' : '') ?>>Не выбрано</option>
                        <? foreach ($list as $entity): ?>
                            <option title="<?= $entity->description ?>" value="<?= $entity->getPrimaryKey() ?>" <?= (!is_null($value) && $entity->getPrimaryKey() == $value ? 'selected="selected"' : '') ?>><?= $entity->name ?></option>
                        <? endforeach; ?>
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