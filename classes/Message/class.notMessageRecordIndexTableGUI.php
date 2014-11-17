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
    /**
     * @return string
     */
    protected function getAddButtonTxt(){
        return $this->txt("msg_add_item");
    }

    protected function customizeFields()
    {
        /**
         * Sets the txt prefix for all fields at once
         * Prefixes can also be set individually per field with the same method
         * The txt string can also be replaced as a whole per field by setting $field->setTxt("newTxtName");
         */
        $this->getFields()->setTxtPrefix("msg_");

        $field = $this->getField("title");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(0);

        $field = $this->getField("type");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setPosition(10);

        $field = $this->getField("type_during_event");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setPosition(20);

        $field = $this->getField("event_start");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(30);

        $field = $this->getField("event_end");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(40);

        $field = $this->getField("display_start");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(50);

        $field = $this->getField("display_end");
        $field->setVisibleDefault(true);
        $field->setSortable(true);
        $field->setHasFilter(true);
        $field->setPosition(60);

        /**
         * Created by and updated by fields can be indicated, so that the display is handled automatically
         */
        $field = $this->getField("create_date");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setIsCreationDateField(true);
        $field->setPosition(70);

        $field = $this->getField("last_update");
        $field->setVisibleDefault(false);
        $field->setSortable(true);
        $field->setHasFilter(false);
        $field->setIsModificationDateField(true);
        $field->setPosition(80);

        $field = $this->getField("created_by");
        $field->setVisibleDefault(false);
        $field->setSortable(false);
        $field->setHasFilter(false);
        $field->setIsCreatedByField(true);
        $field->setPosition(90);

        $field = $this->getField("last_update_by");
        $field->setVisibleDefault(false);
        $field->setSortable(false);
        $field->setHasFilter(false);
        $field->setIsModifiedByField(true);
        $field->setPosition(100);
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