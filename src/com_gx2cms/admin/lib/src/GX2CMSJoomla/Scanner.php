<?php

namespace GX2CMSJoomla;

use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Page\Pages;

class Scanner
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

    public function __construct(string $projectRoot)
    {
        if (empty($projectRoot) || !file_exists($projectRoot)) {
            new Error('projectRoot cannot be empty', 404);
        }
        self::$projectRoot = $projectRoot;

        $this->validate();
        $this->scan();
    }

    public static function ds(): string {return DIRECTORY_SEPARATOR;}
    public static function getProjectRoot(): string {return self::$projectRoot;}

    public function getPages(): Pages {return empty($this->pages) ? new Pages([]) : $this->pages;}

    public function getGlobalConfigFile(): string {return $this->globalConfigFile;}

    private function validate(): void
    {
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
        $this->loadPages();
    }

    private function loadPages()
    {
        if (isset($this->globalConfigData['pages']) && is_array($this->globalConfigData['pages']))
        {
            $this->pages = new Pages($this->globalConfigData['pages']);
        }
    }
}