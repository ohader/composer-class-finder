<?php
declare(strict_types=1);

namespace OliverHader\ComposerClassFinder\Tests;

use OliverHader\ComposerClassFinder\TYPO3;
use PHPUnit\Framework\TestCase;

class TYPO3Test extends TestCase
{
    public function isComposerModeDeterminedDataProvider()
    {
        $fixturesDirectory = __DIR__ . '/Fixtures';
        return [
            [$fixturesDirectory . '/ComposerMode/vendor/', true],
            [$fixturesDirectory . '/NonComposerMode/vendor/', false],
        ];
    }

    /**
     * @param string $directory
     * @param bool $expectation
     *
     * @test
     * @dataProvider isComposerModeDeterminedDataProvider
     */
    public function isComposerModeDetermined(string $directory, bool $expectation)
    {
        self::assertSame($expectation, TYPO3::isComposerMode($directory));
    }
}
