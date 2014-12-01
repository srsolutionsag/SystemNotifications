<?php
require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Utilities/classes/class.ilConfirmationGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageFormGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/class.ilSystemNotificationsPlugin.php');
/**
 * Class ilSystemNotificationsConfigGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilSystemNotificationsConfigGUI extends ilPluginConfigGUI {

	const CMD_STD = 'configure';
	const CMD_SAVE = 'save';
	const CMD_UPDATE = 'update';
	const CMD_ADD = 'add';
	const CMD_EDIT = 'edit';
	const CMD_CANCEL = 'cancel';
	const CMD_CONFIRM_DELETE = 'confirmDelete';
	const CMD_DELETE = 'delete';
	const NOT_MSG_ID = 'not_msg_id';
	/**
	 * @var notMessage
	 */
	protected $notMessage;


	public function __construct() {
		global $tpl, $ilCtrl;
		/**
		 * @var $tpl    ilTemplate
		 * @var $ilCtrl ilCtrl
		 */
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		if(!$this->pl->isActive()) {
			$this->ctrl->redirectByClass('');
		}
		//		$this->pl->updateLanguageFiles();
		$this->ctrl->setParameter($this, self::NOT_MSG_ID, $_REQUEST[self::NOT_MSG_ID]);
		$this->notMessage = notMessage::find($_GET[self::NOT_MSG_ID]);
	}


	/**
	 * @param $cmd
	 */
	public function performCommand($cmd) {
		$this->{$cmd}();
	}


	protected function configure() {
		/**
		 * @var $ilToolbar ilToolbarGUI
		 */
		global $ilToolbar;
		$ilToolbar->addButton($this->pl->txt('common_add_msg'), $this->ctrl->getLinkTarget($this, self::CMD_ADD));
		$notMessageTableGUI = new notMessageTableGUI($this, self::CMD_STD);
		$this->tpl->setContent($notMessageTableGUI->getHTML());
	}


	protected function add() {
		$notMessageFormGUI = new notMessageFormGUI($this, new notMessage());
		$this->tpl->setContent($notMessageFormGUI->getHTML());
	}


	protected function save() {
		$notMessageFormGUI = new notMessageFormGUI($this, new notMessage());
		$notMessageFormGUI->setValuesByPost();
		if ($notMessageFormGUI->saveObject()) {
			ilUtil::sendInfo($this->pl->txt('msg_success'), true);
			$this->ctrl->redirect($this, self::CMD_STD);
		}
		$this->tpl->setContent($notMessageFormGUI->getHTML());
	}


	protected function cancel() {
		$this->ctrl->setParameter($this, self::NOT_MSG_ID, NULL);
		$this->ctrl->redirect($this, self::CMD_STD);
	}


	protected function edit() {
		$notMessageFormGUI = new notMessageFormGUI($this, $this->notMessage);
		$notMessageFormGUI->fillForm();
		$this->tpl->setContent($notMessageFormGUI->getHTML());
	}


	protected function update() {
		$notMessageFormGUI = new notMessageFormGUI($this, $this->notMessage);
		$notMessageFormGUI->setValuesByPost();
		if ($notMessageFormGUI->saveObject()) {
			ilUtil::sendInfo($this->pl->txt('msg_success'), true);
			$this->ctrl->redirect($this, self::CMD_STD);
		}
		$this->tpl->setContent($notMessageFormGUI->getHTML());
	}


	protected function confirmDelete() {
		$ilConfirmationGUI = new ilConfirmationGUI();
		$ilConfirmationGUI->setFormAction($this->ctrl->getFormAction($this));
		$ilConfirmationGUI->addItem(self::NOT_MSG_ID, $this->notMessage->getId(), $this->notMessage->getTitle());
		$ilConfirmationGUI->setCancel($this->pl->txt('msg_form_button_cancel'), self::CMD_CANCEL);
		$ilConfirmationGUI->setConfirm($this->pl->txt('msg_form_button_delete'), self::CMD_DELETE);

		$this->tpl->setContent($ilConfirmationGUI->getHTML());
	}


	protected function delete() {
		$this->notMessage->delete();
		ilUtil::sendInfo($this->pl->txt('msg_success'), true);
		$this->cancel();
	}
}

?>
