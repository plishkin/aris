<?php

class MySiteConfig extends DataExtension {

    private static $db = array(
        'AdminEmails' => 'Varchar(512)',
    );

    private static $has_one = array(
    );

    private static $many_many = array(
    );

    public function updateCMSFields(FieldList $fields) {
        $owner = $this->getOwner();

        $fields->addFieldToTab('Root.Main', new TextField('AdminEmails', 'Admin Emails (Commas separated)'));
    }
}