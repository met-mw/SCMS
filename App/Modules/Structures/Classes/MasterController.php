<?php
/**
 * Created by PhpStorm.
 * User: metr
 * Date: 21.11.15
 */

namespace App\Modules\Structures\Classes;


use \App\Classes\MasterController as MasterDefaultController;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\StructureSetting;
use App\Modules\Modules\Classes\MasterController as MasterModuleController;
use App\Modules\Structures\Models\Structure;
use SFramework\Classes\Registry;
use SORM\DataSource;
use SORM\Tools\Builder;

class MasterController extends MasterDefaultController {

    /** @var Structure */
    protected $structure;

    public function __construct() {
        parent::__construct();
        $this->bindStructure();
    }

    public function actionIndex() {
        $this->executeModule();
    }

    protected function executeModule() {
        if (!$this->structure->active || $this->structure->module_id == 0) {
            $controllerName = $this->router->error404();
            $action = 'actionIndex';
            $controller = new $controllerName();
            $controller->{$action}();

            return;
        }

        /** @var Module $module */
        $module = DataSource::factory(Module::cls(), $this->structure->module_id);
        $modulePath = SFW_MODULES_ROOT . ucfirst($module->name) . DIRECTORY_SEPARATOR;
        $indexFilePath = "{$modulePath}manifest.php";
        $manifest = include($indexFilePath);
        $frame = $this->structure->frame != ''
            ? Registry::frame($this->structure->frame)
            : $this->frame;

        $seoTitle = $this->structure->seo_title;
        $seoDescription = $this->structure->seo_description;
        $seoKeywords = $this->structure->seo_keywords;
        $frame->setTitle(empty($seoTitle) ? $this->structure->name : $seoTitle);
        if (!empty($seoDescription)) {
            $frame->addMeta(['name' => 'description', 'content' => $seoDescription]);
        }
        if (!empty($seoKeywords)) {
            $frame->addMeta(['name' => 'keywords', 'content' => $seoKeywords]);
        }

        $controllerName = "App\\Modules\\" . ucfirst($module->name) . "\\{$manifest['run']['controller']}";

        /** @var ModuleSetting[] $moduleSettings */
        $moduleSettings = $module->field()->loadRelation(ModuleSetting::cls());
        /** @var StructureSetting[] $structureSettings */
        $structureSettings = $this->structure->field()->loadRelation(StructureSetting::cls());

        /** @var MasterModuleController $controller */
        $controller = new $controllerName($frame);
        foreach ($structureSettings as $oStructureSetting) {
            foreach ($moduleSettings as $oModuleSetting) {
                if ($oModuleSetting->module_id == $oStructureSetting->module_setting_id) {
                    $controller->{$oModuleSetting->parameter} = $oStructureSetting->value;
                }
            }
        }

        $controller->{$manifest['run']['action']}();
    }

    protected function getStructure() {
        return $this->structure;
    }

    protected function bindStructure() {
        $structure = DataSource::factory(Structure::cls());
        $structure->builder()
            ->where('path=' . $structure->field('path')->type->toQueryWithQuotes(basename($this->currentPath)))
            ->limit(1);
        $structures = $structure->findAll();
        $this->structure = reset($structures);
    }

} 