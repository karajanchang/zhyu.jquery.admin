<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use App\Usergroup;
use Zhyu\Repositories\Eloquents\Repository;

class UsergroupRepository extends Repository
{
	
	public function model()
	{
		return Usergroup::class;
	}
	
}