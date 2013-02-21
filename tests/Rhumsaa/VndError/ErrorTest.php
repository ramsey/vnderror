<?php
namespace Rhumsaa\VndError;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Rhumsaa\VndError\Error::__construct
     */
    public function testConstructor()
    {
        $error = new Error('1234-5678', 'This is an error message');
        $this->assertInstanceOf('Rhumsaa\VndError\Error', $error);
        $this->assertEquals('1234-5678', $error->getLogRef());
        $this->assertEquals('This is an error message', $error->getMessage());
    }

    /**
     * @covers Rhumsaa\VndError\Error::addLink
     */
    public function testAddLink()
    {
        $error = new Error('1234-5678', 'This is an error message');

        $links1 = array(
            'uri' => 'http://example.org/error/1234-5678.en-US',
            'title' => 'This is an error',
            'attributes' => array('hreflang' => 'en-US'),
        );
        $links2 = array(
            'uri' => 'http://example.org/error/1234-5678.es-MX',
            'title' => 'Este es un error',
            'attributes' => array('hreflang' => 'es-MX'),
        );

        // Assert that addLink() returns an Error Object
        $this->assertInstanceOf('Rhumsaa\VndError\Error', $error->addLink(
            'describedby',
            $links1['uri'],
            $links1['title'],
            $links1['attributes']
        ));

        $error->addLink(
            'describedby',
            $links2['uri'],
            $links2['title'],
            $links2['attributes']
        );

        $returnedLinks = $error->getLinks();

        $this->assertEquals($links1, $returnedLinks['describedby'][0]);
        $this->assertEquals($links2, $returnedLinks['describedby'][1]);
    }

    /**
     * @covers Rhumsaa\VndError\Error::getLinks
     */
    public function testGetLinks()
    {
        $error = new Error('1234-5678', 'This is an error message');
        $this->assertInternalType('array', $error->getLinks());
    }

    /**
     * @covers Rhumsaa\VndError\Error::getLogRef
     */
    public function testGetLogRef()
    {
        $error = new Error('1234-5678', 'This is an error message');
        $this->assertEquals('1234-5678', $error->getLogRef());
    }

    /**
     * @covers Rhumsaa\VndError\Error::getMessage
     */
    public function testGetMessage()
    {
        $error = new Error('1234-5678', 'This is an error message');
        $this->assertEquals('This is an error message', $error->getMessage());
    }
}
