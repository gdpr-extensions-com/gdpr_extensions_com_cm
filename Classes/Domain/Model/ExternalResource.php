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
 * ExternalResource
 */
class ExternalResource extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * url
     *
     * @var string
     */
    protected $url = null;

      /**
     * externalResourceList
     *
     * @var string
     */
    protected $externalResourceList = '';

    /**
     * Returns the url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the url
     *
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

     /**
     * Get the value of externalResourceList
     *
     * @return  string
     */
    public function getExternalResourceList() : string 
    {
        return $this->externalResourceList;
    }

    /**
     * Set the value of externalResourceList
     *
     * @param   string $externalResourceList
     */
    public function setExternalResourceList(string $externalResourceList)
    {
        return $this->externalResourceList = $externalResourceList;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
}
