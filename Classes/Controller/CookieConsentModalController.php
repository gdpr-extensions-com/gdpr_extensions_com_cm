<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Controller;
use GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\CookieRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;


/**
 * This file is part of the "gdpr_extensions_com_cm" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * CookieConsentModalController
 */
class CookieConsentModalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $rootPid = $GLOBALS['TSFE']->rootLine[0]['uid'];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_cookie_consent');
        $cookiesWidget = $queryBuilder
            ->select('*')
            ->from('gdpr_cookie_consent')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAll();
        $cookies = $this->cookieRepository->findByRootPid($rootPid)->toArray();
        $groupedCookies = [];
        $twoClickCookies = [];
        $twoClickCookiesTitles = [];
        $twoClickCookiesDescriptions = [];
        foreach ($cookies as $cookie) {

            $category = $cookie->getCategory();
            $cookie->__set("subPagesArray", explode(',', $cookie->getPagesList()));
            if($category == 'GDPR-extensions.com'){
                $pos = strrpos($cookie->getName(), '_');
                if ($pos !== false) {
                    $cookietitle = $cookie->getName();
                    $new_str = substr($cookie->getName(), 0, $pos);
                    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_gdprextensionscomcm_domain_model_cookie');
                    $cookiesTitle = $queryBuilder
                        ->select('*')
                        ->from('tx_gdprextensionscomcm_domain_model_cookie')
                        ->where(
                            $queryBuilder->expr()->eq('name', $queryBuilder->createNamedParameter($cookietitle, \PDO::PARAM_STR))
                        )
                        ->setMaxResults(1)
                        ->executeQuery()
                        ->fetchAssociative();
                    $twoClickCookiesTitles[$cookie->getName()] = $cookiesTitle['cookie_title'];
                    $twoClickCookiesDescription[$cookie->getName()] = $cookiesTitle['description'];
                    $twoClickCookies[$cookie->getName()] = $new_str;
                    $this->view->assign('twoClickCookiesTitles', $twoClickCookiesTitles);
                    $this->view->assign('twoClickCookiesDescription', $twoClickCookiesDescription);
                }
            }
            else{
                if (!isset($groupedCookies[$category])) {
                    $groupedCookies[$category] = [];
                }
                $groupedCookies[$category][] = $cookie;
            }
        }
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('gdpr_cookie_categories');
        $query = $queryBuilder
            ->select('*')
            ->from('gdpr_cookie_categories')
            ->where(
                $queryBuilder->expr()->eq('location_page_id', $queryBuilder->createNamedParameter($rootPid, \PDO::PARAM_INT))
            )
            ->execute();

       $categories = [];
        while ($row = $query->fetchAssociative()) {
            $categoryTitle = ($row['category_title'] === 'gdpr-extensions.com') ? 'gdprExtensionsCom' : $row['category_title'];
            $categories[$categoryTitle] = $row;
        }
        // Assign fetched categories to the view
        
        $this->view->assign('categories', $categories);
        $this->view->assign('data', $this->contentObject->data);
        $this->view->assign('groupedCookies', $groupedCookies);
        $this->view->assign('twoClickCookies', $twoClickCookies);
        $this->view->assign('cookiesWidget', $cookiesWidget);
        $this->view->assign('rootPid', $GLOBALS['TSFE']->site->getRootPageId());
        return $this->htmlResponse();
    }
    public function ajaxAction() {
        // dd('sdfsdf');
        $json_str = file_get_contents('php://input');
        // Get as an object
        $json_obj = json_decode($json_str);
        $rootId = (int)$json_obj->rootPid;
    
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('gdpr_tracking');
        $result = $queryBuilder
            ->select('*')
            ->from('gdpr_tracking')
            ->where(
                $queryBuilder->expr()->eq(
                    'root_pid',
                    $queryBuilder->createNamedParameter($rootId)
                )
            )
            ->executeQuery()
            ->fetchAssociative();
    
        if($result){
            die(json_encode($result));
        }else{
            die(json_encode(['status' => 0]));
        }
    
    }
}
