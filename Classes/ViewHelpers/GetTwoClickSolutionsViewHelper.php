<?php

namespace GdprExtensionsCom\GdprExtensionsComCm\ViewHelpers;

// use GdprExtensionsCom\GdprTwoXGreviewThreecgd\Domain\Repository\GdprManagerRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Database\Connection;

class GetTwoClickSolutionsViewHelper extends AbstractViewHelper
{

    /**
     * @var GdprManagerRepository
     */
    // protected $gdprManagerRepository = null;


    /**
     * @return void
     */
    // public function injectGdprManagerRepository(GdprManagerRepository $gdprManagerRepository)
    // {
    //     $this->gdprManagerRepository = $gdprManagerRepository;
    // }
    public function initializeArguments()
    {


    }
    public function render()
    {
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $extensions = ExtensionManagementUtility::getLoadedExtensionListArray();
        $extensionNames = [];
        
        foreach ($extensions as $extensionKey) {
            if ($packageManager->isPackageAvailable($extensionKey)) {
                $extensionName = $packageManager->getPackage($extensionKey)->getPackageMetaData()->getTitle();
                // Directly assign the name to the key in the associative array.
                $extensionNames[$extensionKey] = $extensionName;
            }
        }
        
        // Filter based on keys, looking for 'gdpr_two_x' in the extensionKey.
        $twoClickSolutions = array_filter($extensionNames, function ($key) {
            return str_contains($key, 'gdpr_two_x') || str_contains($key, 'gdpr_extensions_com');
        }, ARRAY_FILTER_USE_KEY); // Use ARRAY_FILTER_USE_KEY to filter by key.

        $schemaManager = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_gdprextensionscomcm_domain_model_externalresource')->createSchemaManager();
    
        $table = 'tx_gdprextensionscomyoutube_domain_model_gdprmanager';
        if ($schemaManager->tablesExist($table)) {
            $gdprDellQb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');

            $gdprDellQb
                ->delete('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
                ->where(
                    $gdprDellQb->expr()->notIn(
                        'extension_title',
                        $gdprDellQb->createNamedParameter($twoClickSolutions,Connection::PARAM_STR_ARRAY)
                    )
                )
                ->executeStatement();
             
                $twoClickSolutionsWithoutReviews = array_filter($twoClickSolutions, function ($ext) {
                    // Use 'stripos' for case-insensitive search; returns false if 'review' is not found.
                    return stripos($ext, 'review') === false;
                });
                
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
    
                $gdprManagers = $queryBuilder
                    ->select('*')
                    ->from('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
                    ->execute()
                    ->fetchAll();
                
    
            $installedTwoClickSol = [];
            foreach ($gdprManagers as $twoClickSol){
                array_push($installedTwoClickSol,$twoClickSol['extension_title']);
            }
    
            $missingExtensions = array_diff($twoClickSolutionsWithoutReviews, $installedTwoClickSol);
        
    
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
          
    
            foreach ($missingExtensions as $key => $value) {
                $queryBuilder
                    ->insert('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
                    ->values([
                        'extension_title' => $value,
                        'extension_key' => $key,
                        'heading' => '', // Default empty string
                        'content' => '', // Default empty string
                        'button_text' => '', // Default empty string
                        'enable_background_image' => 0, // Default 0
                        'background_image' => '', // Default empty string
                        'background_image_color' => '', // Default empty string
                        'button_color' => '', // Default empty string
                        'text_color' => '', // Default empty string
                        'button_shape' => '' // Default empty string
                    ])
                    ->execute();
            }
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomyoutube_domain_model_gdprmanager');
    
            $gdprManagers = $queryBuilder
                ->select('*')
                ->from('tx_gdprextensionscomyoutube_domain_model_gdprmanager')
                ->where(
                    $queryBuilder->expr()->notLike('extension_title',
                        $queryBuilder->createNamedParameter('%' . $queryBuilder->escapeLikeWildcards('review') . '%')
                    )
                )
                ->execute()
                ->fetchAll();
             
            $normalizedGdprManagers = [];
            $pluginNames = [
                'GDPR-Extensions-com - Google Map 2xClick Solution' => 'maps.google.com',
                'GDPR-Extensions-com - Bing Map 2xClick Solution' => 'bing.com/maps',
                'GDPR-Extensions.com - Youtube 2xClick Solution' => 'youtube.com',
                'GDPR-Extensions.com - Youtube Shorts 2xClick Solution' => 'youtube.com',
                'GDPR-Extensions-com - TikTok 2xClick Solution' => 'tiktok.com',
                'GDPR-Extensions.com - Vimeo 2xClick Solution' => 'vimeo.com',
                'GDPR-Extensions.com - Matomo 2xClick Solution' => 'matomo.org',
                'GDPR-Extensions.com - Google-Tag-Manager 2xClick Solution' => 'tagmanager.google.com',
                'GDPR-Extensions-com - Pinterest Board 2xClick Solution' => 'pinterest.com',
                'GDPR-Extensions-com - Pinterest Pin 2xClick Solution' => 'pinterest.com',
                'GDPR-Extensions-com - Pinterest Profile 2xClick Solution' => 'pinterest.com',
                'GDPR-Extensions-com - Social Feed curator 2xClick Solution' => 'curator.io'
            ];
            foreach ($gdprManagers as $gdprManager) {
                if(array_key_exists($gdprManager['extension_key'], $extensionNames)) {
                    if(stripos($gdprManager['extension_title'], '2xClick')){
                    $extensionTitle = $gdprManager['extension_title'];
                    $gdprManager['cookie_title'] = $pluginNames[$extensionTitle];
                    $shortTitle = substr($extensionTitle, strpos($extensionTitle, ' - ') + 3);
                    $gdprManager['short_title'] = $shortTitle;
                    $normalizedGdprManagers[$gdprManager['extension_key']] = $gdprManager;
                    }
                }
            }
            $jsonString = json_encode($normalizedGdprManagers);
             
            return $jsonString;
        }

        return '{}';
    }

}
