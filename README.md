### 1. Introduction:

PWA or Progressive Web Application uses web browser capabilities and provides a mobile app like experience to the users. It develops from a browser tab and makes pages more immersive with a low friction user experience. It is a web technology of making a website which acts and feels like an application. A user can launch Progressive Web Application same like a native application regardless of browser choice.

It packs in lots of demanding features that allows your business to scale in no time:

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

* **Bagisto**: v0.1.x, v0.2.1, v0.2.2.


### 3. Installation:

* Unzip the respective extension zip and then merge "packages" folder into project root directory.
* Goto config/app.php file and add following line under 'providers'

~~~
Webkul\PWA\Providers\PWAServiceProvider::class
~~~

* Goto config/concord.php file and add following line under 'modules'

~~~
\Webkul\PWA\Providers\ModuleServiceProvider::class
~~~

* Goto composer.json file and add following line under 'psr-4'

~~~
"Webkul\\PWA\\": "packages/Webkul/PWA/src"
~~~

* Install Jenssegers Agent via following command

~~~
composer require jenssegers/agent
~~~

* Add the following line under 'providers' in config/app.php

~~~
Jenssegers\Agent\AgentServiceProvider::class
~~~

* Add the following line under 'aliases' in config/app.php

~~~
'Agent' => Jenssegers\Agent\Facades\Agent::class,
~~~

* Run these commands below to complete the setup

~~~
composer dump-autoload
~~~

~~~
php artisan migrate
~~~

~~~
php artisan route:cache
~~~

~~~
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
~~~

> That's it, now just execute the project on your specified domain.
