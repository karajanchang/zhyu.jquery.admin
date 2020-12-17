<?php

namespace ZhyuJqueryAdmin\Repositories\Eloquents;


use ZhyuJqueryAdmin\Model\Resource;
use Zhyu\Repositories\Eloquents\Repository;

class ResourceRepository extends Repository
{
	
	public function model()
	{
	    return Resource::class;
	}

}