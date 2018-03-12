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
	private $trackingSnipped;
	private $optoutSnipped;

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

		$path = JPluginHelper::getLayoutPath('system', 'ganalytics', $this->params->get('gamethod','default'));
		// Rendere Google Analytics JS Snipped
		ob_start();
		include $path;
		$this->trackingSnipped = ob_get_clean();

		// Rendere das Opt-out Script
		$path = JPluginHelper::getLayoutPath('system', 'ganalytics', 'optout');
		ob_start();
		include $path;
		$this->optoutSnipped = ob_get_clean();
	}

	public function onAfterRender()
	{
		if(empty($this->trackingSnipped)) return;

		$buffer = JResponse::getBody();
		$buffer = str_replace("</head>", $this->trackingSnipped . "\n</head>", $buffer);

		if(!empty($this->optoutSnipped))
		{
			$buffer = str_replace("</body>", $this->optoutSnipped . "\n</body>", $buffer);
		}
		JResponse::setBody( $buffer );
	}
}
