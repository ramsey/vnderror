<?php
namespace Rhumsaa\VndError;

class VndErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var VndError
     */
    protected $object;

    /**
     * Sets up the test case
     */
    protected function setUp()
    {
        $this->object = new VndError();
        $this->errors[0] = new Error('1234-5678', 'This is an error message 1');
        $this->errors[1] = new Error('5678-1234', 'This is an error message 2');
    }

    /**
     * @covers Rhumsaa\VndError\VndError::addError
     */
    public function testAddErrorOfTypeError()
    {
        $this->assertInstanceOf('Rhumsaa\VndError\Error', $this->object->addError($this->errors[0]));
    }

    /**
     * @covers Rhumsaa\VndError\VndError::addError
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing required message
     */
    public function testAddErrorWithLogRefOnly()
    {
        $error = $this->object->addError('54321');
    }

    /**
     * @covers Rhumsaa\VndError\VndError::addError
     */
    public function testAddErrorWithLogRefAndMessage()
    {
        $this->assertInstanceOf('Rhumsaa\VndError\Error', $this->object->addError('54321', 'Message'));
    }

    /**
     * @covers Rhumsaa\VndError\VndError::getErrors
     */
    public function testGetErrors()
    {
        $this->assertInternalType('array', $this->object->getErrors());
    }

    /**
     * @covers Rhumsaa\VndError\VndError::asJson
     */
    public function testAsJson()
    {
        $this->assertInternalType('string', $this->object->asJson());
    }

    /**
     * @covers Rhumsaa\VndError\VndError::asXml
     */
    public function testAsXml()
    {
        $this->assertInternalType('string', $this->object->asXml());
    }
}
