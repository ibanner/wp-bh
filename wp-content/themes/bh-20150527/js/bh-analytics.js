var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-8676522-1']);
_gaq.push(['_setDomainName', 'bh.org.il']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_trackPageview']);

/** this will not work, as the iframe is created dinamically - see ticketnet.js for parameters concatenation to the iframe src
_gaq.push(function() {
    var pageTracker = _gat._getTrackerByName();
    var iframe = document.getElementById('randsPopupContainer');
    iframe.src = pageTracker._getLinkerUrl('ticketsoft.co.il/reservint/index.jsp?referentId=1&lang=he');
});
*/

(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
