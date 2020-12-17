<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use ZhyuJqueryAdmin\Model\UserPermission;
use Zhyu\Repositories\Eloquents\Repository;

class UserPermissionRepository extends Repository
{
	
	public function model()
	{
		return UserPermission::class;
	}
	
}