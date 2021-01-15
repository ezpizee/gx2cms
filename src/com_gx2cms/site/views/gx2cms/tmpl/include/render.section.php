<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use GX2CMSJoomla\Hbs;
use GX2CMSJoomla\Section\RenderSection;
use HandlebarsHelpers\Processors\Processor;

if (!isset($root)) {$root = '';}

if (!empty($page) && !empty($section) && !empty($root) && !empty($sectionSelector))
{
    $tmpl = GX2CMS_COMP_ROOT.GX2CMS_DS.'asset'.GX2CMS_DS.'hbs'.GX2CMS_DS.'render-section.hbs';
    RenderSection::removeDoubleSlashes($tmpl);
    $sectionRenderer = new RenderSection(
        $scanner,
        $renderPage,
        $root,
        $page,
        $section,
        $sectionSelector
    );
    $context = $sectionRenderer->getContext();
    $context['urlPfx'] = $renderPage.'&page='.$page.'&section='.$section;
    $content = Hbs::render($tmpl, $context, $root);
    Processor::processAssetTag($content, ['renderPage'=>$renderPage]);
    die($content);
}