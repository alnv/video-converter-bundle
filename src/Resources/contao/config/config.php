<?php

$GLOBALS['BE_MOD']['video-converter-bundle'] = [];

array_insert($GLOBALS['BE_MOD']['video-converter-bundle'], 1, [
    'video-converter' => [
        'name' => 'video-converter',
        'convert' => [\Alnv\VideoConverterBundle\Backend\ConvertView::class, 'compile'],
        'tables' => [
            'tl_video_converting'
        ]
    ]
]);