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

	public function requireDefaultRecords()
	{
		parent::requireDefaultRecords();
		$admin = \Security::findAnAdministrator();
		if (!$admin->Password) {
			$admin->Password = $_ENV['SS_DEFAULT_ADMIN_PASSWORD'];
			$admin->write();
		}
	}

}
