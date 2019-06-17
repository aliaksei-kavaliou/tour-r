<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'messenger.routable_message_bus' shared service.

include_once $this->targetDirs[3].'/vendor/symfony/messenger/MessageBusInterface.php';
include_once $this->targetDirs[3].'/vendor/symfony/messenger/RoutableMessageBus.php';

return $this->privates['messenger.routable_message_bus'] = new \Symfony\Component\Messenger\RoutableMessageBus(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($this->getService, [
    'messenger.bus.default' => ['services', 'message_bus', 'getMessageBusService.php', true],
], [
    'messenger.bus.default' => '?',
]), ($this->services['message_bus'] ?? $this->load('getMessageBusService.php')));
