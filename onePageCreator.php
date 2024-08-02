<?php

$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'OnePage Creator💪',	 //Plugin name
	'1.2',		 //Plugin version
	'Multicolor',  //Plugin author
	'https://ko-fi.com/multicolorplugins', //author website
	'Turn individual pages into sections for a One Page site.', //Plugin description
	'theme', //page type - on which admin tab to display
	'OnePageHow'  //main function (administration)
);

add_action('theme-sidebar', 'createSideMenu', array($thisfile, 'OnePage Creator? 💪'));

# Start Instructions
function OnePageHow()
{
	$html = '
	<style>
		hr{border:1px solid #ccc}
		.before{background:#fafafa;border:solid 1px #ddd;color:red;width:100%;padding:5px;display:inline-block;width:auto;margin:10px 0}
		.after{background:#fafafa;border:solid 1px #ddd;color:green;width:100%;padding:5px;display:inline-block;width:auto;margin:10px 0}
		h4{font-weight:bold;font-size:15px;margin-top:30px}
		.plugin p{margin:0px}
		.plugin em{color:blue}
	</style>
	
<div class="plugin">
	<h3>How to use OnePage Creator?</h3>
	<p>Turn individual pages into sections for a One Page site.</p>
	<hr>
	
	<div>
		<h4>Step 1:</h4>  
		<p>In your theme, update the following:</p><br>
		
		<p>Replace:</p>
		<code class="before">&lt;?php get_navigation();?></code>
		
		<p>With: </p>
		<code class="after">&lt;?php get_onePage_navigation();?></code>

		<p>And replace:</p>
		<code class="before">&lt;?php get_page_content();?></code>
		
		<p>With: </p>
		<code class="after">&lt;?php get_onePage_content();?></code>
	</div>
	
	<div>
		<h4>Step 2:</h4>

		<p>Create templates for your page sections. For example: </p><br>
		
		<p>Create new "<em>section1.php</em>" with the following: </p>
		<code class="after">
		&lt;?php get_onePage_redirect();?><br>
		&lt;section id="&lt;?php echo $OnePageSlug; ?>"><br>
			&nbsp;&nbsp; &lt;main><br>
				&nbsp;&nbsp;&nbsp;&nbsp; &lt;h2 style="color:blue">&lt;?php echo $OnePageTitle; ?>&lt;/h2><br>
				&nbsp;&nbsp;&nbsp;&nbsp; &lt;?php echo $OnePageContent; ?><br>
			&nbsp;&nbsp; &lt;/main><br>
		&lt;/section>
		</code>

		<p>And "<em>section2.php</em>" with the following: </p>
		<code class="after">
		&lt;?php get_onePage_redirect();?><br>
		&lt;section id="&lt;?php echo $OnePageSlug; ?>"><br>
			&nbsp;&nbsp; &lt;main><br>
				&nbsp;&nbsp;&nbsp;&nbsp; &lt;h2 style="color:green">&lt;?php echo $OnePageTitle; ?>&lt;/h2><br>
				&nbsp;&nbsp;&nbsp;&nbsp; &lt;?php echo $OnePageContent; ?><br>
			&nbsp;&nbsp; &lt;/main><br>
		&lt;/section>
		</code>
	</div>
	
	<div>
		<h4>Step 3:</h4>
		<p>&nbsp;</p>
		<ul>
			<li><p><span style="color:red;font-weight:bold;">Important!</span> In Homepage, "<b><span style="font-size:2em">&square;</span> Add this to the menu</b>" must be unchecked.</p></li>
			
			<li><p>Create new page "Info" for example, select template "<em>section1.php</em>" and add to menu.</p></li>
			
			<li><p>Create new page "Contact" for example, select template "<em>section2.php</em>" and add to menu.</p></li>
		</ul>
		<p>Your new sections will be based on menu order.</p>
	</div>
	
</div>
';

	$html .= '
	<hr style="margin:50px 0 20px">

	<script type="text/javascript" src="https://storage.ko-fi.com/cdn/widget/Widget_2.js"></script><script type="text/javascript">kofiwidget2.init("Buy Me Ko-fi", "#e02828", "I3I2RHQZS");kofiwidget2.draw();</script> ';

	echo $html;
};

function get_onePage_redirect(){
	if (!isset($OnePageContent) || empty($OnePageContent)) {
		// Przekieruj na stronę główną z kodem 301
		putenv("REDIRECT_TO_INDEX=true");
	}
}

# Start Logic
function get_onePage_navigation(){

	global $SITEURL;
	global $classPrefix;

	$Homexml = simplexml_load_file(GSDATAPAGESPATH . 'index.xml');

	global $menu;

	$menu .= '
	<li class=""><a href="' . $SITEURL . '" title="' . encode_quotes(cl($Homexml->title)) . '">' .  $Homexml->menu  . '</a></li>' . "\n";

	global $pagesArray, $id;
	if (empty($currentpage)) $currentpage = $id;

	$pagesSorted = subval_sort($pagesArray, 'menuOrder');
	if (count($pagesSorted) != 0) {
		foreach ($pagesSorted as $page) {
			$sel = '';
			$classes = '';
			$url_nav = $page['url'];

			if ($page['menuStatus'] == 'Y') {
				$parentClass = !empty($page['parent']) ? $classPrefix . $page['parent'] . " " : "";
				$classes = trim($parentClass . $classPrefix . $url_nav);
				if ($currentpage == $url_nav) $classes .= " current active";
				if ($page['menu'] == '') {
					$page['menu'] = $page['title'];
				}
				if ($page['title'] == '') {
					$page['title'] = $page['menu'];
				}
				$menu .= '<li class="' . $classes . '"><a href="#' . $page['slug'] . '" title="' . encode_quotes(cl($page['title'])) . '">' . strip_decode($page['menu']) . '</a></li>' . "\n";
			}
		}
	}

	echo exec_filter('menuitems', $menu);
};

function get_onePage_content(){
	global $pagesArray, $id;
	if (empty($currentpage)) $currentpage = $id;
	$pagesSorted = subval_sort($pagesArray, 'menuOrder');

	if (count($pagesSorted) != 0) {
		foreach ($pagesSorted as $page) {
			$sel = '';
			$classes = '';
			$url_nav = $page['url'];

			if ($page['menuStatus'] == 'Y') {

				global $TEMPLATE;

				$OnePageTitle = $page['title'];
				$OnePageContent = returnPageContent($page['slug']);
				$OnePageSlug = $page['slug'];
				
				include('theme/' . $TEMPLATE . '/' . $page['template']);
			}
		}
	}
};

function get_onePage_section($pageslug = ''){
	$page = simplexml_load_file(GSDATAPAGESPATH . $pageslug . '.xml');
	global $TEMPLATE;

	$OnePageTitle = $page->title;
	$OnePageContent = html_entity_decode($page->content);
	$OnePageSlug = $page->slug;
 include('theme/' . $TEMPLATE . '/' . $page->template);
};
