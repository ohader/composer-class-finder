<?php
declare(strict_types=1);

namespace OliverHader\ComposerClassFinder\Tests;

use OliverHader\ComposerClassFinder\Composer;
use OliverHader\ComposerClassFinder\Exception\InvalidVendorDirectoryException;
use PHPUnit\Framework\TestCase;

class ComposerTest extends TestCase
{
    /**
     * @test
     */
    public function assertVendorDirectoryReturnsDirectoryWithoutTrailingSlash()
    {
        $vendorDirectory = dirname(__DIR__) . '/vendor/';
        $actual = Composer::assertVendorDirectory($vendorDirectory);
        $expected = dirname(__DIR__) . '/vendor';
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function assertVendorDirectoryThrowsException()
    {
        $this->expectException(InvalidVendorDirectoryException::class);
        $this->expectExceptionCode(1602928766);
        Composer::assertVendorDirectory(random_bytes(10));
    }
}
