<?php

require_once('./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Config/class.sysnotConfig.php');

/**
 * Class ilSystemNotificationsPlugin
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 *
 */
class ilSystemNotificationsPlugin extends ilUserInterfaceHookPlugin {

	const PLUGIN_ID = 'sys_not';
	const PLUGIN_NAME = 'SystemNotifications';
	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected static $instance;
	/**
	 * @var ilDB
	 */
	protected $db;


	/**
	 * @return ilSystemNotificationsPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function __construct() {
		parent::__construct();

		global $DIC;

		$this->db = $DIC->database();
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return bool
	 * @throws ilPluginException
	 */
	protected function beforeActivation() {
		return true;
	}


	/**
	 * @param $a_var
	 *
	 * @return mixed|string
	 */
	//	public function txt($a_var) {
	//		require_once('./Customizing/global/plugins/Libraries/PluginTranslator/class.sragPluginTranslator.php');
	//
	//		return sragPluginTranslator::getInstance($this)->active()->write()->txt($a_var);
	//	}

	protected function beforeUninstall() {
		require_once './Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Dismiss/class.sysnotDismiss.php';
		require_once './Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php';

		$this->db->dropTable(sysnotDismiss::TABLE_NAME, false);
		$this->db->dropTable(notMessage::TABLE_NAME, false);

		return true;
	}
}
