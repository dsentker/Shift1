<?php
namespace Shift1Test\View;

use Shift1\Core\View\View;
use Shift1\Core\InternalFilePath;
use Shift1\Core\App;
use Shift1\Core\Exceptions\ViewException;

class ViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Shift1\Core\View\View
     */
    protected $view;

    public function setUp() {
        App::getInstance()->getConfig()->filesystem->defaultViewFolder = 'Shift1/View/ViewFiles';
        $this->view = new View();
    }

    public function tearDown() {
        unset($this->view);
    }

    public function testImplementsInterface() {
        $this->assertInstanceOf('\Shift1\Core\View\iView', $this->view);
    }

    public function testFileExistString() {

        $this->assertTrue($this->view->fileExists('Index'));
        $this->assertTrue($this->view->fileExists('Index.php'));

        $this->assertFalse($this->view->fileExists('iDoNotExist.php'));
        $this->assertFalse($this->view->fileExists('/'));
        $this->assertFalse($this->view->fileExists('../'));
        $this->assertFalse($this->view->fileExists(''));
        $this->assertFalse($this->view->fileExists('.php'));
    }


    public function testStaticNewInstance() {
        $view = View::instance('index');
        $this->assertInstanceOf('\Shift1\Core\View\View', $view);
        $this->assertNotSame($view, $this->view);
    }

    /**
     * @dataProvider constructorStrictParamProvider
     */
    public function testConstructorStrictParam($strict, $setConfigStrict = null) {


        if(null !== $setConfigStrict) {
            App::getInstance()->getConfig()->view->strict = $setConfigStrict;
            $exceptedIsStrict = $setConfigStrict;
        } else {
            $exceptedIsStrict = $strict;
        }

        $view = new View(null, $strict, null);
        $this->assertEquals($view->isStrict(), $exceptedIsStrict);

        unset($view);
    }

    public function testAssignmentsString() {

        $this->view->setStrict(false);
        $this->assertNull($this->view->get('iDoNotExist'));

        $this->view->assign('foo', 'bar');
        $this->assertEquals('bar', $this->view->get('foo'));

        $this->view->assign('foo', 'baz');
        $this->assertEquals('baz', $this->view->get('foo'));

        $this->view->assign('foo', 'newFoo', false);
        $this->assertNotEquals('newFoo', $this->view->get('foo'));
        $this->assertEquals('baz', $this->view->get('foo'));
    }

    public function testEmptyAssignment() {
        try {
            $this->view->assign(' ', 'value');
        } catch(\Shift1\Core\Exceptions\ViewException $e) {
            return;
        }
        $this->fail('Expected ViewException was not thrown');
    }

    public function testGetValueStrict() {
        $this->view->setStrict(true);
        try {
            $this->view->get('iDoNotExist');
        } catch(\PHPUnit_Framework_Error_Notice $e) {
            return;
        }
        $this->fail('Expected Notice was not returned');
    }

    public function testCloning() {

        $this->view->assign('foo', 'bar');
        $this->view->setViewFile('index');

        $viewClone = clone $this->view;

        $this->assertNotSame($viewClone, $this->view);
        $this->assertEquals($viewClone->foo, 'bar');
        $this->assertEquals($viewClone->foo, $this->view->foo);

        $this->assertEquals($viewClone->getViewFile(), $this->view->getViewFile());

        $viewClone2 = $viewClone->newSelf('EmptyFile');
        $this->assertNotEquals($viewClone->getViewFile(), $viewClone2->getViewFile());
    }

    public function testAssignmentsMixed() {
        $anArray = array('foo', 'bar', 'baz');
        $this->view->anArray = $anArray;
        $this->assertEquals($this->view->get('anArray'), $anArray);
        $this->assertEquals($this->view->anArray,        $anArray);

        $anObject = new \StdClass();
        $anObject->foo = 'bar';

        $this->view->anObject = $anObject;
        $this->assertEquals($this->view->get('anObject'), $anObject);
        $this->assertEquals($this->view->anObject,        $anObject);
        $this->assertEquals($this->view->anObject->foo,   'bar');

    }

    public function testAssignmentsOverloading() {

        $this->view->setStrict(false);

        $this->assertNull($this->view->iDoNotExist);

        $this->view->foo = 'bar';
        $this->assertEquals('bar', $this->view->foo);
        $this->assertEquals('bar', $this->view->get('foo'));

        $this->assertTrue(isset($this->view->foo));
        $this->assertFalse(empty($this->view->foo));

        $this->assertTrue($this->view->removeVar('foo'));
        $this->assertNull($this->view->foo);
        $this->assertFalse(isset($this->view->foo));
        $this->assertTrue(empty($this->view->foo));

    }

    public function testRenderingSimple() {
        $this->view->setViewFile('TinyView');
        $this->assertEquals('This is a  test!', $this->view->render());

        $this->view->key = 'nice';
        $this->assertEquals('This is a nice test!', $this->view->render());

        $this->view->key = '';
        $this->assertEquals('This is a  test!', $this->view->render());

        $this->view->key = ' ';
        $this->assertEquals('This is a   test!', $this->view->render());

        $this->view->key = false;
        $this->assertEquals('This is a  test!', $this->view->render());

        $this->view->key = array(1,2,3);
        $this->assertEquals('This is a Array test!', $this->view->render());

        $this->view->setViewFile('EchoVar');
        $this->view->assign('key', 'value');
        $this->assertEquals('value', $this->view->render());

        $this->view->key .= ' anotherValue';
        $this->assertEquals('value anotherValue', $this->view->render());

        $viewVars = $this->view->getViewVars();
        $this->assertArrayHasKey('key', $viewVars);
        unset($viewVars);

        // prefixed
        $viewVars = $this->view->getViewVars(true);
        $this->assertArrayHasKey(View::VAR_KEY_PREFIX . 'key', $viewVars);
        unset($viewVars);

        $this->view->clearVars();
        $viewVars = $this->view->getViewVars();
        $this->assertArrayNotHasKey('key', $viewVars);
        unset($viewVars);


    }

    public function testRenderingExtended() {

        $this->view->setViewFile('TinyViewWrapped');
        $this->assertEquals('This is a  test!', $this->view->render());

        $this->view->setViewFile('EmptyFile');
        $this->view->foo = 'bar';
        $this->assertEmpty($this->view->render());

        $this->view->wrappedBy($this->view->newSelf('TinyView'));
        $this->assertTrue($this->view->hasWrapper());
        $this->assertEquals('This is a  test!', $this->view->render());
        $this->assertInstanceOf('\Shift1\Core\View\iView', $this->view->getWrapper());
    }

    public function testCommon() {
        $this->view->foo = 'bar';
        $this->assertTrue($this->view->has('foo'));
        $this->assertFalse($this->view->has('bar'));
        $this->assertFalse($this->view->has(''));
        $this->assertFalse($this->view->has(null));
    }

    public function testGetContent() {

        try {
            $this->view->setViewFile('');
            $this->view->getContent();
        } catch(ViewException $e) {
            return;
        }

        $this->fail('Expected ViewException while given an empty view file');

    }

    public function testEmptyFile() {
        $this->view->setViewFile('EmptyFile');
        $this->assertEmpty($this->view->render());
    }


    public function constructorStrictParamProvider() {
        return array(
            array(true,  null),
            array(false, null),
            array(null,  true),
            array(null,  false),
            array(null,  null),
        );
    }

}