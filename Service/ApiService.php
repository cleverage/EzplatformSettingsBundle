<?php
namespace Ezplatform\SettingsBundle\Service;


use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ChainConfigResolver;

class ApiService {

    private $configResolver;

    public function __construct(ChainConfigResolver $configResolver, $logger)
    {

    }

}