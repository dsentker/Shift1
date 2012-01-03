<?php
namespace Shift1Test\Config\File;

use Shift1\Core\Config\File\ArrayFile;

class ArrayFileTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Shift1\Core\Config\File\ArrayFile
     */
    protected $arrayFile;

    public function setUp() {
        $this->arrayFile = new ArrayFile(BASEPATH . '/Shift1/Config/File/Resources/_testArrayFile.php');
    }

    public function testInterface() {
        $this->assertInstanceOf('\Shift1\Core\Config\File\ConfigFileInterface', $this->arrayFile);
    }

    public function testAsArray() {
        $asArray = $this->arrayFile->toArray();
        $this->assertInternalType('array', $asArray);
        $this->assertArrayHasKey('cars', $asArray);
        $this->assertArrayHasKey('stuff', $asArray);
    }

    public function testAsArrayObject() {
        $asObject = $this->arrayFile->toArrayObject();
        $this->assertInstanceOf('\ArrayObject', $asObject);
        $this->assertTrue(\property_exists($asObject, 'cars'));
        $this->assertInstanceOf('\ArrayObject', $asObject->cars);
    }

    public function testNested() {
        $asObject = $this->arrayFile->toArrayObject();
        $this->assertTrue(\property_exists($asObject->cars, 'vw'));
        $this->assertEquals('t5', $asObject->cars->vw[2]);

        $this->assertEquals(3, count($asObject->stuff));
        $this->assertEquals(1, count($asObject->stuff->array));
        $this->assertEquals(0, count($asObject->stuff->array->anotherArray));
    }
}
