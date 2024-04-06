<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Utility;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

class FetchCategories
{
    public function getCoookieCategoriesForRoodPid(array &$params )
    {
        $pageId = $params['record']['pid'];
        $page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RootlineUtility::class, abs($pageId));
        $rootLines = $page->get();
        $rootPid = 0;
        if (count($rootLines) > 0) {
            $rootPid = $rootLines[0]['uid'];
        }
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $cookieClientQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomcm_domain_model_cookie'
        );

        $cookiesResult = $cookieClientQB->select('*')
            ->from('tx_gdprextensionscomcm_domain_model_cookie')
            ->where(
                $cookieClientQB->expr()
                    ->eq('root_pid', $cookieClientQB->createNamedParameter($rootPid)),
            )
            ->executeQuery()->fetchAllAssociative();
        $groupedCookies = [];
        foreach ($cookiesResult as $cookie) {
            $category = $cookie['category'];
            if (!in_array($category, $groupedCookies ) ){
                array_push($groupedCookies, strtolower($category) );
            }
        }
        if(in_array(strtolower($params['conditionParameters'][0]) ,$groupedCookies)){
            return true;
        }
        return false;
    }
}
