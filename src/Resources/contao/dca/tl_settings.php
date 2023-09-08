<?php

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{ffmpeg_legend},ffmpegbinaries,ffprobebinaries';

$GLOBALS['TL_DCA']['tl_settings']['fields']['ffmpegbinaries'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50'
    ]
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['ffprobebinaries'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50'
    ]
];