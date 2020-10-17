<?php
declare(strict_types=1);

namespace OliverHader\ComposerClassFinder\Tests;

use OliverHader\ComposerClassFinder\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{
    /**
     * @test
     */
    public function findFileResolves()
    {
        $vendorDirectory = dirname(__DIR__) . '/vendor/';
        $finder = Finder::fromVendorDirectory($vendorDirectory);
        $actual = realpath($finder->findFile(Finder::class));
        $expected = dirname(__DIR__) . '/src/Finder.php';
        self::assertSame($expected, $actual);
    }
}
