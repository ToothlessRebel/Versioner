<?php

namespace ExposureSoftware\Versioner;

use Stringable;

class VersionString implements Stringable
{
    private int $major = 0;
    private int $minor = 0;
    private int $patch = 0;

    public readonly string $original;

    private readonly ?string $prefix;

    private ?string $build;

    private ?string $suffix;
    private bool $preserveSuffix;

    public function __construct(
        string $version,
    )
    {
        $this->original = $version;
        $this->prefix = preg_replace('/\d.*$/', '', $version);
        $this->build = preg_replace('/.+?[+]/', '', $version);
        $this->suffix = preg_replace('/.+?[-]/', '', preg_replace('/\+.*$/', '', $version));

        [$this->major, $this->minor, $this->patch] = array_map(
            fn(string $segment) => (int)$segment,
            explode('.', preg_replace('/[^0-9.]*([0-9.]+).*/', '$1', $version))
        );
        $this->preserveSuffix = false;
    }

    public static function original(string $version): static
    {
        return new static($version);
    }

    public static function minor(string $original): string
    {
        return (new static($original))->bump(VersionSegment::MINOR);
    }

    public static function major(string $original): string
    {
        return (new static($original))->bump(VersionSegment::MAJOR);
    }

    public static function patch(string $original): string
    {
        return (new static($original))->bump(VersionSegment::PATCH);
    }

    public function bump(VersionSegment $segment): static
    {
        $this->clearExtensions();

        match ($segment) {
            VersionSegment::MAJOR => $this->set(++$this->major, 0, 0),
            VersionSegment::MINOR => $this->set($this->major, ++$this->minor, 0),
            VersionSegment::PATCH => $this->patch++
        };

        return $this;
    }

    public function preserveSuffix(): static
    {
        $this->preserveSuffix = true;

        return $this;
    }

    public function __toString(): string
    {
        return trim(
            implode('.', [$this->prefix . $this->major, $this->minor, $this->patch]) . '-' . $this->suffix . '+' . $this->build,
            '.-+'
        );
    }

    protected function set(int $major, int $minor, int $patch): static
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        return $this;
    }

    protected function clearExtensions(): void
    {
        if ($this->preserveSuffix === false) {
            $this->suffix = null;
        }
        $this->build = null;
    }
}
