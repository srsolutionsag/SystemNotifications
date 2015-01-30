<?php
require_once('class.notMessageList.php');
require_once('class.notMessageGUI.php');

/**
 * Class notMessageListGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageListGUI {

	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var notMessageList
	 */
	protected $list;


	/**
	 * @param notMessageList $notMessageList
	 */
	public function __construct(notMessageList $notMessageList) {
		$this->tpl = new ilTemplate('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/templates/default/tpl.notification_list.html', false, false);
		$this->list = $notMessageList;
	}


	/**
	 * @return string
	 */
	public function getHTML() {
		$html = '';
		foreach ($this->list->getActive() as $not) {
			$notGUI = new notMessageGUI($not);
			$html = $notGUI->append($html);
		}
		$this->tpl->setVariable('LIST', $html);

		return $this->tpl->get();
	}
}

?>
