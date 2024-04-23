<?php

namespace PrintCalc;

/**
 * Class MemberExtension
 * @package Imagick
 * @property \Member $owner
 * @method \Member|MemberExtension getOwner()
 */
class MemberExtension extends \DataExtension
{

	public function FirstNameLastName() {
		$member = $this->getOwner();
		return $member->FirstName.($member->Surname?' ':'').$member->Surname;
	}

}