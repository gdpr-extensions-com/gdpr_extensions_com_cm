<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Controller;

use GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\CookieRepository;
use  GdprExtensionsCom\GdprExtensionsComCm\Domain\Repository\ExternalResourceRepository;
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
class ReportController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

   
 private $cookieRepository = null;
 private $externalResourceRepository = null;
    public function injectCookieRepository(CookieRepository $cookieRepository)
    {
        $this->cookieRepository = $cookieRepository;
    }

    public function injectExternalResourceRepository(ExternalResourceRepository $externalResourceRepository)
    {
        $this->externalResourceRepository = $externalResourceRepository;
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $rootPid = $GLOBALS['TSFE']->rootLine[0]['uid'];
        $cookies = $this->cookieRepository->findByRootPid($rootPid)->toArray();

        $externalResources = $this->externalResourceRepository->findByRootPid($rootPid)->toArray();
        $mainCount = 0;
        $groupedCookies = [];

        foreach ($cookies as $cookie) {
            $category = $cookie->getCategory();
            $cookie->__set("subPagesArray", explode(',', $cookie->getPagesList()));
            if (!isset($groupedCookies[$category])) {
                $groupedCookies[$category] = [];
            }
            $groupedCookies[$category][] = $cookie;
        }

        foreach ($externalResources as $resource) {
            $jsonArray = json_decode($resource->getExternalResourceList());
            $decodedArray = array_map('urldecode', $jsonArray);
            $resource->setExternalResourceList($resource->getExternalResourceList());
            $resource->__set("externalResourceArray", $decodedArray);
            if (empty($decodedArray[0])) {
                $resource->__set("externalResourceArrayCount", 0);
            } else {
                $mainCount += 1;
                $resource->__set("externalResourceArrayCount", count($decodedArray));
            }

        }
        $this->view->assign('groupedCookies', $groupedCookies);
        $this->view->assign('externalResources', $externalResources);
        return $this->htmlResponse();
    }
}
