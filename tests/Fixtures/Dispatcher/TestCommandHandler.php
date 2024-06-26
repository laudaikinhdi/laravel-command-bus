<?php

declare(strict_types=1);

namespace ThinkCodee\Laravel\CommandBus\Tests\Fixtures\Dispatcher;

class TestCommandHandler
{
    public function handle(TestCommand $command): int
    {
        return $command->value;
    }
}
