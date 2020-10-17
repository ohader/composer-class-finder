<?php
declare(strict_types=1);

namespace OliverHader\ComposerClassFinder;

class TYPO3
{
    const SIMULATED_PREFIX = '__!!SimulatedPrefix!!__';

    /**
     * @param string $vendorDirectory
     * @return bool
     */
    public static function isComposerMode(string $vendorDirectory): bool
    {
        $vendorDirectory = Composer::assertVendorDirectory($vendorDirectory);
        $filePath = $vendorDirectory . '/typo3/autoload-include.php';
        if (!file_exists($filePath)) {
            return false;
        }
        $fileContent = file_get_contents($filePath);
        return strpos($fileContent, 'TYPO3_COMPOSER_MODE') !== false;
    }

    /**
     * @param string $projectDirectory
     * @return Finder
     */
    public static function resolveFinder(string $projectDirectory): Finder
    {
        $projectDirectory = rtrim($projectDirectory, '/');
        $finder = self::resolveWebRoot($projectDirectory)
            ?? self::resolveComposerRoot($projectDirectory);
        if ($finder === null) {
            throw new Exception\ResolvingException('Could not resolve autoload information', 1602933447);
        }
        return $finder;
    }

    /**
     * @param string $directory
     * @return Finder|null
     */
    private static function resolveWebRoot(string $directory)
    {
        if (!file_exists($directory . '/index.php') || !is_dir($directory . '/typo3conf')) {
            return null;
        }
        $indexPath = realpath($directory . '/index.php');
        $indexDirectory = dirname($indexPath);
        try {
            $finder = Finder::fromVendorDirectory($indexDirectory . '/vendor');
            self::exposeConstants();
            $autoloadDirectory = $directory . '/typo3conf/autoload';
            if (file_exists($autoloadDirectory . '/autoload_classmap.php')) {
                $classMap = require $autoloadDirectory . '/autoload_classmap.php';
                $classMap = self::removeSimulatedPrefix($directory, $classMap);
                $finder->addClassMap($classMap);
            }
            if (file_exists($autoloadDirectory . '/autoload_psr4.php')) {
                $map = require $autoloadDirectory . '/autoload_psr4.php';
                $map = self::removeSimulatedPrefix($directory, $map);
                foreach ($map as $prefix => $paths) {
                    $finder->addPsr4($prefix, $paths);
                }
            }
            return $finder;
        } catch (Exception\InvalidVendorDirectoryException $exception) {
        }
        return null;
    }

    /**
     * @param string $directory
     * @return Finder|null
     */
    private static function resolveComposerRoot(string $directory)
    {
        try {
            if (!self::isComposerMode($directory . '/vendor')) {
                return null;
            }
            return Finder::fromVendorDirectory($directory . '/vendor');
        } catch (Exception\InvalidVendorDirectoryException $exception) {
        }
        return null;
    }

    private static function removeSimulatedPrefix($directory, array $array): array
    {
        return array_map(
            function ($item) use ($directory) {
                if (is_array($item)) {
                    return self::removeSimulatedPrefix($directory, $item);
                }
                if (!is_string($item) || strpos($item, self::SIMULATED_PREFIX) === false) {
                    return $item;
                }
                return str_replace(self::SIMULATED_PREFIX, $directory, $item);
            },
            $array
        );
    }

    private static function exposeConstants()
    {
        if (!defined('PATH_site')) {
            define('PATH_site', self::SIMULATED_PREFIX);
        }
    }
}
