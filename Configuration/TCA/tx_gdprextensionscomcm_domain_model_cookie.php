<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie',
        'label' => 'category',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
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
        'searchFields' => 'category,domain,platform,name,description,session,type',
        'iconfile' => 'EXT:gdpr_extensions_com_cm/Resources/Public/Icons/tx_gdprextensionscomcm_domain_model_cookie.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'category, domain, platform, name, description, session, type, root_pid,  pages_list, expires, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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
                    ['', 0],
                ],
                'foreign_table' => 'tx_gdprextensionscomcm_domain_model_cookie',
                'foreign_table_where' => 'AND {#tx_gdprextensionscomcm_domain_model_cookie}.{#pid}=###CURRENT_PID### AND {#tx_gdprextensionscomcm_domain_model_cookie}.{#sys_language_uid} IN (-1,0)',
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
                        0 => '',
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

        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.category',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'domain' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.domain',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'platform' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.platform',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.description',
            'config' => [
                'type' => 'input',
                'size' => 10000,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'session' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.session',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'expires' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.expires',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

        'root_pid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.root_pid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],

        'content_hash' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.content_hash',
            'config' => [
                'type' => 'input',
                'size' => 100,
                'eval' => 'trim',
                'default' => ''
            ],
        ],

        'pages_list' => [
            'exclude' => true,
            'label' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdprextensionscomcm_domain_model_cookie.pages_list',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '6',
                'default' => '',
            ],
        ],



    ],
];
