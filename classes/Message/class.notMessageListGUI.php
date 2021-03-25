<?php
require_once __DIR__ . "/../../vendor/autoload.php";

/**
 * Class notMessageListGUI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class notMessageListGUI
{

    /**
     * @var ilTemplate
     */
    protected $tpl;
    /**
     * @var notMessageList
     */
    protected $list;
    /**
     * @var ilObjUser
     */
    protected $usr;
    /**
     * @var ilSystemNotificationsPlugin
     */
    protected $pl;

    /**
     * @param notMessageList $notMessageList
     */
    public function __construct(notMessageList $notMessageList)
    {
        global $DIC;
        $this->pl   = ilSystemNotificationsPlugin::getInstance();
        $this->tpl  = $this->pl->getTemplate('default/tpl.notification_list.html', false, false);
        $this->list = $notMessageList;
        $this->usr  = $DIC->user();
    }

    /**
     * @return string
     */
    public function getHTML()
    {
        $html = '';
        foreach ($this->list->getActive() as $not) {
            if ($not->isVisibleForUser($this->usr)) {
                $notGUI = new notMessageGUI($not);
                $html   = $notGUI->append($html);
            }
        }
        $this->tpl->setVariable('LIST', $html);

        return $this->tpl->get();
    }
}
