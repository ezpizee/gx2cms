<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   Copyright (C) 2018 - 2021 WEBCONSOL Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

use GX2CMS\ConnectorUtils\Client;
$env = $this->getFormData(Client::KEY_ENV);
?>
<div class="contained-m border-ddd">
    <div class="row-fluid">
        <div class="container">
            <h1><?php echo JText::_('COM_GX2CMS_PORTAL_CONFIGURATION');?></h1>
            <form action="<?php echo JRoute::_('index.php?option=com_gx2cms&view=install'); ?>"
                  method="post" name="adminForm" id="adminForm" class="form-validate">
                <div class="form-vertical">
                    <label for="jform_client_id"><?php echo JText::_('COM_GX2CMS_CLIENT_ID');?></label>
                    <div class="row-fluid">
                        <div class="span12 col-12">
                            <input type="text" name="client_id" id="jform_client_id"
                                   placeholder="<?php echo JText::_('COM_GX2CMS_CLIENT_ID_PLACEHOLDER');?>"
                                   value="<?php echo $this->getFormData(Client::KEY_CLIENT_ID);?>" />
                        </div>
                    </div>
                </div>
                <div class="form-vertical">
                    <label for="jform_client_secret"><?php echo JText::_('COM_GX2CMS_CLIENT_SECRET');?></label>
                    <div class="row-fluid">
                        <div class="span12 col-12">
                            <input type="text" name="client_secret" id="jform_client_secret"
                                   placeholder="<?php echo JText::_('COM_GX2CMS_CLIENT_SECRET_PLACEHOLDER');?>"
                                   value="<?php echo $this->getFormData(Client::KEY_CLIENT_SECRET);?>" />
                        </div>
                    </div>
                </div>
                <div class="form-vertical">
                    <label for="jform_app_name"><?php echo JText::_('COM_GX2CMS_APP_NAME');?></label>
                    <div class="row-fluid">
                        <div class="span12 col-12">
                            <input type="text" name="app_name" id="jform_app_name"
                                   placeholder="<?php echo JText::_('COM_GX2CMS_APP_NAME_PLACEHOLDER');?>"
                                   value="<?php echo $this->getFormData(Client::KEY_APP_NAME);?>" />
                        </div>
                    </div>
                </div>
                <div class="form-vertical">
                    <label for="jform_env"><?php echo JText::_('COM_GX2CMS_ENV');?></label>
                    <div class="row-fluid">
                        <div class="span12 col-12">
                            <select name="env" id="jform_env">
                                <option value=""><?php echo JText::_('COM_GX2CMS_ENV_PLACEHOLDER');?></option>
                                <option value="local"<?php echo $env==='local' ? ' selected' : '';?>>Local</option>
                                <option value="dev"<?php echo $env==='dev' ? ' selected' : '';?>>Development</option>
                                <option value="stage"<?php echo $env==='stage' ? ' selected' : '';?>>Staging</option>
                                <option value="prod"<?php echo $env==='production'||empty($env) ? ' selected' : '';?>>Production</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-vertical">
                    <button type="submit" class="btn btn-primary"><?php echo JText::_('COM_GX2CMS_SAVE_CONFIGURATION');?></button>
                </div>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
    </div>
</div>
