<h2>GX2CMS Cheet Sheet</h2>
<div id="toc">
<h4>Table of Content</h4>
<ul>
<li><a href="#file-ext">File extension</a></li>
<li><a href="#folder-structure">Folder Structure</a></li>
<li><a href="#partial-include">Partial Include</a></li>
<li><a href="#include-resource">Include Resource</a></li>
<li><a href="#string-literal">String Literal</a></li>
<li><a href="#display-html">Display/Output HTML</a></li>
<li><a href="#if-statement">IF Statement</a></li>
<li><a href="#for-loop">For Loop</a></li>
<li><a href="#conditional-statment">Conditional Statement</a></li>
<li><a href="#work-with-css-js">How to work CSS & JS</a></li>
<li><a href="#work-with-image">How to work images, files, and links</a></li>
<li><a href="#parsys">What is paragraph system or parsys?</a></li>
</ul>
</div>
<hr />
<h4 id="file-ext">File extension</h4>
<p><b>.gx2cms</b></p>
<hr />
<h4 id="folder-structure">Folder structure</h4>
<p>In your project, you should have root level folders like below:</p>
<ul>
<li><b>bundle</b>. This is mainly used for PHP Model object.</li>
<li><b>clientlib</b>. This is mainly used for global client library (CSS and JS).</li>
<li><b>config</b>. This should contain only global.json, which is used to maintain the structure 
of the site that you are working on. It is also used to keep the i18n.json file, which is used to mock
 localized string for different languages.</li>
<li><b>section</b>. This should be used to keep components, which can be reused in different pages..</li>
<li><b>structure</b>. This should be used to keep all pages, which include components.</li>
</ul>
<p>See example project here:<a href="https://github.com/ezpizee/gx2cms/blob/master/dist/com_gx2cms.zip" target="_blank">Example Project</a></p>
<p><img class="width-300" src="https://github.com/ezpizee/gx2cms/blob/master/src/com_gx2cms/admin/asset/images/folder-structure.png" alt="folder-structure" /></p>
<hr />
<h4 id="partial-include">Include Partial</h4>

```html
<sly data-sly-include="/path/to/the/partial/file.gx2cms"></sly>
```

<p>It is used to include partial script/code, which doesn't have its own context nor model.</p>
<hr />
<h4 id="include-resource">Include Resource</h4>

```html
<sly data-sly-resource="${'node1' @ resourceType='/path/to/the/component'}"></sly>

OR

<sly data-sly-resource="${'node2' @ resourceType='/path/to/the/component'}"
data-model="test"></sly>

OR

<sly data-sly-resource="${'node3' @ resourceType='/path/to/the/component'}"
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

```htl
${properties.test}

OR

${'Hello World!'}

OR

${'Localized Hello World' @ i18n}
```
<p>It is used to display value of variable or a string</p>
<hr />
<h4 id="display-html">Display/Output HTML</h4>

```html
${properties.someRichtextVar @ context='html'}
```

<h4 id="if-statement">If Statement</h4>

```html
<sly data-sly-test="${properties.exists}">
<p>Do something</p>
</sly>
<sly data-sly-test="${!properties.exists}">
<p>Do something else</p>
</sly>
```
<p>It is used like any IF-STATEMENT</p>
<hr />
<h4 id="for-loop">For Loop</h4>

```html
<sly data-sly-test="${properties.list}"></sly>
<ul class="my-list">
<sly data-sly-list="${properties.list}">
 <li>${itemList.index} - ${item.name}</li>
</sly>
</ul>
</sly>
```
<p>It is used like any FOR-LOOP</p>
<hr />

<h4 id="conditional-statment">Conditional Statement</h4>
<p>It is like conditional statement in any other languages</p>

```html
${properties.exists ? properties.displayMe : properties.displaySomethingElse}

${properties.exists ? 'Show something here' : properties.showSomeThingElse}

${properties.exists ? (properties.propA ? properties.propA : properties.propB) : properties.showSomeThingElse}

${properties.exists ? properties.showSomeThing : (properties.propX ? properties.propX : properties.propY)}
```
<hr />

<h4 id="work-with-css-js">How to work CSS & JS</h4>
<p><b>Global CSS/JS</b> should be kept in:</p>
<ul>
<li>/clientlib/{project-folder}/css for CSS</li>
<li>/clientlib/{project-folder}/js for JS</li>
</ul>
<p>Where <b>{project-folder}</b> is the same folder name as your project folder. 
For instance, your project's name is "test", the name of that folder should also be "test". So, it would be 
<b>/clientlib/test</b></p>
<p><b>CSS/JS specific to the page (custom css)</b> should be kept in:</p>
<ul>
<li>/structure/{page-folder}/clientlib/css for CSS</li>
<li>/structure/{page-folder}/clientlib/js for JS</li>
</ul>
<p>Where <b>{page-folder}</b> is the name of your page. 
For instance, your project's name is "home", the folder structure would be 
<b>/structure/home/clientlib/css</b>
or <b>/structure/home/clientlib/js</b>
</p>
<p>Your page's specific CSS/JS will be loaded automatically to the page, when the page is loaded.</p>
<p>Your <b>global CSS/JS</b> needs to be loaded with the following script, in your age:</p>

```html
<!-- for css; should go in the <head> section -->
<sly data-sly-clientlib="['/clientlib/test']" data-type="css"></sly>
<!-- for js; can go anywhere -->
<sly data-sly-clientlib="['/clientlib/test']" data-type="js"></sly>
``` 

<p>In your /clientlib/{project-folder} and /structure/{page-folder}/clientlib, you should have:</p>
<ul>
<li><b>css.txt</b>, it is used to include any css files, which contained in the clientlib <b>css</b> folder, that you want to load</li>
<li><b>js.txt</b>, it is used to include any js files, which contained in the clientlib <b>js</b> folder, that you want to load</li>
</ul>
<p><b>Content of your css.txt and js.txt</b></p>
<p>
<b>Example file and folder structure:</b><br />
- /clientlib/test/css<br />
- /clientlib/test/css/bootstrap.css<br />
- /clientlib/test/css/custom_css_file.css<br />
- /clientlib/test/css/custom_less_file.less<br />
- /clientlib/test/css.txt<br />
- /clientlib/test/js<br />
- /clientlib/test/js/jquery.js<br />
- /clientlib/test/js/bootstrap.js<br />
- /clientlib/test/js/custom.js<br />
- /clientlib/test/js.txt<br />
</p>
<p><b>Content of /clientlib/test/css.txt</b> (any file cotained in the css.txt will be loaded in that order)</p>
<pre>
css/bootstrap.css
css/custom_less_file.less
css/custom_css_file.css
</pre>
<p><b>Content of /clientlib/test/js.txt</b> (any file cotained in the js.txt will be loaded in that order)</p>
<pre>
js/jquery.js
js/bootstrap.js
js/custom.js
</pre>
<p>Any images/icons/fonts that you want to include in your CSS (both global and page specific) should be kept in:</p>
<ul>
<li>/clientlib/{project-folder}/images for global</li>
<li>/structure/{page-folder}/clientlib/images for page specific</li>
</ul>
<p>You then can include them in your CSS code as you would do in a regular CSS development</p>
<hr />
<h4 id="work-with-image">How to work images and files</h4>
<p>You can place your images that you need for your project (besides those for clientlib)
any where under your project folder.</p>
<p>However, our recommendation is to keep it as following:</p>
<ul>
<li>
/{project-folder}/assets
<ul>
<li>/{project-folder}/assets/images</li>
<li>/{project-folder}/assets/files</li>
</ul>
</li>
</ul>
<p>Here is how to include an image in your code like this:</p>

```html
<img src="/assets/images/{file-name}" alt="my image" data-render-asset="image"/>

OR (if you have imagePath property in your model) 

<img src="${properties.imagePath}" alt="my image" data-render-asset="image"/>
```

<p>Include <b>data-render-asset="image"</b> attribute to the image tag</p>

<p>Here is how to link to a file in your code like this:</p>

```html
<a href="/assets/files/{file-name}" title="my file" data-render-asset="file">My File</a>

OR (if you have imagePath property in your model) 

<a href="${properties.imagePath}" title="my file" data-render-asset="file">My File</a>
```

<p>Include <b>data-render-asset="file"</b> attribute to the href tag</p>

<p>Here is how to link to an page/structure:</p>

```html
<a href="/path/to/url/structure" title="my link" data-href-page="/path/to/url/structure">My Link</a>
```

<hr />

<h4 id="parsys">What is paragraph system or parsys?</h4>
<p>Paragraph system or parsys is like a container which allows user to drag other components to drop on it in order to composer content.</p>
<p>There are three types of parsys. They have their own meanings in AEM, but for GX2CMS, they are all the same. You might use different one deppend on the requirement from backend Developer.</p>
<p>Here they are</p>

```html
<sly data-sly-resource="${'par' @ resourceType='/libs/foundation/components/parsys'}"></sly>

OR

<sly data-sly-resource="${'par' @ resourceType='/libs/wcm/foundation/components/parsys'}"></sly>

OR

<sly data-sly-resource="${'par' @ resourceType='/libs/wcm/foundation/components/iparsys'}"></sly>
```

<p>Then, in your model file (i.e. the properties.json file)</p>

```json
{
  "properties": {
    "...": "..."
  },
  "parsys": [
    {
      "nodePath": "leftcol",
      "resourceType": "/libs/wcm/foundation/components/parsys",
      "children": [
        {
          "nodePath": "suicidal",
          "resourceType": "/section/heading",
          "model": "suicidal"
        },
        {
          "nodePath": "suicidaldescription",
          "resourceType": "/section/richtext",
          "model": "suicidal-description"
        }
      ]
    }
  ]
}
```

<p><strong>Note:</strong></p>
<ul>
<li>Use /libs/foundation/components/parsys in the model file, if in your gx2cms you use it.</li>
<li>Use /libs/wcm/foundation/components/parsys in the model file, if in your gx2cms you use it.</li>
<li>Use /libs/wcm/foundation/components/iparsys in the model file, if in your gx2cms you use it.</li>
</ul>