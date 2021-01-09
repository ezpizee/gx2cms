<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   Copyright (C) 2018 - 2021 WEBCONSOL Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * General Controller of GX2CMS component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 * @since       0.0.7
 */
class GX2CMSController extends BaseController
{
    /**
     * The default view for the display method.
     *
     * @var string
     * @since 12.2
     */
    protected $default_view = 'gx2cms';

    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}