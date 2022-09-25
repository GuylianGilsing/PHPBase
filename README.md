# PHPBase
A simple base project for custom PHP applications.

## Table of Contents

<!-- TOC -->

- [PHPBase](#phpbase)
    - [Table of Contents](#table-of-contents)
    - [Prerequisites](#prerequisites)
        - [Application and tool versions](#application-and-tool-versions)
        - [Required PHP extensions](#required-php-extensions)
        - [Running commands](#running-commands)
    - [Project setup](#project-setup)
        - [Install composer dependencies](#install-composer-dependencies)
        - [Create environment file](#create-environment-file)
    - [Webserver setup (optional)](#webserver-setup-optional)
        - [Application root directory](#application-root-directory)
        - [Request redirects](#request-redirects)
            - [Apache](#apache)
            - [Nginx](#nginx)
    - [Running the project locally](#running-the-project-locally)
    - [Static code analysis](#static-code-analysis)
        - [Running the tool](#running-the-tool)
        - [Receive a detailed report for your written source code](#receive-a-detailed-report-for-your-written-source-code)
        - [Configuring rules](#configuring-rules)
    - [Testing](#testing)
        - [Run unit test suite](#run-unit-test-suite)
        - [Run integration test suite](#run-integration-test-suite)

<!-- /TOC -->

## Prerequisites
Before this project base can be used, the following tools and applications must be installed and accessible through the command line:

- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/downloads)
- [Docker](https://www.docker.com/)
- [Docker compose](https://docs.docker.com/compose/install/)

### Application and tool versions
This project base has been created with the following application and tool versions:

| Name | Min. version |
|------|--------------|
| composer | 2.4.2 |
| php | 8.1.0 |

### Required PHP extensions
The following extensions must be activated inside the `php.ini` file:

- curl
- fileinfo
- openssl

### Running commands
Within this README file, there will be commands listed. All these commands must be run from the root directory. The root directory is the directory that this README file is located in.

**Note:** The version of composer does not matter, as long as it is version 2.

**IMPORTANT**: The listed versions have been used in the development of this project base and should work without any problems. Be mindful about other dependencies that you may add since they could not provide support for the given PHP version.

## Project setup
Before the project can be started, a setup process must be done first.

### Install composer dependencies
```bash
$ composer install
```

### Create environment file
It is important that the contents of the `example.env` file is copied  to a new `.env` file. The contents of the `.env` file are read at the start of each request to the application. Not creating this file will result in an exception error.

## Webserver setup (optional)
When running this application on a webserver, make sure that the following setup process is done.

**Note**: For more information about a web server setup, visit the [slim framework documentation](https://www.slimframework.com/docs/v4/start/web-servers.html).

### Application root directory
The root directory of the entire application is located within the `public` folder. When using an external webserver, make sure that the root directory inside your configuration is set to this folder.

### Request redirects
All requests should be redirected to the index.php file.

#### Apache
Make sure that the following apache modules are actived:

- rewrite
- actions

```htaccess
# Redirects all incomming requests (and failed file paths) to the index.php file
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

#### Nginx
The following Nginx configuration can be used:

```nginx
server {
    listen 80;
    server_name example.com;
    index index.php;
    error_log /path/to/example.error.log;
    access_log /path/to/example.access.log;
    root /path/to/public_folder;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_index index.php;
        fastcgi_pass 127.0.0.1:9123;
    }
}
```

## Running the project locally

```bash
$ cd public
$ php -S 127.0.0.1:8080
```

## Static code analysis
[PHP insights](https://phpinsights.com/) is used, and configured, to provide a static code analysis report of the application source code.

### Running the tool
```bash
$ ./vendor/bin/phpinsights.bat
```

### Receive a detailed report for your written source code
```bash
$ ./vendor/bin/phpinsights.bat analyse -v
```

### Configuring rules
PHP insights's analysis rules can be configured through the `phpinsights.php` file inside the root directory.

## Testing
[PHP unit](https://phpunit.de/) has been set up and configured with two testing suites: unit and integration.

### Run unit test suite
```bash
$ ./vendor/bin/phpunit --configuration phpunit.xml --testsuite unit
```

### Run integration test suite
```bash
$ ./vendor/bin/phpunit --configuration phpunit.xml --testsuite integration
```
