<?php

namespace Tests\Unit;

use ExposureSoftware\Versioner\VersionSegment;
use ExposureSoftware\Versioner\VersionString;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VersionStringTest extends TestCase
{
    #[DataProvider('validVersions')]
    public function testHandlesAllFormats(string $original, string $expected): void
    {
        $this->assertSame($expected, (string)VersionString::original($original));
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
            '0.0.1-alpha-1',
            (string)(new VersionString('0.0.0-alpha-1'))->preserveSuffix()->bump(VersionSegment::PATCH)
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

    public static function validVersions(): array
    {
        return [
            'typical' => ['1.2.3', '1.2.3'],
            'prefixed typical' => ['v1.2.3', 'v1.2.3'],
            'prefixed with pre-release' => ['v1.2.3-alpha-1', 'v1.2.3-alpha-1'],
            'with pre-release' => ['1.2.3-alpha', '1.2.3-alpha'],
            'prefixed with build' => ['v1.2.3+build-123', 'v1.2.3+build-123'],
            'only major' => ['1', '1.0.0'],
            'missing patch' => ['1.2', '1.2.0'],
            'major with decorators' => ['v1-alpha+abce123', 'v1.0.0-alpha+abce123'],
            'missing patch with build' => ['v1.2+abce123', 'v1.2.0+abce123'],
            'complete standard' => ['7.8.9-pre-release-1+build-data-2', '7.8.9-pre-release-1+build-data-2']
        ];
    }
}
