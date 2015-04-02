<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once('class.ilSystemNotificationsPlugin.php');
require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Config/class.sysnotConfig.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageList.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageListGUI.php');

/**
 * Class ilSystemNotificationsUIHookGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilSystemNotificationsUIHookGUI extends ilUIHookPluginGUI {

	const TPL_ID = 'tpl_id';
	const SERVICES_INIT_TPL_LOGIN_HTML = 'Services/Init/tpl.login.html';
	const TPL_ADM_CONTENT_HTML = 'tpl.adm_content.html';
	const SERVICES_INIT_TPL_LOGOUT_HTML = 'Services/Init/tpl.logout.html';


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
			//			if ($a_par[self::TPL_ID] == 'Services/UICore/tpl.footer.html' AND ! self::isLoaded('const')) {
			//			var_dump($a_par[self::TPL_ID]); // FSX
			// tpl.statusline.html
			$tpls = array(
				self::TPL_ADM_CONTENT_HTML,
				self::SERVICES_INIT_TPL_LOGIN_HTML,
				self::SERVICES_INIT_TPL_LOGOUT_HTML,
			);
			$tpls_wo_css = array(
				self::SERVICES_INIT_TPL_LOGIN_HTML,
				self::SERVICES_INIT_TPL_LOGOUT_HTML,
			);

			if (in_array($a_par[self::TPL_ID], $tpls) AND ! self::isLoaded('const')) {
				$css = $this->getCss('notifications');
				if (! in_array($a_par[self::TPL_ID], $tpls_wo_css)) {
					$css .= $this->getCss('50');
				}

				self::setLoaded('const');

				return array( 'mode' => ilUIHookPluginGUI::PREPEND, 'html' => $css . $this->getNotificatiosHTML() );
			}
		} elseif (sysnotConfig::is44()) {
			$part = ($a_par[self::TPL_ID] == 'Services/Init/tpl.startup_screen.html' OR $a_par[self::TPL_ID] == self::TPL_ADM_CONTENT_HTML);
			if ($part AND ! self::isLoaded('const')) {
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

		$notMessageList = new notMessageList();
		$notMessageList->check($ilUser);
		$notifications = new notMessageListGUI($notMessageList);

		return $notifications->getHTML();
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


	public function gotoHook() {
		if (preg_match("/xnot_dismiss_(.*)/uim", $_GET['target'], $matches)) {
			global $ilUser;
			/**
			 * @var $notMessage notMessage
			 */
			$notMessage = notMessage::find($matches[1]);
			if ($notMessage instanceof notMessage) {
				$notMessage->dismiss($ilUser);
			}
			ilUtil::redirect($_SERVER['HTTP_REFERER']);
		}
	}
}

?>
