<?php namespace FullFame\Folio;

use Illuminate\Support\Facades\View;

class PageRouter {

    private $pages;

    public function __construct()
    {
        $this->loadPages();
        $this->registerViewNamespaces();
    }

    public function registerPageRoutes()
    {
        foreach ($this->pages as $page) {
            \Route::get($page->route, $page->controller.'@showPage');
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


} 