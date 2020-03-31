<?php

namespace Masev\SettingsBundle;

use Masev\SettingsBundle\DependencyInjection\Security\PolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MasevSettingsBundle extends Bundle
{

    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new PolicyProvider());
    }

}
