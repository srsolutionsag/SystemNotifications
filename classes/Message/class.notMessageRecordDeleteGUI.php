<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/Delete/class.arDeleteGUI.php');

/**
 * GUI-Class ActiveRecordDeleteGUI
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id:
 *
 */
class notMessageRecordDeleteGUI extends arDeleteGUI
{
    public function customizeFields()
    {
        $this->getFields()->setTxtPrefix("msg_");

        $field = $this->getField("title");
        $field->setVisible(true);
        $field->setPosition(0);

        $field = $this->getField("type");
        $field->setVisible(true);
        $field->setPosition(10);
    }
}