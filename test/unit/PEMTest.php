<?php

declare(strict_types=1);

namespace Sop\CryptoEncoding\Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sop\CryptoEncoding\PEM;
use UnexpectedValueException;

use function base64_encode;
use function file_get_contents;

/**
 * @group pem
 *
 * @internal
 */
class PEMTest extends TestCase
{
    public function testFromString(): void
    {
        $str = file_get_contents(TEST_ASSETS_DIR . '/public_key.pem');
        $pem = PEM::fromString($str);
        $this->assertInstanceOf(PEM::class, $pem);
    }

    /**
     * @return \Sop\CryptoEncoding\PEM
     */
    public function testFromFile(): PEM
    {
        $pem = PEM::fromFile(TEST_ASSETS_DIR . '/public_key.pem');
        $this->assertInstanceOf(PEM::class, $pem);
        return $pem;
    }

    /**
     * @depends testFromFile
     *
     * @return \Sop\CryptoEncoding\PEM
     */
    public function testType(PEM $pem): void
    {
        $this->assertEquals(PEM::TYPE_PUBLIC_KEY, $pem->type());
    }

    public function testData(): void
    {
        $data = 'payload';
        $encoded = base64_encode($data);
        $str = <<<DATA
-----BEGIN TEST-----
{$encoded}
-----END TEST-----
DATA;
        $this->assertEquals($data, PEM::fromString($str)->data());
    }

    public function testInvalidPEM(): void
    {
        $this->expectException(UnexpectedValueException::class);
        PEM::fromString('invalid');
    }

    public function testInvalidPEMData(): void
    {
        $str = <<<'DATA'
-----BEGIN TEST-----
%%%
-----END TEST-----
DATA;
        $this->expectException(UnexpectedValueException::class);
        PEM::fromString($str);
    }

    public function testInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        PEM::fromFile(TEST_ASSETS_DIR . '/nonexistent');
    }

    /**
     * @depends testFromFile
     *
     * @param \Sop\CryptoEncoding\PEM $pem
     */
    public function testString(PEM $pem): void
    {
        $this->assertIsString($pem->string());
    }

    /**
     * @depends testFromFile
     *
     * @param \Sop\CryptoEncoding\PEM $pem
     */
    public function testToString(PEM $pem): void
    {
        $this->assertIsString(strval($pem));
    }
}
