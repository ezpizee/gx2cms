<?php

namespace GX2CMSJoomla;

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Page\Page;
use GX2CMSJoomla\Page\Pages;
use GX2CMSJoomla\Section\Section;
use GX2CMSJoomla\Section\Sections;
use JsonSerializable;

class Scanner implements JsonSerializable
{
    /**
     * @var string
     */
    private static $projectRoot = '';

    /**
     * @var string
     */
    private $globalConfigFile = '';

    /**
     * @var array
     */
    private $globalConfigData = [];

    /**
     * @var Pages
     */
    private $pages;

    /**
     * @var Sections
     */
    private $sections;

    private static $pageParentFolderName = "structure";
    private static $componentParentFolderName = "section";

    public function __construct(string $projectRoot)
    {
        self::$projectRoot = $projectRoot;
        $this->validate();
        $this->scan();
    }

    public static function getProjectRoot(): string {return self::$projectRoot;}

    public function getPages(): Pages {return empty($this->pages) ? new Pages([]) : $this->pages;}

    public function getSections(): Sections {return empty($this->sections) ? new Sections([]) : $this->sections;}

    public function getGlobalConfigData(): array {return $this->globalConfigData;}

    public function getGlobalConfigFile(): string {return $this->globalConfigFile;}

    private function validate(): void
    {
        if (empty(self::$projectRoot)) {
            new Error('projectRoot cannot be empty', 404);
        }
        if (!file_exists(self::$projectRoot)) {
            new Error('projectRoot does not exist', 404);
        }
        $this->globalConfigFile = self::$projectRoot.GX2CMS_DS.'config'.GX2CMS_DS.'global.json';
        if (!file_exists($this->globalConfigFile)) {
            new Error($this->globalConfigFile.' is required, but missing.', 500);
        }
    }

    private function scan(): void
    {
        $this->globalConfigData = json_decode(file_get_contents($this->globalConfigFile), true);
        if (empty($this->globalConfigData)) {
            new Error($this->globalConfigFile.' cannot be error', 500);
        }
        $i18n = dirname($this->globalConfigFile).DIRECTORY_SEPARATOR.'i18n.json';
        if (file_exists($i18n)) {
            $this->globalConfigData['i18n'] = json_decode(file_get_contents($i18n), true);
        }
        if (!isset($this->globalConfigData['pages'])) {
            $this->scanPages(self::$projectRoot.GX2CMS_DS.self::$pageParentFolderName);
        }
        if (!isset($this->globalConfigData['sections'])) {
            $this->scanSections(self::$projectRoot.GX2CMS_DS.self::$componentParentFolderName);
        }
    }

    private function scanPages(string $root): void
    {
        $pages = glob($root.GX2CMS_DS.'*');
        if (!empty($pages)) {
            if (empty($this->pages)) {
                $this->pages = new Pages([]);
            }
            foreach ($pages as $page) {
                if (is_dir($page)) {
                    $name = pathinfo($page, PATHINFO_FILENAME);
                    if (file_exists($page.GX2CMS_DS.$name.'.json')) {
                        $path = str_replace(self::$projectRoot, '', $page);
                        $pageProperties = [
                            'path' => $path,
                            'name' => $name,
                            'currentPage' => json_decode(file_get_contents($page.GX2CMS_DS.$name.'.json'), true)
                        ];
                        $this->pages->addChild(new Page(new Properties($pageProperties), $path),  $path);
                        $this->scanPages($page);
                    }
                }
            }
        }
    }

    private function scanSections(string $root): void
    {
        $sections = glob($root.GX2CMS_DS.'*');
        if (!empty($sections)) {
            if (empty($this->sections)) {
                $this->sections = new Sections([]);
            }
            foreach ($sections as $section) {
                if (is_dir($section)) {
                    $name = pathinfo($section, PATHINFO_FILENAME);
                    if (file_exists($section.GX2CMS_DS.$name.'.json')) {
                        $path = str_replace(self::$projectRoot, '', $section);
                        $pageProperties = json_decode(file_get_contents($section.GX2CMS_DS.$name.'.json'), true);
                        $pageProperties['path'] = $path;
                        $pageProperties['name'] =  $name;
                        $this->sections->addChild(new Section(new Properties($pageProperties), $path), $path);
                        $this->scanSections($section);
                    }
                }
            }
        }
    }

    public function jsonSerialize()
    {
        return array_merge([
            'projectRoot' => self::$projectRoot,
            'pages' => $this->pages->toArray(),
            'sections' => $this->sections->toArray()
        ], $this->globalConfigData);
    }

    public function toArray(): array {return json_decode(json_encode($this->jsonSerialize()), true);}
}