<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Form/classes/class.ilDateDurationInputGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');

/**
 * Class notMessageFormGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageFormGUI extends ilPropertyFormGUI {

	const F_TITLE = 'title';
	const F_BODY = 'body';
	const F_TYPE = 'type';
	const F_TYPE_DURING_EVENT = 'type_during_event';
	const F_EVENT_DATE = 'event_date';
	const F_DISPLAY_DATE = 'display_date';
	const F_PERMANENT = 'permanent';
	const F_POSITION = 'position';
	const F_ADDITIONAL_CLASSES = 'additional_classes';
	const F_PREVENT_LOGIN = 'prevent_login';
	const F_ALLOWED_USERS = 'allowed_users';
	const F_DISMISSABLE = 'dismissable';
	const F_LIMIT_TO_ROLES = 'limit_to_roles';
	const F_LIMITED_TO_ROLE_IDS = 'limited_to_role_ids';
	/**
	 * @var notMessage
	 */
	protected $notMessage;
	/**
	 * @var array
	 */
	protected static $tags = array(
		'a',
		'strong',
		'ol',
		'ul',
		'li',
		'p',
	);


	/**
	 * @param            $parent_gui
	 * @param notMessage $notMessage
	 */
	public function __construct($parent_gui, notMessage $notMessage) {
		global $ilCtrl;
		/**
		 * @var $ilCtrl ilCtrl
		 */
		$this->notMessage = $notMessage;
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		//		$this->pl->updateLanguageFiles();
		$this->is_new = $notMessage->getId() == 0;
		$this->setFormAction($ilCtrl->getFormAction($parent_gui));
		$this->initForm();
	}


	/**
	 * @param $var
	 *
	 * @return string
	 */
	protected function txt($var) {
		return $this->pl->txt('msg_' . $var);
	}


	public function initForm() {
		$this->setTitle($this->txt('form_title'));

		$type = new ilSelectInputGUI($this->txt(self::F_TYPE), self::F_TYPE);
		$type->setOptions(array(
			notMessage::TYPE_INFO    => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_INFO),
			notMessage::TYPE_WARNING => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_WARNING),
			notMessage::TYPE_ERROR   => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_ERROR),

		));
		$this->addItem($type);

		$title = new ilTextInputGUI($this->txt(self::F_TITLE), self::F_TITLE);
		$this->addItem($title);

		$body = new ilTextAreaInputGUI($this->txt(self::F_BODY), self::F_BODY);
		$body->setUseRte(true);
		$body->setRteTags(self::$tags);
		$this->addItem($body);

		$permanent = new ilRadioGroupInputGUI($this->txt(self::F_PERMANENT), self::F_PERMANENT);

		$permanent_yes = new ilRadioOption($this->txt(self::F_PERMANENT . '_yes'), 1);
		$permanent->addOption($permanent_yes);
		$this->addItem($permanent);

		$dismissable = new ilCheckboxInputGUI($this->txt(self::F_DISMISSABLE), self::F_DISMISSABLE);
		$this->addItem($dismissable);

		$limit_to_roles = new ilCheckboxInputGUI($this->txt(self::F_LIMIT_TO_ROLES), self::F_LIMIT_TO_ROLES);
		$limited_to_role_ids = new ilMultiSelectInputGUI($this->txt(self::F_LIMITED_TO_ROLE_IDS), self::F_LIMITED_TO_ROLE_IDS);
		$limited_to_role_ids->setOptions(self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL));
		$limit_to_roles->addSubItem($limited_to_role_ids);
		$this->addItem($limit_to_roles);

		$permanent_no = new ilRadioOption($this->txt(self::F_PERMANENT . '_no'), 0);
		$display_time = new ilDateDurationInputGUI($this->txt(self::F_DISPLAY_DATE), self::F_DISPLAY_DATE);
		$display_time->setShowTime(true);
		$display_time->setMinuteStepSize(1);
		$permanent_no->addSubItem($display_time);
		$event_time = new ilDateDurationInputGUI($this->txt(self::F_EVENT_DATE), self::F_EVENT_DATE);
		$event_time->setShowTime(true);
		$event_time->setMinuteStepSize(1);
		$permanent_no->addSubItem($event_time);
		$type_during_event = new ilSelectInputGUI($this->txt(self::F_TYPE_DURING_EVENT), self::F_TYPE_DURING_EVENT);
		$type_during_event->setOptions(array(
			notMessage::TYPE_INFO    => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_INFO),
			notMessage::TYPE_WARNING => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_WARNING),
			notMessage::TYPE_ERROR   => $this->txt(self::F_TYPE . '_' . notMessage::TYPE_ERROR),

		));
		$permanent_no->addSubItem($type_during_event);

		$permanent->addOption($permanent_no);

		$position = new ilSelectInputGUI($this->txt(self::F_POSITION), self::F_POSITION);
		$position->setOptions(array(
			notMessage::POS_TOP    => $this->txt(self::F_POSITION . '_' . notMessage::POS_TOP),
			notMessage::POST_LEFT  => $this->txt(self::F_POSITION . '_' . notMessage::POST_LEFT),
			notMessage::POS_RIGHT  => $this->txt(self::F_POSITION . '_' . notMessage::POS_RIGHT),
			notMessage::POS_BOTTOM => $this->txt(self::F_POSITION . '_' . notMessage::POS_BOTTOM),
		));
		// $this->addItem($position);

		$additional_classes = new ilTextInputGUI($this->txt(self::F_ADDITIONAL_CLASSES), self::F_ADDITIONAL_CLASSES);
		$this->addItem($additional_classes);

		$prevent_login = new ilCheckboxInputGUI($this->txt(self::F_PREVENT_LOGIN), self::F_PREVENT_LOGIN);
		$allowed_users = new ilTextInputGUI($this->txt(self::F_ALLOWED_USERS), self::F_ALLOWED_USERS);

		$prevent_login->addSubItem($allowed_users);
		$this->addItem($prevent_login);

		$this->addButtons();
	}


	public function fillForm() {
		$array = array(
			self::F_TITLE               => $this->notMessage->getTitle(),
			self::F_BODY                => $this->notMessage->getBody(),
			self::F_TYPE                => $this->notMessage->getType(),
			self::F_TYPE_DURING_EVENT   => $this->notMessage->getTypeDuringEvent(),
			self::F_PERMANENT           => (int)$this->notMessage->getPermanent(),
			self::F_POSITION            => $this->notMessage->getPosition(),
			self::F_ADDITIONAL_CLASSES  => $this->notMessage->getAdditionalClasses(),
			self::F_PREVENT_LOGIN       => $this->notMessage->getPreventLogin(),
			self::F_ALLOWED_USERS       => @implode(',', $this->notMessage->getAllowedUsers()),
			self::F_DISMISSABLE         => $this->notMessage->getDismissable(),
			self::F_LIMIT_TO_ROLES      => $this->notMessage->isLimitToRoles(),
			self::F_LIMITED_TO_ROLE_IDS => $this->notMessage->getLimitedToRoleIds(),
		);
		$this->setValuesByArray($array);
		/**
		 * @var $f_event_date   ilDateDurationInputGUI
		 * @var $f_display_date ilDateDurationInputGUI
		 */
		if ($eventStart = $this->notMessage->getEventStart()) {
			$f_event_date = $this->getItemByPostVar(self::F_EVENT_DATE);
			$f_event_date->setStart(new ilDateTime($eventStart, IL_CAL_UNIX));
			$f_event_date->setEnd(new ilDateTime($this->notMessage->getEventEnd(), IL_CAL_UNIX));
		}

		if ($displayStart = $this->notMessage->getDisplayStart()) {
			$f_display_date = $this->getItemByPostVar(self::F_DISPLAY_DATE);
			$f_display_date->setStart(new ilDateTime($displayStart, IL_CAL_UNIX));
			$f_display_date->setEnd(new ilDateTime($this->notMessage->getDisplayEnd(), IL_CAL_UNIX));
		}
	}


	/**
	 * @return bool
	 */
	protected function fillObject() {
		if (!$this->checkInput()) {
			return false;
		}

		$this->notMessage->setTitle($this->getInput(self::F_TITLE));
		$this->notMessage->setBody($this->getInput(self::F_BODY));
		$this->notMessage->setType($this->getInput(self::F_TYPE));
		$this->notMessage->setTypeDuringEvent($this->getInput(self::F_TYPE_DURING_EVENT));
		$this->notMessage->setPermanent($this->getInput(self::F_PERMANENT));
		$this->notMessage->setPosition($this->getInput(self::F_POSITION));
		$this->notMessage->setAdditionalClasses($this->getInput(self::F_ADDITIONAL_CLASSES));
		$this->notMessage->setPreventLogin($this->getInput(self::F_PREVENT_LOGIN));
		$this->notMessage->setAllowedUsers(@explode(',', $this->getInput(self::F_ALLOWED_USERS)));
		$this->notMessage->setDismissable($this->getInput(self::F_DISMISSABLE));
		$this->notMessage->setLimitToRoles($this->getInput(self::F_LIMIT_TO_ROLES));
		$this->notMessage->setLimitedToRoleIds($this->getInput(self::F_LIMITED_TO_ROLE_IDS));

		/**
		 * @var $f_event_date   ilDateDurationInputGUI
		 * @var $f_display_date ilDateDurationInputGUI
		 */
		$f_event_date = $this->getItemByPostVar(self::F_EVENT_DATE);
		if ($f_event_date->getStart() instanceof ilDateTime) {
			$this->notMessage->setEventStart($f_event_date->getStart()->get(IL_CAL_UNIX));
		}
		if ($f_event_date->getEnd() instanceof ilDateTime) {
			$this->notMessage->setEventEnd($f_event_date->getEnd()->get(IL_CAL_UNIX));
		}

		$f_display_date = $this->getItemByPostVar(self::F_DISPLAY_DATE);
		if ($f_display_date->getStart() instanceof ilDateTime) {
			$this->notMessage->setDisplayStart($f_display_date->getStart()->get(IL_CAL_UNIX));
		}
		if ($f_display_date->getEnd() instanceof ilDateTime) {
			$this->notMessage->setDisplayEnd($f_display_date->getEnd()->get(IL_CAL_UNIX));
		}

		return true;
	}


	/**
	 * @param ilDateTime $ilDate_start
	 * @param ilDateTime $ilDate_end
	 *
	 * @return array
	 */
	public function getDateArray(ilDateTime $ilDate_start, ilDateTime $ilDate_end) {
		$return = array();
		$timestamp = $ilDate_start->get(IL_CAL_UNIX);
		$return['start']['d'] = date('d', $timestamp);
		$return['start']['m'] = date('m', $timestamp);
		$return['start']['y'] = date('Y', $timestamp);
		$timestamp = $ilDate_end->get(IL_CAL_UNIX);
		$return['end']['d'] = date('d', $timestamp);
		$return['end']['m'] = date('m', $timestamp);
		$return['end']['y'] = date('Y', $timestamp);

		return $return;
	}


	/**
	 * @return bool false when unsuccessful or int request_id when successful
	 */
	public function saveObject() {
		if (!$this->fillObject()) {
			return false;
		}
		if ($this->notMessage->getId() > 0) {
			$this->notMessage->update();
		} else {
			$this->notMessage->create();
		}

		return $this->notMessage->getId();
	}


	protected function addButtons() {
		if ($this->is_new) {
			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_SAVE, $this->txt('form_button_'
			                                                                             . ilSystemNotificationsConfigGUI::CMD_SAVE));
		} else {
			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_UPDATE, $this->txt('form_button_'
			                                                                               . ilSystemNotificationsConfigGUI::CMD_UPDATE));
			//			$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_UPDATE_AND_STAY, $this->txt('form_button_'
			//				. ilSystemNotificationsConfigGUI::CMD_UPDATE_AND_STAY));
		}
		$this->addCommandButton(ilSystemNotificationsConfigGUI::CMD_CANCEL, $this->txt('form_button_'
		                                                                               . ilSystemNotificationsConfigGUI::CMD_CANCEL));
	}


	/**
	 * @param int $filter
	 * @param bool $with_text
	 *
	 * @return array
	 */
	public static function getRoles($filter, $with_text = true) {
		global $rbacreview;
		/**
		 * @var $rbacreview ilRbacReview
		 */
		$opt = array( 0 => 'Login' );
		$role_ids = array( 0 );
		foreach ($rbacreview->getRolesByFilter($filter) as $role) {
			$opt[$role['obj_id']] = $role['title'] . ' (' . $role['obj_id'] . ')';
			$role_ids[] = $role['obj_id'];
		}
		if ($with_text) {
			return $opt;
		} else {
			return $role_ids;
		}
	}
}

