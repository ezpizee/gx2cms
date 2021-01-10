<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use GX2CMSJoomla\Constants as GX2CMSConstants;
use GX2CMSJoomla\Exception\Error;
use GX2CMSJoomla\Hbs;
use GX2CMSJoomla\Scanner;
use HandlebarsHelpers\Utils\ClientlibManager;
use HandlebarsHelpers\Utils\FileExtension;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
try {
    $input = Factory::getApplication()->input;
    $root = $this->getMenuParam('gx2cms_project_root');
    $layout = $input->getString('layout', 'render');
    $scanner = new Scanner($root);
    $page = $input->getString('page', '');
    $section = $input->getString('section', '');
    $sectionSelector = $input->getString('selector', '');
    if (empty($sectionSelector)) {
        $sectionSelector = 'properties';
    }

    if (!empty($page)) {
        if (empty($section)) {
            $page = $scanner->getPages()->getChild($page);
            $properties = $page->getProperties()->getAsArray();
            Hbs::setGlobalContext(array_merge(
                ['currentPage' => isset($properties['properties']) ? $properties['properties'] : $properties],
                $scanner->getGlobalConfigData()
            ));
            die(Hbs::render(
                $page->getFSFile(),
                $properties,
                $root
            ));
        }
        else {
            $pageConfig = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS,
                $root.GX2CMS_DS.$page.GX2CMS_DS.pathinfo($page, PATHINFO_FILENAME).'.'.FileExtension::JSON);
            if (!file_exists($pageConfig)) {
                new Error('Page config: '.$pageConfig.' does not exist', 500);
            }
            $sectionConfig = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS,
                $root.GX2CMS_DS.$section.GX2CMS_DS.pathinfo($section, PATHINFO_FILENAME).'.'.FileExtension::JSON);
            if (!file_exists($sectionConfig)) {
                new Error('Section config: '.$pageConfig.' does not exist', 500);
            }
            $sectionModel = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS,
                $root.GX2CMS_DS.$section.GX2CMS_DS.'model'.GX2CMS_DS.$sectionSelector.'.'.FileExtension::JSON);
            if (!file_exists($sectionModel)) {
                new Error('Section model: '.$sectionModel.' does not exist', 500);
            }

            $pageProperties = json_decode(file_get_contents($pageConfig), true);
            $clientlib = pathinfo($root, PATHINFO_FILENAME);
            $pageClientLib = $root.$page.GX2CMS_DS.'clientlib'.GX2CMS_DS.$clientlib;
            $clientLibManager = new ClientlibManager($root, '/clientlib/'.$clientlib.'.'.FileExtension::CSS);
            $pageProperties['style'] = $clientLibManager->getContent();
            $clientLibManager = new ClientlibManager($root, '/clientlib/'.$clientlib.'.'.FileExtension::JS);
            $pageProperties['script'] = $clientLibManager->getContent();

            $sectionSelectors = glob(str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS,
                    $root.GX2CMS_DS.$section).GX2CMS_DS.'model'.GX2CMS_DS.'*.json');
            $sectionFile = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS,
                $root.GX2CMS_DS.$section.GX2CMS_DS.pathinfo($section, PATHINFO_FILENAME).'.'.GX2CMSConstants::EXT);
            $sectionProperties = array_merge(
                json_decode(file_get_contents($sectionConfig), true),
                json_decode(file_get_contents($sectionModel), true)
            );
            if (sizeof($sectionSelectors) > 1) {
                $sectionProperties['selectors'] = [];
                foreach ($sectionSelectors as $modelFile) {
                    $modelFile = pathinfo($modelFile, PATHINFO_FILENAME);
                    $sectionProperties['selectors'][] = [
                        'text' => $modelFile,
                        'value' => $modelFile,
                        'selected' => ($sectionSelector === $modelFile ? 'true' : 'false')
                    ];
                }
            }

            $sectionProperties['content'] = Hbs::render(
                file_get_contents($sectionFile),
                $sectionProperties,
                $root
            );

            echo Hbs::render(
                GX2CMS_COMP_ROOT.GX2CMS_DS.'asset'.GX2CMS_DS.'hbs'.GX2CMS_DS.'render-section.hbs',
                [
                    'urlPfx' => Route::_('index.php?option=com_gx2cms&layout='.$layout.'&page='.$page.'&section='.$section.'&selector='),
                    'page' => $pageProperties,
                    'section' => $sectionProperties
                ],
                $root
            );
            die();
        }
    }
    else {
        new Error('page is required, but missing', 500);
    }
}
catch (Exception $e) {
    new Error($e->getMessage(), 500);
}