CleverEzSettingsBundle
===================

CleverEzSettingsBundle is a modification of the MasevSettingsBundle for ezPlatform 2.x
Link to MasevSettings : https://github.com/masev/MasevSettingsBundle/blob/master/README.md

CleverEzSettingsBundle introduce a settings system into eZ Platform 3.x
All settings are injected in Symfony container as a parameter.
There are compatible with the eZ Publish Config Resolver allowing you the define settings per siteaccess.

![Screenshot of the UI](https://github.com/cleverage/CleverEZSettingsBundle/blob/master/ui.png?raw=true)

## Install

### Step 1: Download CleverEzSettingsBundle using composer

Add CleverEzSettingsBundle in your composer.json with the repository:

```js
{
    "require": {
        "clever/settings-bundle": "dev-master"
    },
    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/cleverage/CleverEZSettingsBundle"
      }
    ]    
}
```

Change dev-master with the release you want.

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update clever/settings-bundle
```

Composer will install the bundle to your project's `vendor/clever/settings-bundle` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// config/bundles.php

return [
    // ...
    Masev\SettingsBundle\MasevSettingsBundle::class => ['all' => true],
];

```

Add the routes in your routing base configuration by creating a masev_settings.yml file :

```yaml
# config/routes/masev_settings.yml

piles_settings:
    resource: "@MasevSettingsBundle/Resources/config/routing.yml"
    prefix:   /
```    

### Step 3: Configuration

Create a config file in config/packages/

Mysql example :
```yaml
# config/packages/masev_settings.yml
masev_settings:
    mysql:
        url: '%env(resolve:DATABASE_URL)%'
    http_cache_purge:
        enabled: true (to enable varnish purge)
```

 For Mysql Storage you need to initialize the setting table with the following query :

```sql
CREATE TABLE `masev_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `value` TEXT NOT NULL,
  `updatedAt` DATETIME NOT NULL,    
  `updatedBy` TEXT NOT NULL,
  `scope` varchar(255) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier_scope` (`identifier`,`scope`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
```

### Step 4: Declaring configurable settings

Create a file named settings.xml in the global config folder config/masev_settings.xml

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<settings xmlns="http://william-pottier.fr/schema/settings"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="http://william-pottier.fr/schema/settings https://raw.github.com/wpottier/WizadSettingsBundle/master/Resources/schema/settings-1.0.xsd">

    <parameter key="category.sub_category.sender_name">
        <name>Email sender name</name>
        <default>Me</default>
    </parameter>

    <parameter key="category.sub_category.sender_email">
        <name>Email sender address</name>
        <default>me@my-site.com</default>
    </parameter>
    
    <parameter key="category.sub_category.message">
        <name>Message</name>
        <default></default>
        <form type="textarea" cols="30" rows="10"></form>
    </parameter>

</settings>
```
Settings key must have a category and sub_category name to be displayed correctly in the admin UI.

Clear the Symfony cache :

```
php bin/console cache:clear && php bin/console cache:warmup
```

At this step you should be able the define settings in the admin UI (configuration tab in the eZ Publish Legacy Administration).

### Step 5 : Query your settings

Now that you have define settings you can query them with the [eZ Publish config resolver](https://doc.ezplatform.com/en/latest/guide/config_dynamic/).

```php
// Get the 'category.sub_category.sender_name' settings in the current scope (i.e. current siteaccess)
$this->configResolver->getParameter('category.sub_category.sender_name', 'masev_settings');

// You can force siteaccess
$this->configResolver->getParameter('category.sub_category.sender_name', 'masev_settings', 'my_site_access');
```

In a twig template you can use the getMasevSettings() Twig function.