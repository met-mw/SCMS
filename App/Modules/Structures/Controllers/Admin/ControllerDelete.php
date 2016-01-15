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
        /** @var Structure $structure */
        $structure = DataSource::factory(Structure::cls(), $structureId);
        $this->deepDelete($structure);
        NotificationLog::instance()->pushMessage("Структура \"$structure->name\" успешно удалена.");
        $this->response->send();
    }

    /**
     * Удаление элемента структуры и всех её дочерних элементов.
     *
     * @param Structure $structure
     */
    private function deepDelete(Structure $structure) {
        /** @var Structure[] $aStructures */
        $aStructures = $structure->field()->loadRelation(Structure::cls());
        foreach ($aStructures as $structure) {
            $this->deepDelete($structure);
        }

        $structure->delete();
    }

} 