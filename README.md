garage-web
==========

2nd hand car website - simple catalog site for customer, but for site owner it has admin pages for entering new car details, adding photos, arranging layout of photo and text. php-mysql driven.

I wrote this as a side project for a friend, a few years into my first job. Requirement-wise 90% completed, but due to financial qualification exam I was taking, I had no more free time to work on this and the project died.

As this was done many years ago, there's no new javascript effects or ajax stuff. But I did spend some good many hours on this even though I didn't have a lot of dev experience then. So I'm putting it up as an example of work.



Features
--------

- Home page: vehicle list (name + price). Show top N entries
- Showroom view (menu - all): list of car basic spec & thumbnail photo
- Showroom view (menu - page per type)
- Detail view: spec, photos, descriptions (text blocks)
- About page
- Contact page
- Admin mode
    - Login
    - Add new car: enter spec, thumbnail photo
    - Layout edit mode: drag spec block, photos, description blocks, save
    - Add (upload) photos
    - Edit spec
    - Add/Edit description text blocks
    - Delete car


Screenshots
-----------

![Showroom all](/resources/screenshots/web/showroom_all.jpg)

![Admin add new](/resources/screenshots/web/admin_add_new.jpg)

![Admin edit details](/resources/screenshots/web/admin_edit_details.jpg)


Installation
------------


1. Unpack to site root, /wwwroot will be the public_html root
1. Set /wwwroot/car_img/ to allow rwx by Apache process group
1. Edit Apache configs to add as new site.
1. Create a MySQL db, run script /resources/database.txt to create the tables and populate with static data
1. In /wwwroot/db_open.php, edit the dev box IP and database details


Design notes
------------

TODO
need to specify width/height for spec/detail box, or drag will not work
homepage cars table display

    add_new.php
     |
    create new car, CarMain info
     |
    edit_detail.php
     CarMain, CarDetail, CarDesc, DetailPic, LargePic, DetailLayout

    change_pic.php
     DetailPic

    show_detail.php
