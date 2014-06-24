<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 23/06/14
 * Time: 17:14
 */

namespace FullFame\Folio;


class Page {

    private $page_location;
    private $meta_data = [];
    private $properties = [];

    private $paths = [
        'properties'    => '/meta/info.json',
        'html_meta'     => '/meta/html.json',
        'facebook'      => '/meta/facebook.json'
    ];

    private $property_defaults = [
        'controller'    => 'PageController'
    ];

    public function __construct(\DirectoryIterator $page_dir)
    {
        $this->loadpage($page_dir);
    }

    public static function isAPage( \DirectoryIterator $file)
    {
        $exploded_name = explode('.', $file->getBasename());

        if (count($exploded_name) > 1 && ($extenstion = array_pop($exploded_name)) == 'page' && $file->isDir()) {
            return true;
        }
        return false;
    }

    public function isValidPage($page)
    {
        return
            $this->isAPage();
    }

    public function meetsPageRequirements()
    {
        return
            file_exists($this->paths['properties'])
            && $this->hasRequiredProperties();
    }

    public function loadPage(\DirectoryIterator $page){

        // Load the meta data from json
        $this->registerPaths($page);
        $this->loadFromJson('properties');
        $this->properties->name = $page->getBasename('.page');
    }

    private function registerPaths(\DirectoryIterator $page)
    {
        foreach ($this->paths as &$path) {
            $path = $page->getPathname().$path;
        }
        $this->page_location = $page->getPathname();
    }

    private function hasRequiredProperties()
    {
        return
            property_exists($this->properties, 'route');
    }

    private function loadFromJson($data_type)
    {
        if (array_key_exists($data_type, $this->paths)) {
            if (file_exists($this->paths[$data_type])) {
                $data = file_get_contents($this->paths[$data_type]);
                if (trim($data)) {
                    $properties = json_decode($data);
                    $this->properties = $properties;
                    if (! $this->hasRequiredProperties()) {
                        throw new \Exception('Missing required properties in: "'.$this->page_location.'".');
                    }
                } else {
                    throw new \Exception('No data found in '.$data_type.'-file "'.$this->paths[$data_type].'".');
                }
            } else {
                throw new \Exception('No '.$data_type.'-file found, should have been at "'.$this->paths[$data_type].'".');

            }
        } else {
            throw new \Exception('There is no path configured for this data type "'.$data_type.'".');
        }
    }

    public function viewNamespace()
    {
        return $this->name;
    }

    /*
        |--------------------------------------------------------------------------
        | GETTERS & SETTERS
        |--------------------------------------------------------------------------
        |
        |
        */
    public function location()
    {
        return $this->page_location;
    }

    /*
        |--------------------------------------------------------------------------
        | MAGIC METHODS
        |--------------------------------------------------------------------------
        |
        | Magic methods for getting properties and meta data
        |
        */

    public function __get($property_name)
    {
        if (property_exists($this->properties, $property_name)) {
            return $this->properties->$property_name;
        }
        if (array_key_exists($property_name, $this->property_defaults)) {
            return $this->property_defaults[$property_name];
        }
        return null;
    }
} 