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

use GX2CMSJoomla\Joomla\Menu;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * HTML View class for the GX2CMS Component
 *
 * @since  0.0.1
 */
class GX2CMSViewGX2CMS extends HtmlView
{
    /**
     * @var \Joomla\Registry\Registry
     */
    private $menuParams = null;

    /**
     * Display the GX2CMS view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     */
    public function display($tpl = null)
    {
        // Display the view
        parent::display($tpl);
    }

    protected function getMenuParam($k)
    {
        if ($this->menuParams === null) {
            $this->menuParams = Menu::getActiveMenuParams();
        }

        return $this->menuParams->get($k, "");
    }
}