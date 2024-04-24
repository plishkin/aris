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

		$adminLogin = $_ENV['SS_DEFAULT_ADMIN_USERNAME'];
		$adminPass = $_ENV['SS_DEFAULT_ADMIN_PASSWORD'];
		if ($adminLogin && $adminPass) {
			$admin = \Member::get()->filter('Email', $adminLogin)->first();
			if (!$admin) {
				$admin = \Member::create();
				$admin->Email = $adminLogin;
				$admin->FirstName = _t('Member.DefaultAdminFirstname', 'Default Admin');
				$admin->write();
				$admin->Password = $adminPass;
				$admin->write();
				// find a group with ADMIN permission
				$adminGroup = \Permission::get_groups_by_permission('ADMIN')->First();
				$adminGroup
					->DirectMembers()
					->add($admin);
			}
		}
	}

}
