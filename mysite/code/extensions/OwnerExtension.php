<?php

namespace PrintCalc;

use \FieldList;


/**
 * Class ActionExtension
 * @package ImagickFlow
 * @property int OwnerID
 * @method \Member Owner()
 */
class OwnerExtension extends \DataExtension {

	private static $has_one = array(
		'Owner' => 'Member',
	);

	public function updateSummaryFields(&$fields) {
		parent::updateSummaryFields($fields);
		$owner = $this->getOwner();

		$fields['Owner.FirstNameLastName'] = $owner->fieldLabel('Owner');
	}

	public function updateCMSFields(FieldList $fields) {
		parent::updateCMSFields($fields);
		$owner = $this->getOwner();

		if ($owner->exists()) {
			$ownerField = $fields->dataFieldByName('OwnerID');
			if ($ownerField instanceof \FormField) {
				$fields->replaceField('OwnerID', $ownerField->performReadonlyTransformation());
			}
		} else {
			$fields->removeByName('OwnerID');
		}
	}


	public function onBeforeWrite() {
		$workflow = $this->getOwner();
		if (!$workflow->OwnerID) $workflow->OwnerID = \Member::currentUserID();
		parent::onBeforeWrite();
	}

}
