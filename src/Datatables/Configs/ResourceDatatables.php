<?php

namespace ZhyuJqueryAdmin\Datatables\Configs;

use ZhyuJqueryAdmin\Datatables\AbstractDatatables;
use ZhyuJqueryAdmin\Datatables\DatatablesInterface;
use ZhyuJqueryAdmin\Model\Resource;

class ResourceDatatables extends AbstractDatatables implements DatatablesInterface
{
    public function model(){
        return Resource::class;
    }

    /**
     * Set custom act.
     *
     * @return string
     */
    public function act(){
        return 'ajax';
    }

    public function criteria(): array
    {
        return [];
    }

    public function config() : array{
        return [
            'id' =>  'myTable',
            'css' =>  [ 'table', 'manage-u-table', 'table-striped', 'dataTable', 'nowrap' ],
            'searchable_cols' => [ 'name',  'route'],
            'no_orderable_cols' => [],
            'cols_display' => [
                'name' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'route' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'orderby' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'icon_css' => [
                    'attributes' => [],
                    'css' => [ 'text-center' ],
                    'cols_css' => [ 'text-center' ],
                ],
                'buttons' => [
                    'title' => '',
                ],
            ],
        ];
    }

}

