<?php
namespace App\Classes;


use App\Modules\Catalogue\Classes\Cart;
use App\Modules\Catalogue\Views\ViewCartModal;
use App\Models\Structure;
use App\Views\Admin\ViewBreadcrumbs;
use App\Views\Admin\ViewConfirmationModal;
use App\Views\Admin\ViewInformationModal;
use App\Views\Admin\ViewNotificationsModal;
use SFramework\Classes\Breadcrumb;
use SFramework\Classes\Controller;
use SFramework\Classes\Frame;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Response;

class PublicAreaController extends Controller 
{

    /** @var Response */
    protected $Response;
    /** @var ViewBreadcrumbs */
    protected $BreadcrumbsView;
    /** @var Structure */
    protected $oStructure;

    /** @var Cart */
    protected $cart;

    public function __construct(Frame $Frame, array $manifest, Structure $oStructure) 
    {
        $this->Frame = $Frame;
        $this->oStructure = $oStructure;

        $this->Response = new Response(NotificationLog::instance());
        $this->BreadcrumbsView = new ViewBreadcrumbs();
        $this->BreadcrumbsView->Breadcrumbs = [];
        if (!$this->oStructure->is_main) {
            $this->BreadcrumbsView->Breadcrumbs = [new Breadcrumb('Главная', '/')];
            if (!$this->oStructure->anchor) {
                $this->BreadcrumbsView->Breadcrumbs[] = new Breadcrumb($this->oStructure->name, $this->oStructure->path);
            }
        }

        $this->cart = new Cart();

        $this->Frame->addCss('/public/assets/js/fancybox2/source/jquery.fancybox.css');
        $this->Frame->bindView('breadcrumbs', $this->BreadcrumbsView);
        $this->Frame->bindView('modal-notification', new ViewNotificationsModal());
        $this->Frame->bindView('modal-confirmation', new ViewConfirmationModal());
        $this->Frame->bindView('modal-information', new ViewInformationModal());
        $this->Frame->bindView('modal-catalogue-cart', new ViewCartModal());
        $this->Frame->bindData('catalogue-cart-total-count', $this->cart->getTotalCount());
    }

} 