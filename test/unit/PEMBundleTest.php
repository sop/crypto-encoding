<?php
use Sop\CryptoEncoding\PEM;
use Sop\CryptoEncoding\PEMBundle;

/**
 * @group pem
 */
class PEMBundleTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @return PEMBundle
     */
    public function testBundle()
    {
        $bundle = PEMBundle::fromFile(TEST_ASSETS_DIR . "/cacert.pem");
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
        $values = array();
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
        $this->assertInternalType("string", $bundle->string());
    }
    
    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testToString(PEMBundle $bundle)
    {
        $this->assertInternalType("string", strval($bundle));
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidPEM()
    {
        PEMBundle::fromString("invalid");
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidPEMData()
    {
        $str = <<<DATA
-----BEGIN TEST-----
%%%
-----END TEST-----
DATA;
        PEMBundle::fromString($str);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testInvalidFile()
    {
        PEMBundle::fromFile(TEST_ASSETS_DIR . "/nonexistent");
    }
    
    /**
     * @expectedException LogicException
     */
    public function testFirstEmptyFail()
    {
        $bundle = new PEMBundle();
        $bundle->first();
    }
    
    /**
     * @depends testBundle
     *
     * @param PEMBundle $bundle
     */
    public function testWithPEMs(PEMBundle $bundle)
    {
        $bundle = $bundle->withPEMs(new PEM("TEST", "data"));
        $this->assertCount(151, $bundle);
    }
}
