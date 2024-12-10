<?php

use Alnv\VideoConverterBundle\Backend\ConvertView;
use Contao\ArrayUtil;

$GLOBALS['BE_MOD']['video-converter-bundle'] = [];

ArrayUtil::arrayInsert($GLOBALS['BE_MOD']['video-converter-bundle'], 1, [
    'video-converter' => [
        'name' => 'video-converter',
        'convert' => [ConvertView::class, 'compile'],
        'tables' => [
            'tl_video_converting'
        ]
    ]
]);