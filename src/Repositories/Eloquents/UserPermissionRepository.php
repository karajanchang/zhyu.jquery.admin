<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use Zhyu\Model\UserPermission;
use Zhyu\Repositories\Eloquents\Repository;

class UserPermissionRepository extends Repository
{
	
	public function model()
	{
		return UserPermission::class;
	}
	
}