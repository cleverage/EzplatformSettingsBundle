<?php

namespace Ezplatform\SettingsBundle;

use Ezplatform\SettingsBundle\DependencyInjection\EzplatformSettingsExtension;
use Ezplatform\SettingsBundle\DependencyInjection\Security\PolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EzplatformSettingsBundle extends Bundle
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

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EzplatformSettingsExtension();
        }

        return $this->extension;
    }

}
