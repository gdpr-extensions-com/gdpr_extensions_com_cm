<?php

namespace GdprExtensionsCom\GdprExtensionsComCm\Commands;

use GdprExtensionsCom\GdprExtensionsComCm\Utility\SyncPrivacyStatement;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GdprExtensionsCom\GdprExtensionsComCm\Utility\SyncCookies;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SyncPrivacyStatementTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    protected $syncPrivacyStatement;
    protected $connectionPool;
    protected $siteFinder;
    protected $requestFactory;

    protected function configure()
    {

    }

    // The execute() method contains the command’s logic.
    public function execute()
    {
        $this->syncPrivacyStatement = GeneralUtility::makeInstance(SyncPrivacyStatement::class);
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $this->syncPrivacyStatement->run($this->connectionPool, $this->requestFactory);
        return true;
    }
}
