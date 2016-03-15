<?php
namespace App\Modules\Modules\Classes;


use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Controller;
use SFramework\Classes\Frame;

class MasterController extends Controller {

    /** @var ViewBreadcrumbs */
    protected $breadcrumbsView;
    /** @var Structure */
    protected $oStructure;

    public function __construct(Frame $frame, array $manifest, Structure $oStructure) {
        $this->frame = $frame;
        $this->oStructure = $oStructure;

        $this->breadcrumbsView = new ViewBreadcrumbs();
        $this->breadcrumbsView->breadcrumbs = [];
        if (!$this->oStructure->is_main) {
            $this->breadcrumbsView->breadcrumbs = [
                new Breadcrumb('Главная', '/'),
                new Breadcrumb($this->oStructure->name, $this->oStructure->path)
            ];
        }

        $this->frame->addCss('/public/assets/js/fancybox2/source/jquery.fancybox.css');
        $this->frame->bindView('breadcrumbs', $this->breadcrumbsView);
        $this->frame->bindView('modal-notification', new ViewNotificationsModal());
        $this->frame->bindView('modal-confirmation', new ViewConfirmationModal());
        $this->frame->bindView('modal-information', new ViewInformationModal());
    }

} 