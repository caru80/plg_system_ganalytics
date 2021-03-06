<?php
	defined('_JEXEC') or die;
	/**
		Google Analytics Plugin-Template für analytics.js

		$this->trackingId = Tracking Id, wie im Backend eingestellt.
		$this->cookieName = Name des Cookies, welcher beim Opt-out gesetzt wird.

		$this->params->get('reloadpage',1) = Seite neuladen bei Opt-out
		$this->params->get('showmessage',1) = Eine Nachricht anzeigen bei Opt-out
	*/
?>
<script>
'use strict';
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', '<?php echo $this->trackingId;?>', 'auto');
ga('set', 'anonymizeIp', true);
ga('send', 'pageview');
</script>
