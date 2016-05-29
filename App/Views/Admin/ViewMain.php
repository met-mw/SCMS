<?php
namespace App\Views\Admin;


use App\Classes\SCMSNotificationLog;
use App\Models\NotificationLog;
use SFramework\Classes\View;

class ViewMain extends View {

    /** @var NotificationLog[] */
    public $aNotificationLogs;
    /** @var int */
    public $logsCount;
    /** @var array */
    public $modulesManifests;

    public function currentRender() {
        ?>
        <div class="row">
            <div class="col-lg-4">
                <h4>SCMS<small>&trade;</small> <small>(Simple Content Management System)</small>.</h4>
                <blockquote>
                    <p class="text-primary"><small>Простая система управления контентом - залог успешного запуска проекта.</small></p>
                    <footer><cite>С уважением, Ваш <a href="http://sproject.ru" target="_blank">SProject</a></cite></footer>
                    <footer><cite>Официальный сайт системы:</cite>&nbsp;<a href="http://sproject.ru" target="_blank">http://sproject.ru</a></footer>
                </blockquote>
            </div>
            <div class="col-lg-8">
                <p>Системные сообщения <span class="badge alert-danger"><?= $this->logsCount ?></span></p>
                <div class="table-responsive" style="height: 200px; overflow: auto;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">-</th>
                                <th class="text-center">Сообщение</th>
                                <th class="text-center">Дата</th>
                                <th class="text-center">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if (empty($this->aNotificationLogs)): ?>
                                <tr>
                                    <td colspan="5" class="text-success text-center">
                                        Системных сообщений нет
                                    </td>
                                </tr>
                            <? else: ?>
                                <? $notificationCount = 1; ?>
                                <? foreach ($this->aNotificationLogs as $oNotificationLog): ?>
                                    <?
                                        $rowClass = 'active';
                                        switch ($oNotificationLog->type) {
                                            case SCMSNotificationLog::TYPE_ERROR:
                                                $rowClass = 'danger';
                                                break;
                                            case SCMSNotificationLog::TYPE_WARNING:
                                                $rowClass = 'warning';
                                                break;
                                            case SCMSNotificationLog::TYPE_NOTICE:
                                                $rowClass = 'info';
                                                break;
                                        }
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td><?= $notificationCount ?></td>
                                        <td><span class="glyphicon glyphicon-alert"></span></td>
                                        <td><?= $oNotificationLog->message ?></td>
                                        <td><?= $oNotificationLog->date->format('d.m.Y H:i:s') ?></td>
                                        <td><?= $oNotificationLog->ip ?></td>
                                    </tr>
                                    <? $notificationCount++; ?>
                                <? endforeach; ?>
                            <? endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br/>
        <h3>Комплектация системы:</h3>
        <hr/>
        <div class="container-fluid">
            <div class="row eq-height">
                <? foreach($this->modulesManifests as $moduleManifest): ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <blockquote style="border-left-color: #58aac6;">
                        <p>
                            <span class="glyphicon glyphicon-pushpin"></span>
                            &nbsp;
                            <span class="label label-success">v. <?= $moduleManifest['version'] ?></span>
                            &nbsp;
                            &laquo;<?= $moduleManifest['meta']['alias'] ?>&raquo;
                        </p>
                        <footer class="text-justify">
                            <?= $moduleManifest['meta']['description'] ?>
                        </footer>
                        <br/>
                        <footer>
                            <cite>Разработчик:</cite>&nbsp;<?= $moduleManifest['author']['name'] ?>&nbsp;(<?= $moduleManifest['author']['nick'] ?>)
                        </footer>
                        <footer>
                            <cite>E-mail:</cite>&nbsp;<a href="mailto:<?= $moduleManifest['author']['email'] ?>"><?= $moduleManifest['author']['email'] ?></a>
                        </footer>
                    </blockquote>
                </div>
                <? endforeach; ?>
            </div>
        <?
    }

}