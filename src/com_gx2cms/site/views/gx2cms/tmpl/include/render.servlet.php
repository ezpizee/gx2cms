<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   2020 - 2021 GX2CMS Co., Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
use Ezpizee\Utils\Request;
use Ezpizee\Utils\ResponseCodes;
use Ezpizee\ContextProcessor\CustomLoader;
use GX2CMSJoomla\Exception\Error;
use GX2CMS\Project\Servlet;

defined('_JEXEC') or die('Restricted access');

if (!empty($root) && is_string($root) && !empty($servlet) && !empty($requestMethod))
{
    $dot = '.';
    $namespace = str_replace($dot, '\\', $servlet);
    $servletPath = $root.GX2CMS_DS.'bundle'.GX2CMS_DS.str_replace($dot, GX2CMS_DS, $servlet).'.php';
    $servletPath = str_replace(GX2CMS_DS.GX2CMS_DS, GX2CMS_DS, $servletPath);
    if (file_exists($servletPath)) {
        if (!class_exists($namespace, false)) {
            /** @noinspection PhpIncludeInspection */
            include $servletPath;
        }
    }
    if (!class_exists($namespace, false)) {
        new Error('Servlet '.$servlet.' does not exist', ResponseCodes::CODE_ERROR_ITEM_NOT_FOUND);
    }
    $servletFirstBit = explode($dot, $servlet)[0];
    if (!CustomLoader::packageExists($servletFirstBit)) {
        CustomLoader::appendPackage([
            $servletFirstBit => $root.GX2CMS_DS.'bundle'
        ]);
    }
    $class = new $namespace();
    if ($class instanceof Servlet) {
        if (!in_array($requestMethod, $class->allowedMethods())) {
            new Error('Request method '.$requestMethod.' is not allowed for '.$servlet,
                ResponseCodes::CODE_METHOD_NOT_ALLOWED);
        }
        $request = new Request();
        $class->{'do'.$requestMethod}($request);
        header('Content-Type: '.$class->getResponseContentType());
        $output = '';
        $output = $class->getData();
        if (is_array($output) || is_object($output)) {
            $output = json_encode($output);
        }
        die($output);
    }
}