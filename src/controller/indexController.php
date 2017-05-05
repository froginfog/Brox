<?php
namespace Controller;

class indexController extends Controller {

    function index(){
        $this->view->assign('name', '首页');
        $this->view->display('index.html');
    }

    function blog(){
        $this->view->assign('name', 'fucker');
        $this->view->display('index.html');
    }

    function asd(){
        $this->view->assign('name', 'fucker');
        $this->view->display('index.html');
    }
}