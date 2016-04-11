<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Siteusers\Models\Siteuser;
use App\Modules\Siteusers\Views\Admin\ViewSiteuserEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends MasterAdminController
{

    public function actionIndex()
    {
        $this->authorizeIfNot();

        $siteuserId = Param::get('id', false)->asInteger(false);

        /** @var Siteuser $oSiteuser */
        $oSiteuser = is_null($siteuserId) ? null : DataSource::factory(Siteuser::cls(), $siteuserId);

        $view = new ViewSiteuserEditForm();
        $view->oSiteuser = $oSiteuser;

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Пользователи', '/siteusers')
        ];
        if ($oSiteuser !== null) {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb("Редактирование \"{$oSiteuser->name}\"", '');
        } else {
            $viewBreadcrumbs->breadcrumbs[] = new Breadcrumb('Добавление нового пользователя', '');
        }
        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->breadcrumbs, 1);

        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->frame->bindView('content', $view);
        $this->frame->render();
    }

}