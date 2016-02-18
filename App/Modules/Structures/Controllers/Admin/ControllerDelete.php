<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Structures\Models\Structure;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerDelete extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();
        $structureId = Param::get('pk')->asInteger();
        /** @var Structure $oStructure */
        $oStructure = DataSource::factory(Structure::cls(), $structureId);
        $name = $oStructure->name;
        $this->deepDelete($oStructure);
        NotificationLog::instance()->pushMessage("Структура \"{$name}\" успешно удалена.");

        $this->response->send();
    }


    /**
     * Удаление элемента структуры и всех её дочерних элементов.
     *
     * @param Structure $oStructure
     */
    private function deepDelete(Structure $oStructure) {
        /** @var Structure[] $aStructures */
        $aStructures = $oStructure->field()->loadRelation(Structure::cls());
        foreach ($aStructures as $oStructure) {
            $this->deepDelete($oStructure);
        }

        $oStructure->deleted = true;
        $oStructure->commit();
    }

} 