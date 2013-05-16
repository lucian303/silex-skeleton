silex-skeleton
==============

A silex application skeleton.

It sets up Silex and common service providers such as db, logging, security, etc. Working login based on MySQL (or other RDBMS).

It uses Twig currently for simplicity (8-10% performance hit for the templating engine). Due to convenience, I can accept this for now, although a switch to Zend_View / Zend_Layout would be preferable (assuming it can be benchmarked).

The skeleton is split up and modularized for the controllers and views. This lets the framework do what it does best (server HTTP requests) while decoupling our code from the delivery mechanism (Silex). Also, it allows simple organization using the module pattern (in php using $var = require_once 'somefile.php') and efficient loading as it skips the autoloader. It loads all controller routes into the app at all times, but so does every other major framework. They are just registered callbacks and this does not have a major impact on performance.

Autoloading, even with a generated classmap still takes up 30% of the request's time. This is where real optimization efforts should be focused as this is a problem for all frameworks that use autoloading. Ie-> all except CodeIgniter.

Usage
=====

Clone the repo. Write your code. Build your dream (or nightmare).

Feature requests, bug reports, ideas, discussions, comments, are all very welcome.

Enjoy!

Lucian Hontau
http://lucianux.com/

Copyright 2013 Lucian Hontau
