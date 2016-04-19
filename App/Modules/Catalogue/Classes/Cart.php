<?php
namespace App\Modules\Catalogue\Classes;


use App\Modules\Catalogue\Models\Item;
use SORM\DataSource;

class Cart
{

    const SESSION_SECTION_NAME = 's-catalogue-cart';

    /** @var array */
    protected $items = [];

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION[self::SESSION_SECTION_NAME])) {
            $_SESSION[self::SESSION_SECTION_NAME] = [];
        }

        $cartData = $_SESSION[self::SESSION_SECTION_NAME];
        foreach ($cartData as $id => $data) {
            $this->items[$id] = $data;
        }
    }

    public function isEmpty()
    {
        return empty($_SESSION[self::SESSION_SECTION_NAME]);
    }

    public function addItem($catalogueItemId, $count)
    {
        /** @var Item $oCatalogueItem */
        $oCatalogueItem = DataSource::factory(Item::cls(), $catalogueItemId);
        if ($oCatalogueItem) {
            if (isset($_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()])) {
                $_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()] += $count;
            } else {
                $_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()] = $count;
            }
        }
    }

    public function removeItem($catalogueItemId, $count = 1)
    {
        /** @var Item $oCatalogueItem */
        $oCatalogueItem = DataSource::factory(Item::cls(), $catalogueItemId);
        if ($oCatalogueItem) {
            if (isset($_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()])) {
                $_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()] -= $count;
            } else {
                unset($_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()]);
            }
        }
    }

    public function getItemIds()
    {
        return array_keys($_SESSION[self::SESSION_SECTION_NAME]);
    }

    public function getCountById($catalogueItemId)
    {
        return isset($_SESSION[self::SESSION_SECTION_NAME][$catalogueItemId])
            ? $_SESSION[self::SESSION_SECTION_NAME][$catalogueItemId]
            : 0;
    }

    public function getTotalCount()
    {
        return array_sum($_SESSION[self::SESSION_SECTION_NAME]);
    }

    public function getItems()
    {
        if (!$this->isEmpty()) {
            /** @var Item $oItems */
            $oItems = DataSource::factory(Item::cls());
            $oItems->builder()->where('id in (' . implode(', ', $this->getItemIds()) . ')');
            /** @var Item[] $aItems */
            $aItems = $oItems->findAll();
        } else {
            $aItems = [];
        }

        return $aItems;
    }

    public function setItemCount($catalogueItemId, $count)
    {
        /** @var Item $oCatalogueItem */
        $oCatalogueItem = DataSource::factory(Item::cls(), $catalogueItemId);
        if ($oCatalogueItem) {
            $_SESSION[self::SESSION_SECTION_NAME][$oCatalogueItem->getPrimaryKey()] = $count;
        }
    }

}