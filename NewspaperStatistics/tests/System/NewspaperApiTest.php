<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\NewspaperStatistics\tests\System;

use Piwik\Plugins\NewspaperStatistics\Archiver;
use Piwik\Plugins\NewspaperStatistics\tests\Fixtures\SimpleFixtureTrackFewVisits;
use Piwik\Tests\Framework\TestCase\SystemTestCase;

/**
 * @group NewspaperStatistics
 * @group NewspaperApiTest
 * @group Plugins
 */
class NewspaperApiTest extends SystemTestCase
{
    /**
     * @var SimpleFixtureTrackFewVisits
     */
    public static $fixture = null; // initialized below class definition

    /**
     * @dataProvider getApiForTesting
     */
    public function testApi($api, $params)
    {
        $this->runApiTests($api, $params);
    }

    public function getApiForTesting()
    {
        $api =    'NewspaperStatistics.getArticleId, NewspaperStatistics.getPaywallPlan';
        
        
        $apiToTest = array();


        $apiToTest[] = array($api, array(
           // $idSite, $period, $date,
            'idSite'     => 1,
            'periods'    => array('day'),
            'date'       => self::$fixture->dateTime
        ));
        

        return $apiToTest;
    }

    public static function getOutputPrefix()
    {
        return '';
    }

    public static function getPathToTestDirectory()
    {
        return dirname(__FILE__);
    }

}

NewspaperApiTest::$fixture = new SimpleFixtureTrackFewVisits();