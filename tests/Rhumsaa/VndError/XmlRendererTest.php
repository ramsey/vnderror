<?php
namespace Rhumsaa\VndError;

class XmlRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VndError
     */
    protected $vndError;

    /**
     * Sets up the test case
     */
    protected function setUp()
    {
        $errors = array();

        $errors[0] = new Error('1234-5678', 'This is an "error" message 1');
        $errors[0]->addLink(
            'describedby',
            'http://example.org/error/1234-5678.en-US',
            'This is an error',
            array('hreflang' => 'en-US')
        );
        $errors[0]->addLink(
            'describedby',
            'http://example.org/error/1234-5678.en-MX',
            'Este es un error',
            array('hreflang' => 'es-MX')
        );
        $errors[0]->addLink(
            'help',
            'http://example.org/help',
            'Find more "help" here'
        );

        $errors[1] = new Error('5678-1234', 'This is an error message 2');
        $errors[1]->addLink(
            'describedby',
            'http://example.org/error/5678-1234',
            'This is an error',
            array('hreflang' => 'en-US')
        );

        $this->vndError = new VndError();
        $this->vndError->addError($errors[0]);
        $this->vndError->addError($errors[1]);
    }

    /**
     * @covers Rhumsaa\VndError\XmlRenderer::render
     * @covers Rhumsaa\VndError\XmlRenderer::getDomForError
     */
    public function testRender()
    {
        $expectedXml = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<errors><error logref="1234-5678"><message>This is an "error" message 1</message><link rel="describedby" href="http://example.org/error/1234-5678.en-US" title="This is an error" hreflang="en-US"/><link rel="describedby" href="http://example.org/error/1234-5678.en-MX" title="Este es un error" hreflang="es-MX"/><link rel="help" href="http://example.org/help" title="Find more &quot;help&quot; here"/></error><error logref="5678-1234"><message>This is an error message 2</message><link rel="describedby" href="http://example.org/error/5678-1234" title="This is an error" hreflang="en-US"/></error></errors>

EOD;

        $renderer = new XmlRenderer();
        $this->assertEquals($expectedXml, $renderer->render($this->vndError));
    }

    /**
     * @covers Rhumsaa\VndError\XmlRenderer::render
     */
    public function testRenderWithPrettyPrint()
    {
        $expectedXml = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<errors>
  <error logref="1234-5678">
    <message>This is an "error" message 1</message>
    <link rel="describedby" href="http://example.org/error/1234-5678.en-US" title="This is an error" hreflang="en-US"/>
    <link rel="describedby" href="http://example.org/error/1234-5678.en-MX" title="Este es un error" hreflang="es-MX"/>
    <link rel="help" href="http://example.org/help" title="Find more &quot;help&quot; here"/>
  </error>
  <error logref="5678-1234">
    <message>This is an error message 2</message>
    <link rel="describedby" href="http://example.org/error/5678-1234" title="This is an error" hreflang="en-US"/>
  </error>
</errors>

EOD;

        $renderer = new XmlRenderer();
        $this->assertEquals($expectedXml, $renderer->render($this->vndError, true));
    }
}
