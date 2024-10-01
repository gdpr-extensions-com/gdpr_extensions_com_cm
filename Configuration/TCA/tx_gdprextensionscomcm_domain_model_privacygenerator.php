<?php
return [
    'ctrl' => [
        'title' => 'Privacy generator',
        'label' => 'Privacy generator',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'header_content , quill_content_data, content_block_data, website_id, website_url, group, template_name',
        'iconfile' => 'EXT:gdpr_extensions_com_cm/Resources/Public/Icons/tx_gdprextensionscomcm_domain_model_externalresource.gif'
    ],
    'types' => [
        '1' => ['showitem' => ' header_content , quill_content_data, content_block_data, website_id, website_url, group, template_name,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_gdprextensionscomcm_domain_model_privacygenerator',
                'foreign_table_where' => 'AND {#tx_gdprextensionscomcm_domain_model_privacygenerator}.{#pid}=###CURRENT_PID### AND {#tx_gdprextensionscomcm_domain_model_privacygenerator}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'header_content' => [
            'exclude' => 1,
            'label' => 'Header Content',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'quill_content_data' => [
            'exclude' => 1,
            'label' => 'Quill Content Data',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'content_block_data' => [
            'exclude' => 1,
            'label' => 'Content Block Data',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],
        'website_id' => [
            'exclude' => true,
            'label' => 'Website Id',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_goclientwebsites_domain_model_website',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'website_url' => [
            'exclude' => false,
            'label' => 'Website Url',
            'config' => [
                'type' => 'input',
                'foreign_table' => 'tx_goclientwebsites_domain_model_website',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
                'required' => true
            ],
        ],

        'template_name' => [
            'exclude' => 1,
            'label' => 'Template Name',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],

        'dashboard_api_key' => [
            'exclude' => true,
            'label' => 'Dashboard API Key',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

        'root_pid' => [
            'exclude' => true,
            'label' => 'Root Pid',
            'description' => 'Root Pid Desc',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],


    ],
];
