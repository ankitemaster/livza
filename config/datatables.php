<?php

return [

    'search'          => [

        'smart'            => true,

        'case_insensitive' => true,

        'use_wildcards'    => false,
    ],

    'fractal'         => [
        'includes'   => 'include',

        'serializer' => 'League\Fractal\Serializer\DataArraySerializer',
    ],

    'script_template' => 'datatables::script',

    'index_column'    => 'DT_Row_Index',

    'namespace'       => [

        'base'  => 'DataTables',

        'model' => '',
    ],

    'pdf_generator'   => 'excel',

    'snappy'          => [

        'options'     => [
            'no-outline'    => true,
            'margin-left'   => '0',
            'margin-right'  => '0',
            'margin-top'    => '10mm',
            'margin-bottom' => '10mm',
        ],
        'orientation' => 'landscape',
    ],
];

?>