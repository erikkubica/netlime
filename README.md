# NetLime WordPress Theme Base

###### I am not man of words

## The goal
The goal is to create WordPress Theme base that is simple, fast, multi-wrapper and section(block) based.

## The idea
The idea of block & wrapper based theme came from Magento, where each template can have its own
wrapper and blocks. By default WordPress has multiple template files which works as template and wrapper.
That approach is not really dynamic and makes too much files in your theme which makes it not really "clean". 
So I had an idea to make these templates virtual and wrap them around with wrappers.
The same idea applies to navigation menus and sidebars. Why create a php file that is full of repeated
code of arrays to create navigation or sidebars, instead this theme uses configuration file for navigation and
separate file configuration file for sidebars, which is more readable and cleaner that basic php.

## Speed
There is one major problem with all caching plugins, you cannot define which parts(sections) of page
you want to cache and which parts you won't cache. These caching plugins forces you to use full page for whole website
or just to few pages.
    
Sections are parts of your page. You can have sections where content changes only once per month/week/day/year. 
Some of them changes content hourly. So why to not cache only pars that are changing its content rarely.
This will prevent to execute loops, queries, printing outputs and more, each time the user visits the page.

[-!-] Caching of section is template specific. You can enable cache for section on templace XY, 
but you can disable caching of same section on Template XS. [-!-] 

Lets say a example from https://www.netlime.eu content inside head html tag is cached. I call this section
"head" where caching is enabled. Until I don't change site title, meta, and others... I don't need from wordpress
to generate its content using wp_head() which impacts on performance.

Same with list of posts on homepage or any archive page. Yes WordPress will make a query on page load, but inside the loops is performance impact
which I don't need to do each time user visits my page, so section post_list can be cached, so I can achieve more performance.

Now let's look on post detail page. I din't change post content, thumbnail or meta data too often so section "post_content" can be cached. But I have comments
Where users are adding comments once/1-2 days. If user posts an comment, I don't want to load them the 
cached content, they will think that posting comment was not successful or maybe they will checking for reply and they will not
see the reply, because cache is olden than reply was submitted. So I can disable cache for comments section while anything other 
sections has cache enabled.

This approach can help to gain huge performance gain without caching full page and worry about if there is any content changed that 
will be not displayed, because full page is cached.

## Features & Planned features
#### Feaures
* Virtual templates
* Template wrappers
* Page sections
* Yaml configuration
* Separate caching for each section
* Disable emoji implemented
* Define navigation menus
* Define widget areas (sidebars)
* Comes with bootstrap sources (less)
* More caching options
  * Do not generate cache on post requests
  * Do not generate cache on ajax requests
  * Do not generate cache if user/admin is logged in
* Clear cache from admin
* Expendable method using hooks

#### Planned

* Define custom post types
* Define ACF fields
* More caching options
  * Do not generate cache when using not existing get query parameters
* Allow sections to have their view class containing functions for required data
* Allow to configure basic WordPress features like turn on/off comments
* Allow to brand admin login page
* Some useful asset managing system
* And more... @ToDo: Think about it

## What are wrappers and how to create them
#### What are wrappers?
Wrappers are basically php files containing skeleton layout for your template/templates.
An wrapper contains the html "html,head,body" tags. Inside body you have the layout for example
1 column, 2 columns with left sidebar, 2 columns with right sidebar, 3 columns, etc...

#### How do I set content for wrapper?
Using locations. When you are configuring an template, you place sections to given locations.
Locations are defined by you. Let´s say that you configure for template an section to "myCustomLocation"
Then you need just call Theme::getContent("myCustomLocation"); in your wrapper.

Basically what getContent does, is that it includes all sections defined on given location defined
in actual template.

Real world usage example:
I have defined "post_content" and "comments" section for "post detail" template, both of these 
section has location called "content".

My "post detail" template has "2columns-right" wrapper. So when I call Theme::getContent("content"); 
in wrapper, these two sections are rendered, post_content section is rendered from cache, 
because caching for section this is turned on, but the comments section is rendered casually, because has caching off.

#### How I create wrapper?
Please refer to /app/etc/wrappers.yaml file

## What are virtual templates and how to create them
Virtual templates non-existing template files (single.php single-mycpt.php, template-xy.php,etc...).

Using these templates you specify which wrapper & sections will your template have.

Virtual templates is defined inside /app/etc/templates.yaml refer this file more information.

## What are sections (blocks) and how to create them
Sections are the final files that contains html that contains some data. 

Imagine it like a puzzle. Let´s assume, that a complete page is when all puzzle pieces are on right place. 
Section are just these puzzle pieces. When you create these pieces in /app/etc/sections.yaml then you can use them in /app/etc/templates.yaml

Section caching is template specific, this is the reason why there is no cachne configuration inside /app/etc/sections.yaml, 
caching configuration for section is done in template configuration. That approach allows you to cache same section in template XY
but don´ cache it on template XS.