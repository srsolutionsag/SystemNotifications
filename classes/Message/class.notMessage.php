<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Dismiss/class.sysnotDismiss.php');
/**
 * Class notMessage
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessage extends ActiveRecord {

	const POS_TOP = 1;
	const POS_RIGHT = 2;
	const POST_LEFT = 3;
	const POS_BOTTOM = 4;
	const DATE_FORMAT = 'd.m.Y H:i:s';
	const TYPE_INFO = 1;
	const TYPE_WARNING = 2;
	const TYPE_ERROR = 3;
	const TABLE_NAME = 'xnot_message';


	/**
	 * @return string
	 * @description Return the Name of your Database Table
	 * @deprecated
	 */
	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @param ilObjUser $ilObjUser
	 */
	public function dismiss(ilObjUser $ilObjUser) {
		sysnotDismiss::dismiss($ilObjUser, $this);
	}


	/**
	 * @return string
	 */
	public function getFullTimeFormated() {
		return date(self::DATE_FORMAT, $this->getEventStart()) . ' - ' . date(self::DATE_FORMAT, $this->getEventEnd());
	}


	/**
	 * @return int
	 */
	public function getActiveType() {
		if ($this->getPermanent()) {
			return $this->getType();
		}
		if ($this->hasEventStarted() AND !$this->hasEventEnded()) {
			return $this->getTypeDuringEvent();
		}
		if ($this->hasDisplayStarted() AND !$this->hasDisplayEnded()) {
			return $this->getType();
		}
	}


	/**
	 * @return bool
	 */
	public function isVisible() {
		if ($this->getPermanent()) {
			return true;
		}
		$hasEventStarted = $this->hasEventStarted();
		$hasDisplayStarted = $this->hasDisplayStarted();
		$hasEventEnded = !$this->hasEventEnded();
		$hasDisplayEnded = !$this->hasDisplayEnded();

		return ($hasEventStarted OR $hasDisplayStarted) AND ($hasEventEnded OR $hasDisplayEnded);
	}


	/**
	 * @param $usr_id
	 *
	 * @return bool
	 */
	public function isUserAllowed($usr_id) {
		global $rbacreview;
		/**
		 * @var $rbacreview ilRbacReview
		 */
		if ($usr_id == 6 OR $rbacreview->isAssigned($usr_id, 2)) {
			return true;
		}
		if ($this->getPreventLogin()) {
			if ($this->isDuringEvent() OR $this->getPermanent()) {
				if (!in_array($usr_id, $this->getAllowedUsers())) {
					return false;
				}
			}
		}

		return true;
	}


	/**
	 * @var int
	 *
	 * @con_is_primary true
	 * @con_sequence   true
	 * @con_is_unique  true
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $title = '';
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $body = '';
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $event_start;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $event_end;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $display_start;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $display_end;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $type = self::TYPE_INFO;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $type_during_event = self::TYPE_ERROR;
	/**
	 * @var bool
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $dismissable = false;
	/**
	 * @var bool
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $permanent = false;
	/**
	 * @var bool
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $position = self::POS_TOP;
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $additional_classes = '';
	/**
	 * @var bool
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     1
	 */
	protected $prevent_login = false;
	/**
	 * @var array
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     256
	 */
	protected $allowed_users = array( 6, 0 );
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $parent_id = NULL;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $create_date;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  timestamp
	 */
	protected $last_update;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $created_by = NULL;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $last_update_by = NULL;


	/**
	 * @param $field_name
	 * @param $field_value
	 *
	 * @return int|mixed
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case 'event_start':
			case 'event_end':
			case 'display_end':
			case 'display_start':
			case 'create_date':
			case 'last_update':
				return strtotime($field_value);
				break;
			case 'allowed_users':
				$array_unique = array_unique(json_decode($field_value, true));
				sort($array_unique);

				return $array_unique;
				break;
		}
	}


	/**
	 * @param $field_name
	 *
	 * @return bool|mixed|string
	 */
	public function sleep($field_name) {
		switch ($field_name) {
			case 'event_start':
			case 'event_end':
			case 'display_end':
			case 'display_start':
			case 'create_date':
			case 'last_update':
				return date(DATE_ISO8601, $this->{$field_name});
				break;
			case 'allowed_users':
				$allowed_users = array_unique(array_merge($this->allowed_users, array( 0, SYSTEM_USER_ID, ANONYMOUS_USER_ID )));

				return json_encode($allowed_users);
				break;
		}
	}


	public function create() {
		global $ilUser;
		$this->setCreateDate(time());
		$this->setCreatedBy($ilUser->getId());
		parent::create();
	}


	public function update() {
		global $ilUser;
		$this->setLastUpdate(time());
		$this->setLastUpdateBy($ilUser->getId());
		parent::update();
	}


	/**
	 * @param string $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}


	/**
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}


	/**
	 * @param int $display_end
	 */
	public function setDisplayEnd($display_end) {
		$this->display_end = $display_end;
	}


	/**
	 * @return int
	 */
	public function getDisplayEnd() {
		return $this->display_end;
	}


	/**
	 * @param int $display_start
	 */
	public function setDisplayStart($display_start) {
		$this->display_start = $display_start;
	}


	/**
	 * @return int
	 */
	public function getDisplayStart() {
		return $this->display_start;
	}


	/**
	 * @param int $event_end
	 */
	public function setEventEnd($event_end) {
		$this->event_end = $event_end;
	}


	/**
	 * @return int
	 */
	public function getEventEnd() {
		return $this->event_end;
	}


	/**
	 * @param int $event_start
	 */
	public function setEventStart($event_start) {
		$this->event_start = $event_start;
	}


	/**
	 * @return int
	 */
	public function getEventStart() {
		return $this->event_start;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param int $type
	 */
	public function setType($type) {
		$this->type = $type;
	}


	/**
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * @param int $type_during_event
	 */
	public function setTypeDuringEvent($type_during_event) {
		$this->type_during_event = $type_during_event;
	}


	/**
	 * @return int
	 */
	public function getTypeDuringEvent() {
		return $this->type_during_event;
	}


	/**
	 * @param boolean $dismissable
	 */
	public function setDismissable($dismissable) {
		$this->dismissable = $dismissable;
	}


	/**
	 * @return boolean
	 */
	public function getDismissable() {
		return $this->dismissable;
	}


	/**
	 * @return bool
	 */
	protected function hasEventStarted() {
		return $this->getTime() > $this->getEventStart();
	}


	/**
	 * @return bool
	 */
	protected function hasDisplayStarted() {
		return $this->getTime() > $this->getDisplayStart();
	}


	/**
	 * @return bool
	 */
	protected function hasEventEnded() {
		return $this->getTime() > $this->getEventEnd();
	}


	/**
	 * @return bool
	 */
	protected function hasDisplayEnded() {
		return $this->getTime() > $this->getDisplayEnd();
	}


	/**
	 * @param boolean $permanent
	 */
	public function setPermanent($permanent) {
		$this->permanent = $permanent;
	}


	/**
	 * @return boolean
	 */
	public function getPermanent() {
		return $this->permanent;
	}


	/**
	 * @param boolean $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}


	/**
	 * @return boolean
	 */
	public function getPosition() {
		return $this->position;
	}


	/**
	 * @param string $additional_classes
	 */
	public function setAdditionalClasses($additional_classes) {
		$this->additional_classes = $additional_classes;
	}


	/**
	 * @return string
	 */
	public function getAdditionalClasses() {
		return $this->additional_classes;
	}


	/**
	 * @param boolean $prevent_login
	 */
	public function setPreventLogin($prevent_login) {
		$this->prevent_login = $prevent_login;
	}


	/**
	 * @return boolean
	 */
	public function getPreventLogin() {
		return $this->prevent_login;
	}


	/**
	 * @param array $allowed_users
	 */
	public function setAllowedUsers($allowed_users) {
		$this->allowed_users = $allowed_users;
	}


	/**
	 * @return array
	 */
	public function getAllowedUsers() {
		return $this->allowed_users;
	}


	/**
	 * @return bool
	 */
	protected function isDuringEvent() {
		return $this->hasEventStarted() AND !$this->hasEventEnded();
	}


	/**
	 * @param int $create_date
	 */
	public function setCreateDate($create_date) {
		$this->create_date = $create_date;
	}


	/**
	 * @return int
	 */
	public function getCreateDate() {
		return $this->create_date;
	}


	/**
	 * @param int $created_by
	 */
	public function setCreatedBy($created_by) {
		$this->created_by = $created_by;
	}


	/**
	 * @return int
	 */
	public function getCreatedBy() {
		return $this->created_by;
	}


	/**
	 * @param int $parent_id
	 */
	public function setParentId($parent_id) {
		$this->parent_id = $parent_id;
	}


	/**
	 * @return int
	 */
	public function getParentId() {
		return $this->parent_id;
	}


	/**
	 * @param int $last_update
	 */
	public function setLastUpdate($last_update) {
		$this->last_update = $last_update;
	}


	/**
	 * @return int
	 */
	public function getLastUpdate() {
		return $this->last_update;
	}


	/**
	 * @param int $last_update_by
	 */
	public function setLastUpdateBy($last_update_by) {
		$this->last_update_by = $last_update_by;
	}


	/**
	 * @return int
	 */
	public function getLastUpdateBy() {
		return $this->last_update_by;
	}


	/**
	 * @return int
	 */
	protected function getTime() {
		return time();
		//		return strtotime('2014-11-25 06:15:00');
	}
}

?>
