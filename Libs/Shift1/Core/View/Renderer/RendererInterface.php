<?php
namespace Shift1\Core\View\Renderer;

interface RendererInterface {

    function setVars(array $vars);

    function setTemplate($viewFile);

    function render();

}