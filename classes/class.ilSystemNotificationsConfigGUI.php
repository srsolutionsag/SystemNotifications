<?php
include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordGUI.php');


/**
 * Class ilSystemNotificationsConfigGUI
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @author  Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version 1.0.0
 *
 *
 * @ilCtrl_Calls      ilSystemNotificationsConfigGUI: notMessageRecordGUI
 */


class ilSystemNotificationsConfigGUI extends ilPluginConfigGUI {
    /**
     * @var ilCtrl
     */
    protected $ctrl;


    /**
     * @param $cmd
     */
    function performCommand($cmd)
    {
        global $ilCtrl;

        $this->ctrl = $ilCtrl;

        if($cmd == "configure")
        {
            $this->ctrl->setCmd("index");
        }

        $record_gui = new notMessageRecordGUI("notMessageRecord",$this->getPluginObject());
        $this->ctrl->forwardCommand($record_gui);
    }
}

?>
