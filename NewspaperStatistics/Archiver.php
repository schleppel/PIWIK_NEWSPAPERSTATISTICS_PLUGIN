<?php
namespace Piwik\Plugins\NewspaperStatistics;

use Piwik\Metrics;
use Piwik\Config;
use Piwik\DataTable;
use Piwik\RankingQuery;
use Piwik\Plugins\Actions\ArchivingHelper;
use Piwik\DataArray;

/**
 * Class Archiver
 * @package Piwik\Plugins\NewspaperStatistics
 */
class Archiver extends \Piwik\Plugin\Archiver
{
    const NEWSPAPER_STATISTICS_ARTICLE_ID = 'NewspaperStatistics_article_id';
    const NEWSPAPER_STATISTICS_PAYWALL_PLAN = 'NewspaperStatistics_paywall_plan';

    /**@var DataArray[] */
    protected $arrays = array();

    /** @var array|string */
    protected $maximumRowsInSubDataTable;

    /** @var int */
    protected $columnToSortByBeforeTruncation;

    /** @var array|string */
    protected $maximumRowsInDataTable;

    /** @inheritdoc */
    function __construct($processor)
    {
        parent::__construct($processor);
        $this->columnToSortByBeforeTruncation = Metrics::INDEX_NB_VISITS;
        $this->maximumRowsInDataTable = Config::getInstance()->General['datatable_archiving_maximum_rows_events'];
        $this->maximumRowsInSubDataTable = Config::getInstance()->General['datatable_archiving_maximum_rows_subtable_events'];
    }

    /** @inheritdoc */
    public function aggregateDayReport()
    {
        $this->aggregateDayArticleIds();
        $this->aggregateDayPaywallPlan();
        $this->insertDayReports();
    }

    /** @inheritdoc */
    public function aggregateMultipleReports()
    {
        $dataTableToSum = $this->getRecordNames();
        $columnsAggregationOperation = null;

        $this->getProcessor()->aggregateDataTableRecords(
            $dataTableToSum,
            $this->maximumRowsInDataTable,
            $this->maximumRowsInSubDataTable,
            $this->columnToSortByBeforeTruncation,
            $columnsAggregationOperation,
            $columnsToRenameAfterAggregation = null,
            $countRowsRecursive = array());
    }

    protected function aggregateDayArticleIds()
    {
        $select = "
                log_link_visit_action.article_id,
				count(distinct log_link_visit_action.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
				count(distinct log_link_visit_action.idvisitor) as `" . Metrics::INDEX_NB_UNIQ_VISITORS . "`,
				count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`
        ";

        $from = array(
            "log_link_visit_action"
        );

        $where = "log_link_visit_action.server_time >= ?
                    AND log_link_visit_action.server_time <= ?
                    AND log_link_visit_action.idsite = ?
                    ";

        $groupBy = "log_link_visit_action.article_id";

        $orderBy = "`" . Metrics::INDEX_NB_VISITS . "` DESC";

        $rankingQueryLimit = ArchivingHelper::getRankingQueryLimit();
        $rankingQuery = null;
        if ($rankingQueryLimit > 0) {
            $rankingQuery = new RankingQuery($rankingQueryLimit);
            $rankingQuery->setOthersLabel(DataTable::LABEL_SUMMARY_ROW);
            $rankingQuery->addLabelColumn(array('article_id'));
            $rankingQuery->addColumn(array(Metrics::INDEX_NB_UNIQ_VISITORS));
        }

        $this->archiveDayQueryProcess($select, $from, $where, $groupBy, $orderBy, $rankingQuery, $this->getArticleIdRecordToDimensions());
    }

    protected function aggregateDayPaywallPlan()
    {

        $select = "
                log_visit.paywall_plan,
				count(distinct log_visit.idvisit) as `" . Metrics::INDEX_NB_VISITS . "`,
				count(distinct log_visit.idvisitor) as `" . Metrics::INDEX_NB_UNIQ_VISITORS . "`,
				count(*) as `" . Metrics::INDEX_EVENT_NB_HITS . "`
        ";

        $from = array(
            "log_visit"
        );

        $where = "log_visit.visit_last_action_time >= ?
                    AND log_visit.visit_last_action_time <= ?
                    AND log_visit.idsite = ?
                    ";

        $groupBy = "log_visit.paywall_plan";

        $orderBy = "`" . Metrics::INDEX_NB_VISITS . "` DESC";

        $rankingQueryLimit = ArchivingHelper::getRankingQueryLimit();
        $rankingQuery = null;
        if ($rankingQueryLimit > 0) {
            $rankingQuery = new RankingQuery($rankingQueryLimit);
            $rankingQuery->setOthersLabel(DataTable::LABEL_SUMMARY_ROW);
            $rankingQuery->addLabelColumn(array('paywall_plan'));
            $rankingQuery->addColumn(array(Metrics::INDEX_NB_UNIQ_VISITORS));
            $rankingQuery->addColumn(array(Metrics::INDEX_EVENT_NB_HITS, Metrics::INDEX_NB_VISITS), 'sum');
        }

        $this->archiveDayQueryProcess($select, $from, $where, $groupBy, $orderBy, $rankingQuery, $this->getPaywallPlanRecordToDimensions());
     }

    /**
     * @param $select
     * @param $from
     * @param $where
     * @param $groupBy
     * @param $orderBy
     * @param RankingQuery $rankingQuery
     * @param $recordToDimensions
     */
    protected function archiveDayQueryProcess($select, $from, $where, $groupBy, $orderBy, RankingQuery $rankingQuery, $recordToDimensions)
    {
        // get query with segmentation
        $query = $this->getLogAggregator()->generateQuery($select, $from, $where, $groupBy, $orderBy);

        // apply ranking query
        if ($rankingQuery) {
            $query['sql'] = $rankingQuery->generateRankingQuery($query['sql']);
        }

        // get result
        $resultSet = $this->getLogAggregator()->getDb()->query($query['sql'], $query['bind']);

        if ($resultSet === false) {

            return;
        }

        while ($row = $resultSet->fetch()) {



            $this->aggregateRow($row, $recordToDimensions);
        }
    }

    /**
     * @param $row
     * @param $recordToDimensions
     */
    protected function aggregateRow($row, $recordToDimensions)
    {
        foreach ($recordToDimensions as $record => $dimensions) {
            $dataArray = $this->getDataArray($record);

            $mainDimension = $dimensions[0];
            $mainLabel = $row[$mainDimension];

            $dataArray->sumMetricsEvents($mainLabel, $row);
        }
    }

    /**
     * @param string $name
     * @return DataArray
     */
    protected function getDataArray($name)
    {
        if (empty($this->arrays[$name])) {
            $this->arrays[$name] = new DataArray();
        }
        return $this->arrays[$name];
    }

    /**
     * Records the daily data tables
     */
    protected function insertDayReports()
    {
        foreach ($this->arrays as $recordName => $dataArray) {
            $dataTable = $dataArray->asDataTable();
            $blob = $dataTable->getSerialized(
                $this->maximumRowsInDataTable,
                $this->maximumRowsInSubDataTable,
                $this->columnToSortByBeforeTruncation);
            $this->getProcessor()->insertBlobRecord($recordName, $blob);
        }
    }

    protected function getArticleIdRecordToDimensions()
    {
        return array(
            self::NEWSPAPER_STATISTICS_ARTICLE_ID => array("article_id")
        );
    }

    protected function getPaywallPlanRecordToDimensions()
    {
        return array(
            self::NEWSPAPER_STATISTICS_PAYWALL_PLAN => array("paywall_plan")
        );
    }

    protected function getRecordNames()
    {
        $mapping = array_merge($this->getArticleIdRecordToDimensions(), $this->getPaywallPlanRecordToDimensions());
        return array_keys($mapping);
    }
}