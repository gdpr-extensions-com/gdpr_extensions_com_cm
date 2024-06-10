<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComCm',
        'CookieConsentModal',
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\CookieConsentModalController::class => 'index ,  ajax'
        ],
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\CookieConsentModalController::class => 'index ,  ajax'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComCm',
        'Report',
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\ReportController::class => 'index'
        ],
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\ReportController::class => 'index'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // Register Scheduler Task
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncCookiesAndExternalResCommand::class] = [
        'extension' => 'gdpr_extensions_com_cm',
        'title' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:fetch_cookies_schedular_title',
        'description' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:fetch_cookies_schedular_desc',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncCookiesAndExternalResCommand::class,
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncApiconnectsTask::class] = [
        'extension' => 'gdpr_extensions_com_cm',
        'title' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:sync.apiconnects.title',
        'description' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:sync.apiconnects.description',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncApiconnectsTask::class,
    ];
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComCm\Commands\UpdateOwnStatusTask::class] = [
        'extension' => 'gdpr_extensions_com_cm',
        'title' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:sync.update_own_status.title',
        'description' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:sync.update_own_status.description',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComCm\Commands\UpdateOwnStatusTask::class,
    ];

    // Register Hook here
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \GdprExtensionsCom\GdprExtensionsComCm\Hooks\DataHandlerHook::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = \GdprExtensionsCom\GdprExtensionsComCm\Hooks\DataHandlerHook::class;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.common {
                elements {
                    report {
                        iconIdentifier = gdpr_extensions_com_cm-plugin-gdprextensionscomcm
                        title = LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_cm_report.name
                        description = LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_cm_report.description
                        tt_content_defValues {
                            CType = gdprextensionscomcm_report
                        }
                    }
                }
                show = *
            }
        }'
    );


})();
