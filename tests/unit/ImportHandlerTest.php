<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Events\ImportedEvent;
use App\Events\ImportFailedEvent;
use App\Handler\ImportHandler;
use App\Interfaces\ClientInterface;
use App\Interfaces\ParserInterface;
use App\Message\ImportMessage;
use App\Service\ClientProvider;
use App\Service\ParserProvider;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ImportHandlerTest extends TestCase
{
    private $clientProvider;
    private $parserProvider;
    private $s3Client;
    private $dispatcher;


    protected function setUp()
    {
        $client = new class implements ClientInterface
        {
            public function loadData(): string
            {
                return 'bar';
            }

        };
        $this->clientProvider = $this->prophesize(ClientProvider::class);
        $this->clientProvider->getClient('foo')->willReturn($client)->shouldBeCalled();

        $parser = new class implements ParserInterface
        {
            public function parse(string $data): string
            {
                return 'baz';
            }
        };

        $this->parserProvider = $this->prophesize(ParserProvider::class);
        $this->parserProvider->getParser('foo')->willReturn($parser)->shouldBeCalled();

        $this->s3Client = $this->prophesize(S3Client::class);
        $this->s3Client->putObject(Argument::type('array'))->shouldBeCalled();

        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
    }

    public function testInvoke(): void
    {
        $this->dispatcher->dispatch(Argument::type(ImportedEvent::class))->shouldBeCalled();
        $handler = new ImportHandler(
            $this->clientProvider->reveal(),
            $this->parserProvider->reveal(),
            $this->s3Client->reveal(),
            'tour_storage',
            $this->dispatcher->reveal()
        );

        $handler->__invoke(new ImportMessage('foo'));
    }

    public function testInvokeFailed(): void
    {
        $this->s3Client->putObject(Argument::type('array'))->willThrow(S3Exception::class);
        $this->dispatcher->dispatch(Argument::type(ImportFailedEvent::class))->shouldBeCalled();
        $handler = new ImportHandler(
            $this->clientProvider->reveal(),
            $this->parserProvider->reveal(),
            $this->s3Client->reveal(),
            'tour_storage',
            $this->dispatcher->reveal()
        );

        $handler->__invoke(new ImportMessage('foo'));
    }
}
