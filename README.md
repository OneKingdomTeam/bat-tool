# Benchmark Assessment Tool Plugin

This tool allows us to understand our teams better in the critical areas that they are mentored in during the initial launch of their M2M project.

The goal of this tool is to make it easier and more interactive for the teams to fill in the questions as well as for mentors to be able to quickly recognize areas where they could provide more support.

Areas are composited of different segments that change colors based on the answers that teams provide.

## Requirements

This is not fully working plugin by itself. For it to work properly it utlizes custom Child theme build on top of the twenty-twenty-three default theme of wordpress.

This theme is responsible for loading extra css styles that are used across the tool as well as providing some extra functions necessary for smooth running. One of which is to contain templates for custom post types generated by this plugin.

## How to get an instance running

From practical point of view getting instance running is fairly easy and requires only few steps.

1) Have a Wordpress instance running. NOT TESTED ON MULTISITE - stand alone instllation recommended
2) Make sure twenty-twenty-three theme is installed and updated
3) Install the child theme 'bat-tool-theme'
4) Install this plugin
5) Change the permalinks to "postname" structure

With those settings and installation in place you should see the tool set up.

For prevention of any unexpected behavior, it's recommended to disable caching on the hosting. App itself isn't any more demanding than raw Guttenberg website, and by disabling caching you always know that users are served with the current data from the server.

## Known Issues

If you update the plugin by deactiavting the current version and than installing the new one, you might encounter 404 errors on the interactive editor page or the report detail page.

This can be fixed in wp-admin -> settings -> permalinks; and hitting the save. No need for any changes just hit save. This will regenerate all permalinks and pages should be working again without any problems.

### Change log

#### 1.3.5 Reporting updates

- Restricted REST api to the logged in users only
- Updated block editor header class


#### 1.3.4 Reporting updates

- Moved some of the VanillaJS to jQuery calls
- Added process that includes snapshot of the wheel as "src" tag of the image block


#### 1.3.3 Reporting updates

- Creating system that creates snapshot of the wheel and insert static version into the report

