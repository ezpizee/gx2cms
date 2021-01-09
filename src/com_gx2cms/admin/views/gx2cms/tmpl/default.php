<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_gx2cms
 *
 * @copyright   Copyright (C) 2018 - 2021 WEBCONSOL Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted Access');
?>
<div class="container">
    <div class="row">
        <div class="span5">
            <h2>Install instruction</h2>
            <ul>
                <li>Go to Menu</li>
                <li>Add your new Site Menu item</li>
                <li>Menu Type, Select "GX2CMS Main View"</li>
                <li>Field in the name of the your menu item</li>
                <li>Select "Menu" from the dropdown that you want to display in</li>
                <li>Select "Parent Item" from the dropdown that you want to display</li>
                <li>Click on the "GX2CMS Menu Settings" tab</li>
                <li>Type in the absolute path to your GX2CMS on your file system (or drive) in the "Root Path" field.</li>
                <li>Save</li>
            </ul>
            <p>
                Now, go to the front-end (or site) and click on your menu item that you just created
                <br />
                <a href="<?php echo $_SERVER['HTTPS']?'https://':'http://',Uri::getInstance()->getHost();?>" target="_blank">
                    <?php echo Factory::getApplication()->get('sitename'); ?>
                </a>
            </p>
        </div>
        <div class="span6 offset1">
            <h2>GX2CMS Cheet Sheet</h2>
            <h4>Table Content</h4>
            <ul>
                <li><a href="#file-ext">File extension</a></li>
                <li><a href="#folder-structure">Folder Structure</a></li>
                <li><a href="#partial-include">Partial Include</a></li>
                <li><a href="#include-resource">Include Resource</a></li>
                <li><a href="#if-statement">IF Statement</a></li>
                <li><a href="#for-loop">For Loop</a></li>
            </ul>
            <hr />
            <h4 id="file-ext">File extension</h4>
            <p><b>.gx2cms</b></p>
            <hr />
            <h4 id="folder-structure">Folder structure</h4>
            <p>In your project, you should have root level folders like below:</p>
            <ul>
                <li><b>bundle</b>. This is mainly used for PHP Model object.</li>
                <li><b>clientlib</b>. This is mainly used for global client library (CSS and JS).</li>
                <li><b>config</b>. This should contain only global.json, which is used to maintain the structure of the site that you are working on.</li>
                <li><b>section</b>. This should be used to keep components, which can be reused in different pages..</li>
                <li><b>structure</b>. This should be used to keep all pages, which include components.</li>
            </ul>
            <p>See example project here: <a href="https://github.com/ezpizee/gx2cms/blob/master/dist/com_gx2cms.zip" target="_blank">Example Project</a></p>
            <p><img class="width-300" src="/administrator/components/com_gx2cms/asset/images/folder-structure.png" /> </p>
            <hr />
            <h4 id="partial-include">Include Partial</h4>
            <pre>&lt;sly data-sly-include="/path/to/the/partial/file.gx2cms"&gt;&lt;/sly&gt;</pre>
            <p>It is used to include partial script/code, which doesn't have its own context nor model.</p>
            <hr />
            <h4 id="include-resource">Include Resource</h4>
            <pre>
&lt;sly data-sly-resource="${'/path/to/the/component'}"&gt;&lt;/sly&gt;

OR

&lt;sly data-sly-resource="${'/path/to/the/component'}"
    data-model="test"&gt;&lt;/sly&gt;

OR

&lt;sly data-sly-resource="${'/path/to/the/component'}"
    data-model="com.test.core.models.TestModel"&gt;&lt;/sly&gt;
            </pre>
            <p>
                It is used to include component (in the section folder), which has its own context and model.
                <br />
                When there is no data-model, the default model to use for the include context is the
                properties.json (located in the /section/{component-folder}/model).
                <br />
                When there data-model, the framework will look first in the <b>bundle</b> folder to see if it is a PHP model.
                If it doesn't exist there, it will look in /section/{component-folder}/model.
            </p>
            <hr />
            <h4 id="string-literal">String Literal</h4>
            <pre>${properties.test}</pre>
            <p>It is used to display value of variable or a string</p>
            <hr />
            <h4 id="if-statement">If Statement</h4>
             <pre>
&lt;sly data-sly-test="${properties.exists}"&gt;&lt;/sly&gt;
    &lt;div class="test"&gt;
        ${properties.displaySomeThing}
        &lt;p class="test"&gt;
            Any thing can go inside the IF-STATEMENT block.
        &lt;/p&gt;
    &lt;/div&gt;
&lt;/sly&gt;
            </pre>
            <p>It is used like any IF-STATEMENT</p>
            <hr />
            <h4 id="for-loop">For Loop</h4>
            <pre>
&lt;sly data-sly-test="${properties.list}"&gt;&lt;/sly&gt;
    &lt;ul class="my-list"&gt;
        &lt;sly data-sly-list="${properties.list}"&gt;
            &lt;li&gt;${itemList.index} - ${item.name}&lt;/li&gt;
        &lt;/sly&gt;
    &lt;/ul&gt;
&lt;/sly&gt;
            </pre>
            <p>It is used like any FOR-LOOP</p>
            <hr />
        </div>
    </div>
</div>