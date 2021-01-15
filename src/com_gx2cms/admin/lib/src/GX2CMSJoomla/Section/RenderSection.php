<?php

namespace GX2CMSJoomla\Section;

use GX2CMSJoomla\Constants as GX2CMSConstants;
use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Hbs;
use GX2CMSJoomla\HTMLDoc;
use GX2CMSJoomla\Page\Page;
use GX2CMSJoomla\Scanner;

class RenderSection extends HTMLDoc
{
    /**
     * @var Scanner
     */
    private $scanner;
    /**
     * @var string
     */
    private $root = '';
    /**
     * @var string
     */
    private $page = '';
    /**
     * @var string
     */
    private $section = '';
    /**
     * @var string
     */
    private $sectionSelector = 'properties';
    /**
     * @var string
     */
    private $renderPage = '';
    /**
     * @var Page
     */
    private $pageObject;
    /**
     * @var array
     */
    private $context = ['renderPage'=>'', 'pageProperties'=>[], 'sectionProperties'=>[], 'css'=>[], 'js'=>[]];

    public function __construct(Scanner $scanner, string $renderPage, string $root, string $page, string $section, string $sectionSelector = 'properties')
    {
        $this->scanner = $scanner;
        $this->renderPage = $renderPage;
        $this->root = $root;
        $this->page = $page;
        $this->section = $section;
        $this->sectionSelector = $sectionSelector;
        $this->context['renderPage'] = $renderPage;
        $this->ini();
    }

    public function getContext(): array {return $this->context;}

    private function ini(): void
    {
        $this->loadGlobalObject();
        $this->loadPageObject();
        $this->loadSectionObject();
    }

    private function loadGlobalObject(): void
    {
        $this->pageObject = $this->scanner->getPages()->getChild($this->page);
        $properties = $this->pageObject->getProperties()->getAsArray();
        Hbs::setGlobalContext(array_merge(
            ['currentPage' => isset($properties['properties']) ? $properties['properties'] : $properties],
            ['renderPage' => $this->renderPage],
            $this->scanner->getGlobalConfigData()
        ));
        $project = pathinfo(trim($this->root, GX2CMS_DS), PATHINFO_FILENAME);
        $clientlib = '/clientlib/'.$project;
        $clientlibDir = $this->root.GX2CMS_DS.'clientlib'.GX2CMS_DS.$project;
        HTMLDoc::removeDoubleSlashes($clientlibDir);
        // aggregate global css
        if (file_exists($clientlibDir.GX2CMS_DS.'css')) {
            $this->context['css'][] = HTMLDoc::styleSheet($this->renderPage.'&clientlib='.rawurldecode($clientlib.'.css').'&type=css');
        }
        // aggregate global js
        if (file_exists($clientlibDir.GX2CMS_DS.'js')) {
            $this->context['js'][] = HTMLDoc::script($this->renderPage.'&clientlib='.rawurldecode($clientlib.'.js').'&type=js');
        }
    }

    private function loadPageObject(): void
    {
        // model
        $pageConfig = $this->root.GX2CMS_DS.$this->page.GX2CMS_DS.pathinfo($this->page, PATHINFO_FILENAME).'.json';
        HTMLDoc::removeDoubleSlashes($pageConfig);
        if (!file_exists($pageConfig)) {
            new Error('Page config: '.$pageConfig.' does not exist', 500);
        }
        $this->context['pageProperties'] = json_decode(file_get_contents($pageConfig), true);
        $pageModel = $this->root.GX2CMS_DS.$this->page.GX2CMS_DS.'model'.GX2CMS_DS.'properties.json';
        HTMLDoc::removeDoubleSlashes($pageModel);
        if (file_exists($pageConfig)) {
            $this->context['pageProperties'] = array_merge($this->context['pageProperties'],
                json_decode(file_get_contents($pageModel), true));
        }

        // clientlibs
        $clientlib = $this->page.'/clientlib';
        $clientLibDir = str_replace('/', GX2CMS_DS, $this->root.GX2CMS_DS.$this->page.GX2CMS_DS.'clientlib');
        $this->removeDoubleSlashes($clientLibDir);
        $this->removeDoubleSlashes($clientlib);

        // aggregate page css
        if (file_exists($clientLibDir.GX2CMS_DS.'css')) {
            $this->context['css'][] = HTMLDoc::styleSheet($this->renderPage.'&clientlib=/'.rawurldecode($clientlib.'.css').'&type=css');
        }
        // aggregate page js
        if (file_exists($clientLibDir.GX2CMS_DS.'js')) {
            $this->context['js'][] = HTMLDoc::script($this->renderPage.'&clientlib=/'.rawurldecode($clientlib.'.js').'&type=js');
        }
    }

    private function loadSectionObject(): void
    {
        $sectionConfig = $this->root.GX2CMS_DS.$this->section.GX2CMS_DS.pathinfo($this->section, PATHINFO_FILENAME).'.json';
        HTMLDoc::removeDoubleSlashes($sectionConfig);
        if (!file_exists($sectionConfig)) {
            new Error('Section config: '.$sectionConfig.' does not exist', 500);
        }
        $this->context['sectionProperties'] = json_decode(file_get_contents($sectionConfig), true);
        $sectionModel = $this->root.GX2CMS_DS.$this->section.GX2CMS_DS.'model'.GX2CMS_DS.$this->sectionSelector.'.json';
        HTMLDoc::removeDoubleSlashes($sectionModel);
        if (file_exists($sectionModel)) {
            $this->context['sectionProperties'] = array_merge($this->context['sectionProperties'],
                json_decode(file_get_contents($sectionModel), true));
            $sectionSelectors = glob(dirname($sectionModel).GX2CMS_DS.'*.json');
            if (sizeof($sectionSelectors) > 0) {
                $this->context['sectionProperties']['selectors'] = [];
                foreach ($sectionSelectors as $modelFile) {
                    $modelFile = pathinfo($modelFile, PATHINFO_FILENAME);
                    $this->context['sectionProperties']['selectors'][] = [
                        'text' => $modelFile,
                        'value' => $modelFile,
                        'selected' => ($this->sectionSelector === $modelFile ? 'true' : 'false')
                    ];
                }
            }
        }
        $sectionFile = $this->root.GX2CMS_DS.$this->section.GX2CMS_DS.pathinfo($this->section, PATHINFO_FILENAME).'.'.GX2CMSConstants::EXT;
        HTMLDoc::removeDoubleSlashes($sectionFile);
        if (!file_exists($sectionFile)) {
            new Error('Section file: '.$sectionFile.' does not exist', 500);
        }
        $this->context['sectionProperties']['content'] = Hbs::render(
            file_get_contents($sectionFile),
            $this->context['sectionProperties'],
            $this->root
        );
    }
}