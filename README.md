# onecore - The single file CMS

onecore consists of one single file which contains the onecore-source (PHP 5)
and the content of the website, too. Imagine, you have one HTML site - a manual,
a frontpage, a linklist or something like that - and you want to edit this page
without any external editor. Then onecore is perfect for you. Onecore is HTML
page and content in one file.

Note that onecore is mostly a proof of concept and by no means secure.

## Features

* Administration area
* Password protection (optional)
* Configuration form
* Import / Export functionality
* Editor which supports tabs
* JavaScript preview

## Technology & requirements

Onecore is an HTML site containing some PHP and JavaScript which make it
possible to edit the HTML part of the site without any additional tool.

### Server requirements

- PHP 5+
- Write permisions for PHP on the file

### Client requirements

- Web browser (of course)
- JavaScript support

## News
- 2010-10-12 Version 1.0.0 stable released
- 2007-07-31 Version 0.1.0 beta b005 released

## Installation

To install onecore, simply copy it to a directory inside your web root and name
it as you want. Make sure that the file extension tells your webserver to
activate PHP. Important is the write permission for PHP respective the webserver
on this file.

After that, launch onecore (https://yoursite.com/onecoreFileName.extension).

Now you see a HTML-Page with sample content. To open the administration
area, simply go to https://yoursite.com/onecoreFileName.extension?admin.
