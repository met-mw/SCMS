<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Models\Module;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\Entities\Decorations\ViewActive;
use App\Views\Admin\Entities\Decorations\ViewCallback;
use App\Views\Admin\Entities\Decorations\ViewIsEmpty;
use App\Views\Admin\Entities\Decorations\ViewParent;
use App\Views\Admin\Entities\ViewList;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerMain extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();

        $parentPK = (int)Param::get('parent_pk', false)->asInteger(false);

        $view = new ViewList();
        $addItemUrl = "/admin/modules/structures/edit/";
        if ($parentPK != 0) {
            $addItemUrl .= "?parent_pk={$parentPK}";
        }
        $view->menu->addItem('Добавить новый элемент структуры', $addItemUrl, 'glyphicon-plus');

        $manifest = $this->moduleInstaller->getManifest($this->moduleName);

        $view->table->caption = $manifest['meta']['alias'];
        $view->table->description = $manifest['meta']['description'];

        $view->table
            ->addAction('Редактирование', '/admin/modules/structures/edit/', 'glyphicon-pencil')
            ->addAction('Удалить', '/admin/modules/structures/delete/', 'glyphicon-trash', false, ['entity-delete'])
        ;

        /** @var Structure $structure */
        $structure = DataSource::factory(Structure::cls());
        $structure->builder()
            ->sqlCalcFoundRows()
            ->where("structure_id={$parentPK}")
            ->order('priority');

        if ($this->pager) {
            $structure->builder()
                ->limit($this->pager->getLimit())
                ->offset($this->pager->getOffset());
        }

        $structures = $structure->findAll();
        if ($this->pager) {
            $this->pager->prepare();
        }
        $view->table->tableBody->data = $structures;
        $view->table
            ->addColumn('id', '№')
            ->addColumn('name', 'Наименование')
            ->addColumn('description', 'Описание')
            ->addColumn('path', 'Путь')
            ->addColumn('frame', 'Фрейм')
            ->addColumn('module_id', 'Модуль')
            ->addColumn('priority', 'Приоритет')
            ->addColumn('seo_title', 'SEO T')
            ->addColumn('seo_description', 'SEO D')
            ->addColumn('seo_keywords', 'SEO K')
            ->addColumn('anchor', 'Фрагмент')
            ->addColumn('active', 'Активна')
        ;

        $view->table->tableBody->addDecoration('seo_title', new ViewIsEmpty('seo_title'));
        $view->table->tableBody->addDecoration('seo_description', new ViewIsEmpty('seo_description'));
        $view->table->tableBody->addDecoration('seo_keywords', new ViewIsEmpty('seo_keywords'));
        $view->table->tableBody->addDecoration('anchor', new ViewActive('anchor'));
        $view->table->tableBody->addDecoration('active', new ViewActive('active'));

        $parentUrl = '/admin/modules/structures/';
        $view->table->tableBody->addDecoration('name', new ViewParent('name', $parentUrl));
        $view->table->tableBody->addDecoration('module_id',
            new ViewCallback(
                'module_id',
                function($moduleId) {
                    if ($moduleId == 0) {
                        return;
                    }

                    /** @var Module $module */
                    $module = DataSource::factory(Module::cls(), $moduleId);
                    echo $module->name;
                }
            )
        );

        if ($this->pager) {
            $this->fillPager($view);
        }


        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs($parentPK);
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $this->breadcrumbs->setIgnores(['page', 'back_params']);
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame->bindView('content', $view);

        $this->frame->render();
    }

    protected function fillBreadcrumbs($parentId = 0) {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Структура сайта', 'structures');
        $bcStructures = $bcModules->findChildNodeByPath('structures');

        if ($parentId != 0) {
            $aStructures = [];
            /** @var Structure $oStructure */
            $oStructure = DataSource::factory(Structure::cls(), $parentId);
            $aStructures[] = $oStructure;
            while ($oStructure->structure_id != 0) {
                $oStructure = DataSource::factory(Structure::cls(), $oStructure->structure_id);
                $aStructures[] = $oStructure;
            }

            foreach (array_reverse($aStructures) as $oStructure) {
                $path = "parent_pk={$oStructure->id}";
                $bcStructures->addChildNode($oStructure->name, $path, true, false, true);
                $bcStructures = $bcStructures->findChildNodeByPath($path);
            }
        }
    }

} 