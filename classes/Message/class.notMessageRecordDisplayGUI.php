<?php
require_once('./Customizing/global/plugins/Libraries/ActiveRecord/Views/Display/class.arDisplayGUI.php');

/**
 * Class MessageRecord
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version 1.0.0
 */
class notMessageRecordDisplayGUI extends arDisplayGUI {
    public function customizeFields()
    {
        $this->getFields()->setTxtPrefix("msg_");

        /**
         * Hide the Id of the record
         */
        $this->getField('id')->setVisible(false);

        /**
         * Hide fields that are not yet implemented
         */
        $this->getField("dismissable")->setVisible(false);
        $this->getField("position")->setVisible(false);
        $this->getField("parent_id")->setVisible(false);

        /**
         * Order some fields (0 is default position)
         */
        $this->getField("type")->setPosition(-100);
        $this->getField("title")->setPosition(-90);
        $this->getField("body")->setPosition(-80);
        $this->getField("permanent")->setPosition(-60);
        $this->getField("type_during_event")->setPosition(-50);

        $this->getField("additional_classes")->setPosition(10);
        $this->getField("prevent_login")->setPosition(20);
        $this->getField("allowed_users")->setPosition(30);
        $this->getField("create_date")->setPosition(40);
        $this->getField("last_update")->setPosition(50);
        $this->getField("created_by")->setPosition(60);
        $this->getField("created_by")->setIsCreatedByField(true);
        $this->getField("last_update_by")->setPosition(70);
        $this->getField("last_update_by")->setIsModifiedByField(true);

    }
}