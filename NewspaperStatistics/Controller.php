<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\NewspaperStatistics;

use Piwik\View;

/**
 * Class Controller
 * @package Piwik\Plugins\NewspaperStatistics
 */
class Controller extends \Piwik\Plugin\ControllerAdmin
{
    // Page
    public function index()
    {
        $view = new View("@NewspaperStatistics/index.twig");
        // Generate the report visualization to use it in the view
        $view->getArticleId = $this->renderReport('getArticleId');

        $view->getPaywallPlan = $this->renderReport('getPaywallPlan');

        return $view->render();
    }

    /**
     * @param $function
     * @return string|void
     * @throws \Exception
     */
    public function getReport($function)
    {
        return $this->renderReport($function);
    }

}
