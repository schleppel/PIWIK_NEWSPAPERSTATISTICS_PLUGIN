# Piwik NewspaperStatistics Plugin

## Description

Add your plugin description here.

New dimenstions and are added and defined based on documentation from: http://developer.piwik.org/guides/dimensions
Example tracking code for tracking new dimensions:

<!-- Piwik -->
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="//localhost:8000/";
        _paq.push(['setTrackerUrl', u+'piwik.php?article_id=123&paywall_plan=321']);
        _paq.push(['setSiteId', 1]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<noscript><p><img src="//localhost:8000/piwik.php?idsite=1&article_id=123&paywall_plan=321" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->

## FAQ

__My question?__

My answer

## Changelog

Here goes the changelog text.

## Support

Please direct any feedback to ...
