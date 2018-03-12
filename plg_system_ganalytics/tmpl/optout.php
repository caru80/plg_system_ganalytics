<?php
	defined('_JEXEC') or die;
	/**
		Google Analytics Plugin-Template für Opt-out

		$this->trackingId = Tracking Id, wie im Backend eingestellt.
		$this->cookieName = Name des Cookies, welcher beim Opt-out gesetzt wird.

		$this->params->get('reloadpage',1) = Seite neuladen bei Opt-out
		$this->params->get('showmessage',1) = Eine Nachricht anzeigen bei Opt-out
	*/
?>
<script>
'use strict';
(function($) {
	$(function() {
		window.gaOptOut.init({
			cookiename 	: '<?php echo $this->cookieName;?>'
			<?php if($this->params->get('reloadpage',1)):?>,reloadPage : true <?php endif;?>
			<?php if($this->params->get('showmessage',1)):?>

			,message 	: 	'<div id="plg-googleanalytics" class="plg-googleanalytics">' +
								'<span><?php echo JText::_('PLG_GANALYTICS_FRONT_TEXT');?></span>' +
								'<span>' +
									'<a tabindex="0" class="btn msg-close"><?php echo JText::_('PLG_GANALYTICS_FRONT_BTN_OK');?></a>' +
								'</span>' +
							'</div>'
			<?php endif;?>
		});
	});
})(jQuery);
</script>
