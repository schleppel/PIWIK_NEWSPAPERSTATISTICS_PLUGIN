<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\NewspaperStatistics\Reports;

use Piwik\Plugin\Report;
use Piwik\Piwik;

/**
 * Class Base
 * @package Piwik\Plugins\NewspaperStatistics\Reports
 */
abstract class Base extends Report
{
    /** @inheritdoc */
    protected function init()
    {
        $this->category = Piwik::translate('NewspaperStatistics_NewspaperStatistics');
    }
}
