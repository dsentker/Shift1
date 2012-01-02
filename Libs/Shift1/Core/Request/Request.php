<?php
namespace Shift1\Core\Request;

use Shift1\Core\FrontController;

class Request extends AbstractRequest {

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
     * @static
     * @return Request
     */
    public static function fromGlobals() {
        $req = new static;
        $req->setServer($_SERVER);
        $req->setCookie($_COOKIE);
        $req->setEnv($_ENV);
        $req->setFiles($_FILES);
        $req->setGet($_GET);
        $req->setPost($_POST);
        return $req;
    }

    /**
    /**
     * @TODO Implement this method
     * @return void
     */
    public function isAjaxRequest() {
        /**
         * @TODO
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
    public function getProjectUri($appWebRoot) {
        return \str_replace($appWebRoot, '', $this->getDomain() . $this->getRequestUri());
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