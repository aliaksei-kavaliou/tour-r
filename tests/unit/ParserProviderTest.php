<?php declare(strict_types = 1);

namespace App\Tests\unit;

use App\Exceptions\NoParserException;
use App\Interfaces\ParserInterface;
use App\Service\ParserProvider;
use PHPUnit\Framework\TestCase;

class ParserProviderTest extends TestCase
{
    public function testGetParser(): void
    {
        $parser = $this->getParser();
        $provider = new ParserProvider();
        $provider->addParser('foo', $parser);
        $this->assertSame($parser, $provider->getParser('foo'));
    }

    public function testGetParserException(): void
    {
        $this->expectException(NoParserException::class);
        $parser = $this->getParser();
        $provider = new ParserProvider();
        $provider->addParser('foo', $parser);
        $provider->getParser('baz');
    }

    private function getParser(): ParserInterface
    {
        return new class implements ParserInterface
        {
            public function parse(string $data): string
            {
                return 'bar';
            }

        };
    }
}
