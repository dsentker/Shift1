<?php
namespace Shift1Test\View;

use Shift1\Core\View\View;

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

    public function testFileExistChecking() {
        $this->assertInstanceOf('\Shift1\Core\View\iView', $this->view);
        $this->assertTrue($this->view->fileExists('Shift1/View/ViewFiles/index'));
        $this->assertTrue($this->view->fileExists('Shift1/View/ViewFiles/index.php'));
        $this->assertFalse($this->view->fileExists('Shift1/View/ViewFiles/'));
    }

}