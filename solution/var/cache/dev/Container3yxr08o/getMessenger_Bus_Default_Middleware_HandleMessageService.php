<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'messenger.bus.default.middleware.handle_message' shared service.

include_once $this->targetDirs[3].'/vendor/symfony/messenger/Middleware/MiddlewareInterface.php';
include_once $this->targetDirs[3].'/vendor/psr/log/Psr/Log/LoggerAwareTrait.php';
include_once $this->targetDirs[3].'/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php';
include_once $this->targetDirs[3].'/vendor/symfony/messenger/Handler/HandlersLocatorInterface.php';
include_once $this->targetDirs[3].'/vendor/symfony/messenger/Handler/HandlersLocator.php';

$this->privates['messenger.bus.default.middleware.handle_message'] = $instance = new \Symfony\Component\Messenger\Middleware\HandleMessageMiddleware(new \Symfony\Component\Messenger\Handler\HandlersLocator(['App\\Message\\ImportMessage' => new RewindableGenerator(function () {
    yield 0 => ($this->privates['.messenger.handler_descriptor.jzO5rOv'] ?? $this->load('get_Messenger_HandlerDescriptor_JzO5rOvService.php'));
}, 1)]));

$instance->setLogger(($this->privates['logger'] ?? ($this->privates['logger'] = new \Symfony\Component\HttpKernel\Log\Logger())));

return $instance;