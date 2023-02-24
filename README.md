==========================================
LOTTERY FEED
==========================================

GIT: https://github.com/pdolinaj/lottery-feed

Programming Language: PHP 8.0 / Laravel Framework 8
Database: MySQL


PROJECT REQUIREMENTS
====================
We need to build automated system which will import all USA lotteries into MySQL database.

This can be done either by importing any available lottery feeds we can find or scraping lottery results websites.

For scraping we can use this website:

https://lottery.com/

OR

https://www.lotteryusa.com/

OR any other reliable source.


Full list of USA lotteries can be found here:

https://lottery.com/us-lotteries/
OR HERE: https://www.lotteryusa.com/

Here they also show old retired lottery games which we are not interested in. The way to spot them is to see the latest draw results. If it has old results, it's most likely retired and we won't need it.

Best is to Google, for example: "California Lottery Official Site"

https://www.calottery.com/

And then you will see what lotteries they play there.

Obviously, if you can scrape the results directly from the official website, that would be the best!



MULTISTATE LOTTERIES
====================

There are several lotteries which are multi-state, e.g. multiple states are participating in them.

This is the full list: https://www.lotterypost.com/results/xx

- MEGA MILLIONS
- POWER BALL
- LUCKY FOR LIFE
- LOTTO AMERICA
- CASH 4 LIFE
- MEGABUCKS
- GIMME 5
- 2 BY 2

So for example if you see results for California Powerball or Florida Powerball => it's the same thing so no need to import multiple times.


DATABASE TABLES NAMING STANDARDS
================================

We are going to standardise the table names in the database so it's logically easy to find.

The table format will be: <COUNTRY>_<STATE_CODE>_<LOTTERY_NAME>

E.g. Arizona Pick 3: "usa_az_pick_3"

For "Multistate" lotteries, we skip the <STATE_CODE>.

E.g. MegaMillions: "usa_megamillions"



USE PACKAGES FOR SCRAPING WORK
==============================

You can use existing packages to do the scraping to make the life easier.

I've installed this one: https://packagist.org/packages/weidner/goutte

But feel free to use others.



DATABASE MIGRATIONS
===================

Use database migrations to create new tables so I can be in sync with your work and you can be in sync with mine when I add new things.

https://laravel.com/docs/8.x/migrations

REST API
========

Build REST API which will provide the lottery results.
We will use https://laravel.com/docs/8.x/sanctum

USER PAGE
=========

Build simple user page where users can register, providing:
- first name
- last name
- email
- password

Upon registration, they'll be able to generate the API key via Laravel Sanctum.

PERMISSIONS
===========
We will be using https://packagist.org/packages/spatie/laravel-permission when/if we introduce user roles, e.g. role "user", "admin", ... etc
