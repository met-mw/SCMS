<?php
namespace App\Modules\Employees\Controllers\Admin;


use App\Classes\AdministratorAreaController;
use App\Modules\Employees\Models\Admin\Employee;
use App\Modules\Employees\Views\Admin\Forms\ViewRegistration;
use App\Views\Admin\ViewBreadcrumbs;
use SFramework\Classes\Breadcrumb;
use App\Classes\SCMSNotificationLog;
use SFramework\Classes\Param;
use SORM\DataSource;

class ControllerRegistration extends AdministratorAreaController {

    public function actionIndex() {
        $this->needAuthenticate();
        $view = new ViewRegistration();

        // Подготовка хлебных крошек
        $viewBreadcrumbs = new ViewBreadcrumbs();
        $viewBreadcrumbs->Breadcrumbs = [
            new Breadcrumb('Панель управления', '/admin'),
            new Breadcrumb('Сотрудники', '/modules/employees'),
            new Breadcrumb('Добавление нового сотрудника', '')
        ];

        $this->Frame->bindView('breadcrumbs', $viewBreadcrumbs)
            ->bindView('content', $view)
            ->render();
    }

    public function actionSignup() {
        $this->needAuthenticate();

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
                SCMSNotificationLog::instance()->pushError("\"Пароль\" и \"Повтор пароля\" должны быть одинаковы.");
            }

            if (SCMSNotificationLog::instance()->hasProblems()) {
                $this->Response->send();
                exit;
            }

            /** @var Employee $oEmployee */
            $oEmployee = DataSource::factory(Employee::cls());
            $oEmployee->name = $name;
            $oEmployee->email = $email;
            $oEmployee->password = $this->EmployeeAuthentication->encodePassword($password, Employee::SALT);
            $oEmployee->active = true;
            $oEmployee->deleted = false;
            $oEmployee->commit();

            SCMSNotificationLog::instance()->pushMessage("Успешно зарегистрирован!");
            $this->Response->send('/admin/modules/employees');
            exit;
        } else {
            SCMSNotificationLog::instance()->pushError("Форма регистрации сотрудника заполнена неверно!");
        }

        $this->Response->send();
    }

} 