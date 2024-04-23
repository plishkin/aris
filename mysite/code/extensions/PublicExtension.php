<?php

namespace PrintCalc;

use \FieldList;


/**
 * Class ActionExtension
 * @package ImagickFlow
 * @method \DataObject getOwner()
 */
class PublicExtension extends \DataExtension {

	public function canView($member = null) {
		$member = $member ?: \Member::currentUser();
		if (!$member) return null;
		$owner = $this->getOwner();
		if (preg_match('/^PC\\\/', $owner->ClassName) ||
			preg_match('/^BB\\\/', $owner->ClassName)) return true;
		return null;
	}

	public function canEdit($member = null) {
		return $this->canView($member);
	}

	public function canCreate($member = null) {
		return $this->canView($member);
	}

}
