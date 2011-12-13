<?php
namespace Shift1\Core\Request;

use Shift1\Core\Router\iRouter;

class HttpRequest extends AbstractRequest {

    /**
     * @var null|\Shift1\Core\Router\iRouter
     */
    protected $router = null;

    /**
     * @var array
     */
    protected $server = array();

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @var array
     */
    protected $post = array();

    /**
     * @var array
     */
    protected $get = array();

    /**
     * @var array
     */
    protected $cookie = array();

    /**
     * @var array
     */
    protected $env = array();

    /**
     * @var array
     */
    protected $controller = array();

    /**
     * @param \Shift1\Core\Router\iRouter $router
     */
    public function __construct(iRouter $router) {

        $this->router = $router;
        $this->setServer($_SERVER);
        $this->setCookie($_COOKIE);
        $this->setEnv($_ENV);
        $this->setFiles($_FILES);
        $this->setGet($_GET);
        $this->setPost($_POST);
    }

    /**
     * @return array
     */
    public function assembleController() {
        $this->setController($this->getRouter()->resolveUri($this->getProjectUri()));
        return $this->getController();
    }

    /**
     * @return null|\Shift1\Core\Router\iRouter
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @TODO Implement this method
     * @return void
     */
    public function isAjaxRequest() {
        /**
         * return x_requested_with || $_POST[is_ajax] ....
         */
    }

    /**
     * @return string
     */
    public function getDomain() {
        return (!empty($this->server['SERVER_NAME']))
                ? $this->server['SERVER_NAME']
                : $this->server['HTTP_HOST'];
    }

    /**
     * @return string
     */
    public function getRequestUri() {
        return $this->server['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getProjectUri() {
        return \str_replace($this->getApp()->getConfig()->route->appWebRoot, '', $this->getDomain() . $this->getRequestUri());
    }

    /**
     * @param array $controller
     * @return void
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * @return array
     */
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