<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

defined('TYPO3') || die();

(static function() {
    if ((int)VersionNumberUtility::getCurrentTypo3Version() < 12) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'GdprExtensionsComCm',
            'web',
            'gdprManager',
            'bottom',
            [
                \GdprExtensionsCom\GdprExtensionsComCm\Controller\GdprManagerController::class => 'list,edit,show,update,apiValidationStatus',
            ],
            [
                'access' => 'user,group',
                'iconIdentifier' => 'gdpr_extensions_com_tab', // Replace with your icon identifier
                'labels' => 'LLL:EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_gdprmanager.xlf',
                'navigationComponent' => '',
                'navigationComponentId' => null,
                'inheritNavigationComponentFromMainModule'=> false,
            ]
        );
    }

    ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_gdprextensionscomcm_domain_model_cookie',
        'EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomcm_domain_model_cookie.xlf'
    );
    ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_gdprextensionscomcm_domain_model_cookie'
    );

    ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_gdprextensionscomcm_domain_model_externalresource',
        'EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomcm_domain_model_externalresource.xlf'
    );
    ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_gdprextensionscomcm_domain_model_externalresource'
    );
})();
