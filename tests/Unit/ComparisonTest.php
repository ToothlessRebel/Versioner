<?php

namespace Tests\Unit;

use ExposureSoftware\Versioner\VersionString;
use PHPUnit\Framework\TestCase;

class ComparisonTest extends TestCase
{

    public function testPatchLessThan(): void
    {
        $this->assertTrue((new VersionString('0.0.1'))->isLessThan(new VersionString('0.0.2')));
        $this->assertFalse((new VersionString('0.0.3'))->isLessThan(new VersionString('0.0.2')));
    }

    public function testMinorLessThan(): void
    {
        $this->assertTrue((new VersionString('0.1'))->isLessThan(new VersionString('0.2')));
        $this->assertFalse((new VersionString('0.3'))->isLessThan(new VersionString('0.2')));
        $this->assertTrue((new VersionString('0.1.80'))->isLessThan(new VersionString('0.2.90')));
    }

    public function testMajorLessThan(): void
    {
        $this->assertTrue((new VersionString('1'))->isLessThan(new VersionString('2')));
        $this->assertFalse((new VersionString('3'))->isLessThan(new VersionString('2.9.9')));
        $this->assertTrue((new VersionString('8.1.80'))->isLessThan(new VersionString('9.2.90')));
    }

    public function testIsEqualTo(): void
    {
        $this->assertTrue((new VersionString('1'))->isEqualTo(new VersionString('1.00.0')));
        $this->assertTrue((new VersionString('1'))->isEqualTo(new VersionString('1.0.0')));
        $this->assertTrue((new VersionString('1'))->isEqualTo(new VersionString('1.0')));
        $this->assertTrue((new VersionString('1'))->isEqualTo(new VersionString('1')));
        $this->assertFalse((new VersionString('3'))->isEqualTo(new VersionString('2.9.9')));
    }

    public function testPatchGreaterThan(): void
    {
        $this->assertFalse((new VersionString('0.0.1'))->isGreaterThan(new VersionString('0.0.2')));
        $this->assertTrue((new VersionString('0.0.3'))->isGreaterThan(new VersionString('0.0.2')));
    }

    public function testMinorGreaterThan(): void
    {
        $this->assertFalse((new VersionString('0.1'))->isGreaterThan(new VersionString('0.2')));
        $this->assertTrue((new VersionString('0.3'))->isGreaterThan(new VersionString('0.2')));
        $this->assertFalse((new VersionString('0.1.80'))->isGreaterThan(new VersionString('0.2.90')));
    }

    public function testMajorGreaterThan(): void
    {
        $this->assertFalse((new VersionString('1'))->isGreaterThan(new VersionString('2')));
        $this->assertTrue((new VersionString('3'))->isGreaterThan(new VersionString('2.9.9')));
        $this->assertFalse((new VersionString('8.1.80'))->isGreaterThan(new VersionString('9.2.90')));
    }
}