<?php

if ((int)\TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version() >= 12) {
    return[
        // 'gdpr' => [
        //     'labels' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_mod_web.xlf',
        //     'iconIdentifier' => 'gdpr_extensions_com_cm',
        //     'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        // ],

        'gdprconsentmanager' => [
            // 'parent' => 'gdpr',
            'position' => [],
            'access' => 'user,group',
            'iconIdentifier' => 'gdpr_extensions_com_tab',
            'path' => '/module/gdprconsentmanager',
            'labels' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_gdprmanager.xlf',
            'extensionName' => 'GdprExtensionsComCm',
            'controllerActions' => [
                \GdprExtensionsCom\GdprExtensionsComCm\Controller\GdprManagerController::class => [
                    'list',
                    'index',
                    'show',
                    'new',
                    'create',
                    'edit',
                    'resetToDefault',
                    'update',
                    'delete',
                    'uploadImage',
                    'apiValidationStatus'
                ],
            ],
        ]
    ];

}


