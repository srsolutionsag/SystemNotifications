<#1>
<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/vendor/autoload.php";
notMessage::updateDB();
?>
<#2>
<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/vendor/autoload.php";
sysnotDismiss::updateDB();
notMessage::updateDB();
global $DIC;
$DIC->database()->manipulate('UPDATE '.notMessage::TABLE_NAME.' SET active = 1, link_type = 0;');
?>
<#3>
<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/vendor/autoload.php";
notMessage::updateDB();
global $DIC;
$DIC->database()->modifyTableColumn(notMessage::TABLE_NAME, 'body', array(
	"type" => "clob"
));
?>
<#4>
<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/SystemNotifications/vendor/autoload.php";
notMessage::updateDB();
?>