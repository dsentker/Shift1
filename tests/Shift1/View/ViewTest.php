<?php
namespace Shift1Test\View;

use Shift1\Core\View\View;
use Shift1\Core\InternalFilePath;
use Shift1\Core\App;

class ViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Shift1\Core\View\View
     */
    protected $view;

    public function setUp() {
        $this->view = new View();
    }

    public function tearDown() {
        unset($this->view);
    }

    public function testImplementsInterface() {
        $this->assertInstanceOf('\Shift1\Core\View\iView', $this->view);
    }

    public function testFileExistString() {
        $this->assertTrue($this->view->fileExists('Shift1/View/ViewFiles/index'));
        $this->assertTrue($this->view->fileExists('Shift1/View/ViewFiles/index.php'));
        $this->assertFalse($this->view->fileExists('Shift1/View/ViewFiles/'));
        $this->assertFalse($this->view->fileExists(''));
        $this->assertFalse($this->view->fileExists('.php'));
    }


    public function testStaticNewInstance() {
        $view = View::instance('Shift1/View/ViewFiles/index');
        $this->assertInstanceOf('\Shift1\Core\View\View', $view);
        $this->assertNotSame($view, $this->view);
    }

    public function testConstructorStrictParam() {

        $view = new View(null, true, null);
        $this->assertTrue($view->isStrict());
        unset($view);

        $view = new View(null, false, null);
        $this->assertFalse($view->isStrict());
        unset($view);

        App::getInstance()->getConfig()->view->strict = false;
        $view = new View(null, null, null);
        $this->assertFalse($view->isStrict());
        unset($view);

        App::getInstance()->getConfig()->view->strict = true;
        $view = new View(null, null, null);
        $this->assertTrue($view->isStrict());
        unset($view);

    }

    public function testAssignmentsString() {

        $this->view->setStrict(false);
        $this->assertNull($this->view->get('iDoNotExist'));

        $this->view->setStrict(true);
        $this->setExpectedException('\PHPUnit_Framework_Error_Notice');
        $this->assertNull($this->view->get('iDoNotExist'));

        $this->view->assign('foo', 'bar');
        $this->assertEquals('bar', $this->view->get('foo'));

        $this->view->assign('foo', 'baz');
        $this->assertEquals('baz', $this->view->get('foo'));

        $this->view->assign('foo', 'newFoo', false);
        $this->assertNotEquals('newFoo', $this->view->get('foo'));
        $this->assertEquals('baz', $this->view->get('foo'));

        $this->setExpectedException('\Shift1\Core\Exceptions\ViewException');
        $this->view->assign('', 'value');

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
        $this->view->setViewFile('Shift1/View/ViewFiles/TinyView', false);
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

        $this->view->setViewFile('Shift1/View/ViewFiles/EchoVar', false);
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


}