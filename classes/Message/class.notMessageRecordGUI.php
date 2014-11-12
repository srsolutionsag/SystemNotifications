<?php
include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordIndexTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordEditGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecordDeleteGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessageRecord.php');
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/class.arGUI.php');

/**
 * @author  Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version $Id$
 *
 */
class notMessageRecordGUI extends arGUI
{
}
?>