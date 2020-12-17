<?php

namespace ZhyuJqueryAdmin\Datatables;

Interface DatatablesInterface {
    public function config() : array;
    public function criteria() : array;
}