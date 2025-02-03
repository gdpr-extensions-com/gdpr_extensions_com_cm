<?php

declare (strict_types = 1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Controller;

use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\CookieRepository;
use GdprExtensionsCom\GdprExtensionsComCm\ViewHelpers\GetTwoClickSolutionsViewHelper;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;

/**
 * This file is part of the "gdpr-extensions-com-google_reviewlist" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * GdprManagerController
 */
class GdprManagerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController

{
    /**
     * ContentObject
     *
     * @var ContentObject
     */
    protected $contentObject = null;

    private $cookieRepository = null;

    /**
     * Action initialize
     */
    protected function initializeAction()
    {
        $this->contentObject = $this->configurationManager->getContentObject();

        // intialize the content object
    }
    public function injectCookieRepository(CookieRepository $cookieRepository)
    {
        $this->cookieRepository = $cookieRepository;
    }
    /**
     * gdprManagerRepository
     *
     * @var \GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\GdprManagerRepository
     */

    /**
     * @var ModuleTemplateFactory
     */
    protected $moduleTemplateFactory;
    protected $gdprManagerRepository = null;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $sites = $siteFinder->getAllSites();
        $configurations = [];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('multilocations');

        $query = $queryBuilder
            ->select('*')
            ->from('multilocations')
            ->execute();

         $apiStatus = [];
        while ($row = $query->fetch()) {
            $apiStatus[$row['location_page_id']] = $row;
        }

        foreach ($sites as $siteKey => $site) {
            $configurations[$siteKey] = $site->getConfiguration();
        }


        // $this->view->assign('gdprManager', $gdprManager);
        $this->view->assign('sites', $configurations);
        $this->view->assign('apiStatus', $apiStatus);
        return $this->htmlResponse();
    }

    /**
     * action resetToDefault
     *
     * @param int $id
     * @param string $url
     * @param string $categoryTitle
     * @param string $editStatus
     */
    public function resetToDefaultAction(int $id, string $url, string $categoryTitle, string $editStatus): \Psr\Http\Message\ResponseInterface
    {
        
        if($categoryTitle=='More detail'){
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_consent');
            $queryBuilder
                ->update('gdpr_cookie_consent')
                ->Where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->set('detail_text', $categoryTitle)
                ->execute();
        return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url, 'editStatus' => $editStatus, 'tabvalue' => 'Consent Manager']);

        }
        if($categoryTitle=='Always active'){
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_consent');
            $queryBuilder
                ->update('gdpr_cookie_consent')
                ->Where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->set('tag_text', $categoryTitle)
                ->execute();
        return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url, 'editStatus' => $editStatus, 'tabvalue' => 'Consent Manager']);

        }
        if($categoryTitle!='Always active' && $categoryTitle!='More detail'){
           $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_categories');
            $queryBuilder
                ->update('gdpr_cookie_categories')
                ->where(
                $queryBuilder->expr()->eq('category_title', $queryBuilder->createNamedParameter($categoryTitle))
                )
                ->andWhere(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->set('category_name', $categoryTitle)
                ->execute();
        return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url, 'editStatus' => $editStatus, 'tabvalue' => 'Consent Manager']);
        }
        

    }
    /**
     * action edit
     *
     * @param int $id
     * @param string $url
     */
    public function editAction(int $id, string $url, string $apiKeys= '', string $editStatus = '', string $tabvalue=''): \Psr\Http\Message\ResponseInterface
    {
        if($editStatus=='Invalid key')

        {
        $this->view->assign('id', $id);
        $this->view->assign('apiKey', $apiKeys);
        $this->view->assign('url', $url);
        $this->view->assign('editStatus', 'Invalid key');
        return $this->htmlResponse();
        }
         $getTwoClickSolutionsViewHelper = new GetTwoClickSolutionsViewHelper();
        $jsonString = $getTwoClickSolutionsViewHelper->render();
        $dataArray = json_decode($jsonString, true);
        // Extract the normalizedGdprManagers
        // $normalizedGdprManagers = $dataArray['normalizedGdprManagers'];
        $extensionTitles = [];
        foreach ($dataArray as $extensionData) {
            $extensionTitles[] = $extensionData['extension_title'];
        }
        $contains2xClick = false;
        foreach ($extensionTitles as $title) {
            if (stripos($title, '2xClick') !== false) {
                $contains2xClick = true;
                break; // No need to continue looping if found
            }
        }
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $reportQueryBuilder = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
        $reportQuery = $reportQueryBuilder
            ->select('report')
            ->from('tx_gdprextensionscomcm_domain_model_report')
            ->where(
                $reportQueryBuilder->expr()->eq('root_pid', $reportQueryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute();

        $reportRecord = $reportQuery->fetch();
        $editStatus = $this->request->hasArgument('editStatus') ? $this->request->getArgument('editStatus') : 0;
        $queryBuilder = $connectionPool->getQueryBuilderForTable('multilocations');
        $query = $queryBuilder
            ->select('*')
            ->from('multilocations')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute();

        $record = $query->fetch();

        if ($record !== false) {
            $apiKey = $record['dashboard_api_key'];
        } else {
            $apiKey = '';
        }

        $validateAuthKeyUrl = $this->uriBuilder->reset()->uriFor('apiValidationStatus');

          // Fetch data from gdpr_cookie_consent
        $queryBuilder = $connectionPool->getQueryBuilderForTable('gdpr_cookie_consent');
        $gdprQuery = $queryBuilder
            ->select('*')
            ->from('gdpr_cookie_consent')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute();

        $gdprRecord = $gdprQuery->fetch();
        $cookieClientQB = $connectionPool->getQueryBuilderForTable(
            'tx_gdprextensionscomcm_domain_model_cookie'
        );

        $cookiesResult = $cookieClientQB->select('*')
            ->from('tx_gdprextensionscomcm_domain_model_cookie')
            ->where(
                $cookieClientQB->expr()
                    ->eq('root_pid', $cookieClientQB->createNamedParameter($id)),
            )
            ->executeQuery()->fetchAllAssociative();
        $groupedCookies = [];
        foreach ($cookiesResult as $cookie) {
            $category = $cookie['category'];
            // Convert to lowercase to ensure case-insensitive comparison
            $category = strtolower($category);
            // Check if the category is not already in the groupedCookies array
            if (!in_array($category, $groupedCookies)) {
                // Add the category to the groupedCookies array
                $groupedCookies[] = $category;
            }
        }
        $queryBuilder = $connectionPool->getQueryBuilderForTable('gdpr_cookie_categories');
        $query = $queryBuilder
            ->select('*')
            ->from('gdpr_cookie_categories')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->execute();

        $categories = $query->fetchAllAssociative();

        // Assign fetched categories to the view
        $this->view->assign('categories', $categories);
        // Remove duplicate categories
        $groupedCookies = array_unique($groupedCookies);
        $this->view->assign('cookies', $groupedCookies);
        $this->view->assign('gdprRecord', $gdprRecord);
        $this->view->assign('id', $id);
        $this->view->assign('url', $url);
        $this->view->assign('apiKey', $apiKey);
        $this->view->assign('siteInfo', $record);
        if($editStatus=='save')
        {
        $this->view->assign('editStatus','save');

        }
        else{
        $this->view->assign('editStatus', $record ? $editStatus : 'new');

        }
        $this->view->assign('reportRecord', $reportRecord);
        $this->view->assign('tabvalue', $tabvalue);
        $this->view->assign('contains2xClick',$contains2xClick);
        $this->view->assign('validateAuthKeyUrl',$validateAuthKeyUrl);
        return $this->htmlResponse();
    }
    /**
     * action show
     */
    public function showAction(): \Psr\Http\Message\ResponseInterface
    {
        // $editStatus = $this->request->hasArgument('editStatus') ? $this->request->getArgument('editStatus') : 0;

        $id = $_POST['id'] ?? '';
        $editStatus = $_POST['editStatus'] ?? '';
        $url = $_POST['url'] ?? '';
        $locationPageId = $_POST['id'] ?? '';
        $imageData = $_POST['imageUpload'] ?? '';
        $imageUrl = '';
         if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        // Retrieve the temporary file path
        $uploadedFileTmpName = $_FILES['imageUpload']['tmp_name'];
        // Read the binary data of the uploaded file
        $imageData = file_get_contents($uploadedFileTmpName);
        // Save the image to the specified directory
        $localDir = '../fileadmin/user_upload/GDPR-Extensions.com/Consent_Manager/';
         if (!is_dir($localDir)) {
            mkdir($localDir, 0755, true);
        }
        $fileName = $_FILES['imageUpload']['name'];
        $localPath = $localDir . $fileName;

        // Save the image to the local directory
        file_put_contents($localPath, $imageData);
        // Set the image URL for further use
        $imageUrl = '/fileadmin/user_upload/GDPR-Extensions.com/Consent_Manager/' . $fileName;

         }
        $headerTitle = $_POST['title'] ?? '';
        $declinebtnText = $_POST['declinebtnText'] ?? '';
        $privacyPage = $_POST['privacyPage'] ?? '';
        $privacyLink = $_POST['privacyLink'] ?? '';
        $hyperLinkedText = $_POST['hyperLinkedText'] ?? '';
        $btnText = $_POST['btnText'] ?? '';
        $btntagtextColor = $_POST['btn-tag-text-color'] ?? '';
        $tagText = $_POST['tagText'] ?? '';
        $detailtagText = $_POST['detailtagText'] ?? '';
        $btnbgColor = $_POST['btn-bg-color'] ?? '';
        $btntextColor = $_POST['btn-text-color'] ?? '';
        $headerDescription = $_POST['desc'] ?? '';
        $icon_placement = $_POST['icon_placement'] ?? '';
        $background_color = $_POST['bg-color'] ?? '';
        $text_color = $_POST['text-color'] ?? '';
        $header_text_color = $_POST['header-text-color'] ?? '';
        
        $two_click_desc = $_POST['two_click_desc'] ?? '';
        $uploaded_file_name = $fileName ?? '';
         $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_consent');

        // Check if the ID already exists
        $existingRow = $queryBuilder
            ->select('*')
            ->from('gdpr_cookie_consent')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter((string) $locationPageId, \PDO::PARAM_STR))
            )
            ->execute()
            ->fetch();
        if($_FILES['imageUpload']['name']=='' && !empty($existingRow['icon_url'])) {
             $imageUrl = $existingRow['icon_url'];
             $uploaded_file_name = $existingRow['uploaded_file_name'];
        }
         if ($_POST['cancelUpload'] == 1) {
            // User canceled the upload, set the image URL to a default or empty value
            $imageUrl = ''; 
            $uploaded_file_name = '';

        }
        if ($existingRow) {
            // If the ID exists, update the row with the new values
            $queryBuilder
                ->update('gdpr_cookie_consent')
                ->where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter((string) $locationPageId, \PDO::PARAM_STR))
                )

                ->set('icon_url', $imageUrl)
                ->set('header_title', $headerTitle)
                ->set('privacy_page', $privacyPage)
                ->set('privacy_link', $privacyLink)
                ->set('hyper_linked_text', $hyperLinkedText)
                ->set('btn_text', $btnText)
                ->set('decline_btn_text', $declinebtnText)
                ->set('tag_text', $tagText)
                ->set('detail_text', $detailtagText)
                ->set('btn_tag_text_color', $btntagtextColor)
                ->set('btn_background_color', $btnbgColor)
                ->set('btn_text_color', $btntextColor)
                ->set('icon_placement', $icon_placement)
                ->set('header_description', $headerDescription)
                ->set('background_color', $background_color)
                ->set('text_color', $text_color)
                ->set('header_text_color', $header_text_color)
                ->set('two_click_desc', $two_click_desc)
                ->set('uploaded_file_name', $uploaded_file_name)
                ->execute();
        } else {
            // If the ID doesn't exist, insert a new row with the provided values
            $queryBuilder
                ->insert('gdpr_cookie_consent')
                ->values([
                    'location_page_id' => $locationPageId,
                    'icon_url' => $imageUrl,
                    'header_title' => $headerTitle,
                    'privacy_page' => $privacyPage,
                    'privacy_link' => $privacyLink,
                    'hyper_linked_text' => $hyperLinkedText,
                    'btn_text' => $btnText,
                    'decline_btn_text' => $declinebtnText,
                    'tag_text' => $tagText,
                    'detail_text' => $detailtagText,
                    'btn_tag_text_color' => $btntagtextColor,
                    'btn_background_color' => $btnbgColor,
                    'btn_text_color' => $btntextColor,
                    'icon_placement' => $icon_placement,
                    'header_description' => $headerDescription,
                    'background_color' => $background_color,
                    'text_color' => $text_color,
                    'header_text_color' => $header_text_color,
                    'two_click_desc' => $two_click_desc,
                    'uploaded_file_name' => $uploaded_file_name
                ])
                ->execute();
        }
          // Retrieve data from the POST request
            $categoryDesc = $_POST['categorydesc'] ?? [];
            $categoryNames = $_POST['categoryname'] ?? [];

            // Prepare database query
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_categories');
            // Retrieve existing category titles for the given location_page_id
            $existingCategoryTitles = [];
            $existingRows = $queryBuilder
                ->select('category_title')
                ->from('gdpr_cookie_categories')
                ->where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter((string) $locationPageId, \PDO::PARAM_STR))
                )
                ->execute()
                ->fetchAll();

            foreach ($existingRows as $row) {
                $existingCategoryTitles[] = $row['category_title'];
            }

            // Iterate through each category description from the POST request
            foreach ($_POST['categorydesc'] ?? [] as $categoryTitle => $categoryDescription) {
                // Ensure both category title and description are provided and not empty
                if (!empty($categoryTitle) ) {
                    // Update existing row if category title already exists
                    if (in_array($categoryTitle, $existingCategoryTitles)) {
                        $queryBuilder
                            ->update('gdpr_cookie_categories')
                            ->where(
                          $queryBuilder->expr()->eq('category_title', $queryBuilder->createNamedParameter($categoryTitle))
                            )
                            ->andWhere(
                                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($locationPageId, \PDO::PARAM_INT))
                            )
                            ->set('category_description', $categoryDescription)
                            ->set('category_name', $categoryNames[$categoryTitle])
                            ->execute();
                    } else {
                        // Insert new row if category title does not exist
                        $queryBuilder
                            ->insert('gdpr_cookie_categories')
                            ->values([
                                'category_title' => $categoryTitle,
                                'location_page_id' => $locationPageId,
                                'category_description' => $categoryDescription,
                                'category_name' => $categoryNames[$categoryTitle],
                            ])
                            ->execute();
                    }
                }
            }

        $this->view->assign('id', $locationPageId);
        $this->view->assign('imageUpload', $imageUrl);
        $this->view->assign('title', $headerTitle);
        $this->view->assign('desc', $headerDescription);
        $this->view->assign('icon_placement', $icon_placement);
        return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url, 'editStatus' => $editStatus]);

        // return $this->htmlResponse();
    }
    /**
     * action update
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function updateAction(): \Psr\Http\Message\ResponseInterface
    {
        $apiKey = $_POST['apiKey'] ?? '';
        $id = $_POST['id'] ?? '';
        $url = $_POST['url'] ?? '';


        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('multilocations');
        // Check if $id already exists in multilocations table
        $existingRecord = $queryBuilder
            ->select('dashboard_api_key')
            ->from('multilocations')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute()
            ->fetch();
        $olddashboard_api_key = null;    
         if ($existingRecord !== false) {
            $olddashboard_api_key = $existingRecord['dashboard_api_key'];
        }
         // Call the apiValidation function
        $isValid =  $this->apiValidation($apiKey);
        if($isValid=='Invalid key' && $olddashboard_api_key != null)
        {
            if ($olddashboard_api_key != $apiKey) {
                $this->sendStatusUpdate($olddashboard_api_key,$id);
                $cookiesQB = $connectionPool->getQueryBuilderForTable('gdpr_cookie_consent');
                    $cookiesQB->delete('gdpr_cookie_consent')
                        ->where(
                            $cookiesQB->expr()->eq('location_page_id', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('gdpr_cookie_categories');
                    $cookiesQB->delete('gdpr_cookie_categories')
                        ->where(
                            $cookiesQB->expr()->eq('location_page_id', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_externalresource');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_externalresource')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_cookie')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();

            }
            $cookiesQB = $connectionPool->getQueryBuilderForTable('multilocations');
                    $cookiesQB->delete('multilocations')
                        ->where(
                            $cookiesQB->expr()->eq('location_page_id', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
            return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url,'apiKeys' => $_POST['apiKey'] ,'editStatus' => 'Invalid key']);
        }
        if ($existingRecord) {

            $existingRecord = $queryBuilder
            ->select('dashboard_api_key')
            ->from('multilocations')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
            )
            ->setMaxResults(1)
            ->execute()
            ->fetch();
            if ($olddashboard_api_key != $apiKey) {
                $this->sendStatusUpdate($olddashboard_api_key,$id);

            }
            // If $id exists, update the dashboard_api_key
            $queryBuilder
                ->update('multilocations')
                ->where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->set('dashboard_api_key', $apiKey)
                ->set('api_create_time', time())
                ->execute();
            $queryBuilder
                ->delete('gdpr_cookie_categories')
                ->where(
                    $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
                )
                ->execute();
                //
            if($olddashboard_api_key != $apiKey)
            {
                $cookiesQB = $connectionPool->getQueryBuilderForTable('gdpr_cookie_consent');
                    $cookiesQB->delete('gdpr_cookie_consent')
                        ->where(
                            $cookiesQB->expr()->eq('location_page_id', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_report');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_report')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_externalresource');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_externalresource')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
                $cookiesQB = $connectionPool->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                    $cookiesQB->delete('tx_gdprextensionscomcm_domain_model_cookie')
                        ->where(
                            $cookiesQB->expr()->eq('root_pid', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
            }

        } else {
            $cookiesQB = $connectionPool->getQueryBuilderForTable('gdpr_cookie_categories');
                    $cookiesQB->delete('gdpr_cookie_categories')
                        ->where(
                            $cookiesQB->expr()->eq('location_page_id', $cookiesQB->createNamedParameter($id))
                        )
                        ->execute();
            // If $id does not exist, insert a new record
            $queryBuilder
                ->insert('multilocations')
                ->values([
                    'dashboard_api_key' => $apiKey,
                    'location_page_id' => $id,
                    'api_create_time' => time(),
                    'pages' => $id,
                ])
                ->execute();
        }
        // Redirect to the edit action with $id and $url
        return $this->redirect('edit', null, null, ['id' => $id, 'url' => $url]);
    }

    /**
     * API validation function
     * @param string $apiKey
     * @return string
     */
    private function apiValidation(string $apiKey): string
    {
        $dashBoardEndPoint = 'https://dashboard.gdpr-extensions.com/review/api/'.$apiKey.'/authenticate-key.json';

        $client = new Client();
        try {
            $response = $client->request('POST', $dashBoardEndPoint);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                // Parse JSON response
                $responseData = json_decode($response->getBody()->getContents(), true);
                if (isset($responseData['message'])) {
                    return $responseData['message'];
                }
            }
        } catch (ClientException $e) {
            // Handle client-side errors
            error_log('Client Error in API request: ' . $e->getMessage());
        } catch (ServerException $e) {
            // Handle server-side errors
            error_log('Server Error in API request: ' . $e->getMessage());
        }
        return ('Invalid key'); // Return null if message not found or on error
    }
    /**
     * API validation Status function
     *
     *
     */
    public function apiValidationStatusAction(): ResponseInterface
    {
        $authKeyInput = GeneralUtility::_GP('authKeyInput') ?: '';
        $dashBoardEndPoint = 'https://dashboard.gdpr-extensions.com/review/api/'.$authKeyInput.'/authenticate-key.json';

        $client = new Client();
        try {
            $response = $client->request('POST', $dashBoardEndPoint);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                // Parse JSON response
                $responseData = json_decode($response->getBody()->getContents(), true);
                return new JsonResponse($responseData);
            }
        } catch (ClientException $e) {
            // Handle client-side errors
            error_log('Client Error in API request: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Client Error in API request'], 400);
        } catch (ServerException $e) {
            // Handle server-side errors
            error_log('Server Error in API request: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Server Error in API request'], 500);
        }

        return new JsonResponse(['error' => 'Invalid key'], 400);
    }
    public function sendStatusUpdate($oldRecord,$rootPid){
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $client = GeneralUtility::makeInstance(Client::class);


        $multilocationQB = $connectionPool->getQueryBuilderForTable('multilocations');

        $sysTempQB = $connectionPool->getQueryBuilderForTable('sys_template');

        $BaseUris = [];


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

            $BaseURL = "https://dashboard.gdpr-extensions.com/";

            if ($apiKey) {

                $BaseUris[$location['pages']] = $BaseURL;
            }
        }

        $requests = function ($oldRecord) use ($BaseUris,$rootPid) {
            yield new Request(
                'POST',
                (is_null($BaseUris[$rootPid]) ? 'https://dashboard.gdpr-extensions.com/': $BaseUris[$rootPid]) .'review/api/' . $oldRecord . '/update-status.json',
                [
                    'Content-Type' => 'application/json'
                ],
                json_encode([
                    'elements' =>  [],
                    'extensions' => [],
                ]));


        };


        $pool = new Pool($client, $requests($oldRecord), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {},
            'rejected' => function ($reason, $index) {},
        ]);

        $promise = $pool->promise();
        $promise->wait();
    }

    /**
     * @param \GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\GdprManagerRepository $gdprManagerRepository
     */
    public function injectGdprManagerRepository(\GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\GdprManagerRepository $gdprManagerRepository)
    {
        $this->gdprManagerRepository = $gdprManagerRepository;
    }
}
