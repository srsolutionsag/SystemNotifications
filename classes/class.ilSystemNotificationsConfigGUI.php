<?php

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class ilSystemNotificationsConfigGUI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilSystemNotificationsConfigGUI extends ilPluginConfigGUI
{

    const CMD_ADD = 'add';
    const CMD_CANCEL = 'cancel';
    const CMD_CONFIGURE = 'configure';
    const CMD_CONFIRM_DELETE = 'confirmDelete';
    const CMD_DELETE = 'delete';
    const CMD_EDIT = 'edit';
    const CMD_RESET_FOR_ALL = 'resetForAll';
    const CMD_SAVE = 'save';
    const CMD_UPDATE = 'update';
    const CMD_UPDATE_AND_STAY = 'updateAndStay';
    const NOT_MSG_ID = 'xnot_msg_id';
    /**
     * @var notMessage
     */
    protected $notMessage;
    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;
    /**
     * @var ilTemplate
     */
    protected $tpl;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilSystemNotificationsPlugin
     */
    protected $pl;

    public function __construct()
    {
        global $DIC;
        /**
         * @var ilTemplate $tpl
         * @var ilCtrl     $ilCtrl
         */
        $this->tpl     = $DIC->ui()->mainTemplate();
        $this->ctrl    = $DIC->ctrl();
        $this->toolbar = $DIC->toolbar();
        $this->pl      = ilSystemNotificationsPlugin::getInstance();
        if (!$this->pl->isActive()) {
            $this->ctrl->redirectByClass('');
        }
        $this->ctrl->setParameter($this, self::NOT_MSG_ID, $_REQUEST[self::NOT_MSG_ID]);
        $this->notMessage = notMessage::find($_GET[self::NOT_MSG_ID]);
    }

    /**
     * @param string $cmd
     */
    public function performCommand($cmd)
    {
        switch ($cmd) {
            case self::CMD_CONFIGURE:
            case self::CMD_CONFIRM_DELETE:
            case self::CMD_SAVE:
            case self::CMD_UPDATE:
            case self::CMD_UPDATE_AND_STAY:
            case self::CMD_ADD:
            case self::CMD_EDIT:
            case self::CMD_CANCEL:
            case self::CMD_RESET_FOR_ALL:
            case self::CMD_DELETE:
                $this->{$cmd}();
                break;
        }
    }

    protected function configure()
    {
        $button = ilLinkButton::getInstance();
        $button->setCaption($this->pl->txt('common_add_msg'), false);
        $button->setUrl($this->ctrl->getLinkTarget($this, self::CMD_ADD));
        $this->toolbar->addButtonInstance($button);
        $notMessageTableGUI = new notMessageTableGUI($this, self::CMD_CONFIGURE);
        $this->tpl->setContent($notMessageTableGUI->getHTML());
    }

    protected function add()
    {
        $notMessageFormGUI = new notMessageFormGUI($this, new notMessage());
        $this->tpl->setContent($notMessageFormGUI->getHTML());
    }

    protected function save()
    {
        $notMessageFormGUI = new notMessageFormGUI($this, new notMessage());
        $notMessageFormGUI->setValuesByPost();
        if ($notMessageFormGUI->saveObject()) {
            ilUtil::sendInfo($this->pl->txt('msg_success'), true);
            $this->ctrl->redirect($this, self::CMD_CONFIGURE);
        }
        $this->tpl->setContent($notMessageFormGUI->getHTML());
    }

    protected function cancel()
    {
        $this->ctrl->setParameter($this, self::NOT_MSG_ID, null);
        $this->ctrl->redirect($this, self::CMD_CONFIGURE);
    }

    protected function edit()
    {
        $notMessageFormGUI = new notMessageFormGUI($this, $this->notMessage);
        $notMessageFormGUI->fillForm();
        $this->tpl->setContent($notMessageFormGUI->getHTML());
    }

    protected function update()
    {
        $notMessageFormGUI = new notMessageFormGUI($this, $this->notMessage);
        $notMessageFormGUI->setValuesByPost();
        if ($notMessageFormGUI->saveObject()) {
            ilUtil::sendInfo($this->pl->txt('msg_success'), true);
            $this->ctrl->redirect($this, self::CMD_CONFIGURE);
        }
        $this->tpl->setContent($notMessageFormGUI->getHTML());
    }

    protected function updateAndStay()
    {
        $notMessageFormGUI = new notMessageFormGUI($this, $this->notMessage);
        $notMessageFormGUI->setValuesByPost();
        if ($notMessageFormGUI->saveObject()) {
            ilUtil::sendInfo($this->pl->txt('msg_success'));
        }
        $this->tpl->setContent($notMessageFormGUI->getHTML());
    }

    protected function confirmDelete()
    {
        $ilConfirmationGUI = new ilConfirmationGUI();
        $ilConfirmationGUI->setFormAction($this->ctrl->getFormAction($this));
        $ilConfirmationGUI->addItem(self::NOT_MSG_ID, $this->notMessage->getId(), $this->notMessage->getTitle());
        $ilConfirmationGUI->setCancel($this->pl->txt('msg_form_button_cancel'), self::CMD_CANCEL);
        $ilConfirmationGUI->setConfirm($this->pl->txt('msg_form_button_delete'), self::CMD_DELETE);

        $this->tpl->setContent($ilConfirmationGUI->getHTML());
    }

    protected function delete()
    {
        $this->notMessage->delete();
        ilUtil::sendInfo($this->pl->txt('msg_success'), true);
        $this->cancel();
    }

    protected function resetForAll()
    {
        $this->notMessage->resetForAllUsers();
        $this->cancel();
    }
}
