## Mini-Blog

This is a mini-blog that shows the posts which are posted by the admin or any user that has an account. As a visitor one can see all the published posts. Anyone can sign up and make their own post. The posts can be only created but can not be updated or deleted.

In addition, the admin can import posts from a different URL. For that, he needs to go with some procedures. The posts are being cached by the system for a certain period of time. After that, it will be cleared until the new cache is not stored. For caching **Redis** has been used which is a very popular in-memory database.


## Project Setup

#### setup composer
After cloning the project, go to the project directory and open the terminal and hit

``composer install --no-dev``

This command will install all the dependencies for the project skipping the dev dependencies. If you want to the dev
dependencies to be installed ignore ``--no-dev`` and simply hit ``composer install``

Then add a .env file in the root of the project directory and copy the .env.example contents or you may copy the .env.example file and paste in the root directory and rename it to .env

Next, you should fill up the **APP_KEY** in the .env file with a long random string, or you hit the command below

``php artisan key:generate``

#### setup database
You need to fill up the database credentials in the .env file. Create a database according to your connection. The following fields need to fill up in the .env file


```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

#### Setup cache driver

As we are using **Redis** we need to tell our system to use `redis` as our cache and queue driver. For that fill the following fields in your .env file

``CACHE_DRIVER=redis``

``QUEUE_CONNECTION=redis``

Optionally you can set your own prefix for caching. For that, you need to fill

``CACHE_PREFIX=``

Next, we need to set up the Redis client credentials. We will be using the ``predis/predis`` package.

```
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Prerequisite setup to import post from other site/blog
In order to import data from other sites/blogs, an `IMPORT_URL` key needs to be present in the .env file with a valid URL of the site from which the data is to be fetched.

``IMPORT_URL=``

Optionally if you want to get notified after data import fill up the following fields (for test purposes fill up those fields with mailtrap credential)

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

NOTIFY_ADMIN=true # false for not sending notification
```

Next you need to set admin's info

```
ADMIN_NAME=
ADMIN_EMAIL=
```

#### Database migrations & queue worker
Now it's time for migrating. Hit the command below

`php artisan migrate`

An 'admin' is needed by default for the system. To generate an admin, simply hit the command below

`` php artisan db:seed``

You can combine the migrate and seed command in one by following

``php artisan migrate --seed``

A queue worker is needed to run the queue in the background. For that hit the following command

`` php artisan queue:work ``

#### Running the test cases

Before running test cases you need to configure your IDE and set the interpreter to run phpunit. Additionaly you should
have setup something like this in the phpunit.xml file.
```
<php>
<server name="APP_ENV" value="testing"/>
<server name="BCRYPT_ROUNDS" value="4"/>
<server name="CACHE_DRIVER" value="array"/>
<server name="DB_CONNECTION" value="sqlite"/>
<server name="DB_DATABASE" value=":memory:"/>
<server name="MAIL_MAILER" value="array"/>
<server name="QUEUE_CONNECTION" value="sync"/>
<server name="SESSION_DRIVER" value="array"/>
<server name="TELESCOPE_ENABLED" value="false"/>
</php>
```

If all goes well, you can run your test suite simply by hitting the command below

`` php artisan test``

#### Console command to import post from other site

To import post from another blog you have two options. You can log in as an admin and click the 'Import Post' button in the top
right corner of your panel. Then it will redirect you to your home page and the posts will be fetched in the background.

Or you can simply hit the command below to import post

`` php artisan import:posts``

This command optionally takes two parameters. First one is the url from where data will be fetched. Secondly the user id, against whom
the fetched post will be saved. Both are optional. If no url is provided, the url will be taken from
the `IMPORT_URL` key in .env file. And in case the user id is not provided, the posts will be saved against the default admin.

***N.B: This command is a helper command for site admin. User has nothing to do with it.***

#### Running the application
Before running the application you need to install the node modules in your project. Hit the following commands to install them

`npm install && npm run dev`

You're ready to go. Serve the application and visit it at the url provided in the terminal.

`php artisan serve`

For better performance you can cache the route and config and dump the auto-loads by the following commands

```
php artisan config:cache
php artisan route:cache
php artisan optimize --force
composer dumpautoload -o
npm run production
```
