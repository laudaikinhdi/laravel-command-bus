<?php

declare(strict_types=1);

namespace ThinkCodee\Laravel\CommandBus\Tests\Dispatcher;

use Orchestra\Testbench\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ThinkCodee\Laravel\CommandBus\CommandDispatcher;
use ThinkCodee\Laravel\CommandBus\Tests\Fixtures\Handler\TestCommand;
use ThinkCodee\Laravel\CommandBus\Tests\Fixtures\Handler\TestCommandWithHandlerAttribute;
use ThinkCodee\Laravel\CommandBus\Tests\Fixtures\Handler\TestCommandWithHandlerAndMethodAttribute;
use ThinkCodee\Laravel\CommandBus\Tests\Fixtures\Handler\TestOtherCommand;

class CommandDispatcherHandlerMethodResolvingTest extends TestCase
{
    private CommandDispatcher $commandDispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandDispatcher = new CommandDispatcher($this->app);
    }

    public function testItResolvesMethodFromAttribute()
    {
        $method = $this->getMethod();

        $this->assertEquals(
            'customMethod',
            $method->invoke($this->commandDispatcher, new TestCommandWithHandlerAndMethodAttribute)
        );
    }

    public function testItResolvesWithConfigValueWhenMethodNotSpecified(): void
    {
        $this->commandDispatcher->handlerMethod('someMethod');

        $method = $this->getMethod();

        $this->assertEquals(
            'someMethod',
            $method->invoke($this->commandDispatcher, new TestCommandWithHandlerAttribute)
        );
    }

    public function testItResolvesWithConfigValueWhenNoAttribute()
    {
        $this->commandDispatcher->handlerMethod('someMethod');

        $method = $this->getMethod();

        $this->assertEquals(
            'someMethod',
            $method->invoke($this->commandDispatcher, new TestCommand)
        );
    }

    private function getMethod(): ReflectionMethod
    {
        $method = (new ReflectionClass($this->commandDispatcher))->getMethod('getMethod');

        $method->setAccessible(true);

        return $method;
    }
}
