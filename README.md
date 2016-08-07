# NetLime WordPress Theme Base

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
Todo

## What is wrappers and how to create them
Todo

## What is virtual templates and how to create them
Todo

## What is sections (blocks) and how to create them
Todo
