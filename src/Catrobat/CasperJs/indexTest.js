/**
 * Created by catroweb on 4/6/14.
 */

var host = "http://catroid.local/";

casper.test.begin('Index page', 11, function suite(test) {
    casper.start(host+"app_dev.php", function() {

        test.assertTitle("Pocket Code Website", "homepage title is the one expected");
        test.assertExists('#logo', "logo tag is present");

        test.assertExists({type: 'xpath', path: '//div[@id="largeSearchButton"]' }, 'search button element exists');
        test.assertExists({type: 'xpath', path: '//div[@class="largeSearchBarMiddle"]/input' }, 'search field (header) element exists');
        test.assertExists({type: 'xpath', path: '//div[@id="largeMenuButton"]/button' }, 'avatar button element exists');
        test.assertElementCount({type: 'xpath', path: '//article' },4 , 'check if article container count button element exists');

        test.assertElementCount({type: 'xpath', path: '//div[@id="newestProjects"]/ul/li' },18 , 'check number of newest projects elements');
        test.assertElementCount({type: 'xpath', path: '//div[@id="mostDownloadedProjects"]/ul/li' },18 , 'check number of most downloaded projects elements');
        test.assertElementCount({type: 'xpath', path: '//div[@id="mostViewedProjects"]/ul/li' },18 , 'check number of most viewed projects elements');

        test.assertExists({type: 'xpath', path: '//input[@type="search"]' }, 'search field (footer) element exists');
        test.assertExists({type: 'xpath', path: '//div[@class="footerMenu"]/div/select[@id="switchLanguage"]' }, 'language selector element exists');

    });


    casper.run(function() {
        test.done();
    });
});