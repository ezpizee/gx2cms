<?php

namespace GX2CMSJoomla\Section;

use Ezpizee\Utils\ListModel;
use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Properties;
use JsonSerializable;

class Sections implements JsonSerializable
{
    /**
     * @var array
     */
    private $children = [];

    public function __construct(array $children)
    {
        $this->loadChildren($children);
    }

    public function getChild(string $path): Section
    {
        return isset($this->children[$path]) && $this->children[$path] instanceof Section
            ? $this->children[$path] : new Section(new Properties([]));
    }

    public function addChild(Section $page, string $path)
    {
        $this->children[$path] = $page;
    }

    public function hasChildren(): bool {return !empty($this->children);}

    public function getChildren(): array {return $this->jsonSerialize();}

    public function jsonSerialize(): array {return $this->children;}

    public function toArray(): array {return json_decode(json_encode($this->jsonSerialize()), true);}

    public function __toString() {return json_encode($this->jsonSerialize());}

    private function loadChildren(array $children): void
    {
        if (!empty($children))
        {
            foreach ($children as $child)
            {
                $path = '';
                if ($child instanceof Section) {
                    $path = $child->getPath();
                }
                else if ($child instanceof Properties) {
                    $path = $child->get('path');
                    $this->children[$path] = new Section($child, $path);
                }
                else if ($child instanceof ListModel) {
                    $path = $child->get('path');
                    $child = new Section(new Properties($child->getAsArray()), $path);
                }
                else if (is_array($child)) {
                    $path = $child['path'];
                    $child = new Section(new Properties($child), $path);
                }
                if (empty($path)) {
                    new Error('Missing path for: '.json_encode($child), 500);
                }
                $this->children[$path] = $child;
            }
        }
    }
}