<?php
namespace App\Modules\Siteusers\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Siteusers\Models\Siteuser;
use App\Modules\Siteusers\Views\Admin\ViewSiteuserEditForm;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\CoreFunctions;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerEdit extends AdministratorAreaController
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
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Модули', '/modules'),
            new Breadcrumb('Пользователи', '/siteusers')
        ];
        if ($oSiteuser !== null) {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb("Редактирование \"{$oSiteuser->name}\"", '');
        } else {
            $viewBreadcrumbs->Breadcrumbs[] = new Breadcrumb('Добавление нового пользователя', '');
        }
        $view->backUrl = CoreFunctions::buildUrlByBreadcrumbs($viewBreadcrumbs->Breadcrumbs, 1);

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs);
        $this->Frame->bindView('content', $view);
        $this->Frame->render();
    }

}