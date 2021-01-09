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

use GX2CMS\ConnectorUtils\Client;
use GX2CMS\Utils\Logger;
use GX2CMS\Utils\ResponseCodes;
use GX2CMSJoomla\GX2CMSSanitizer;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Session\Session;

/**
 * Install View
 *
 * @since  0.0.1
 */
class GX2CMSViewInstall extends HtmlView
{
    private $formData = [];
    private $values = [];

    /**
     * @param null $tpl
     *
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $app = Factory::getApplication();
        $input = $app->input;

        if ($input->getMethod() === 'POST') {

            if (!Session::checkToken()) {
                die('Invalid request');
            }

            $hasError = false;
            $dbo = Factory::getDbo();

            foreach (Constants::API_CONFIG_KEYS as $key) {
                if ($input->get($key)) {
                    $this->formData[$key] = $input->getString($key);
                    GX2CMSSanitizer::sanitize($this->formData[$key], true);
                    $this->values[] = '('.
                        $dbo->quote(md5($key)).','.
                        $dbo->quote($key).','.
                        $dbo->quote($this->formData[$key]).
                        ')';
                }
                else {
                    $hasError = true;
                    $app->enqueueMessage(
                        sprintf(Text::_('COM_GX2CMS_REQUIRED_BUT_MISSING'), Text::_('COM_GX2CMS_'.strtoupper($key))),
                        'error'
                    );
                }
            }

            if (!$hasError) {
                $this->install();
            }
        }
        else {
            $this->loadData();
        }

        parent::display($tpl);
    }

    protected function getFormData(string $key, $default=null) {
        return isset($this->formData[$key]) ? $this->formData[$key] : $default;
    }

    private function install(): void {

        try {
            $app = Factory::getApplication();
            $tokenHandler = 'GX2CMSJoomla\TokenHandler';
            $response = Client::install(Client::DEFAULT_ACCESS_TOKEN_KEY, $this->formData, $tokenHandler);

            if (!empty($response)) {
                if (isset($response['code']) && (int)$response['code'] !== 200) {
                    if ($response['message']==='ITEM_ALREADY_EXISTS') {
                        $app->enqueueMessage(Text::_('COM_GX2CMS_INSTALL_ERROR_ALREADY_EXISTS'), 'error');
                    }
                    else {
                        $app->enqueueMessage($response['message'], 'error');
                    }
                }
                else {
                    $sql = 'INSERT'.' INTO '.Constants::DB_TB_GX2CMS.'(config_key_md5,config_key,config_value)'.
                        ' VALUES'.implode(',', $this->values);
                    Factory::getDbo()->setQuery($sql)->execute();
                    $app->redirect('/administrator/index.php?option=com_gx2cms&view=ezpz');
                }
            }
            else {
                $app->enqueueMessage(Text::_('COM_GX2CMS_INSTALL_ERROR_FAILED_TO_INSTALL'));
            }
        }
        catch (Exception $e) {
            Logger::error($e->getMessage());
            throw new RuntimeException($e->getMessage(), ResponseCodes::CODE_ERROR_INTERNAL_SERVER);
        }
    }

    private function loadData(): void {
        $keys = [
            md5(Client::KEY_CLIENT_ID),
            md5(Client::KEY_CLIENT_SECRET),
            md5(Client::KEY_APP_NAME),
            md5(Client::KEY_ENV)
        ];
        $sql = 'SELECT *'.' FROM '.Constants::DB_TB_GX2CMS.
            ' WHERE config_key_md5 IN("'.implode('","', $keys).'")';
        $rows = Factory::getDbo()->setQuery($sql)->loadAssocList();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $this->formData[$row['config_key']] = $row['config_value'];
            }
        }
    }
}