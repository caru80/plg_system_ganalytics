<?php
defined( '_JEXEC' ) or die();

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

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
		$app = JFactory::getApplication();
		$doc = $app->getDocument();

		$doc->addScript('media/plg_ganalytics/js/plgganalytics.min.js');

		if((bool)$this->params->get('showmessage', true))
		{
			if(JFile::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . $app->getTemplate() . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "plg_system_ganalytics" . DIRECTORY_SEPARATOR . "plgganalytics.css"))
			{
				// CSS Override
				$doc->addStylesheet("templates/" . $app->getTemplate() . "/html/plg_system_ganalytics/plgganalytics.css");
			}
			else
			{
				// Plugin CSS
				$doc->addStylesheet('media/plg_ganalytics/css/plgganalytics.min.css');
			}
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

		$buffer = str_replace("</head>", $this->html . "\n</head>", $buffer);

		JResponse::setBody( $buffer );
	}
}
