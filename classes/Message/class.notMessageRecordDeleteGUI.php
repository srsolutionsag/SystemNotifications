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
        $field = $this->getField("message_before_event_title");
        $field->setVisibleDefault(true);
        $field->setPosition(0);

        $field = $this->getField("message_during_event_title");
        $field->setVisible(true);

        $field->setPosition(10);

        $field = $this->getField("message_during_event_type");
        $field->setVisible(true);
        $field->setPosition(15);

        $field = $this->getField("start_message_date_time");
        $field->setVisible(true);
        $field->setPosition(20);

        $field = $this->getField("start_event_date_time");
        $field->setVisible(true);
        $field->setPosition(30);

        $field = $this->getField("end_event_date_time");
        $field->setVisible(true);
        $field->setPosition(40);
    }
}