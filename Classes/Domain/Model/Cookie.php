<?php

declare(strict_types=1);

namespace GdprExtensionsCom\GdprExtensionsComCm\Domain\Model;


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
class Cookie extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * rootPid
     *
     * @var int
     */
    protected $rootPid = 0;

    /**
     * category
     *
     * @var string
     */
    protected $category = null;

    /**
     * domain
     *
     * @var string
     */
    protected $domain = null;

    /**
     * platform
     *
     * @var string
     */
    protected $platform = null;

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = null;

    /**
     * session
     *
     * @var string
     */
    protected $session = null;

     /**
     * session
     *
     * @var string
     */
    protected $expires = null;

    /**
     * type
     *
     * @var string
     */
    protected $type = null;

    /**
     * pagesList
     *
     * @var string
     */
    protected $pagesList = '';

    /**
     * Returns the category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param string $category
     * @return void
     */
    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    /**
     * Returns the domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the domain
     *
     * @param string $domain
     * @return void
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Returns the platform
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Sets the platform
     *
     * @param string $platform
     * @return void
     */
    public function setPlatform(string $platform)
    {
        $this->platform = $platform;
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the expires
     *
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Sets the expires
     * @param string $expires
     */
    public function setExpires(string $expires)
    {
        $this->expires = $expires;
    }

    /**
     * Returns the session
     *
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Sets the session
     * @param string $session
     */
    public function setSession(string $session)
    {
        $this->session = $session;
    }

    /**
     * Returns the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     * @return void
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getRootPid(): int
    {
        return $this->rootPid;
    }

    /**
     * @param int $rootPid
     */
    public function setRootPid(int $rootPid): void
    {
        $this->rootPid = $rootPid;
    }

    /**
     * Get the value of pagesList
     *
     * @return  string
     */
    public function getPagesList(): string
    {
        return $this->pagesList;
    }

    /**
     * Set the value of pagesList
     *
     * @param   string $pagesList
     */
    public function setPagesList(string $pagesList)
    {
        return  $this->pagesList = $pagesList;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
