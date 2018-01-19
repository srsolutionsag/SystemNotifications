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
	 * @var ilObjUser
	 */
	protected $user;


	/**
	 * @param notMessageList $notMessageList
	 */
	public function __construct(notMessageList $notMessageList) {
		global $DIC;
		$this->tpl = new ilTemplate('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/templates/default/tpl.notification_list.html', false, false);
		$this->list = $notMessageList;
		$this->user = $DIC->user();
	}


	/**
	 * @return string
	 */
	public function getHTML() {
		$html = '';
		foreach ($this->list->getActive() as $not) {
			if ($not->isVisibleForUser($this->user)) {
				$notGUI = new notMessageGUI($not);
				$html = $notGUI->append($html);
			}
		}
		$this->tpl->setVariable('LIST', $html);

		return $this->tpl->get();
	}
}
