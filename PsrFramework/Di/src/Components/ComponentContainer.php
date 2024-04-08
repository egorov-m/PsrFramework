<?php

namespace Csu\PsrFramework\Di\Components;

use Csu\PsrFramework\Di\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class ComponentContainer extends BaseComponent implements ContainerInterface
{
    private array $components;
    private array $config;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this -> config = $params;
        $this -> components = [];
    }

    public function init(): void
    {
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = null;
            if (isset($this->components[$id])) {
                $entry = $this->components[$id];
            } elseif (isset($this->config[$id])) {
                $entry = $this->config[$id];
            }

            if (is_callable($entry)) {
                return $entry($this);
            }

            $id = $entry;
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        if (array_key_exists($id, $this->components)) {
            return true;
        }
        if (array_key_exists($id, $this->config)) {
            return true;
        }

        return false;
    }

    public function set(string $id, callable|string $concrete): void
    {
        $this->components[$id] = $concrete;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     */
    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);

        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException("Class \"" . $id . "\" is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (! $constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $id();
        }

        $dependencies = array_map(function (ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (! $type) {
                throw new ContainerException(
                    "Failed to resolve class \"" . $id . "\" because param \"" . $name . "\" is missing a type."
                );
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException(
                    "Failed to resolve class \"" . $id . "\" because of union type for param \"" . $name . "\" is missing a type."
                );
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException(
                "Failed to resolve class \"" . $id . "\" because invalid param \"" . $name . "\" is missing a type."
            );
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
