<?php

namespace GdprExtensionsCom\GdprExtensionsComCm\Utility;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Site\SiteFinder;


class SyncCookies
{

    public function run(ConnectionPool $connectionPool, RequestFactory $requestFactory)
    {
        try{
        $multilocationQB = $connectionPool->getQueryBuilderForTable('multilocations');

        $sysTempQB = $connectionPool->getQueryBuilderForTable('sys_template');

        $multilocationQBResult = $multilocationQB
            ->select('*')
            ->from('multilocations')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($multilocationQBResult as $location) {

            $apiKey = $location['dashboard_api_key'] ?? null;
         
            $SiteConfiguration = $sysTempQB->select('constants')
                ->from('sys_template')
                ->where(
                    $sysTempQB->expr()->eq('pid', $sysTempQB->createNamedParameter($location['pages'])),
                )
                ->setMaxResults(1)
                ->executeQuery()
                ->fetchAssociative();
            $sysTempQB->resetQueryParts();

            $constantsArray = $this->extractSecretKey($SiteConfiguration['constants']);
            $BaseURL = null;
            if ($apiKey && $apiKey != '') {
                $reviewsToolUrl = (is_null($BaseURL) ? 'https://dashboard.gdpr-extensions.com/' : $BaseURL) . 'review/api/' . $apiKey . '/website-cookies-extResources.json';
                $params = [
                    'verify' => false,
                ];
                $response = $requestFactory->request($reviewsToolUrl, 'GET', $params);
                $jsonResponse = json_decode($response
                    ->getBody()
                    ->getContents());

                $jsonResponseCookies = $jsonResponse[0];
                $jsonResponseResources = $jsonResponse[1];
                if ($jsonResponseResources && $jsonResponseResources != '') {
                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                        $cookiesQB->resetQueryParts();

                    $externalQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_externalresource');
                    $externalQB->delete('tx_gdprextensionscomcm_domain_model_externalresource')
                        ->where(
                            $externalQB->expr()->eq('root_pid', $externalQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                    $externalQB->resetQueryParts();

                    foreach ($jsonResponseResources as $object) {
                        foreach ($object as $key => $value) {
                            $externalQB
                                ->insert('tx_gdprextensionscomcm_domain_model_externalresource')
                                ->values([
                                    'url' => $key,
                                    'external_resource_list' => $value,
                                    'root_pid' => $location['pages'],
                                ])
                                ->execute();
                        }
                    }

                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_cookie')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                    $cookiesQB->resetQueryParts();
                    foreach ($jsonResponseCookies as $item) {
                        
                        $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                        $cookiesQB
                            ->insert('tx_gdprextensionscomcm_domain_model_cookie')
                            ->values([
                                'platform' => $item->platform,
                                'category' => $item->category,
                                'domain' => $item->domain,
                                'name' => $item->cookie_name,
                                'description' => $item->description ?? '',
                                'type' => $item->type,
                                'session' => $item->session,
                                'expires' => $item->expires,
                                'category' => $item->category,
                                'pages_list' => $item->pages_list,
                                'root_pid' => $location['pages']
                            ])
                            ->executeStatement();
                    }
                }
                if ($jsonResponseCookies && $jsonResponseCookies != '') {
                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                        $cookiesQB->resetQueryParts();

                    $externalQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_externalresource');
                    $externalQB->delete('tx_gdprextensionscomcm_domain_model_externalresource')
                        ->where(
                            $externalQB->expr()->eq('root_pid', $externalQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                    $externalQB->resetQueryParts();

                    foreach ($jsonResponseResources as $object) {
                        foreach ($object as $key => $value) {
                            $externalQB
                                ->insert('tx_gdprextensionscomcm_domain_model_externalresource')
                                ->values([
                                    'url' => $key,
                                    'external_resource_list' => $value,
                                    'root_pid' => $location['pages'],
                                ])
                                ->execute();
                        }
                    }

                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_cookie')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                    $cookiesQB->resetQueryParts();
                    foreach ($jsonResponseCookies as $item) {
                        
                        $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                        $cookiesQB
                            ->insert('tx_gdprextensionscomcm_domain_model_cookie')
                            ->values([
                                'platform' => $item->platform,
                                'category' => $item->category,
                                'domain' => $item->domain,
                                'name' => $item->cookie_name,
                                'description' => $item->description ?? '',
                                'type' => $item->type,
                                'session' => $item->session,
                                'expires' => $item->expires,
                                'category' => $item->category,
                                'pages_list' => $item->pages_list,
                                'root_pid' => $location['pages']
                            ])
                            ->executeStatement();
                    }
                }
                if (empty($jsonResponseCookies) && empty($jsonResponseResources)) {
                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                        $cookiesQB->resetQueryParts();
                        $cookiesQB
                            ->insert('tx_gdprextensionscomcm_domain_model_report')
                            ->values([
                                'report' => 'report not created',
                                'root_pid' => $location['pages']
                            ])
                            ->executeStatement();
                }
                if ($jsonResponse == 'report not created') {
                    $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($location['pages']))
                        )
                        ->execute();
                        $cookiesQB->resetQueryParts();
                        $cookiesQB
                            ->insert('tx_gdprextensionscomcm_domain_model_report')
                            ->values([
                                'report' => 'report not created',
                                'root_pid' => $location['pages']
                            ])
                            ->executeStatement();
                }

            }
        }
    }
        catch (\Exception $e) {
            
        }
    }

    protected function extractSecretKey($constantsString)
    {
        $configLines = explode("\n", $constantsString);
        $configArray = [];

        foreach ($configLines as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $configArray[trim($key)] = trim($value);
            }
        }
        return $configArray;
    }
}
