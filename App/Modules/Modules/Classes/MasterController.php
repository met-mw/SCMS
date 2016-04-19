<?php
namespace App\Modules\Modules\Classes;


use App\Modules\Catalogue\Classes\Cart;
use App\Modules\Catalogue\Views\ViewCartModal;
use App\Modules\Structures\Models\Structure;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Controller;
use SFramework\Classes\Frame;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Response;

class MasterController extends Controller {

    /** @var Response */
    protected $response;
    /** @var ViewBreadcrumbs */
    protected $breadcrumbsView;
    /** @var Structure */
    protected $oStructure;

    /** @var Cart */
    protected $cart;

    public function __construct(Frame $frame, array $manifest, Structure $oStructure) {
        $this->frame = $frame;
        $this->oStructure = $oStructure;

        $this->response = new Response(NotificationLog::instance());
        $this->breadcrumbsView = new ViewBreadcrumbs();
        $this->breadcrumbsView->breadcrumbs = [];
        if (!$this->oStructure->is_main) {
            $this->breadcrumbsView->breadcrumbs = [
                new Breadcrumb('Главная', '/'),
                new Breadcrumb($this->oStructure->name, $this->oStructure->path)
            ];
        }

        $this->cart = new Cart();

        $this->frame->addCss('/public/assets/js/fancybox2/source/jquery.fancybox.css');
        $this->frame->bindView('breadcrumbs', $this->breadcrumbsView);
        $this->frame->bindView('modal-notification', new ViewNotificationsModal());
        $this->frame->bindView('modal-confirmation', new ViewConfirmationModal());
        $this->frame->bindView('modal-information', new ViewInformationModal());
        $this->frame->bindView('modal-catalogue-cart', new ViewCartModal());
        $this->frame->bindData('catalogue-cart-total-count', $this->cart->getTotalCount());
    }

} 