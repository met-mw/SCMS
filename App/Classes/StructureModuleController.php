<?php
namespace App\Classes;


use App\Models\Structure;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Registry;
use SORM\DataSource;
use SORM\Tools\Builder;

class StructureModuleController extends StructureController {

    /** @var Structure */
    protected $structure;

    public function __construct() {
        parent::__construct();
        $this->bindStructure();
    }

    public function actionIndex() {
        if (CoreFunctions::isAJAX()) {
            $this->executeModule($this->getStructure());
        } else {
            ob_start();
            $this->executeModule($this->getStructure());
            $content = ob_get_contents();
            ob_end_clean();

            $frame = $this->getActiveFrame($this->getStructure());
            $frame->bindData('content', $content);

            $frame->render();
        }
    }

    protected function executeModule(Structure $oStructure) {
        if (!$oStructure->active || $oStructure->module_id == 0) {
            $controllerName = $this->Router->error404();
            $action = 'actionIndex';
            $controller = new $controllerName();
            $controller->{$action}();

            return;
        }

        $oModule = $oStructure->getModule();
        $modulePath = SFW_MODULES_ROOT . ucfirst($oModule->name) . DIRECTORY_SEPARATOR;
        $indexFilePath = "{$modulePath}manifest.php";
        $manifest = include($indexFilePath);

        if (!$oStructure->anchor) {
            $frame = $this->getActiveFrame($oStructure);

            $seoTitle = $oStructure->seo_title;
            $seoDescription = $oStructure->seo_description;
            $seoKeywords = $oStructure->seo_keywords;
            $frame->setTitle(empty($seoTitle) ? $oStructure->name : $seoTitle);
            if (!empty($seoDescription)) {
                $frame->addMeta(['name' => 'description', 'content' => $seoDescription]);
            }
            if (!empty($seoKeywords)) {
                $frame->addMeta(['name' => 'keywords', 'content' => $seoKeywords]);
            }
        } else {
            $oClosestIndStructure = $oStructure->getClosetsIndStructure();
            $frame = $this->getActiveFrame($oClosestIndStructure);
        }


        $run = $manifest['run'];
        $controllerName = "App\\Modules\\" . ucfirst($oModule->name) . "\\" . "{$run['controller']}";

        $aModuleSettings = $oModule->getModuleSettings();
        $aStructureSettings = $oStructure->getStructureSettings();

        /** @var PublicAreaController $controller */
        $controller = new $controllerName($frame, $manifest, $oStructure);
        foreach ($aStructureSettings as $oStructureSetting) {
            foreach ($aModuleSettings as $oModuleSetting) {
                if ($oModuleSetting->id == $oStructureSetting->module_setting_id) {
                    $controller->{$oModuleSetting->parameter} = $oStructureSetting->value;
                }
            }
        }

        $controller->{$manifest['run']['action']}();
        $aStructureFragments = $oStructure->getStructureFragments();
        foreach ($aStructureFragments as $oStructureFragment) {
            if ($oStructureFragment->active && !$oStructureFragment->deleted) {
                $this->executeModule($oStructureFragment);
            }
        }
    }

    protected function getActiveFrame(Structure $oStructure)
    {
        return $oStructure->frame != ''
            ? Registry::frame($oStructure->frame)
            : $this->Frame;
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