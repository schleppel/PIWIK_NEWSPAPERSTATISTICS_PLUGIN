<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\NewspaperStatistics;

use Piwik\DataTable;
use Piwik\DataTable\Row;
use Piwik\Archive;
use Piwik\Metrics;
use Piwik\Piwik;

/**
 * API for plugin NewspaperStatistics
 *
 * @method static \Piwik\Plugins\NewspaperStatistics\API getInstance()
 */
class API extends \Piwik\Plugin\API
{

    /**
     * Another example method that returns a data table.
     * @param int    $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getPaywallPlan($idSite, $period, $date, $segment = false)
    {
        $dataTable = $this->getDataTable( Archiver::NEWSPAPER_STATISTICS_PAYWALL_PLAN , $idSite, $period, $date, $segment);

        return $dataTable;
    }

    /**
     * Another example method that returns a data table.
     * @param int    $idSite
     * @param string $period
     * @param string $date
     * @param bool|string $segment
     * @return DataTable
     */
    public function getArticleId($idSite, $period, $date, $segment = false)
    {
        $dataTable = $this->getDataTable( Archiver::NEWSPAPER_STATISTICS_ARTICLE_ID , $idSite, $period, $date, $segment);
        return $dataTable;
    }

    /**
     * @param $recordName
     * @param $idSite
     * @param $period
     * @param $date
     * @param $segment
     * @param bool $expanded
     * @param null $idSubtable
     * @param bool $flat
     * @return DataTable|DataTable\Map
     */
    protected function getDataTable($recordName, $idSite, $period, $date, $segment, $expanded = false, $idSubtable = null, $flat = false)
    {
        Piwik::checkUserHasViewAccess($idSite);

        $dataTable = Archive::createDataTableFromArchive($recordName, $idSite, $period, $date, $segment, $expanded, $flat, $idSubtable);

        return $dataTable;
    }

}
