<?php namespace FullFame\Folio;

use Barryvdh\Debugbar\LaravelDebugbar;
use DebugBar\DataCollector\MessagesCollector;
use Illuminate\Support\Facades\View;

class PageRouter {

    private $pages;
    private $name;

    public function __construct()
    {
        if ( ! $this->pages) {
            $this->loadPages();
            $this->registerViewNamespaces();
        }
    }

    public function registerPageRoutes()
    {
        foreach ($this->pages as $page) {
            $controller = $page->controller;
            \Route::get($page->route, $controller .'@showPage');
        }
    }

    public function loadPages()
    {
        // @todo: Put the location of pages in a config file
        $pages_dir = app_path().'/pages';

        /* @var $file \DirectoryIterator */
        // check for Pages
        foreach (new \DirectoryIterator($pages_dir) as $file) {
            if ($file->isDir() && ! $file->isDot() && Page::isAPage($file)) {
                $this->pages[] = new Page($file);
                $this->name = $file->getBasename();
            }
        }
    }

    public function registerViewNamespaces()
    {
        $namespaces = [];
        /* @var $page FullFame\Folio\Page */
        foreach ($this->pages as $page) {
           $namespaces[$page->viewNamespace()] = $page->location();
           \View::addNamespace($page->viewNamespace(), app_path().'/pages/shweppes.page');
        }
    }

    public function pageForRoute($route)
    {
        foreach ($this->pages() as $page) {
            if ($page->route == $route)
                return $page;
        }
    }

    /*
        |--------------------------------------------------------------------------
        | GETTERS & SETTERS
        |--------------------------------------------------------------------------
        |
        | Just the getters and setters
        |
        */

        public function pages()
        {
            return $this->pages;
        }


} 