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

	/**
	 * @var \ilSystemNotificationsPlugin
	 */
	protected $pl;
	const TPL_ID = 'tpl_id';
	/**
	 * @var array
	 */
	protected static $ztpls = array(
		'Services/Init/tpl.startup_screen.html',
		'tpl.adm_content.html',
	);


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
		return (self::$loaded[$key] == 1);
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

		if ($a_part == 'template_show') {
			$result = $a_par['html'];
			$result = preg_replace("/<div([\\w =\"_\\-]*)mainspacekeeper([\\w =\"_\\-]*)>/uiUmx", "<div$1mainspacekeeper$2>"
			                                                                                      . $this->getNotificatiosHTML(), $result);

			if (!$result) {
				$result = $a_par['html'];
			}

			return array(
				'mode' => ilUIHookPluginGUI::REPLACE,
				'html' => $result,
			);
		}

		// LOGIN / LOGOUT
		if ($a_part == 'template_add' && !self::isLoaded('const')
		    && in_array($a_par[self::TPL_ID], self::$ztpls)
		) {
			global $tpl;
			$tpl->addCss($this->pl->getDirectory() . '/templates/default/notifications.css');
			$tpl->addJavaScript($this->pl->getDirectory() . '/templates/default/xnot.min.js');
			self::setLoaded('const');
		}

		return array(
			'mode' => ilUIHookPluginGUI::KEEP,
			'html' => '',
		);
	}


	/**
	 * @return string
	 */
	protected function getNotificatiosHTML() {
		global $ilUser;
		if (!$ilUser instanceof ilObjUser) {
			return null;
		}

		$notMessageList = new notMessageList();
		$notMessageList->check($ilUser);
		$notifications = new notMessageListGUI($notMessageList);

		return $notifications->getHTML();
	}


	public function gotoHook() {
		if (preg_match("/xnot_dismiss_(.*)/uim", $_GET['target'], $matches)) {
			global $ilUser;
			/**
			 * @var $notMessage notMessage
			 */
			$notMessage = notMessage::find($matches[1]);
			if ($notMessage instanceof notMessage && $ilUser instanceof ilObjUser
			    && $notMessage->isUserAllowedToDismiss($ilUser)
			) {
				$notMessage->dismiss($ilUser);
			}
			ilUtil::redirect($_SERVER['HTTP_REFERER']);
		}
	}
}
