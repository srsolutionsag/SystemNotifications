<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/Edit/class.arEditGUI.php');
/**
 * GUI-Class ActiveRecordEditGUI
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id:
 *
 */
class notMessageRecordEditGUI extends arEditGUI
{
    protected function customizeFields()
    {
        $field = $this->getField("id");
        $field->setVisible(false);
        $field->setPosition(0);

        $field = $this->getField("title");
        $field->setTxt("msg_title");
        $field->setPosition(0);

        $field = $this->getField("event_start");
        $field->setTxt("msg_event_start");
        $field->setPosition(10);

        $field = $this->getField("event_end");
        $field->setTxt("msg_event_end");
        $field->setPosition(20);

        $field = $this->getField("display_start");
        $field->setTxt("msg_display_start");
        $field->setPosition(30);

        $field = $this->getField("display_end");
        $field->setTxt("msg_display_end");
        $field->setPosition(40);

        $field = $this->getField("type");
        $field->setTxt("msg_type");
        $field->setPosition(50);

        $field = $this->getField("type_during_event");
        $field->setTxt("msg_type_during_event");
        $field->setPosition(60);

        
    }

    /**
     * @param ilFormPropertyGUI $form_item
     * @param $get_function
     * @param arEditField $field
     */
    public function fillFormField(ilFormPropertyGUI $form_item,$get_function, arEditField $field){
        switch ($field->getName()) {
            case 'allowed_users':
                $form_item->setValue("array");
                return;

        }
        parent::fillFormField($form_item,$get_function, $field);
    }
}