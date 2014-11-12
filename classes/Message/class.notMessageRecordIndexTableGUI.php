<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/Index/class.arIndexTableGUI.php');



/**
 * TableGUI ilMessageTableGUI
 *
 * @author  Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version $Id:
 *
 */
class notMessageRecordIndexTableGUI extends arIndexTableGUI {
    protected function customizeFields()
    {
        $field = $this->getField("title");
        $field->setTxt("msg_title");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(0);

        $field = $this->getField("event_start");
        $field->setTxt("msg_event_start");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(10);

        $field = $this->getField("event_end");
        $field->setTxt("msg_event_end");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(20);

        $field = $this->getField("display_start");
        $field->setTxt("msg_display_start");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(30);

        $field = $this->getField("display_end");
        $field->setTxt("msg_display_end");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(40);

        $field = $this->getField("type");
        $field->setTxt("msg_type");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setPosition(50);

        $field = $this->getField("type_during_event");
        $field->setTxt("msg_type_during_event");
        $field->setVisible(true);
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setPosition(60);
    }

    /**
     * @param arIndexTableField $field
     * @param $item
     * @param $value
     * @return string
     */
    protected function setArFieldData(arIndexTableField $field, $item, $value)
    {
        if ($field->getName() == 'type' || $field->getName() == 'type_during_event')
        {
            return $this->txt("msg_type_".$value);
        }
        else
        {
            return parent::setArFieldData($field, $item, $value);
        }

    }
}
?>
