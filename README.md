SystemNotifications
===================
Show System-Notification-Banners to ILIAS-Users and Anonymous on every page of ILIAS.

###Installation
####Install ActiveRecord
ILIAS 4.4 does not include ActiveRecord. Therefore please install the latest Version of active record before you install the plugin:
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Libraries/  
cd Customizing/global/plugins/Libraries  
git clone https://github.com/studer-raimann/ActiveRecord.git  
```  
####Install the plugin
Start at your ILIAS root directory
```bash
mkdir -p Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
cd Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/  
git clone https://github.com/studer-raimann/SystemNotifications.git  
```  
As ILIAS administrator go to "Administration->Plugins" and install/activate the plugin.

###Contact
studer + raimann ag  
Waldeggstrasse 72  
3097 Liebefeld  
Switzerland  

info@studer-raimann.ch
www.studer-raimann.ch
