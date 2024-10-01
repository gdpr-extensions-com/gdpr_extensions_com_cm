<?php

namespace GdprExtensionsCom\GdprExtensionsComCm\Utility;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;


class SyncPrivacyStatement
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
                $reviewsToolUrl = (is_null($BaseURL) ? 'https://dashboard.gdpr-extensions.com/' : $BaseURL) . 'review/api/' . $apiKey . '/website-privacy-statement.json';
                $params = [
                    'verify' => false,
                ];
                try {
                    $response = $requestFactory->request($reviewsToolUrl, 'GET', $params);
                    $jsonResponse = json_decode($response
                        ->getBody()
                        ->getContents());

                    if (isset($jsonResponse->error) || isset($jsonResponse->status) && $jsonResponse->status == false ) {
                        // Delete existing data
                        $this->deleteData($connectionPool, $apiKey, $location['pages']);
                    } else {
                        // Process the data
                        $this->processData($jsonResponse, $connectionPool, $apiKey, $location['pages']);
                    }
                }catch (\Exception $exception){
                    $this->deleteData($connectionPool, $apiKey, $location['pages']);

                }

            }
        }
    }
        catch (\Exception $e) {
            dd($e);
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


    /**
     * Process and save data to the database.
     *
     *
     */
    private function processData($data, $connectionPool,$apiKey, $rootPid)
    {
        $privacyGeneratorConnection = $connectionPool->getConnectionForTable('tx_gdprextensionscomcm_domain_model_privacygenerator');
        $this->saveRecord($privacyGeneratorConnection, $data,$apiKey, $rootPid);

    }

    /**
     * Save a single record to the database.
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param array $record
     */
    private function saveRecord($connection, $record,$apiKey, $rootPid)
    {
        // Map API data to database fields
        $fields = [
            'website_id' => $record->website_id,
            'website_url' => $record->website_url,
            'template_name' => $record->template_name,
            'header_content' => $record->header_content,
            'quill_content_data' => $record->quill_content_data,
            'content_block_data' => json_encode($record->content_block_data),
            'dashboard_api_key' => $apiKey,
            'root_pid' => $rootPid,

        ];

        // Check if record already exists based on unique identifiers, e.g., dashboard_api_key and root_pid
        $existingRecord = $connection->select(
            ['uid'],
            'tx_gdprextensionscomcm_domain_model_privacygenerator',
            [
                // 'dashboard_api_key' => $fields['dashboard_api_key'],
                'root_pid' => $fields['root_pid']
            ]
        )->fetch();

        if ($existingRecord) {
            // Update existing record
            $connection->update(
                'tx_gdprextensionscomcm_domain_model_privacygenerator',
                $fields,
                ['uid' => (int)$existingRecord['uid']]
            );
        } else {
            // Insert new record
            $connection->insert(
                'tx_gdprextensionscomcm_domain_model_privacygenerator',
                $fields
            );
        }
    }

    /**
     * Delete existing data from the database when an error occurs.
     *
     * @param ConnectionPool $connectionPool
     * @param string $apiKey
     * @param int $rootPid
     */
    private function deleteData($connectionPool, $apiKey, $rootPid)
    {
        $privacyGeneratorConnection = $connectionPool->getConnectionForTable('tx_gdprextensionscomcm_domain_model_privacygenerator');

        $privacyGeneratorConnection->delete(
            'tx_gdprextensionscomcm_domain_model_privacygenerator',
            [
                'dashboard_api_key' => $apiKey,
                'root_pid' => $rootPid,
            ]
        );
    }
}
