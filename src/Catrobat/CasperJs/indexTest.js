/**
 * Created by catroweb on 4/6/14.
 */
casper.test.begin('Index page', 2, function suite(test) {
    casper.start("http://catroid.local/app_dev.php", function() {
        test.assertTitle("Pocket Code Website", "homepage title is the one expected");
        test.assertExists('#logo', "logo tag is present");
    });

    casper.run(function() {
        test.done();
    });
});