<?php
/**
 * This class is responsible for keeping track of what pages are available and their basic info,
 * with a minimum of overhead.
 * User: jonas
 * Date: 26/06/14
 * Time: 11:40
 */

namespace FullFame\Folio;


class PageController extends \BaseController implements pageRenderer{

    private $page;

    public function __construct(PageRouter $router)
    {
        $route = '/'.\Route::getCurrentRoute()->getPath();
        $this->page = $router->pageForRoute($route);
    }

    public function showPage()
    {
        return \View::make(
            $this->page->viewNameSpace().'::content'
        )->with('page', $this->page);

    }
}

interface pageRenderer {
    public function showPage();
}