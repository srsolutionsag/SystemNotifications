<?php

/**
 * Class notMessageGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageGUI {

	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var notMessage
	 */
	protected $message;


	/**
	 * @param notMessage $notMessage
	 */
	public function __construct(notMessage $notMessage) {
		$this->message = $notMessage;
		$this->tpl = new ilTemplate('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/templates/default/tpl.notification.html', true, true);
	}


	public function getHTML() {
		$this->tpl->setVariable('TITLE', $this->message->getTitle());
		$this->tpl->setVariable('BODY', $this->message->getBody());
		$this->tpl->setVariable('TYPE', $this->message->getActiveType());
		$this->tpl->setVariable('POSITION', $this->message->getPosition());
		$this->tpl->setVariable('ADD_CSS', $this->message->getAdditionalClasses());
		if (!$this->message->getPermanent()) {
			$this->tpl->setVariable('EVENT', $this->message->getFullTimeFormated());
		}
		if ($this->message->getDismissable()) {
			$this->tpl->setCurrentBlock('dismiss');
			$this->tpl->setVariable('DISMISS_LINK', 'goto.php?target=xnot_dismiss_' . $this->message->getId());
			$this->tpl->parseCurrentBlock();
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

?>
