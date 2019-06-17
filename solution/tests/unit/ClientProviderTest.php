<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Exceptions\NoClientException;
use App\Interfaces\ClientInterface;
use App\Service\ClientProvider;
use PHPUnit\Framework\TestCase;

class ClientProviderTest extends TestCase
{
    public function testGetClient():void
    {
        $client = $this->getClient();
        $provider = new ClientProvider();
        $provider->addClient('foo', $client);
        $this->assertSame($client, $provider->getClient('foo'));
    }

    public function testGetClientException():void
    {
        $this->expectException(NoClientException::class);
        $client = $this->getClient();
        $provider = new ClientProvider();
        $provider->addClient('foo', $client);
        $provider->getClient('baz');
    }

    private function getClient(): ClientInterface
    {
        return new class implements ClientInterface
        {
            public function loadData(): string
            {
                return 'bar';
            }

        };
    }
}
