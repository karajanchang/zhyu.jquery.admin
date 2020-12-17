<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use ZhyuJqueryAdmin\Model\UsergroupPermission;
use Zhyu\Repositories\Eloquents\Repository;

class UsergroupPermissionRepository extends Repository
{
	
	public function model()
	{
		return UsergroupPermission::class;
	}
	
}