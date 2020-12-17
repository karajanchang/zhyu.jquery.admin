<?php

namespace Zhyu\Repositories\Eloquents;


use ZhyuJqueryAdmin\Model\Resource;

class ResourceRepository extends Repository
{
	
	public function model()
	{
	    return Resource::class;
	}

}