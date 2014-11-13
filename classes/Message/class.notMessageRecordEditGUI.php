<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/Edit/class.arEditGUI.php');
require_once('./Services/Form/classes/class.ilDateDurationInputGUI.php');
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
        /**
         * Sets the txt prefix for all fields at once
         * Prefixes can also be set individually per field with the same method
         * The txt string can also be replaced as a whole per field by setting $field->setTxt("newTxtName");
         */
        $this->getFields()->setTxtPrefix("msg_");

        /**
         * If those fields are set, the data will automatically set in the ActiveRecord before saving
         */
        $this->getFields()->setCreationDateField($this->getField("create_date"));
        $this->getFields()->setCreatedByField($this->getField("created_by"));
        $this->getFields()->setModificationDateField($this->getField("last_update"));
        $this->getFields()->setModifiedByField($this->getField("last_update_by"));

        $this->getField("id")->setVisible(false);

        $this->getField("type")->setPosition(-20);
        $this->getField("title")->setPosition(-10);
        $this->getField("body")->setPosition(-9);
        $this->getField("permanent")->setPosition(-8);
        $this->getField("additional_classes")->setPosition(-7);


        $this->getField("allowed_users")->setVisible(false);

        $field = $this->getField("prevent_login");
        $field->setPosition(-7);

        $field = $this->getField("event_start");
        $field->setVisible(false);

        $field = $this->getField("event_end");
        $field->setVisible(false);

        $field = $this->getField("display_start");
        $field->setVisible(false);

        $field = $this->getField("display_end");
        $field->setVisible(false);

        /**
         * Those fields are not yet used in the current version
         */
        $this->getField("dismissable")->setVisible(false);
        $this->getField("type_during_event")->setVisible(false);
        $this->getField("position")->setVisible(false);
        $this->getField("parent_id")->setVisible(false);
    }


    /**
     * @param arEditField $field
     */
    protected function addFormField(arEditField $field) {
        switch ($field->getName()) {
            case 'type':
                $type = new ilSelectInputGUI($this->txt($field->getTxt()), $field->getName());
                $type->setOptions(array(
                    notMessageRecord::TYPE_INFO => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_INFO),
                    notMessageRecord::TYPE_WARNING => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_WARNING),
                    notMessageRecord::TYPE_ERROR => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_ERROR),

                ));
                $this->addItem($type);
                return;
            case 'body':
                $this->addItem(new ilTextAreaInputGUI($this->txt($field->getTxt()), $field->getName()));
                return;
            case 'permanent':
                $permanent = new ilRadioGroupInputGUI($this->txt($field->getTxt()), $field->getName());

                $permanent_yes = new ilRadioOption($this->txt($this->getField("permanent")->getTxt() . '_yes'), 1);
                $permanent->addOption($permanent_yes);
                $this->addItem($permanent);

                $permanent_no = new ilRadioOption($this->txt($this->getField("permanent")->getTxt() . '_no'), 0);
                $display_time = new ilDateDurationInputGUI($this->txt('msg_display_date'), 'display_date');
                $display_time->setShowTime(true);
                $display_time->setMinuteStepSize(1);
                $permanent_no->addSubItem($display_time);
                $event_time = new ilDateDurationInputGUI($this->txt('mgs_event_data'), 'event_data');
                $event_time->setShowTime(true);
                $event_time->setMinuteStepSize(1);
                $permanent_no->addSubItem($event_time);
                $type_during_event = new ilSelectInputGUI($this->txt($this->getField('type_during_event')->getTxt()), 'type_during_event');
                $type_during_event->setOptions(array(
                    notMessageRecord::TYPE_INFO => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_INFO),
                    notMessageRecord::TYPE_WARNING => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_WARNING),
                    notMessageRecord::TYPE_ERROR => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_ERROR),

                ));
                $permanent_no->addSubItem($type_during_event);
                $permanent->addOption($permanent_no);
                return;
            case 'prevent_login':
                $prevent_login = new ilCheckboxInputGUI($this->txt($field->getTxt()), $field->getName());
                $allowed_users = new ilTextInputGUI($this->txt($this->getField('allowed_users')->getTxt()),$this->getField('allowed_users')->getName());
                $prevent_login->addSubItem($allowed_users);
                $this->addItem($prevent_login);
                return;

        }
        parent::addFormField($field);
    }

    /**
     * @param ilFormPropertyGUI $form_item
     * @param $get_function
     * @param arEditField $field
     */
    public function fillFormField(ilFormPropertyGUI $form_item, arEditField $field){
        switch ($field->getName()) {
            case 'allowed_users':
                $form_item->setValue("array");
                return;

        }
        parent::fillFormField($form_item, $field);
    }


}