<?php

namespace modules\catalog\commands;

use Yii;
use yii\console\Controller;
use console\components\helpers\Console;
use modules\catalog\models\CatalogOrder;

/**
 * Class CronController
 * @package modules\catalog\commands
 */
class CronController extends Controller
{
    /**
     * Delete expired orders
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionRemoveOrderOverdue()
    {
        foreach (CatalogOrder::find()->overdue($this->module->orderConfirmTokenExpire)->each() as $order) {
            /** @var CatalogOrder $order */
            $this->stdout('ID: ' . $order->id);
            $order->scenario = $order::SCENARIO_ADMIN_CONSOLE;
            if ($order->delete()) {
                Yii::info('Remove expired order ' . $order->id);
                $this->stdout(' Remove expired order - OK', Console::FG_GREEN, Console::BOLD);
            } else {
                Yii::warning('Cannot remove expired order ' . $order->id);
                $this->stderr(' Cannot remove expired order - FAIL', Console::FG_RED, Console::BOLD);
            }
            $this->stdout(PHP_EOL);
        }
        $this->stdout('Done!', Console::FG_GREEN, Console::BOLD);
        $this->stdout(PHP_EOL);
    }
}