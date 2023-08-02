<?php

namespace ExposureSoftware\Versioner;

use ReflectionClass;
use ReflectionException;
use Stringable;

readonly class VersionString implements Stringable
{
    private int $major;
    private int $minor;
    private int $patch;

    public string $original;

    private ?string $prefix;

    private ?string $suffix;
    private bool $preserveSuffix;

    public function __construct(
        string       $version,
    )
    {
        $this->original = $version;
        $this->prefix = preg_replace('/\d.*$/', '', $version);
        // Remove the build metadata.
        preg_replace('/.+?[+]/', '', $version);
        $this->suffix = preg_replace('/.+?[-]/', '', $version);

        while (count(explode('.', $version)) < 3) {
            $version .= '.0';
        }

        [$this->major, $this->minor, $this->patch] = explode('.', $version);
        $this->preserveSuffix = false;
    }

    public static function original(string $version): static
    {
        return new static($version);
    }

    /**
     * @throws ReflectionException
     *
     * @link https://github.com/spatie/php-cloneable/blob/3ffb6eb6caf8d41f916cc0ff39c6853363bc9992/src/Cloneable.php
     */
    protected function newInstance(...$overrides): static
    {
        $reflection = (new ReflectionClass(static::class));
        $clone = $reflection->newInstanceWithoutConstructor();

        foreach (get_object_vars($this) as $property => $value) {
            $value = array_key_exists($property, $overrides) ? $overrides[$property] : $value;
            $scope = $reflection->getProperty($property)->getDeclaringClass()->getName();

            if ($scope === self::class) {
                $clone->$property = $value;
            } else {
                (fn () => $this->$property = $value)->bindTo($clone, $scope);
            }
        }

        return $clone;
    }

    public function __toString(): string
    {
        return trim($this->prefix . $this->major . $this->minor . $this->patch . '-' . $this->suffix, ['.', '-']);
    }
}
