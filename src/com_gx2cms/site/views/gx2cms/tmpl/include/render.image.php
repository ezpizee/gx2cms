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

use HandlebarsHelpers\Utils\DigitalAssetRenderer;

if (!empty($imagePath) && !empty($root))
{
    $digitalAssetRenderer = new DigitalAssetRenderer($root, $imagePath);
    $digitalAssetRenderer->render();
    die();
}