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
    - [Folder structure](#folder-structure)
        - [App directory](#app-directory)
        - [Config directory](#config-directory)
        - [Framework directory](#framework-directory)
    - [Application startup process](#application-startup-process)
    - [Testing](#testing)
        - [Run unit test suite](#run-unit-test-suite)
        - [Run integration test suite](#run-integration-test-suite)

<!-- /TOC -->

## Prerequisites
Before this project base can be used, the following tools and applications must be installed and accessible through the command line:

- [Composer](https://getcomposer.org/)
- [PHP](https://www.php.net/downloads)
- [Docker](https://www.docker.com/)

### Application and tool versions
This project base has been created with the following application and tool versions:

| Name | Min. version | Max. version |
|------|--------------|--------------|
| composer | 2.4.2 | latest
| php | 8.1.0 | 8.1.x

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

## Folder structure
All code that directly provides functionality to the main application should be placed in the `src` (source) directory. Within `src`, the following folders are placed:

- `App` contains all application code.
- `Config` contains all application configuration code (dependency injection setup, ORM setup, loading .env files, etc.).
- `Framework` contains all code that provide basic functionality to the application startup process (providing a routing handler, registering dependency container definitions with the DI library, etc.)

### App directory
The `App` directory contains all application code, a layered architecture is being used by default. The app directory has the following sub directories:

**Common**<br/>
This directory contains all code that needs to be re=used throughout each layer inside the application.

**Common\Helpers**<br/>
This directory contains all helper code that needs to be re=used throughout each layer inside the application. It comes with a standard `HTTP` class that only provides a base for absolute paths.

**Presentation**<br/>
This directory contains all code for the presentation layer of the application. Within this directory, everything that provides functionality for the presentation side of your application should be placed here.

**Presentation\Middleware**<br/>
This directory contains all middleware that the presentation layer needs to use. It comes with a standard middleware class that removes trailing, and duplicate, slashes from the URL.

### Config directory
This directory contains all code that provides functionality for the startup process of the entire application. The directory comes with a singular `di.php` file that provides `DIConfig` classes. A `DIConfig` is a class that provides specific data to the dependency container.

**App**<br/>
This directory contains all configuration classes that provide functionality to the application.

**App\Autoloading**<br/>
This directory contains `DIConfig` classes that provide specific data to the dependency container. A `DIConfig` class can be used to add [definitions](https://php-di.org/doc/definition.html) to the dependency container, or run a necesary startup process (like loading .env files or setting up a database connection). There are three default `DIConfig` classes:

- `SetupDependenciesDIConfig` adds generic dependency container definitions to the dependency container.
- `SetupEnvDIConfig` loads the `.env` file content and adds the variables to the `$_ENV` superglobal.
- `SetupRouterDIConfig` adds routing capability to the application (this is in a separate `DIConfig` class since this process should not be edited).

### Framework directory
This directory contains all code that provide basic functionality to the application startup process. The framework directory can also include general helpers and utilities, but this only should be done when this is necessary for the startup process, otherwise a `Common` directory of some sorts should be created inside the `./src/App` directory.

## Application startup process
It is very important to understand how this application base starts up and configures itself before a route can be registered. The application startup process goes as follows:

1. Create a dependency container
2. Create a base application class that bootstraps a [Slim framework]() `App` instance.

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
