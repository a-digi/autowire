<?php

declare (strict_types=1);

namespace AriAva\Autowire;

use ReflectionAttribute;
use SplFileInfo;

final class AutowireProxy
{
    public string|null $namespace;
    private \ReflectionClass|null $reflection;
    /**
     * @var array<int, ReflectionAttribute>
     */
    private array $attributes = [];

    public function __construct(private readonly SplFileInfo $file, private readonly string $instanceOf)
    {
        $this->namespace = null;
        $this->reflection = null;
        $this->extractNamespace();
    }

    public function canAutowire(): bool
    {
        return $this->isPhpFile() && $this->namespace !== null && $this->hasAutowireAttribute();
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getArguments(): \ReflectionMethod|null
    {
        return $this->reflection->getConstructor();
    }

    public function getReflection(): \ReflectionClass|null
    {
        return $this->reflection;
    }

    public function qualifiedName(): string|null
    {
        if (false === $this->hasAttributes()) {
            return null;
        }

        $attribute = $this->attributes[0]->newInstance();

        if (false === method_exists($attribute, 'getQualifiedName')) {
            return null;
        }

        return $attribute->getQualifiedName();
    }

    public function customConstructorArguments(): array
    {
        if (false === $this->hasAttributes()) {
            return [];
        }

        $attribute = $this->attributes[0]->newInstance();
        if (false === method_exists($attribute, 'getCustomConstructorArguments')) {
            return [];
        }

        return $attribute->getCustomConstructorArguments();
    }

    private function hasAttributes(): bool
    {
        if (0 === count($this->attributes)) {
            return false;
        }

        return true;
    }

    public function hasAutowireAttribute(): bool
    {

        if (null === $this->namespace) {
            return false;
        }

        try {
            $this->reflection = new \ReflectionClass($this->namespace);
            $this->attributes = $this->reflection->getAttributes($this->instanceOf, ReflectionAttribute::IS_INSTANCEOF);

            return count($this->attributes) > 0;
        } catch (\ReflectionException $e) {

            return false;
        }
    }

    private function extractNamespace(): void
    {
        $namespace = null;
        if (false === $this->isPhpFile()) {
            $this->namespace = null;

            return;
        }

        $handle = fopen($this->file->getPathname(), 'r');
        if (false === $handle) {
            $this->namespace = null;

            return;
        }

        while (($line = fgets($handle)) !== false) {
            if (str_starts_with($line, 'namespace')) {
                $parts = explode(' ', $line);
                $namespace = rtrim(trim($parts[1]), ';');
                break;
            }
        }
        fclose($handle);
        $fileName = str_replace('.php', '', $this->file->getFilename());

        $this->namespace = $namespace . '\\' . $fileName;
    }

    public function isPhpFile(): bool
    {
        return str_contains($this->file->getFilename(), '.php');
    }
}
