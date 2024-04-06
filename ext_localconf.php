<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'GdprExtensionsComCm',
        'CookieConsentModal',
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\CookieConsentModalController::class => 'index'
        ],
        [
            \GdprExtensionsCom\GdprExtensionsComCm\Controller\CookieConsentModalController::class => 'index'
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
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncCookiesAndExternalResCommand::class] = [
        'extension' => 'gdpr_extensions_com_cm',
        'title' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:fetch_cookies_schedular_title',
        'description' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang.xlf:fetch_cookies_schedular_desc',
        'additionalFields' => \GdprExtensionsCom\GdprExtensionsComCm\Commands\SyncCookiesAndExternalResCommand::class,
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.common {
                elements {
                    cookieconsentmodal {
                        iconIdentifier = gdpr_extensions_com_cm-plugin-gdprextensionscomcm
                        title = LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_cm_cookieconsentmodal.name
                        description = LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_db.xlf:tx_gdpr_extensions_com_cm_cookieconsentmodal.description
                        tt_content_defValues {
                            CType = gdprextensionscomcm_cookieconsentmodal
                        }
                    }
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
