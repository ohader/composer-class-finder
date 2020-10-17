<?php
declare(strict_types=1);

namespace OliverHader\ComposerClassFinder;

class Composer
{
    public static function assertVendorDirectory(string $vendorDirectory): string
    {
        $vendorDirectory = self::normalizeDirectory($vendorDirectory);
        if (!self::isVendorDirectory($vendorDirectory)) {
            throw new Exception\InvalidVendorDirectoryException(
                sprintf('Invalid vendor directory %s', $vendorDirectory),
                1602928766
            );
        }
        return $vendorDirectory;
    }

    private static function isVendorDirectory(string $vendorDirectory): bool
    {
        $vendorDirectory = self::normalizeDirectory($vendorDirectory);
        return is_dir($vendorDirectory)
            && file_exists($vendorDirectory . '/autoload.php');
    }

    private static function normalizeDirectory(string $directory): string
    {
        return rtrim($directory, '/');
    }
}
