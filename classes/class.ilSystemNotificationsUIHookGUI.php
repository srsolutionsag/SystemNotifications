<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once('class.ilSystemNotificationsPlugin.php');
require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Config/class.sysnotConfig.php');

/**
 * Class ilSystemNotificationsUIHookGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilSystemNotificationsUIHookGUI extends ilUIHookPluginGUI {

	const TPL_ID = 'tpl_id';


	public function __construct() {
		$this->pl = ilSystemNotificationsPlugin::getInstance();
	}


	/**
	 * @var array
	 */
	protected static $loaded = array();


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	protected static function isLoaded($key) {
		return self::$loaded[$key] == 1;
	}


	/**
	 * @param $key
	 */
	protected static function setLoaded($key) {
		self::$loaded[$key] = 1;
	}


	/**
	 * @var int
	 */
	protected static $goto_num = 0;


	/**
	 * @param       $a_comp
	 * @param       $a_part
	 * @param array $a_par
	 *
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = array()) {
		/**
		 * @var $ilCtrl       ilCtrl
		 * @var $tpl          ilTemplate
		 * @var $ilToolbar    ilToolbarGUI
		 */
		if (sysnotConfig::is50()) {
			if ($a_par[self::TPL_ID] == 'Services/UICore/tpl.footer.html' AND !self::isLoaded('const')) {
				$css = $this->getCss('notifications');
				$css .= $this->getCss('50');

				self::setLoaded('const');

				return array( 'mode' => ilUIHookPluginGUI::APPEND, 'html' => $css . $this->getNotificatiosHTML() );
			}
		} elseif (sysnotConfig::is44()) {
			$part = ($a_par[self::TPL_ID] == 'Services/Init/tpl.startup_screen.html' OR $a_par[self::TPL_ID] == 'tpl.adm_content.html');
			if ($part AND !self::isLoaded('const')) {
				if ($_SERVER['SCRIPT_NAME'] != '/goto.php') {
					self::setLoaded('const');
				}
				if (self::$goto_num != 0) {
					self::setLoaded('const');
				}
				self::$goto_num ++;

				$css = $this->getCss('notifications');

				return array( 'mode' => ilUIHookPluginGUI::PREPEND, 'html' => $css . $this->getNotificatiosHTML() );
			}
		}

		return array( 'mode' => ilUIHookPluginGUI::KEEP, 'html' => '' );
	}


	/**
	 * @return string
	 */
	protected function getNotificatiosHTML() {
		global $ilUser;
		/**
		 * @var $ilUser ilObjUser
		 */
		$notifications = new ilTemplate('tpl.notification.html', true, true, $this->pl->getDirectory());
		/**
		 * @var $notMessage notMessage
		 */
		$show = true;
		foreach (notMessage::get() as $notMessage) {
			if (!$notMessage->isVisible()) {
				continue;
			}

			$notifications->setCurrentBlock('notification');
			$notifications->setVariable('TITLE', $notMessage->getTitle());
			$notifications->setVariable('BODY', $notMessage->getBody());
			$notifications->setVariable('TYPE', $notMessage->getActiveType());
			$notifications->setVariable('POSITION', $notMessage->getPosition());
			$notifications->setVariable('ADD_CSS', $notMessage->getAdditionalClasses());
			if (!$notMessage->getPermanent()) {
				$notifications->setVariable('EVENT', $notMessage->getFullTimeFormated());
			}
			$notifications->parseCurrentBlock();

			if (!$notMessage->isUserAllowed($ilUser->getId())) {
				$show = false;
			}
		}

		if (!$show) {
			//			global $tpl;
			//			$tpl->addCss('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/css/notifications.css');
			//			$tpl->getStandardTemplate();
			//			$tpl->setContent($notifications->get());
			//			$tpl->show();

			global $ilAuth;
			/**
			 * @var $ilAuth ilAuthWeb
			 */
			ilSession::setClosingContext(ilSession::SESSION_CLOSE_USER);
			$ilAuth->logout();
			session_destroy();
			ilUtil::redirect('login.php');
			exit;
		}

		return $notifications->get();
	}


	/**
	 * @return string
	 */
	protected function getCss($file = 'notifications') {
		$css =
			'<link rel="stylesheet" type="text/css" href="./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/css/'
			. $file . '.css">';

		return $css;
	}
}

?>
