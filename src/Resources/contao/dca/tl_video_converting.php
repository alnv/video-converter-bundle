<?php

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_video_converting'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],
    'list' => [
        'sorting' => [
            'mode' => 0,
            'fields' => ['name'],
            'panelLayout' => 'filter;search,limit'
        ],
        'label' => [
            'fields' => ['name'],
            'showColumns' => true
        ],
        'operations' => [
            'edit' => [
                'icon' => 'header.svg',
                'href' => 'act=edit'
            ],
            'convert' => [
                'label' => ['', 'Video-Konvertierung starten'],
                'icon' => 'sync.svg',
                'href' => 'key=convert'
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\'))return false;Backend.getScrollOffset()"'
            ]
        ]
    ],
    'palettes' => [
        'default' => 'name;source,dest;outputs'
    ],
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'autoincrement' => true, 'notnull' => true, 'unsigned' => true]
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'notnull' => false, 'unsigned' => true, 'default' => 0]
        ],
        'name' => [
            'inputType' => 'text',
            'eval' => [
                'maxlength' => 32,
                'tl_class' => 'w50',
                'mandatory' => true
            ],
            'search' => true,
            'sql' => ['type' => 'string', 'length' => 32, 'default' => '']
        ],
        'source' => [
            'inputType' => 'fileTree',
            'eval' => [
                'mandatory' => true,
                'fieldType' => 'radio',
                'files' => true,
                'filesOnly' => true,
                'tl_class' => 'clr',
                'extensions' => 'mp4'
            ],
            'sql' => "blob NULL"
        ],
        'dest' => [
            'inputType' => 'fileTree',
            'eval' => [
                'mandatory' => true,
                'fieldType' => 'radio',
                'files' => false,
                'tl_class' => 'clr'
            ],
            'sql' => "blob NULL"
        ],
        'outputs' => [
            'inputType' => 'multiColumnWizard',
            'eval' => [
                'tl_class' => 'w50 clr',
                'columnFields' => [
                    'width' => [
                        'label' => &$GLOBALS['TL_LANG']['tl_video_converting']['width'],
                        'inputType' => 'text',
                        'eval' => [
                            'rgxp' => 'natural'
                        ]
                    ],
                    'height' => [
                        'label' => &$GLOBALS['TL_LANG']['tl_video_converting']['height'],
                        'inputType' => 'text',
                        'eval' => [
                            'rgxp' => 'natural'
                        ]
                    ]
                ]
            ],
            'sql' => "blob NULL"
        ]
    ]
];