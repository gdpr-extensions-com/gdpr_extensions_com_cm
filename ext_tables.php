<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gdprextensionscomcm_domain_model_cookie', 'EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomcm_domain_model_cookie.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gdprextensionscomcm_domain_model_cookie');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_gdprextensionscomcm_domain_model_externalresource', 'EXT:gdpr_extensions_com_cm/Resources/Private/Language/locallang_csh_tx_gdprextensionscomcm_domain_model_externalresource.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_gdprextensionscomcm_domain_model_externalresource');
})();
