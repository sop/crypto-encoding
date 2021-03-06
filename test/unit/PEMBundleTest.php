<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\CryptoEncoding\PEM;
use Sop\CryptoEncoding\PEMBundle;

/**
 * @group pem
 *
 * @internal
 */
class PEMBundleTest extends TestCase
{
    /**
     * @return PEMBundle
     */
    public function testBundle()
    {
        $bundle = PEMBundle::fromFile(TEST_ASSETS_DIR . '/cacert.pem');
        $this->assertInstanceOf(PEMBundle::class, $bundle);
        return $bundle;
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testAll(PEMBundle $bundle)
    {
        $this->assertContainsOnlyInstancesOf(PEM::class, $bundle->all());
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testFirst(PEMBundle $bundle)
    {
        $this->assertInstanceOf(PEM::class, $bundle->first());
        $this->assertEquals($bundle->all()[0], $bundle->first());
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testLast(PEMBundle $bundle)
    {
        $this->assertInstanceOf(PEM::class, $bundle->last());
        $this->assertEquals($bundle->all()[149], $bundle->last());
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testCount(PEMBundle $bundle)
    {
        $this->assertCount(150, $bundle);
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testIterator(PEMBundle $bundle)
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
     * @param PEMBundle $bundle
     */
    public function testString(PEMBundle $bundle)
    {
        $this->assertIsString($bundle->string());
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testToString(PEMBundle $bundle)
    {
        $this->assertIsString(strval($bundle));
    }

    public function testInvalidPEM()
    {
        $this->expectException(UnexpectedValueException::class);
        PEMBundle::fromString('invalid');
    }

    public function testInvalidPEMData()
    {
        $str = <<<'DATA'
-----BEGIN TEST-----
%%%
-----END TEST-----
DATA;
        $this->expectException(UnexpectedValueException::class);
        PEMBundle::fromString($str);
    }

    public function testInvalidFile()
    {
        $this->expectException(RuntimeException::class);
        PEMBundle::fromFile(TEST_ASSETS_DIR . '/nonexistent');
    }

    public function testFirstEmptyFail()
    {
        $bundle = new PEMBundle();
        $this->expectException(LogicException::class);
        $bundle->first();
    }

    public function testLastEmptyFail()
    {
        $bundle = new PEMBundle();
        $this->expectException(LogicException::class);
        $bundle->last();
    }

    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testWithPEMs(PEMBundle $bundle)
    {
        $bundle = $bundle->withPEMs(new PEM('TEST', 'data'));
        $this->assertCount(151, $bundle);
    }
}
