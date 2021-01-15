<?php

namespace GX2CMSJoomla\Page;

use GX2CMSJoomla\Constants;
use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Properties;
use GX2CMSJoomla\Scanner;
use GX2CMSJoomla\Utils\GX2CMSDate;
use JsonSerializable;

class Page implements JsonSerializable
{
    /**
     * @var Properties
     */
    private $properties;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var Pages
     */
    private $children;

    /**
     * @var bool
     */
    private $hasChild = false;

    /**
     * @var bool
     */
    private $isInvalid = true;

    /**
     * @var string
     */
    private $content = '';

    public function __construct(Properties $properties, string $path = '')
    {
        $this->init($properties, $path);
    }

    public function addChild(Page $page, string $path): void
    {
        if ($this->children instanceof Pages) {
            $this->children->addChild($page, $path);
        }
    }

    public function isInvalid(): bool {return $this->isInvalid;}

    public function get($key, $default=null)
    {
        return $this->properties->get($key, $default);
    }

    public function hasChild(): bool {return $this->hasChild;}

    public function getChildren(): Pages {return $this->children;}

    public function getFSFile(): string {return $this->properties->get(Constants::KEY_FS_FILE, '');}

    public function getContent(): string
    {
        if (empty($this->content)) {
            $this->content = file_get_contents($this->properties->get(Constants::KEY_FS_FILE));
        }
        return $this->content;
    }

    public function getPath(): string {return $this->path;}
    public function getTitle(): string {return $this->get('title', '');}
    public function getName(): string {return $this->get('name', pathinfo($this->path, PATHINFO_FILENAME));}
    public function getCreatedDateAsString(): string {return date('Y-m-d i:h:s', $this->get('created', 0));}
    public function getModifiedDateAsString(): string {return date('Y-m-d i:h:s', $this->get('modified', 0));}
    public function getCreatedDate(): GX2CMSDate {return new GX2CMSDate($this->get('created', 0));}
    public function getModifiedDate(): GX2CMSDate {return new GX2CMSDate($this->get('modified', 0));}
    public function getProperties(): Properties {return new Properties(['properties'=>$this->properties->getAsArray()]);}

    public function jsonSerialize(): array {return $this->properties->getAsArray();}

    public function toArray(): array{return json_decode(json_encode($this->jsonSerialize()), true);}

    public function __toString(): string {return json_encode($this->jsonSerialize());}

    private function init(Properties $properties, string $path): void
    {
        if ($properties->hasElement()) {
            $this->isInvalid = false;
            if (!empty($path)) {
                $this->path = $path;
            }
            else if ($properties->has('path') && !empty($properties->get('path'))) {
                $this->path = $path;
            }
            if (!empty($this->path)) {
                $fsFile = Scanner::getProjectRoot().GX2CMS_DS.$this->path.'.'.Constants::EXT;
                if (!file_exists($fsFile)) {
                    $fsFile = Scanner::getProjectRoot().GX2CMS_DS.$this->path.
                        GX2CMS_DS.pathinfo($this->path, PATHINFO_FILENAME).'.'.Constants::EXT;
                    if (!file_exists($fsFile)) {
                        new Error('File: ' . $fsFile . ' does not exist', 500);
                    }
                }
                $this->properties = $properties;
                if (!$this->properties->has('name')) {
                    $this->properties->set('name', pathinfo($this->path, PATHINFO_FILENAME));
                }
                if ($this->properties->has('children')) {
                    $children = $this->properties->get('children');
                    $this->children = new Pages(empty($children) ? [] : $children);
                    $this->hasChild = empty($children);
                }
                else {
                    $this->children = new Pages([]);
                }
                $this->properties->set(Constants::KEY_FS_FILE, str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS, $fsFile));
            }
            else {
                new Error('Page\'s path is required, but missing: '.$properties, 500);
            }
            $this->properties->set('children',  $this->children);
            $this->properties->set('hasChild', !empty($this->children));
            $this->loadModel();
        }
    }

    private function loadModel(): void
    {
        if (!$this->properties->has('properties')) {
            $dir = dirname($this->properties->get('fs_file'));
            $modelDir = $dir . GX2CMS_DS . 'model';
            if (!is_dir($modelDir)) {
                $modelDir = $dir . GX2CMS_DS . pathinfo($this->path, PATHINFO_FILENAME) . GX2CMS_DS . 'model';
                if (!is_dir($modelDir)) {
                    $modelDir = '';
                }
            }
            if (!empty($modelDir)) {
                $modelFile = $modelDir . GX2CMS_DS . 'properties.json';
                if (file_exists($modelFile)) {
                    $config = json_decode(file_get_contents($modelFile), true);
                    $this->properties->set('properties', isset($config['properties']) ? $config['properties'] : $config);
                }
            }
        }
    }
}