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
<p>See example project here:<a href="https://github.com/ezpizee/gx2cms/blob/master/dist/com_gx2cms.zip" target="_blank">Example Project</a></p>
<p><img class="width-300" src="https://github.com/ezpizee/gx2cms/blob/master/src/com_gx2cms/admin/asset/images/folder-structure.png" /></p>
<hr />
<h4 id="partial-include">Include Partial</h4>

```
<sly data-sly-include="/path/to/the/partial/file.gx2cms"></sly></pre>
```

<p>It is used to include partial script/code, which doesn't have its own context nor model.</p>
<hr />
<h4 id="include-resource">Include Resource</h4>

```
<sly data-sly-resource="${'/path/to/the/component'}"></sly>

OR

<sly data-sly-resource="${'/path/to/the/component'}"
    data-model="test"></sly>

OR

<sly data-sly-resource="${'/path/to/the/component'}"
    data-model="com.test.core.models.TestModel"></sly>
```

<p>
     It is used to include component (in the section folder), which has its own context and model.
    <br />
     When there is no data-model, the default model to use for the include context is the
     properties.json (located in the /section/{component-folder}/model).
    <br />
     When there data-model, the framework will look first in the<b>bundle</b> folder to see if it is a PHP model.
     If it doesn't exist there, it will look in /section/{component-folder}/model.
</p>
<hr />
<h4 id="string-literal">String Literal</h4>

```
${properties.test}</pre>
```

<p>It is used to display value of variable or a string</p>
<hr />
<h4 id="if-statement">If Statement</h4>

```
<sly data-sly-test="${properties.exists}"></sly>
    <div class="test">
        ${properties.displaySomeThing}
        <p class="test">
 Any thing can go inside the IF-STATEMENT block.
        </p>
    </div>
</sly>
```
<p>It is used like any IF-STATEMENT</p>
<hr />
<h4 id="for-loop">For Loop</h4>

```
<sly data-sly-test="${properties.list}"></sly>
    <ul class="my-list">
        <sly data-sly-list="${properties.list}">
 <li>${itemList.index} - ${item.name}</li>
        </sly>
    </ul>
</sly>
```
<p>It is used like any FOR-LOOP</p>