<?php
namespace App\Classes;


use App\Models\Structure;
use App\Views\ViewMainMenu;
use App\Views\ViewMenuItems;
use SFramework\Classes\Controller;
use SFramework\Classes\Menu;
use SFramework\Classes\Menu\Item;
use SFramework\Classes\Registry;
use SFramework\Classes\Router;
use SORM\DataSource;
use SORM\Tools\Builder;

class StructureController extends Controller
{

    /** @var array */
    protected $config;
    /** @var Router */
    protected $Router;
    /** @var string */
    protected $currentPath;

    public function __construct()
    {
        $this->config = Registry::get('config');
        $this->Frame = Registry::frame('front');
        $this->Router = Registry::router();
        $this->currentPath = implode('/', $this->Router->explodeRoute());

        $this->Frame->bindView('menu', $this->buildMenu());
    }

    /**
     * @param int $parentStructureId
     *
     * @return Structure[]
     */
    protected function getStructuresByParentId($parentStructureId = 0)
    {
        $oStructures = DataSource::factory(Structure::cls());
        $oStructures->builder()
            ->where("structure_id={$parentStructureId}")
            ->whereAnd()
            ->where("active=1")
            ->order('priority');
        /** @var Structure[] $aStructures */
        $aStructures = $oStructures->findAll();

        return $aStructures;
    }

    protected function setMenuItems(ViewMenuItems $ViewMenuItems, $structureParentId = 0)
    {
        $aStructures = $this->getStructuresByParentId($structureParentId);
        foreach ($aStructures as $oStructure) {
            if ($oStructure->active && !$oStructure->deleted && !$oStructure->anchor) {
                $ViewMenuItems->addItem($oStructure->path, $oStructure->name, null, null, $this->currentPath == $oStructure->path);
                $this->setMenuItems($ViewMenuItems->getItem($oStructure->path)->itemsList, $oStructure->id);
            }
        }
    }

    protected function loadMenuItems(Item $MenuItem, Structure $oCurrentStructure)
    {
        $aStructures = $oCurrentStructure->getStructures();
        foreach ($aStructures as $oStructure) {
            if (!$oStructure->active || $oStructure->deleted || $oStructure->anchor) {
                continue;
            }

            $MenuItem->addChildItem($oStructure->name, $oStructure->path);
            $this->loadMenuItems($MenuItem->findChildItemByPath($oStructure->path), $oStructure);
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

        $ViewMainMenu = new ViewMainMenu($this->config['name']);
        $this->setMenuItems($ViewMainMenu->itemsList);
        $currentPath = explode('?', $this->Router->getRoute());
        $ViewMainMenu->currentPath = reset($currentPath);

        return $ViewMainMenu;
    }

} 