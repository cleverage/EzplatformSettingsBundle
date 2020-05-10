<?php

namespace Masev\SettingsBundle\Twig;

use \Twig\Extension\AbstractExtension;
use \Twig\TwigFunction;

class MasevExtension extends AbstractExtension
{

    private $configResolver;

    public function __construct($configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function getFunctions() {
        return array(
            new TwigFunction('getMasevSettings', [$this, 'getMasevSettings'])
        );
    }

    public function getMasevSettings($key)
    {
        if (!$this->configResolver->hasParameter($key, 'masev_settings')) {
            return false;
        }

        return $this->configResolver->getParameter($key, 'masev_settings');
    }

    public function getName()
    {
        return 'masev_extension';
    }
}
