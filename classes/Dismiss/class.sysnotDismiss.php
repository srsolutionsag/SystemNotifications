<?php

/**
 * Class sysnotDismiss
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class sysnotDismiss extends ActiveRecord {

	const XNOT_DISMISS = 'xnot_dismiss';


	/**
	 * @return string
	 * @description Return the Name of your Database Table
	 */
	static function returnDbTableName() {
		return self::XNOT_DISMISS;
	}


	/**
	 * @return string-
	 */
	public function getConnectorContainerName() {
		return self::XNOT_DISMISS;
	}


	/**
	 * @param ilObjUser $ilObjUser
	 *
	 * @return bool
	 */
	public static function hasDimissed(ilObjUser $ilObjUser, notMessage $notMessage) {
		return self::where(array( 'usr_id' => $ilObjUser->getId(), 'notification_id' => $notMessage->getId() ))->hasSets();
	}


	/**
	 * @param ilObjUser  $ilObjUser
	 * @param notMessage $notMessage
	 */
	public static function dismiss(ilObjUser $ilObjUser, notMessage $notMessage) {
		if (!self::hasDimissed($ilObjUser, $notMessage) AND $notMessage->getDismissable()) {
			$obj = new self();
			$obj->setNotificationId($notMessage->getId());
			$obj->setUsrId($ilObjUser->getId());
			$obj->create();
		}
	}


	/**
	 * @param notMessage $notMessage
	 */
	public static function reactivateAll(notMessage $notMessage) {
		/**
		 * @var $dismiss sysnotDismiss
		 */
		foreach (self::where(array( 'notification_id' => $notMessage->getId() )) as $dismiss) {
			$dismiss->delete();
		}
	}


	/**
	 * @var int
	 *
	 * @con_is_primary true
	 * @con_is_unique  true
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 * @con_sequence   true
	 */
	protected $id = 0;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $usr_id = 0;
	/**
	 * @var int
	 *
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $notification_id = 0;


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
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
	public function getUsrId() {
		return $this->usr_id;
	}


	/**
	 * @param int $usr_id
	 */
	public function setUsrId($usr_id) {
		$this->usr_id = $usr_id;
	}


	/**
	 * @return int
	 */
	public function getNotificationId() {
		return $this->notification_id;
	}


	/**
	 * @param int $notification_id
	 */
	public function setNotificationId($notification_id) {
		$this->notification_id = $notification_id;
	}
}

?>
