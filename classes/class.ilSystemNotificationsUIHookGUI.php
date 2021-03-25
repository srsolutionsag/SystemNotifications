<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Class ilSystemNotificationsUIHookGUI
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version $Id$
 * @ingroup ServicesUIComponent
 */
class ilSystemNotificationsUIHookGUI extends ilUIHookPluginGUI
{

    /**
     * @var ilSystemNotificationsPlugin
     */
    protected $pl;
    const TPL_ID = 'tpl_id';
    /**
     * @var array
     */
    protected static $ztpls = array(
        'Services/Init/tpl.startup_screen.html',
        'tpl.adm_content.html',
    );
    /**
     * @var ilObjUser
     */
    protected $usr;

    public function __construct()
    {
        global $DIC;
        $this->pl  = ilSystemNotificationsPlugin::getInstance();
        $this->usr = $DIC->user();
    }

    /**
     * @var array
     */
    protected static $loaded = array();

    /**
     * @param string $key
     * @return bool
     */
    protected static function isLoaded($key)
    {
        return (self::$loaded[$key] == 1);
    }

    /**
     * @param string $key
     */
    protected static function setLoaded($key)
    {
        self::$loaded[$key] = 1;
    }

    /**
     * @var int
     */
    protected static $goto_num = 0;

    /**
     * @param string $a_comp
     * @param string $a_part
     * @param array  $a_par
     * @return array
     */
    public function getHTML($a_comp, $a_part, $a_par = array())
    {
        if ($a_part == 'template_show') {
            global $DIC;

            $DIC->globalScreen()->layout()->meta()->addCss($this->pl->getDirectory() . '/templates/default/notifications.css');
            $DIC->globalScreen()->layout()->meta()->addJs($this->pl->getDirectory() . '/js/dist/xnot.js');

            $result = $a_par['html'];
            $result = preg_replace("/<div([\\w =\"_\\-]*)mainspacekeeper([\\w =\"_\\-]*)>/uiUmx", "<div$1mainspacekeeper$2>"
                . $this->getNotificatiosHTML(), $result);

            if (!$result) {
                $result = $a_par['html'];
            }

            return array(
                'mode' => ilUIHookPluginGUI::REPLACE,
                'html' => $result,
            );
        }

        return array(
            'mode' => ilUIHookPluginGUI::KEEP,
            'html' => '',
        );
    }

    /**
     * @return string
     */
    protected function getNotificatiosHTML()
    {

        if (!$this->usr instanceof ilObjUser) {
            return null;
        }

        $notMessageList = new notMessageList();
        $notMessageList->check($this->usr);
        $notifications = new notMessageListGUI($notMessageList);

        return $notifications->getHTML();
    }

    public function gotoHook()
    {
        if (preg_match("/xnot_dismiss_(.*)/uim", $_GET['target'], $matches)) {
            /**
             * @var notMessage $notMessage
             */
            $notMessage = notMessage::find($matches[1]);
            if ($notMessage instanceof notMessage && $this->usr instanceof ilObjUser
                && $notMessage->isUserAllowedToDismiss($this->usr)) {
                $notMessage->dismiss($this->usr);
            }
            ilUtil::redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
