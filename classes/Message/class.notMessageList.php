<?php

/**
 * Class notMessageList
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageList extends ActiveRecordList {

	public function __construct() {
		parent::__construct(new notMessage());
	}


	/**
	 * @param ilObjUser $ilUser
	 */
	public function check(ilObjUser $ilUser) {
		$show = true;
		foreach ($this->getActive() as $not) {
			if (!$not->isUserAllowed($ilUser)) {
				$show = false;
			}
		}

		if (!$show) {
			global $ilAuth;
			/**
			 * @var $ilAuth ilAuthWeb
			 */
			ilSession::setClosingContext(ilSession::SESSION_CLOSE_USER);
			$ilAuth->logout();
			session_destroy();
			ilUtil::redirect('login.php');
			exit;
		}
	}


	/**
	 * @return notMessage[]
	 */
	public function get() {
		return parent::get();
	}


	/**
	 * @return notMessage[]
	 */
	public function getActive() {
		return self::get();
	}
}

?>
