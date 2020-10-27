<?php
namespace Masev\SettingsBundle\Service;


use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ChainConfigResolver;
use EzSystems\PlatformHttpCacheBundle\PurgeClient\PurgeClientInterface;

class Purger {

    /** @var PurgeClientInterface  */
    private $purgeClient;

    /**
     * Purger constructor.
     * @param PurgeClientInterface $purgeClient
     */
    public function __construct(PurgeClientInterface $purgeClient)
    {
        $this->purgeClient = $purgeClient;
    }

    public function purgeAll()
    {
        $this->purgeClient->purgeAll();
    }

    /**
     * @return PurgeClientInterface
     */
    public function getPurgeClient(): PurgeClientInterface
    {
        return $this->purgeClient;
    }
}