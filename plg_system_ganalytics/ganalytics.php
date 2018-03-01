<?php
defined( '_JEXEC' ) or die();

jimport('joomla.plugin.plugin');

class plgSystemGanalytics extends JPlugin
{
	protected $autoloadLanguage = true;

	private $cookieName;
	private $optOutDone;
	private $trackingId;
	private $html;

	public function __construct( &$subject, $params )
	{
		parent::__construct( $subject, $params );

		$this->cookieName = $this->params->get('cookiename','gaoptout');
		$this->optOutDone = JFactory::getApplication()->input->cookie->get($this->cookieName, false); // Opt-out schon gemacht?
		$this->trackingId = $this->params->get('trackingid', '');
	}

	private function insertAssets()
	{
		$doc = JFactory::getApplication()->getDocument();

		$doc->addScript('media/plg_ganalytics/js/plgganalytics.min.js');

		if((bool)$this->params->get('showmessage', true))
		{
			$doc->addStylesheet('media/plg_ganalytics/css/plgganalytics.css');
		}

		return;
	}

	public function onBeforeRender()
	{
		if(JFactory::getApplication()->isAdmin() || $this->optOutDone || $this->trackingId == '') return;

		$this->insertAssets();

		$path = JPluginHelper::getLayoutPath('system', 'ganalytics');
		// Rendere Google Analytics
		ob_start();
		include $path;
		$this->html = ob_get_clean();
	}

	public function onAfterRender()
	{
		if(empty($this->html)) return;

		$buffer = JResponse::getBody();

		$buffer = str_replace("</body>", $this->html . "\n</body>", $buffer);

		JResponse::setBody( $buffer );
	}
}
