<?php

namespace Ezplatform\SettingsBundle\Twig;

use \Twig\Extension\AbstractExtension;
use \Twig\TwigFunction;

class SettingsExtension extends AbstractExtension
{

    private $configResolver;

    public function __construct($configResolver)
    {
        $this->configResolver = $configResolver;
    }

    public function getFunctions() {
        return array(
            new TwigFunction('getSettings', [$this, 'getSettings'])
        );
    }

    public function getSettings($key)
    {
        if (!$this->configResolver->hasParameter($key, 'cleverage_settings')) {
            return false;
        }

        return $this->configResolver->getParameter($key, 'cleverage_settings');
    }

    public function getName()
    {
        return 'settings_extension';
    }
}
