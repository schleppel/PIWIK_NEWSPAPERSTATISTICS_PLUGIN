<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\NewspaperStatistics\tests\Integration;

use Piwik\Plugin\Dimension\VisitDimension;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Plugin\Dimension\ActionDimension;

/**
 * @group NewspaperStatistics
 * @group NewspaperDimensionsTest
 * @group Plugins
 */
class NewspaperDimensionsTest extends IntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();
        // set up your test here if needed
    }

    public function tearDown()
    {
        // clean up your test here if needed
        parent::tearDown();
    }

    public function testCheckActionDimensionExists()
    {
        $dimensionColumn = [];

        foreach (ActionDimension::getAllDimensions() as $dimension) {
            $dimensionColumn[$dimension->getName()] =  $dimension->getColumnName();
        }

        $this->assertArrayHasKey('NewspaperStatistics_ArticleId', $dimensionColumn);
        $this->assertEquals('article_id', $dimensionColumn['NewspaperStatistics_ArticleId']);
    }

    public function testCheckVisitDimensionExists()
    {

        $dimensionColumn = [];
        foreach (VisitDimension::getAllDimensions() as $dimension) {

            $dimensionColumn[$dimension->getName()] =  $dimension->getColumnName();
        }

        $this->assertArrayHasKey('NewspaperStatistics_PaywallPlan', $dimensionColumn);
        $this->assertEquals('paywall_plan', $dimensionColumn['NewspaperStatistics_PaywallPlan']);
    }
}
