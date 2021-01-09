<?php

namespace GX2CMSJoomla\Page;

use Ezpizee\Utils\ListModel;
use GX2CMSJoomla\Exception\Error;
use JsonSerializable;

class Pages implements JsonSerializable
{
    /**
     * @var array
     */
    private $children = [];

    public function __construct(array $children)
    {
        $this->loadChildren($children);
    }

    public function getChild(string $path): Page
    {
        return isset($this->children[$path]) && $this->children[$path] instanceof Page ? $this->children[$path] : new Page(new Properties([]));
    }

    public function addChild(Page $page, string $path)
    {
        $this->children[$path] = $page;
    }

    public function hasChildren(): bool {return !empty($this->children);}

    public function getChildren(): array {return $this->jsonSerialize();}

    public function jsonSerialize(): array {return $this->children;}

    public function __toString() {return json_encode($this->jsonSerialize());}

    private function loadChildren(array $children): void
    {
        if (!empty($children))
        {
            foreach ($children as $child)
            {
                $path = '';
                if ($child instanceof Page) {
                    $path = $child->getPath();
                }
                else if ($child instanceof Properties) {
                    $path = $child->get('path');
                    $this->children[$path] = new Page($child, $path);
                }
                else if ($child instanceof ListModel) {
                    $path = $child->get('path');
                    $child = new Page(new Properties($child->getAsArray()), $path);
                }
                else if (is_array($child)) {
                    $path = $child['path'];
                    $child = new Page(new Properties($child), $path);
                }
                if (empty($path)) {
                    new Error('Missing path for: '.json_encode($child), 500);
                }
                $this->children[$path] = $child;
            }
        }
    }
}