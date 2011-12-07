<?php
namespace Shift1\Core\Request;

use Shift1\Core\Router\iRouter;
class HttpRequest extends AbstractRequest {

    protected $router = null;

    protected $server = array();
    protected $files = array();
    protected $post = array();
    protected $get = array();
    protected $cookie = array();
    protected $env = array();
    protected $controller = array();

    public function __construct(iRouter $router) {

        $this->router = $router;
        $this->setServer($_SERVER);
        $this->setCookie($_COOKIE);
        $this->setEnv($_ENV);
        $this->setFiles($_FILES);
        $this->setGet($_GET);
        $this->setPost($_POST);

    }

    public function assembleController() {
        $this->controller = $this->getRouter()->resolveUri($this->getProjectUri());
        return $this->controller;
    }

    public function getRouter() {
        return $this->router;
    }

    public function isAjaxRequest() {
        /**
         * return x_requested_with || $_POST[is_ajax] ....
         */
    }

    public function getDomain() {
        return (!empty($this->server['SERVER_NAME']))
                ? $this->server['SERVER_NAME']
                : $this->server['HTTP_HOST'];
    }

    public function getRequestUri() {
        return $this->server['REQUEST_URI'];
    }

    public function getProjectUri() {
        return \str_replace($this->getApp()->getConfig()->route->appWebRoot, '', $this->getDomain() . $this->getRequestUri());
    }


    public function setController($controller) {
        $this->controller = $controller;
    }

    public function getController() {
        return $this->controller;
    }

    public function setCookie($cookie) {
        $this->cookie = $cookie;
    }

    public function getCookie() {
        return $this->cookie;
    }

    public function setEnv($env) {
        $this->env = $env;
    }

    public function getEnv() {
        return $this->env;
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setGet($get) {
        $this->get = $get;
    }

    public function getGet() {
        return $this->get;
    }

    public function setPost($post) {
        $this->post = $post;
    }

    public function getPost() {
        return $this->post;
    }

    public function setServer($server) {
        $this->server = $server;
    }

    public function getServer() {
        return $this->server;
    }

}