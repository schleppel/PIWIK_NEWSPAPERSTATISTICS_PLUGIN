/*!
 * Piwik - free/libre analytics platform
 *
 * Screenshot integration tests.
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

describe("SummaryReport", function () {
    this.timeout(0);

    // uncomment this if you want to define a custom fixture to load before the test instead of the default one
    // this.fixture = "Piwik\\Plugins\\NewspaperStatistics\\tests\\Fixtures\\YOUR_FIXTURE_NAME";

    var generalParams = 'idSite=1&period=day&date=2016-06-27',
        urlBase = 'module=CoreHome&action=index&' + generalParams;

    before(function () {
        testEnvironment.pluginsToLoad = ['NewspaperStatistics'];
        testEnvironment.save();
    });


     it('should load a page page with empty results by its module and action and take a full screenshot', function (done) {
     var screenshotName = 'emptyPage';
     // will take a screenshot and store it in "processed-ui-screenshots/SummaryReport_simplePage.png"
     var urlToTest = "?" + generalParams + "&module=NewspaperStatistics&action=index";

     expect.screenshot(screenshotName).to.be.capture(function (page) {
     page.load(urlToTest);
     }, done);
     });


    it('should be visible in the menu', function (done) {
        var screenshotName  = 'menu';
        // will take a screenshot and store it in "processed-ui-screenshots/SummaryReport_menu.png"
        var contentSelector = 'li:contains(Newspaper)';
        // take a screenshot only of the content of this CSS/jQuery selector
        var urlToTest       = "?" + generalParams + "&module=NewspaperStatistics&action=index";
        // "?" + urlBase + "#" + generalParams + "&module=NewspaperStatistics&action=index"; this defines a URL for a page within the dashboard

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
        }, done);
    });


/*
    it('should load a simple page by its module and action and take a partial screenshot', function (done) {
        var screenshotName  = 'simplePagePartial';
        // will take a screenshot and store it in "processed-ui-screenshots/SummaryReport_simplePagePartial.png"
        var contentSelector = '#root,.expandDataTableFooterDrawer';
        // take a screenshot only of the content of this CSS/jQuery selector
        var urlToTest       = "?" + generalParams + "&module=NewspaperStatistics&action=index";
        // "?" + urlBase + "#" + generalParams + "&module=NewspaperStatistics&action=index"; this defines a URL for a page within the dashboard

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
        }, done);
    });
    */

});