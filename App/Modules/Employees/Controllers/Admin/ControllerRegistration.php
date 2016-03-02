<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\MasterAdminController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Modules\Employees\Views\Admin\Forms\ViewRegistration;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\NotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerRegistration extends MasterAdminController {

    public function actionIndex() {
        $this->authorizeIfNot();
        $view = new ViewRegistration();

        $this->buildBreadcrumbs();
        $this->fillBreadcrumbs();
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $this->breadcrumbs->setIgnores(['page', 'back_params']);
        $viewBreadcrumbs->breadcrumbs = $this->breadcrumbs->build();
        $this->frame->bindView('breadcrumbs', $viewBreadcrumbs);

        $this->frame
            ->bindView('content', $view)
            ->render();
    }

    protected function fillBreadcrumbs() {
        $bcModules = $this->breadcrumbs->getRoot()->findChildNodeByPath('modules');
        $bcModules->addChildNode('Сотрудники', 'employees');
        $bcEmployees = $bcModules->findChildNodeByPath('employees');
        $bcEmployees->addChildNode('Добавление нового сотрудника', 'registration');
    }

    public function actionSignup() {
        $this->authorizeIfNot();

        if (Param::post('employee-registration-form-sign-up', false)->exists()) {
            $name = Param::post('employee-registration-form-name', false)
                ->noEmpty("Запоните поле \"Имя\".")
                ->asString(true, "Недопустимое значение поля \"Имя\".");
            $email = Param::post('employee-registration-form-email', false)
                ->noEmpty("Заполните поле \"Email\"!")
                ->asEmail(true, "Недопустимое значение поля \"Email\".");
            $password = Param::post('employee-registration-form-password', false)
                ->noEmpty("Заполните поле \"Пароль\".")
                ->asString(true, "Недопустимое значение поля \"Пароль\".");
            $passwordRepeat = Param::post('employee-registration-form-password-repeat', false)
                ->noEmpty("Заполните поле \"Повтор пароля\".")
                ->asString(true, "Недопустимое значение поля \"Повтор пароля\".");

            if ($password != $passwordRepeat) {
                NotificationLog::instance()->pushError("\"Пароль\" и \"Повтор пароля\" должны быть одинаковы.");
            }

            if (NotificationLog::instance()->hasProblems()) {
                $this->response->send();
                exit;
            }

            /** @var Employee $oEmployee */
            $oEmployee = DataSource::factory(Employee::cls());
            $oEmployee->name = $name;
            $oEmployee->email = $email;
            $oEmployee->password = $this->employeeAuthorizator->preparePassword($password);
            $oEmployee->active = true;
            $oEmployee->deleted = false;
            $oEmployee->commit();

            NotificationLog::instance()->pushMessage("Успешно зарегистрирован!");
            $this->response->send('/admin/modules/employees');
            exit;
        } else {
            NotificationLog::instance()->pushError("Форма регистрации сотрудника заполнена неверно!");
        }

        $this->response->send();
    }

} 