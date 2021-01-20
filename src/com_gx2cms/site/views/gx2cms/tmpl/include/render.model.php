<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
use Ezpizee\ContextProcessor\CustomLoader;
use GX2CMSJoomla\Exception\Error;
use GX2CMS\Project\Model;
use Ezpizee\Utils\ResponseCodes;

defined('_JEXEC') or die('Restricted access');

if (!empty($root) && is_string($root) && !empty($model))
{
    $dot = '.';
    $namespace = str_replace($dot, '\\', $model);
    $modelPath = $root.GX2CMS_DS.'bundle'.GX2CMS_DS.str_replace($dot, GX2CMS_DS, $model).'.php';
    $modelPath = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS, $modelPath);
    if (file_exists($modelPath)) {
        if (!class_exists($namespace, false)) {
            include $modelPath;
        }
    }
    if (!class_exists($namespace, false)) {
        new Error('Servlet '.$model.' does not exist', ResponseCodes::CODE_ERROR_ITEM_NOT_FOUND);
    }
    $modelFirstBit = explode($dot, $model)[0];
    if (!CustomLoader::packageExists($modelFirstBit)) {
        CustomLoader::appendPackage([
            $modelFirstBit => $root.GX2CMS_DS.'bundle'
        ]);
    }
    $class = new $namespace();
    $output = '';
    if ($class instanceof Model) {
        $class->process();
        $output = json_encode($class->jsonSerialize());
    }
    header('Content-Type: application/json');
    die($output);
}