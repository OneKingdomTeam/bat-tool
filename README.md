# Benchmark Assessment Tool Plugin

This tool allows us to understand our teams better in the critical areas that they are mentored in during the initial launch of their M2M project.

The goal of this tool is to make it easier and more interactive for the teams to fill in the questions as well as for mentors to be able to quickly recognize areas where they could provide more support.

Areas are composited of different segments that change colors based on the answers that teams provide.

## Requirements

This is not fully working plugin by itself. For it to work properly it utlizes custom Child theme build on top of the twenty-twenty-three default theme of wordpress.

This theme is responsible for loading extra css styles that are used across the tool as well as providing some extra functions necessary for smooth running.

## How to get an instance running

> [!WARNING]
> Unfortunately this plugin version doesn't follow correct zip file structure. While uploading WP will, since version 6.4-ish complain and won't allow the installation unless the zip structure checking is disallowed. At the bottom of this document you can find possible solution.


From practical point of view getting instance running is fairly easy and requires only few steps.

1) Have a Wordpress instance running. NOT TESTED ON MULTISITE - stand alone instllation recommended
2) Make sure twenty-twenty-three
3) Install the child theme
4) Install this plugin
5) Change the permalinks to "postname" structure

With those settings and installation in place you should see the tool set up.

For prevention of any unexpected behavior, it's recommended to disable caching on the hosting. App itself isn't any more demanding that demanding, and by disabling caching you always know that users are served with the current data from the server.

## Solving archive incompatibility

Since you cannot upload the theme, nor the plugin. You need to find a way how to add one extra line of PHP to your WP instance.

One of the many ways is to use the `Code Snippets` plugin, that allows you to insert PHP snippets into the instance:

```php
add_filter( 'unzip_file_use_ziparchive', '__return_false' );
```

This reverts back to using old way of extracting archives for WordPress. In that case, from what I found, the archive compatibility check doesn't run.
