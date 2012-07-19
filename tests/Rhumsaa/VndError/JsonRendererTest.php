<?php
namespace Rhumsaa\VndError;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
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

        $errors[0] = new Error('1234-5678', 'This is an error message 1');
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
            'Find more help here'
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
     * @covers Rhumsaa\VndError\JsonRenderer::render
     * @covers Rhumsaa\VndError\JsonRenderer::buildArrayForJson
     * @covers Rhumsaa\VndError\JsonRenderer::buildLinksForJson
     */
    public function testRender()
    {
        $expectedJson = '[{"logref":"1234-5678","message":"This is an error message 1","_links":{"describedby":[{"href":"http:\/\/example.org\/error\/1234-5678.en-US","title":"This is an error","hreflang":"en-US"},{"href":"http:\/\/example.org\/error\/1234-5678.en-MX","title":"Este es un error","hreflang":"es-MX"}],"help":[{"href":"http:\/\/example.org\/help","title":"Find more help here"}]}},{"logref":"5678-1234","message":"This is an error message 2","_links":{"describedby":[{"href":"http:\/\/example.org\/error\/5678-1234","title":"This is an error","hreflang":"en-US"}]}}]';

        $renderer = new JsonRenderer();
        $this->assertEquals($expectedJson, $renderer->render($this->vndError));
    }

    /**
     * @covers Rhumsaa\VndError\JsonRenderer::render
     */
    public function testRenderWithPrettyPrint()
    {
        if (!version_compare(PHP_VERSION, '5.4.0', '>=')) {
            $this->markTestSkipped('Running a version of PHP earlier than 5.4');
        }

        $expectedJson = <<<EOD
[
    {
        "logref": "1234-5678",
        "message": "This is an error message 1",
        "_links": {
            "describedby": [
                {
                    "href": "http://example.org/error/1234-5678.en-US",
                    "title": "This is an error",
                    "hreflang": "en-US"
                },
                {
                    "href": "http://example.org/error/1234-5678.en-MX",
                    "title": "Este es un error",
                    "hreflang": "es-MX"
                }
            ],
            "help": [
                {
                    "href": "http://example.org/help",
                    "title": "Find more help here"
                }
            ]
        }
    },
    {
        "logref": "5678-1234",
        "message": "This is an error message 2",
        "_links": {
            "describedby": [
                {
                    "href": "http://example.org/error/5678-1234",
                    "title": "This is an error",
                    "hreflang": "en-US"
                }
            ]
        }
    }
]
EOD;

        $renderer = new JsonRenderer();
        $this->assertEquals($expectedJson, $renderer->render($this->vndError, true));
    }
}
