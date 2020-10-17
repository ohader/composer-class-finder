<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Core;

class Environment
{
    public static $prefix = '__!!SimulatedPrefix!!__';

    public static function getPublicPath(): string
    {
        return \OliverHader\ComposerClassFinder\TYPO3::SIMULATED_PREFIX;
    }
}
