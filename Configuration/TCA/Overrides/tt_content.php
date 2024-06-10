<?php
defined('TYPO3') || die();
$frontendLanguageFilePrefix = 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:';
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'GdprExtensionsComCm',
    'CookieConsentModal',
    'Cookies Consent Manager'
);

$fields = [
    'consent_header_title' => [
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim',
        ]
    ],    

    'consent_header' => [
        'config' => [
            'type' => 'text',
            'cols' => 30,
            'rows' => 5,
            'eval' => 'trim',
        ]
    ],

    'functional' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:functional',
        'config' => [
            'type' => 'text',
            'cols' => 30,
            'rows' => 5,
            'eval' => 'trim',
        ]
    ],
    'non_functional' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:non_functional',
        'config' => [
            'type' => 'text',
            'cols' => 30,
            'rows' => 5,
            'eval' => 'trim',
        ]
    ],
    'statistics' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:statistics',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
                'eval' => 'trim',
            ]
    ],
    'marketing' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:marketing',
                'config' => [
                    'type' => 'text',
                    'cols' => 30,
                    'rows' => 5,
                    'eval' => 'trim',
                ]
    ],
    'analytics' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:analytics',
                    'config' => [
                        'type' => 'text',
                        'cols' => 30,
                        'rows' => 5,
                        'eval' => 'trim',
                      
                    ]
    ],
    'others' => [
        'displayCond' => 'USER:GdprExtensionsCom\GdprExtensionsComCm\Utility\FetchCategories->getCoookieCategoriesForRoodPid:others',
             'config' => [
                    'type' => 'text',
                    'cols' => 30,
                    'rows' => 5,
                    'eval' => 'trim',
             ],
        
    ]
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $fields);

$GLOBALS['TCA']['tt_content']['types']['gdprextensionscomcm_cookieconsentmodal'] = [
    'showitem' => '
                --palette--;' . $frontendLanguageFilePrefix . 'palette.general;general,
                 analytics; Analytics Cookies Description,
                 marketing; Marketing Cookies Description,
                 statistics; Statistics Cookies Description,
                 non_functional; Non-Functional Cookies Description,
                 functional; Functional Cookies Description,
                 others; Others Cookies Description,
                 consent_header_title; Header Title,
                 consent_header; Header Description,
                 
                --div--;' . $frontendLanguageFilePrefix . 'tabs.appearance,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.frames;frames,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
                --div--;' . $frontendLanguageFilePrefix . 'tabs.access,
                hidden;' . $frontendLanguageFilePrefix . 'field.default.hidden,
                --palette--;' . $frontendLanguageFilePrefix . 'palette.access;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
        ',
];
// Register Report plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'GdprExtensionsComCm',
    'Report',
    'Cookies & External Resources Report'
);
