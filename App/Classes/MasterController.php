<?php
namespace App\Classes;


use App\Modules\Structures\Models\Structure;
use App\Views\ViewMainMenu;
use App\Views\ViewMenuItems;
use SFramework\Classes\Controller;
use SFramework\Classes\Menu;
use SFramework\Classes\Menu\Item;
use SFramework\Classes\Registry;
use SFramework\Classes\Router;
use SORM\DataSource;
use SORM\Tools\Builder;

class MasterController extends Controller {

    /** @var array */
    protected $config;
    /** @var Router */
    protected $router;
    /** @var string */
    protected $currentPath;

    public function __construct() {
        $this->config = Registry::get('config');
        $this->frame = Registry::frame('front');
        $this->router = Registry::router();
        $this->currentPath = implode('/', $this->router->explodeRoute());

        $this->frame->bindView('menu', $this->buildMenu());
    }

    /**
     * @param int $parentStructureId
     *
     * @return Structure[]
     */
    protected function getStructuresByParentId($parentStructureId = 0) {
        $structure = DataSource::factory(Structure::cls());
        $structure->builder()
            ->where("structure_id={$parentStructureId}")
            ->whereAnd()
            ->where("active=1")
            ->order('priority');
        /** @var Structure[] $structures */
        $structures = $structure->findAll();

        return $structures;
    }

    protected function setMenuItems(ViewMenuItems $itemList, $structureParentId = 0) {
        foreach ($this->getStructuresByParentId($structureParentId) as $oStructure) {
            if ($oStructure->active && !$oStructure->deleted && !$oStructure->anchor) {
                $itemList->addItem($oStructure->path, $oStructure->name);
                $this->setMenuItems($itemList->getItem($oStructure->path)->itemsList, $oStructure->id);
            }
        }
    }

    protected function loadMenuItems(Item $menuItem, Structure $oCurrentStructure) {
        $aStructures = $oCurrentStructure->getStructures();
        foreach ($aStructures as $oStructure) {
            if (!$oStructure->active || $oStructure->deleted || $oStructure->anchor) {
                continue;
            }

            $menuItem->addChildItem($oStructure->name, $oStructure->path);
            $this->loadMenuItems($menuItem->findChildItemByPath($oStructure->path), $oStructure);
        }
    }

    protected function buildMenu() {
//        $menu = new Menu($this->config['name']);
//        $structures = DataSource::factory(Structure::cls());
//        $structures->builder()
//            ->where("structure_id=0")
//            ->order('priority');
//        /** @var Structure[] $aStructures */
//        $aStructures = $structures->findAll();
//        foreach ($aStructures as $oStructure) {
//            $menu->addLeftItem($oStructure->name, $oStructure->path);
//            $this->loadMenuItems($menu->findLeftItemByPath($oStructure->path), $oStructure);
//        }
//
//        $view = new ViewMMenu();
//        $view->menu = $menu;
//        return $view;



        $mainMenu = new ViewMainMenu($this->config['name']);
        $this->setMenuItems($mainMenu->itemsList);
        $currentPath = explode('?', $this->router->getRoute());
        $mainMenu->currentPath = reset($currentPath);

        return $mainMenu;
    }

} 