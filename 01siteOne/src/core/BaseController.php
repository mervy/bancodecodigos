<?php

namespace Core;

abstract class BaseController
{

    protected $view;
    protected $errors;
    protected $success;
    protected $auth;
    private $viewPath;
    private $layoutPath;
    private $pageTitle = null;
    private $siteName = null;
    private $pageAlias; //Para destacar os menus
    private $siteAlias; //Para personalizar o menu de css e javascript

    public function __construct()
    {
        $this->view = new \stdClass;
        $this->auth = new Auth;

        if (@Session::get('success')) {
            $this->success = Session::get('success');
            Session::destroy('success');
        }
        if (@Session::get('errors')) {
            $this->errors = Session::get('errors');
            Session::destroy('errors');
        }
    }

    /**
     * 
     * @param type $viewPath - caminho da view
     * @param type $layoutPath - caminho do layout base
     * Uso:  $this->renderView('home/index','layout');
     */
    protected function renderView($viewPath, $layoutPath = null)
    {
        $this->viewPath = $viewPath;
        $this->layoutPath = $layoutPath;
        if ($layoutPath) {
            return $this->layout();
        } else {
            return $this->content();
        }
    }

    /**
     * Colocar num arquivo html como *cabeçalho* - $this->content(); - *footer*
     */
    protected function content()
    {
        if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.phtml")) {
            require_once __DIR__ . "/../app/Views/{$this->viewPath}.phtml";
        } else {
            echo "Error: View path não encontrada!";
        }
    }

    protected function layout()
    {
        if (file_exists(__DIR__ . "/../app/Views/{$this->layoutPath}.phtml")) {
            require_once __DIR__ . "/../app/Views/{$this->layoutPath}.phtml";
        } else {
            echo "Error: View path não encontrada!";
        }
    }

    /**
     * $pageAlias deve ser definida como home, blog, contato, etc
     */
    protected function setPageTitle($pageTitle, $pageAlias = null)
    {
        $this->pageTitle = $pageTitle;
        $this->pageAlias = $pageAlias;
    }

    protected function getPageTitle($separator = null)   
    {        
        if ($separator) {
            return $this->pageTitle . " " . $separator . " ";
        } else {
            return $this->pageTitle . " ";
        }
        
    }
    protected function getPageAlias()
    {
        return $this->pageAlias;
    }
    /**
     * Usar $siteAlias como artigos, admin, comentarios, etc
     */
    protected function setSiteName($siteName, $siteAlias = null)
    {
        $this->siteName = $siteName;
        $this->siteAlias = $siteAlias;
    }

    protected function getSiteName()
    {
        return $this->siteName;
    }   

    protected function getSiteAlias()
    {
        return $this->siteAlias;
    }   

    public function forbidden()
    {
        return Redirect::route('/login');
    }

}