# Bagisto PWA

### 1. Introduction:

PWA or Progressive Web Application uses web browser capabilities and provides a mobile app experience to the users.  
It develops from a browser tab and makes pages more immersive with a low friction user experience.  
It is a web technology of making a website which acts and feels like an application.  
A user can launch the Progressive Web Application same like a native application regardless of browser choice.

It packs with lots of demanding features that allows your business to scale in no time:

* Separate micro site.
* More user friendly than a web application.
* Works lightning fast if compared to the website.
* Completely responsive on all the platforms.
* Launches without the internet or low-quality internet.
* Looks and feels like a native application.
* Users do not need to update progressive web application.
* No app store require managing the application.
* Increases user engagement on the store.
* Increases the store revenue due to user engagement.
* Admin can enter the application name.
* Admin can upload and change the application icon.
* Admin can set the splash background color of the Progressive Web Application.
* Admin can set the theme color of the Progressive Web Application.


### 2. Requirements:

* **Bagisto**: v1.3.0.


### 3. Installation:
* Install the PWA extension
```
composer require bagisto/pwa:dev-master
```

* Run these commands below to complete the setup

```
php artisan config:cache
```

```
php artisan migrate
```

```
php artisan route:cache
```

```
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
```

> That's it, now go to https://yourdomain/pwa
