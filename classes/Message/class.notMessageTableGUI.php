<?php
require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once('class.notMessage.php');
require_once('./Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php');

/**
 * Class notMessageTableGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageTableGUI extends ilTable2GUI {

	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	/**
	 * @param ilSystemNotificationsConfigGUI $a_parent_obj
	 * @param string $a_parent_cmd
	 */
	public function __construct(ilSystemNotificationsConfigGUI $a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
		$this->setId('msg_msg_table');
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate('tpl.row.html', $this->pl->getDirectory());
		$this->setTitle($this->pl->txt('msg_table_title'));
		$this->setFormAction($this->ctrl->getFormAction($this->parent_obj));
		//
		// Columns
		$this->addColumn($this->pl->txt('msg_title'));
		$this->addColumn($this->pl->txt('msg_type'));
		$this->addColumn($this->pl->txt('msg_type_during_event'));
		$this->addColumn($this->pl->txt('msg_event_start', 'event_start_unix'));
		$this->addColumn($this->pl->txt('msg_event_end', 'event_end_unix'));
		$this->addColumn($this->pl->txt('msg_display_start', 'display_start_unix'));
		$this->addColumn($this->pl->txt('msg_display_end', 'display_end_unix'));
		$this->addColumn($this->pl->txt('common_actions'));
		// ...
		//		$ilToolbar->addButton($this->pl->txt('usr_table_button_select_mem'), '#', '', '', '', 'select_mem');
		//		$ilToolbar->addButton($this->pl->txt('usr_table_button_select_tut'), '#', '', '', '', 'select_tut');
		//		$ilToolbar->addButton($this->pl->txt('usr_table_button_select_adm'), '#', '', '', '', 'select_adm');

		$this->initData();
	}


	protected function initData() {
		$notMessageList = notMessage::getCollection();
		$notMessageList->dateFormat();
		$this->setData($notMessageList->getArray());
	}


	protected function fillRow($a_set) {
		/**
		 * @var $notMessage notMessage
		 */
		$notMessage = notMessage::find($a_set['id']);
		$this->tpl->setVariable('TITLE', $notMessage->getTitle());
		$this->tpl->setVariable('TYPE', $this->pl->txt('msg_type_' . $notMessage->getType()));
		$this->tpl->setVariable('TYPE_DURING_EVENT', $this->pl->txt('msg_type_'
		                                                            . $notMessage->getTypeDuringEvent()));

		if (!$notMessage->getPermanent()) {
			$this->tpl->setVariable('EVENT_START', $a_set['event_start']);
			$this->tpl->setVariable('EVENT_END', $a_set['event_end']);
			$this->tpl->setVariable('DISPLAY_START', $a_set['display_start']);
			$this->tpl->setVariable('DISPLAY_END', $a_set['display_end']);
		}

		$this->ctrl->setParameter($this->parent_obj, ilSystemNotificationsConfigGUI::NOT_MSG_ID, $notMessage->getId());
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->pl->txt('common_actions'));
		$actions->setId('msg_' . $notMessage->getId());
		$actions->addItem($this->lng ->txt('edit'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_EDIT));
		$actions->addItem($this->lng ->txt('delete'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_CONFIRM_DELETE));
		if ($notMessage->getDismissable()) {
			$actions->addItem($this->pl->txt('msg_reset_dismiss'), '', $this->ctrl->getLinkTarget($this->parent_obj, ilSystemNotificationsConfigGUI::CMD_RESET_FOR_ALL));
		}
		$this->tpl->setVariable('ACTIONS', $actions->getHTML());
	}
}
