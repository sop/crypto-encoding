<?php

declare(strict_types=1);

namespace Sop\CryptoEncoding\Test;

use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Sop\CryptoEncoding\PEM;
use Sop\CryptoEncoding\PEMBundle;
use UnexpectedValueException;

/**
 * @group pem
 *
 * @internal
 */
class PEMBundleTest extends TestCase
{
    /**
     * @return \Sop\CryptoEncoding\PEMBundle
     */
    public function testBundle(): PEMBundle
    {
        $bundle = PEMBundle::fromFile(TEST_ASSETS_DIR . '/cacert.pem');
        $this->assertInstanceOf(PEMBundle::class, $bundle);
        return $bundle;
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testAll(PEMBundle $bundle): void
    {
        $this->assertContainsOnlyInstancesOf(PEM::class, $bundle->all());
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testFirst(PEMBundle $bundle): void
    {
        $this->assertInstanceOf(PEM::class, $bundle->first());
        $this->assertEquals($bundle->all()[0], $bundle->first());
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testLast(PEMBundle $bundle): void
    {
        $this->assertInstanceOf(PEM::class, $bundle->last());
        $this->assertEquals($bundle->all()[149], $bundle->last());
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testCount(PEMBundle $bundle): void
    {
        $this->assertCount(150, $bundle);
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testIterator(PEMBundle $bundle): void
    {
        $values = [];
        foreach ($bundle as $pem) {
            $values[] = $pem;
        }
        $this->assertContainsOnlyInstancesOf(PEM::class, $values);
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testString(PEMBundle $bundle): void
    {
        $this->assertIsString($bundle->string());
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testToString(PEMBundle $bundle): void
    {
        $this->assertIsString(strval($bundle));
    }

    public function testInvalidPEM(): void
    {
        $this->expectException(UnexpectedValueException::class);
        PEMBundle::fromString('invalid');
    }

    public function testInvalidPEMData(): void
    {
        $str = <<<'DATA'
-----BEGIN TEST-----
%%%
-----END TEST-----
DATA;
        $this->expectException(UnexpectedValueException::class);
        PEMBundle::fromString($str);
    }

    public function testInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        PEMBundle::fromFile(TEST_ASSETS_DIR . '/nonexistent');
    }

    public function testFirstEmptyFail(): void
    {
        $bundle = new PEMBundle();
        $this->expectException(LogicException::class);
        $bundle->first();
    }

    public function testLastEmptyFail(): void
    {
        $bundle = new PEMBundle();
        $this->expectException(LogicException::class);
        $bundle->last();
    }

    /**
     * @depends testBundle
     *
     * @param \Sop\CryptoEncoding\PEMBundle $bundle
     */
    public function testWithPEMs(PEMBundle $bundle)
    {
        $bundle = $bundle->withPEMs(new PEM('TEST', 'data'));
        $this->assertCount(151, $bundle);
    }
}
