# Composer Class Finder

Resolves class files based on Composer autoload information, without actually
applying autoloader in PHP process. The scope of this package is to retrieve
the file location for classes of particular Composer-based projects.

This way classes can be resolved *standalone* without actually loading them
into memory, since class loading is not invoked at all. 

## Plain Composer

```
<?php
require_once __DIR__ . '/vendor/autoload.php';

$directory = '/other/project/vendor';
$finder = \OliverHader\ComposerClassFinder\TYPO3::resolveFinder($directory);
$filePath = $finder->findFile('Vendor\\Project\\Some\\Utility');
```

## TYPO3 specific

### resolving composer-mode of a project

This basically resolves information concerning TYPO3 "composer-mode".

```
$bool = \OliverHader\ComposerClassFinder\TYPO3::isComposerMode($directory);
```

* `$directory` set to location of root `composer.json` returns `true`
  (given there actually is a valid TYPO3 composer-based installation) 
* `$directory` set to location to web-root (containing `index.php`
  and `typo3conf/autoload/`) returns `false`

### applying corresponding autoload information

Same rules as in previous `composer-mode` section apply for `$directory`.
The library won't do much directory traversing - exceptions are thrown when
providing invalid directories. Thus, it's your job to provide correct values.

Information is fetched from either composer-based data (just `vendor/`) or in
non-composer-mode from `<typo3_src>/vendor/` and `<web-root>/typo3conf/autoload/`.

```
$finder = \OliverHader\ComposerClassFinder\TYPO3::resolveFinder($directory);
$filePath = $finder->findFile('BK2K\\BootstrapPackage\\Backend\\ToolbarItem\\VersionToolbarItem');
```

## Thanks to Composer Folks

Basically this package is just a wrapper for existing Composer class-loader,
the corresponding code has been duplicated from https://github.com/composer/composer.

Thanks a bunch for your great work! :+1:
