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

	const PLUGIN_NAME = 'SystemNotifications';
	const AR_CUST = './Customizing/global/plugins/Libraries/ActiveRecord/class.ActiveRecord.php';
	const AR_SER = './Services/ActiveRecord/class.ActiveRecord.php';
	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected static $instance;


	/**
	 * @return ilSystemNotificationsPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @throws ilPluginException
	 */
	protected function init() {
		$this->checkAR44();
		$this->loadActiveRecord();
	}


	/**
	 * @return bool
	 * @throws ilPluginException
	 */
	protected function beforeActivation() {
		$this->checkAR44();

		return true;
	}


	/**
	 * @throws ilPluginException
	 */
	protected function checkAR44() {
		if (sysnotConfig::is50()) {
			return true;
		}
		if (!is_file(self::AR_CUST)) {
			throw new ilPluginException('Please install ActiveRecord first');
		}
	}


	public function updateLanguageFiles() {
		ini_set('auto_detect_line_endings', true);
		$path = substr(__FILE__, 0, strpos(__FILE__, 'classes')) . 'lang/';
		if (file_exists($path . 'lang_custom.csv')) {
			$file = $path . 'lang_custom.csv';
		} else {
			$file = $path . 'lang.csv';
		}
		$keys = array();
		$new_lines = array();

		foreach (file($file) as $n => $row) {
			//			$row = utf8_encode($row);
			if ($n == 0) {
				$keys = str_getcsv($row, ";");
				continue;
			}
			$data = str_getcsv($row, ";");;
			foreach ($keys as $i => $k) {
				if ($k != 'var' AND $k != 'part') {
					$new_lines[$k][] = $data[0] . '_' . $data[1] . '#:#' . $data[$i];
				}
			}
		}
		$start = '<!-- language file start -->' . PHP_EOL;
		$status = true;

		foreach ($new_lines as $lng_key => $lang) {
			$status = file_put_contents($path . 'ilias_' . $lng_key . '.lang', $start . implode(PHP_EOL, $lang));
		}

		if (!$status) {
			ilUtil::sendFailure('Language-Files could not be written');
		}
		$this->updateLanguages();
	}


	protected function loadActiveRecord() {
		$ar_file = self::AR_SER;
		if (!is_file($ar_file)) {
			$ar_file = self::AR_CUST;
		}

		require_once($ar_file);
	}
}

?>
