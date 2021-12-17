<?php

namespace ItDevgroup\LaravelDeveloperDocs\Test;

use ItDevgroup\LaravelDeveloperDocs\DeveloperDocsService;
use ItDevgroup\LaravelDeveloperDocs\Test\Resource\StorageFacadeTest;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;

/**
 * Class TestCase
 * @package ItDevgroup\LaravelEmailTemplateLite\Test
 */
class TestCase extends BaseTestCase
{
    /**
     * @var ReflectionClass|null
     */
    protected ?ReflectionClass $reflectionClass = null;
    /**
     * @var DeveloperDocsService|object|null
     */
    protected ?DeveloperDocsService $service = null;

    /**
     * @return void
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $instanceStorage = new StorageFacadeTest();

        $this->reflectionClass = new ReflectionClass(DeveloperDocsService::class);

        $this->service = $this->reflectionClass->newInstanceWithoutConstructor();
        $this->setServiceProperty('storage', $instanceStorage);
        $this->setServiceProperty('filesFolder', __DIR__ . '/resources/docs');
        $this->setServiceProperty('routePrefix', 'developer-docs');
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     * @throws ReflectionException
     */
    protected function setServiceProperty(string $propertyName, $value)
    {
        $reflectionProperty = $this->reflectionClass->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(
            $this->service,
            $value
        );
    }

    /**
     * @param mixed ...$args
     * @return mixed
     */
    protected function getServiceProtectedMethod(string $methodName, ...$args)
    {
        $method = $this->reflectionClass->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->service, $args);
    }
}
