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
    /**
     * @return string
     */
    public function getFormPrefix(){
        return "msg_";
    }

    /**
     * @return string
     */
    public function getFormName(){
        return "message";
    }
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

        /**
         * These fields can be handled by arEditGUI automatically since there is a mapping of their db-type to the field
         * in the GUI
         */
        $this->getField("title")->setPosition(-90);
        $this->getField("additional_classes")->setPosition(-50);

        /**
         * The mapping from those fields to their corresponding GUI field has to be done manually
         */
        $field = $this->getField("type");
        $field->setPosition(-100);
        $type = new ilSelectInputGUI($this->txt($field->getTxt()), $field->getName());
        $type->setOptions(array(
            notMessageRecord::TYPE_INFO => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_INFO),
            notMessageRecord::TYPE_WARNING => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_WARNING),
            notMessageRecord::TYPE_ERROR => $this->txt($field->getTxt() . '_' . notMessageRecord::TYPE_ERROR),

        ));
        $field->setFormElement($type);

        $field = $this->getField("body");
        $field->setPosition(-80);
        $field->setFormElement(new ilTextAreaInputGUI($this->txt($field->getTxt()), $field->getName()));

        $field = $this->getField("permanent");
        $field->setPosition(-70);
        $permanent = new ilRadioGroupInputGUI($this->txt($field->getTxt()), $field->getName());
        $permanent_yes = new ilRadioOption($this->txt($field->getTxt() . '_yes'), 1);
        $permanent->addOption($permanent_yes);
        $permanent_no = new ilRadioOption($this->txt($field->getTxt() . '_no'), 0);
        $permanent->addOption($permanent_no);
        $field->setFormElement($permanent);

        $field = $this->getField("type_during_event");
        $type = new ilSelectInputGUI($this->txt($field->getTxt()), $field->getName());
        $type->setOptions(array(
            notMessageRecord::TYPE_INFO => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_INFO),
            notMessageRecord::TYPE_WARNING => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_WARNING),
            notMessageRecord::TYPE_ERROR => $this->txt($this->getField("type")->getTxt() . '_' . notMessageRecord::TYPE_ERROR),

        ));
        $field->setFormElement($type);
        $field->setSubelementOf($permanent_no);
        $field->setPosition(-60);

        $field = $this->getField("prevent_login");
        $field->setFormElement(new ilCheckboxInputGUI($this->txt($field->getTxt()),$field->getName()));
        $field->setPosition(10);

        $this->getField("allowed_users")->setSubelementOf($this->getField("prevent_login")->getFormElement());

        /**
         * Since these fields affect multiple AR fields, these have to been handled completely manually
         */
        $this->getField("display_start")->setVisible(false);
        $this->getField("display_end")->setVisible(false);
        $this->getField("event_start")->setVisible(false);
        $this->getField("event_end")->setVisible(false);

        /**
         * Those fields are not yet used in the current version
         */
        $this->getField("dismissable")->setVisible(false);
        $this->getField("position")->setVisible(false);
        $this->getField("parent_id")->setVisible(false);
    }

    /**
     * Handle the date fields manually
     */
    protected function beforeInitForm() {
        $permanent_options = $this->getField("permanent")->getFormElement()->getOptions();
        $display_time = new ilDateDurationInputGUI($this->txt('msg_display_date'), 'display_date');
        $display_time->setShowTime(true);
        $display_time->setMinuteStepSize(1);
        $permanent_options[1]->addSubItem($display_time);
        $event_time = new ilDateDurationInputGUI($this->txt('msg_event_date'), 'event_date');
        $event_time->setShowTime(true);
        $event_time->setMinuteStepSize(1);
        $permanent_options[1]->addSubItem($event_time);
    }

    /**
     * Fill the date fields manually
     */
    public function afterFillForm(){
        /**
         * @var $f_event_date   ilDateDurationInputGUI
         * @var $f_display_date ilDateDurationInputGUI
         */
        $f_event_date = $this->getItemByPostVar('event_date');
        $f_event_date->setStart(new ilDateTime($this->ar->getEventStart(), IL_CAL_DATETIME));
        $f_event_date->setEnd(new ilDateTime($this->ar->getEventEnd(), IL_CAL_DATETIME));

        $f_display_date = $this->getItemByPostVar('display_date');
        $f_display_date->setStart(new ilDateTime($this->ar->getDisplayStart(), IL_CAL_DATETIME));
        $f_display_date->setEnd(new ilDateTime($this->ar->getDisplayEnd(), IL_CAL_DATETIME));
    }

    /**
     * Save the date fields manually
     * @return bool
     */
    public function afterValidation(){
        /**
         * @var $f_event_date   ilDateDurationInputGUI
         * @var $f_display_date ilDateDurationInputGUI
         */

        $f_event_date = $this->getItemByPostVar('event_date');
        $this->ar->setEventStart($f_event_date->getStart()->get(IL_CAL_DATETIME));
        $this->ar->setEventEnd($f_event_date->getEnd()->get(IL_CAL_DATETIME));

        $f_display_date = $this->getItemByPostVar('display_date');
        $this->ar->setDisplayStart($f_display_date->getStart()->get(IL_CAL_DATETIME));
        $this->ar->setDisplayEnd($f_display_date->getEnd()->get(IL_CAL_DATETIME));

        return true;
    }

}