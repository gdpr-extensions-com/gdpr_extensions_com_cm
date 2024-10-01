<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Domain\Model;


use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * This file is part of the "gdpr_extensions_com_cm" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023
 */

/**
 * Cookie
 */
class PrivacyGenerator extends AbstractEntity
{

    /**
     * rootPid
     *
     * @var string
     */
    protected $rootPid = null;


    /**
     * @var string
     */
    protected $headerContent;

    /**
     * @var string
     */
    protected $quillContentData;

    /**
     * @var string
     */
    protected $contentBlockData;

    /**
     * @var int
     */
    protected $websiteId;

    /**
     * websiteUrl
     *
     * @var string
     */
    protected $websiteUrl = null;

    /**
     * dashboardApiKey
     *
     * @var string
     */
    protected $dashboardApiKey = null;


    /**
     * templateName
     *
     * @var string
     */
    protected $templateName = null;

    /**
     * Gets the Header Content.
     *
     * @return string
     */
    public function getHeaderContent()
    {
        return $this->headerContent;
    }

    /**
     * Sets the Header Content.
     *
     * @param string $headerContent
     */
    public function setHeaderContent($headerContent)
    {
        $this->headerContent = $headerContent;
    }

    /**
     * Gets the Quill Content Data.
     *
     * @return string
     */
    public function getQuillContentData()
    {
        return $this->quillContentData;
    }

    /**
     * Sets the Quill Content Data.
     *
     * @param string $quillContentData
     */
    public function setQuillContentData($quillContentData)
    {
        $this->quillContentData = $quillContentData;
    }

    /**
     * Gets the Content Block Data.
     *
     * @return string
     */
    public function getContentBlockData()
    {
        return $this->contentBlockData;
    }

    /**
     * Sets the Content Block Data.
     *
     * @param string $contentBlockData
     */
    public function setContentBlockData($contentBlockData)
    {
        $this->contentBlockData = $contentBlockData;
    }

    public function getUid(): int
    {
        return $this->uid;
    }
    public function setUid(?int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * Gets the Website Id.
     *
     * @return int
     */
    public function getWebsiteId(): int
    {
        return $this->websiteId;
    }

    /**
     * Sets the Website Id.
     *
     * @param int $websiteId
     */
    public function setWebsiteId(int $websiteId): void
    {
        $this->websiteId = $websiteId;
    }


    /**
     * Returns the url
     *
     * @return string
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }

    /**
     * Sets the url
     *
     * @return void
     */
    public function setWebsiteUrl(string $websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;
    }

    /**
     * Returns the url
     *
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Sets the url
     *
     * @return void
     */
    public function setTemplateName(string $templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Returns the rootPid
     *
     * @return string
     */
    public function getRootPid()
    {
        return $this->rootPid;
    }

    /**
     * Sets the rootPid
     *
     * @param string $rootPid
     * @return void
     */
    public function setRootPid(string $rootPid)
    {
        $this->rootPid = $rootPid;
    }


    /**
     * @return string|null
     */
    public function getDashboardApiKey(): ?string
    {
        return $this->dashboardApiKey;
    }

    /**
     * @param string|null $dashboardApiKey
     */
    public function setDashboardApiKey(?string $dashboardApiKey): void
    {
        $this->dashboardApiKey = $dashboardApiKey;
    }

}

