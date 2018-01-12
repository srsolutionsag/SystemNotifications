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
			global $DIC;

			$ilAuth = $DIC['ilAuthSession'];

			ilSession::setClosingContext(ilSession::SESSION_CLOSE_USER);
			$ilAuth->logout();
			if (session_status() === PHP_SESSION_ACTIVE) {
				session_destroy();
			}
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
		//		$this->reset();
		//		$this->where(array( 'active' => 1 ));
		//				$this->leftjoin(sysnotDismiss::TABLE_NAME, 'id', 'notification_id');
		//						$this->debug();

		return self::get();
	}


	protected function reset() {
		$this->arWhereCollection = arWhereCollection::getInstance($this->getAR());
		$this->arJoinCollection = arJoinCollection::getInstance($this->getAR());
		$this->arLimitCollection = arLimitCollection::getInstance($this->getAR());
		$this->arOrderCollection = arOrderCollection::getInstance($this->getAR());
		$this->arConcatCollection = arConcatCollection::getInstance($this->getAR());
		$this->arSelectCollection = arSelectCollection::getInstance($this->getAR());
		//		$this->arHavingCollection = arHavingCollection::getInstance($this->getAR());

		$arSelect = new arSelect();
		$arSelect->setTableName($this->getAR()->getConnectorContainerName());
		$arSelect->setFieldName('*');
		$this->getArSelectCollection()->add($arSelect);
	}
}
