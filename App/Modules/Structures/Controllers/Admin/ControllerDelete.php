<?php
namespace App\Modules\Structures\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Models\Structure;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;
use SORM\Tools\Builder;

class ControllerDelete extends AdministratorAreaController {

    public function actionIndex() {
        $this->authorizeIfNot();
        $structureId = Param::get('id')->asInteger();
        /** @var Structure $oStructure */
        $oStructure = DataSource::factory(Structure::cls(), $structureId);
        $name = $oStructure->name;
        $this->deepDelete($oStructure);
        SCMSNotificationLog::instance()->pushMessage("Структура \"{$name}\" успешно удалена.");

        $this->Response->send();
    }

    public function actionGroup() {
        // TODO: реализовать групповое удаление
    }


    /**
     * Удаление элемента структуры и всех её дочерних элементов.
     *
     * @param Structure $oStructure
     */
    private function deepDelete(Structure $oStructure) {
        /** @var Structure[] $aStructures */
        $aStructures = $oStructure->getStructures();
        foreach ($aStructures as $oChildStructure) {
            $this->deepDelete($oChildStructure);
        }

        $oStructure->deleted = true;
        $oStructure->commit();
    }

} 