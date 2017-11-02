<?php
declare(strict_types=1);

use Sop\CryptoEncoding\PEM;

/**
 * @group pem
 */
class PEMTest extends PHPUnit_Framework_TestCase
{
    /**
     */
    public function testFromString()
    {
        $str = file_get_contents(TEST_ASSETS_DIR . "/public_key.pem");
        $pem = PEM::fromString($str);
        $this->assertInstanceOf(PEM::class, $pem);
    }
    
    /**
     *
     * @return \Sop\CryptoEncoding\PEM
     */
    public function testFromFile(): PEM
    {
        $pem = PEM::fromFile(TEST_ASSETS_DIR . "/public_key.pem");
        $this->assertInstanceOf(PEM::class, $pem);
        return $pem;
    }
    
    /**
     * @depends testFromFile
     *
     * @param PEM $pem
     */
    public function testType(PEM $pem)
    {
        $this->assertEquals(PEM::TYPE_PUBLIC_KEY, $pem->type());
    }
    
    /**
     */
    public function testData()
    {
        $data = "payload";
        $encoded = base64_encode($data);
        $str = <<<DATA
-----BEGIN TEST-----
$encoded
-----END TEST-----
DATA;
        $this->assertEquals($data, PEM::fromString($str)->data());
    }
    
    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidPEM()
    {
        PEM::fromString("invalid");
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
        PEM::fromString($str);
    }
    
    /**
     * @expectedException RuntimeException
     */
    public function testInvalidFile()
    {
        PEM::fromFile(TEST_ASSETS_DIR . "/nonexistent");
    }
    
    /**
     * @depends testFromFile
     *
     * @param PEM $pem
     */
    public function testString(PEM $pem)
    {
        $this->assertInternalType("string", $pem->string());
    }
    
    /**
     * @depends testFromFile
     *
     * @param PEM $pem
     */
    public function testToString(PEM $pem)
    {
        $this->assertInternalType("string", strval($pem));
    }
}
