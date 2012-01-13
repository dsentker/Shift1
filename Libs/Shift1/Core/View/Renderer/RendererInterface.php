<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\View\ViewInterface;

interface RendererInterface {

    function render(ViewInterface $view);

    function getName();

}