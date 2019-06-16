<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Service\ClientProvider;
use PHPUnit\Framework\TestCase;

class ClientProviderTest extends TestCase
{
    public function testGetClient():void
    {
        $provider = new ClientProvider();
        $provider->addClient('foo', )
    }
}
