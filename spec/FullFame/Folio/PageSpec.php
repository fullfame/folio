<?php

namespace spec\FullFame\Folio;

use Faker\Factory;
use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageSpec extends ObjectBehavior
{
    private $fake;

    function let()
    {
        // Setup our virtual filesystem
        generate_file_system_with_pages();
        // Create a directory iterator at the pages folder
        $pages_dir = new \DirectoryIterator(vfsStream::url('root/app/pages'));
        // Assure the Page object receives the DirIterator on construction
        $this->beConstructedWith($pages_dir);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('FullFame\Folio\Page');
    }

}

/*
    |--------------------------------------------------------------------------
    | FILESYSTEM HELPERS
    |--------------------------------------------------------------------------
    |
    | These helper methods will create a virtual file system to run our tests
    | against.
    |
    */

/**
 * Generates an info file and returns
 * the contents in JSON format.
 *
 * @param null $name
 * @return string
 */
function generate_info_file($name = null)
{
    $fake = Factory::create();

    $name = $name?: $fake->word;

    $info = new \stdClass();

    $info->name     = $name;
    $info->title    = $fake->sentence();
    $info->route    = '/'.$name;

    return json_encode($info);
}

/**
 * Generates a Page structure formatted as an array
 * for use with the virtual filesystem.
 *
 * @return array
 */
function generate_page()
{
    $fake = Factory::create();
    $name = $fake->word;
    $dir  = $name.'.page';
    return [
        $dir => [
            'meta' => [
                'info.json' => generate_info_file($name)
            ]
        ]
    ];
}

/**
 * Create the filesystem structure containing the
 * given number of pages.
 *
 * @param int $nr_of_pages
 */
function generate_file_system_with_pages($nr_of_pages = 3)
{
    $structure = [
        'app'   => [
            'pages' => []
        ]
    ];
    for ($i = 0; $i < $nr_of_pages; $i++) {
        $structure['app']['pages'] = array_merge(
                $structure['app']['pages'],
                generate_page()
            );
    }

//    dd($structure);
    vfsStream::setup('root', null, $structure);
}