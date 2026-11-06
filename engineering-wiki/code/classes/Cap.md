# Cap

> Class in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 62

The capability keys. THE TYPE. Every guard call site names one of these constants.

Naming: '<module>.<area>', where <area> is normally the settings tab it governs, and
'<module>.manage' is the per-module umbrella (holding it satisfies every capability
in that module — see `capExpandUmbrellas()`).

One constant per settings tab, added as each module is converted. The DETAILS of each
capability — its module, its description, whether it is sensitive, which stored settings
it guards — are NOT here: they live in that module's settings manifest, and everything
else derives from it. These constants are only the names, because a name is the one
thing that has to exist in the code for a typo to be a fatal error rather than a silent
403. `capSelfCheck()` proves every constant below is claimed by exactly one manifest.

