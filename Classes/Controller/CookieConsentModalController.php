<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Controller;
use GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\CookieRepository;


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
        $cookies = $this->cookieRepository->findByRootPid($rootPid)->toArray();
        $groupedCookies = [];
        foreach ($cookies as $cookie) {
            $category = $cookie->getCategory();
            $cookie->__set("subPagesArray", explode(',', $cookie->getPagesList()));
            if (!isset($groupedCookies[$category])) {
                $groupedCookies[$category] = [];
            }
            $groupedCookies[$category][] = $cookie;
        }
        $this->view->assign('data', $this->contentObject->data);
        $this->view->assign('groupedCookies', $groupedCookies);

        return $this->htmlResponse();
    }
}
