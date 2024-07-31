<?php

$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
    $thisfile, //Plugin id
    'OnePage Creator💪',     //Plugin name
    '1.0',         //Plugin version
    'Multicolor',  //Plugin author
    'https://ko-fi.com/multicolorplugins', //author website
    'Plugin for create OnePage website', //Plugin description
    'plugins', //page type - on which admin tab to display
    'OnePageHow'  //main function (administration)
);



add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'How to use OnePage Creator? 💪'));

function OnePageHow()
{

    $html = '<h3>How to use OnePage Creator?</h3>


    <b style="font-size:15px;">1 Step:</b>
    <hr>
<br>   
    replace:<br>
    <code style="background:#fafafa;border:solid 1px #ddd;color:black;width:100%;padding:5px;display:inline-block;width:auto;margin:10px 0">&lt;?php get_navigation(return_page_slug());?></code>
    <br>
    to: </br>
        <code style="background:#fafafa;border:solid 1px #ddd;color:black;width:100%;padding:5px;display:inline-block;width:auto;;margin:10px 0">&lt;?php get_navigation(return_page_slug());?></code>

<br><br>
       <b style="font-size:15px;">2 Step:</b>
<hr>
<br>
    replace:<br>
    <code style="background:#fafafa;border:solid 1px #ddd;color:black;width:100%;padding:5px;display:inline-block;width:auto;margin:10px 0">&lt;?php get_page_content();?></code>
    <br>
    to: </br>
        <code style="background:#fafafa;border:solid 1px #ddd;color:black;width:100%;padding:5px;display:inline-block;width:auto;;margin:10px 0">&lt;?php onePageLoop();?></code>

<br><br>
       <b style="font-size:15px;">3 Step:</b>

       
<hr>
<br>
       Create template <b>yoursName.php</b> on yours theme like this example: <br>
 
               <code style="background:#fafafa;border:solid 1px #ddd;color:black;width:100%;padding:5px;display:inline-block;width:auto;;margin:10px 0">
               
                   &lt;section id="&lt;?php echo $OnePageSlug; ?>"><br>
        &lt;main><br>
            &lt;h2>&lt;?php echo $OnePageTitle; ?>&lt;/h2><br>
            &lt;?php echo $OnePageContent; ?><br>
        &lt;/main><br>
    &lt;/section>

               </code>

<br>
               <br>
                   <b style="font-size:15px;">4 Step:</b>
<hr>
<p>
<br>
create a page, add it to the menu and select yoursName.php from the page template included in the page options. The order in which subsequent pages (which will now be sections) are displayed depends on the order setting in the menu. 
<br>
<span style="color:red;font-weight:bold;">Important! Uncheck homepage display in the menu in the page options, failure to do so will result in an error on the page. A link to the home page is automatically added to keep the loop correct. </span><br> 
            
</p>


    ';


 $html .= "<script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script><script type='text/javascript'>kofiwidget2.init('Buy Me Ko-fi', '#e02828', 'I3I2RHQZS');kofiwidget2.draw();</script> ";   

    echo $html;
};

function onePageNav()
{

    global $SITEURL;
    global $classPrefix;


    $Homexml = simplexml_load_file(GSDATAPAGESPATH . 'index.xml');

    global $menu;


    $menu .= '<li class=""><a href="' . $SITEURL . '" title="' . encode_quotes(cl($Homexml->title)) . '">' .  $Homexml->menu  . '</a></li>' . "\n";


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

function onePageLoop()
{

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
