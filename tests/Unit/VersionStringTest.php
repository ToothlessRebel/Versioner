<?php

namespace Tests\Unit;

use ExposureSoftware\Versioner\VersionSegment;
use ExposureSoftware\Versioner\VersionString;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VersionStringTest extends TestCase
{
    public function testWillNotMangleOriginal(): void
    {
        $original = 'v1.2.3-alpha+abce123';

        $this->assertSame($original, (string)VersionString::original($original));
    }

    #[DataProvider('segments')]
    public function testCanBump(VersionSegment $segment, string $original, string $expected): void
    {
        $this->assertSame($expected, (string)(new VersionString($original))->bump($segment));
    }

    public function testClearsExtensions(): void
    {
        $this->assertSame('0.0.1', VersionString::patch('0.0.0-alpha'));
    }

    public function testCanPreserveSuffix(): void
    {
        $this->assertSame(
            '0.0.1-alpha',
            (string)(new VersionString('0.0.0-alpha'))->preserveSuffix()->bump(VersionSegment::PATCH)
        );
    }

    public function testPreservesPrefix(): void
    {
        $this->assertSame(
            'v2.0.0',
            (string)(new VersionString('v1.1.0'))->bump(VersionSegment::MAJOR)
        );
    }

    public function testBumpResetsLowerSegments(): void
    {
        $this->assertSame('3.0.0', VersionString::major('2.2.2'));
        $this->assertSame('2.3.0', VersionString::minor('2.2.2'));
    }

    public static function segments(): array
    {
        return [
            'patch segment' => [VersionSegment::PATCH, '0.0.0', '0.0.1'],
            'minor segment' => [VersionSegment::MINOR, '0.0.0', '0.1.0'],
            'major segment' => [VersionSegment::MAJOR, '0.0.0', '1.0.0'],
        ];
    }
}
