<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use App\User;
use Zhyu\Repositories\Eloquents\Repository;

class UserRepository extends Repository
{
	
	public function model()
	{
		return User::class;
	}
	
}