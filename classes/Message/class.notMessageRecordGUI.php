<?php
include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordIndexTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordEditGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordDeleteGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecord.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordDisplayGUI.php');


require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/class.arGUI.php');

/**
 * @author  Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version $Id$
 *
 */
class notMessageRecordGUI extends arGUI
{
    /**
     * Set some string messages. If permission handling is needed, execute command should be overriden.
     */
    /**
     * @return string
     */
    public function getRecordCreatedMessage(){
        return $this->txt(('msg_record_created'), true);
    }

    /**
     * @return string
     */
    public function getDeleteRecordsMessage(){
        return $this->txt(('msg_records_deleted'), true);
    }

    /**
     * @return string
     */
    public function getDeleteRecordMessage(){
        return $this->txt(('msg_record_deleted'), true);
    }

    /**
     * @return string
     */
    public function getDeleteRecordsConfirmationMessage(){
        return $this->txt(('msg_delete_records_confirmation'), true);
    }

    /**
     * @return string
     */
    public function getDeleteRecordConfirmationMessage(){
        return $this->txt(('msg_delete_record_confirmation'), true);
    }
}