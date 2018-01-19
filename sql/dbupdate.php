<#1>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
notMessage::updateDB();
?>
<#2>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Dismiss/class.sysnotDismiss.php');
sysnotDismiss::updateDB();
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
notMessage::updateDB();
global $ilDB;
/**
 * @var $ilDB ilDB
 */
$ilDB->manipulate('UPDATE '.notMessage::returnDbTableName().' SET active = 1, link_type = 0;');
?>
<#3>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
notMessage::updateDB();
/**
 * @var $ilDB ilDB
 */
$ilDB->modifyTableColumn(notMessage::returnDbTableName(), 'body', array(
	"type" => "clob"
));
?>
<#4>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/classes/Message/class.notMessage.php');
notMessage::updateDB();
?>