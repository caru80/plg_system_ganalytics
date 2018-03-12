<?php
	defined('_JEXEC') or die;
	/**
		Google Analytics Plugin-Template für gtag.js


		$this->trackingId = Tracking Id, wie im Backend eingestellt.
		$this->cookieName = Name des Cookies, welcher beim Opt-out gesetzt wird.

		$this->params->get('reloadpage',1) = Seite neuladen bei Opt-out
		$this->params->get('showmessage',1) = Eine Nachricht anzeigen bei Opt-out
	*/
?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->trackingId;?>"></script>
<script>
	'use strict';
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', '<?php echo $this->trackingId;?>', {'anonymize_ip' : true});
</script>
