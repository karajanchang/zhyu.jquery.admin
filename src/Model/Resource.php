<?php
namespace ZhyuJqueryAdmin\Model;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model {
    protected $table = 'resources';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function __toString()
    {
        return (string) $this->name;
    }

    public function parent(){

        return $this->belongsTo(Resource::class, 'parent_id');
    }

}