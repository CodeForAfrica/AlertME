# Installation

\#GreenAlert is built using [Laravel](#laravel-php-framework), *the PHP framework for web artisans*. For more information on getting started with Laravel, check out there extensive documentation [here](http://laravel.com/docs/4.2/quick).


#### Requirements

- PHP v5.4.7+
- Git
- Composer
- Beanstalkd
- MySQL
- Ruby (for Mailing)
- 256MB RAM


### Server Configuration

We recommend setting up with [Debian Wheezy](https://www.debian.org/releases/wheezy/). #GreenAlert will though be able to run on any system setup that meets the [requirements](#requirements).

##### 1. Install LEMP Stack

After spinning up your instance, you should install Linux, Nginx, MySQL, PHP (LEMP). [Here is a great step by step guide for Debian 7](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-debian-7).

##### 2. Install PHP Command Line Interface

```bash
sudo apt-get install php5-cli
```

##### 3. Install Git

Git is a free and open source distributed version control system designed to handle everything from small to very large projects with speed and efficiency.

- On Debian you can install it by running `sudo apt-get install git`.
- For other systems, you can find installation instructions [here](http://git-scm.com/downloads).

##### 4. Install Composer

[Composer](https://getcomposer.org/) is *the* dependency manager for PHP.

- Install on *nix - https://getcomposer.org/doc/00-intro.md#installation-nix

Recommended: [Install composer globally](https://getcomposer.org/doc/00-intro.md#globally).

##### 5. Install PHP v5.5 on Debian 7 "Wheezy"

Currently, Debian Wheezy [ships with PHP v5.4.4-14+deb7u14](https://packages.debian.org/wheezy/php5). To install PHP v5.5, add the following lines to `/etc/apt/sources.list`:

```debian
deb http://packages.dotdeb.org wheezy all
deb-src http://packages.dotdeb.org wheezy all
deb http://packages.dotdeb.org wheezy-php55 all
deb-src http://packages.dotdeb.org wheezy-php55 all
```

Fetch and install the GnuPG key

```bash
wget http://www.dotdeb.org/dotdeb.gpg
sudo apt-key add dotdeb.gpg
```

To read more on the above, check out http://www.dotdeb.org/instructions/.

Update and upgrade PHP by running the following commands:

```bash
sudo apt-get update
sudo apt-get dist-upgrade php*
```

##### 6. Install MCrypt PHP Extension

The Laravel Framework [requires MCrypt PHP extension](http://laravel.com/docs/4.2/installation#server-requirements). To install it on debian, run the following command:

```bash
sudo apt-get install php5-mcrypt
```

##### 7. Install Beanstalkd

For queue services, #GreenAlert uses Beanstalkd. This allows us to defer the processing of a time consuming task, such as sending an e-mail, until a later time, thus drastically speeding up the web requests to the application.

On Debian, this can be installed by running the following command:

```bash    
sudo apt-get install beanstalkd
```

##### 6. Install Supervisor

To keep background tasks running e.g listening for queues to process jobs, we will need supervisor. Installation on Debian would be by running the following command:

```bash
sudo apt-get install supervisor
```

You can learn more about installing and managing supervisor [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-manage-supervisor-on-ubuntu-and-debian-vps).


##### 7. Ruby Requirements

```bash
sudo apt-get install ruby-dev

# Uninstall
sudo apt-get purge libruby1.9.1 ruby-dev ruby1.9.1 ruby1.9.1-dev
```

Install gems

```bash
sudo gem install premailer getopt nokogiri
```

##### 8. Update MySQL

```bash
sudo apt-get install mysql-server-5.6
```


##### 9. Install NPM , Grunt & Bower

```bash
curl -sL https://deb.nodesource.com/setup | sudo bash -
sudo apt-get install -y nodejs
```

*Optional*: if you run `node` and `npm` in shell and they don't work, you can fix them as such:

```bash
sudo ln -s /usr/bin/nodejs /usr/bin/node

curl -L https://npmjs.org/install.sh | sudo sh
```

Now let's install `grunt-cli` and `bower` as such:

```bash
sudo npm install -g grunt-cli
sudo npm install -g bower
```



### Application Set Up

Now that we have our server set up, we can set up the application. Here, we clone the main repository and install the needed packages. At the end, we should be able to see a welcome page displayed on the browser.

##### 1. Clone From Github

Cloning from github is as simple as running the following command:

```bash
git clone https://github.com/CodeForAfricaLabs/GreenAlert.git
```

(Optional) You can checkout the branch (recommended: `master` (default), `develop`) you want by running the following commands:

```bash
cd GreenAlert
git checkout [<branch>]
```

##### 2. Install Dependencies

Run the `composer install` command in the root of the cloned project's directory. This command will download and install the dependencies.

##### 3. Database Configuration

In this step, we'll use the simple MySQL but feel free to explore with any other Database you like. Currently Laravel supports four database systems: MySQL, Postgres, SQLite, and SQL Server; out of the box.

First, we create the `greenalert` user and `cfa_greenalert` database. Connect to the server as MySQL `root` user using the command `mysql -u root -p` and then run the following:

```mysql
mysql> GRANT ALL PRIVILEGES ON *.* TO 'greenalert'@'localhost'
    -> IDENTIFIED BY 'YOUR_DB_PASSWORD' WITH GRANT OPTION;
mysql> SHOW GRANTS FOR 'greenalert' @ 'localhost';
mysql> CREATE DATABASE cfa_greenalert;
mysql> GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP
    -> ON cfa_greenalert.*
    -> TO 'greenalert'@'localhost';
```


##### 4. Sensitive Configuration

Edit `.env.examlple` file name to `.env` and contain the:

You can generate `YOUR_SECRET_KEY` by running `php artisan key:generate` and copying the resulting key into `.env`

Read more on configuration [here](http://laravel.com/docs/5.0/configuration).

##### 5. Migration

With the database set up, we can do the database migration. To do this, run the `php artisan migrate` command in the root of the cloned project's directory.

Optional: You can define the environment by adding `--env=[<environment>]` argument. For example `php artisan migrate --env=local`.

##### 6. Queue Configuration

Queue configuration to run tasks in the background requires mainly configuration of supervisor to initiate the listener. Create and edit the `/etc/supervisor/conf.d/greenalert.conf` file. In the file, add the following:

```supervisor
[program:beanstalkd]
command=beanstalkd

[program:greenalert_queue]
command=php artisan queue:listen --timeout=0 --memory=256 --tries=5 --queue=greenalert,default
directory=/path/to/GreenAlert
stdout_logfile=/path/to/GreenAlert/app/storage/logs/supervisor_queue.log
```

Once saved, reload supervisor as such:

```bash
sudo supervisorctl
supervisor> reload
supervisor> exit
```

Running `sudo supervisorctl` again, should show you the programs running.


##### 7. Configure Nginx

First create ssl keys:

```bash 
sudo mkdir /etc/nginx/ssl
cd /etc/nginx/ssl
sudo openssl genrsa -des3 -out server.key 2048
sudo openssl req -new -key server.key -out server.csr

# Remove passphrase ?
sudo cp server.key server.key.org
sudo openssl rsa -in server.key.org -out server.key

# Sign SSL Certificate
sudo openssl x509 -req -days 365 -in server.csr -signkey server.key -out server.crt

# Confirm Certificate
sudo cp /etc/nginx/ssl/* /usr/local/share/ca-certificates/
sudo update-ca-certificates -f
```

You can read more on SSL with nginx [here](https://www.digitalocean.com/community/tutorials/how-to-create-a-ssl-certificate-on-nginx-for-ubuntu-12-04).

Finally, to see the page on *example.com* you would need to add the file `/etc/nginx/sites-available/greenalert` with the following:

```nginx
server {
  listen   443;

  root /path/to/GreenAlert/public;
  index index.php index.html index.htm;

  server_name example.com greenalert;

  ssl on;
  ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
  ssl_prefer_server_ciphers on;
  ssl_ciphers "ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES256-GCM-SHA384:AES128-GCM-SHA256:AES256-SHA256:AES128-SHA256:AES256-SHA:AES128-SHA:DES-CBC3-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!MD5:!PSK:!RC4";
  ssl_certificate /etc/nginx/ssl/server.crt;
  ssl_certificate_key /etc/nginx/ssl/server.key; 

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;

    fastcgi_pass unix:/var/run/php5-fpm.sock;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
  }

}

server {
  listen   80;

  server_name example.com greenalert;

  return 301 https://$server_name$request_uri;

}
```

Enable the site by creating a symlink:

```bash
sudo ln -s /etc/nginx/sites-available/greenalert /etc/nginx/sites-enabled/greenalert
sudo service nginx restart
```

Now if you visit *example.com*, you will be able to see the basic #GreenAlert website loaded.


### Upgrading the Platform

We've created a simple command to upgrade the platform from the git branch you are on.

In the root folder, simply run:
```bash
php artisan pahali:upgrade
```

<small>*Sometimes the command has also been updated so try to run it twice if things don't look right.*</small>

***Note: This command does a hard reset for git so please make sure to check the status of all tracked files.***



### Loading Data

##### [ Coming Soon ]

Hint: It's all about the Dashboard.



### License

We care about sharing improvements.

The GPL ([V2](http://choosealicense.com/licenses/gpl-2.0/) or [V3](http://choosealicense.com/licenses/gpl-3.0/)) is a copyleft license that requires anyone who distributes your code or a derivative work to make the source available under the same terms. V3 is similar to V2, but further restricts use in hardware that forbids software alterations.

**Linux**, **Git**, and **WordPress** use the GPL.

Find out more by checking out the `LICENSE` file [here](./LICENSE).


### Contact

Have any questions? Feel free to reach us at [kazini@codeforafrica.org](mailto:kazini@codeforafrica.org).



---



## Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/downloads.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, and caching.

Laravel aims to make the development process a pleasing one for the developer without sacrificing application functionality. Happy developers make the best code. To this end, we've attempted to combine the very best of what we have seen in other web frameworks, including frameworks implemented in other languages, such as Ruby on Rails, ASP.NET MVC, and Sinatra.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing To Laravel

**All issues and pull requests should be filed on the [laravel/framework](http://github.com/laravel/framework) repository.**

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
