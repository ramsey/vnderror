<?php
namespace Ramsey\VndError;

class VndErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Ramsey\VndError\VndError::__construct
     * @covers Ramsey\VndError\VndError::getData
     * @covers Ramsey\VndError\VndError::getMessage
     */
    public function testVndErrorWithMessageOnly()
    {
        $vnderror = new VndError('Validation failed');

        $this->assertEquals('Validation failed', $vnderror->getMessage());
        $this->assertEquals(
            array('message' => 'Validation failed'),
            json_decode($vnderror->asJson(), true)
        );
    }

    /**
     * @covers Ramsey\VndError\VndError::__construct
     * @covers Ramsey\VndError\VndError::getData
     * @covers Ramsey\VndError\VndError::getMessage
     * @covers Ramsey\VndError\VndError::getLogref
     */
    public function testVndErrorWithMessageAndLogref()
    {
        $vnderror = new VndError('Validation failed', 12345);

        $this->assertEquals('Validation failed', $vnderror->getMessage());
        $this->assertEquals(12345, $vnderror->getLogref());
        $this->assertEquals(
            array('logref' => 12345, 'message' => 'Validation failed'),
            json_decode($vnderror->asJson(), true)
        );
    }

    /**
     * @covers Ramsey\VndError\VndError::__construct
     * @covers Ramsey\VndError\VndError::getData
     * @covers Ramsey\VndError\VndError::getMessage
     * @covers Ramsey\VndError\VndError::getLogref
     * @covers Ramsey\VndError\VndError::setMessage
     * @covers Ramsey\VndError\VndError::setLogref
     */
    public function testVndErrorWithLinks()
    {
        $vnderror = new VndError('Validation failed', 12345);
        $vnderror->addLink('help', 'http://.../', array('title' => 'Error Information'));
        $vnderror->addLink('describes', 'http://.../', array('title' => 'Error Description'));

        $this->assertNull($vnderror->setMessage('New validation failed'));
        $this->assertNull($vnderror->setLogref('foo-id'));
        $this->assertEquals('New validation failed', $vnderror->getMessage());
        $this->assertEquals('foo-id', $vnderror->getLogref());
        $this->assertEquals(
            array(
                'logref' => 'foo-id',
                'message' => 'New validation failed',
                '_links' => array(
                    'help' => array(
                        'href' => 'http://.../',
                        'title' => 'Error Information',
                    ),
                    'describes' => array(
                        'href' => 'http://.../',
                        'title' => 'Error Description',
                    ),
                ),
            ),
            json_decode($vnderror->asJson(), true)
        );

        $sxe = new \SimpleXMLElement($vnderror->asXml());

        $this->assertInstanceOf('SimpleXMLElement', $sxe);
        $this->assertEquals('New validation failed', $sxe->message);
        $this->assertEquals('foo-id', $sxe['logref']);

        $this->assertEquals('help', $sxe->link[0]['rel']);
        $this->assertEquals('http://.../', $sxe->link[0]['href']);
        $this->assertEquals('Error Information', $sxe->link[0]['title']);

        $this->assertEquals('describes', $sxe->link[1]['rel']);
        $this->assertEquals('http://.../', $sxe->link[1]['href']);
        $this->assertEquals('Error Description', $sxe->link[1]['title']);
    }

    /**
     * Test for Issue #4
     * @link https://github.com/ramsey/vnderror/issues/4
     */
    public function testFromJson()
    {
        $sample = <<<JSON
{
    "message": "New validation failed",
    "logref": "foo-id",
    "_links": {
        "help": {
            "href": "http:\/\/...\/",
            "title": "Error Information"
        },
        "describes": {
            "href": "http:\/\/...\/",
            "title": "Error Description"
        }
    }
}
JSON;

        $vndError = VndError::fromJson($sample);

        $this->assertInstanceOf('Ramsey\\VndError\\VndError', $vndError);
    }

    /**
     * Test for Issue #4
     * @link https://github.com/ramsey/vnderror/issues/4
     */
    public function testFromXml()
    {
        $sample = <<<XML
<?xml version="1.0"?>
<resource logref="foo-id">
    <link rel="help" href="http://.../" title="Error Information"/>
    <link rel="describes" href="http://.../" title="Error Description"/>
    <message>New validation failed</message>
</resource>
XML;

        $vndError = VndError::fromXml($sample);

        $this->assertInstanceOf('Ramsey\\VndError\\VndError', $vndError);
    }
}
