<?php

/**
 * Class notMessageGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageGUI {

	const ALERT_SUCCESS = 'alert-success';
	const ALERT_INFO = 'alert-info';
	const ALERT_WARNING = 'alert-warning';
	const ALERT_DANGER = 'alert-danger';
	/**
	 * @var array
	 */
	protected static $css_map = array(
		notMessage::TYPE_WARNING => self::ALERT_WARNING,
		notMessage::TYPE_ERROR => self::ALERT_DANGER,
		notMessage::TYPE_INFO => self::ALERT_INFO,
	);
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var notMessage
	 */
	protected $message;
	/**
	 * @var ilObjUser
	 */
	protected $usr;
	/**
	 * @var ilSystemNotificationsPlugin
	 */
	protected $pl;


	/**
	 * @param notMessage $notMessage
	 */
	public function __construct(notMessage $notMessage) {
		global $DIC;
		$this->message = $notMessage;
		$this->pl = ilSystemNotificationsPlugin::getInstance();
		$this->tpl = new ilTemplate($this->pl->getDirectory() . '/templates/default/tpl.notification.html', true, true);
		$this->usr = $DIC->user();
	}


	/**
	 * @return string
	 */
	public function getHTML() {
		$this->tpl->setVariable('TITLE', $this->message->getTitle());
		$this->tpl->setVariable('BODY', $this->message->getBody());
		$this->tpl->setVariable('ALERT_TYPE', self::$css_map[$this->message->getActiveType()]);
		//		$this->tpl->setVariable('POSITION', $this->message->getPosition());
		$this->tpl->setVariable('ADD_CSS', $this->message->getAdditionalClasses());
		if (!$this->message->getPermanent()) {
			$this->tpl->setVariable('EVENT', $this->message->getFullTimeFormated());
		}
		if ($this->message->isInterruptive()) {
			$this->tpl->setVariable('INTERRUPTIVE', 'interruptive');
		}
		if ($this->message->isUserAllowedToDismiss($this->usr)) {
			$this->tpl->setVariable('DISMISS_LINK', 'goto.php?target=xnot_dismiss_' . $this->message->getId());
		}

		return $this->tpl->get();
	}


	/**
	 * @param $html
	 *
	 * @return string
	 */
	public function append(&$html) {
		$html = $html . $this->getHTML();

		return $html;
	}
}

