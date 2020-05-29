
# Script to read all files shared with an especific service account #

## Requirements ##

1- PHP 5.4 or greater with the command-line interface (CLI) and JSON extension installed.

2- The Composer dependency management tool.

3- Google Cloud Platform project with the Drive API enabled.

4- A Service Account for the Google Cloud Platform project.

## Installation ##

### Composer

The preferred method is via [composer](https://getcomposer.org/). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require google/apiclient:"^2.0"
```

Finally, be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

### Credentials of the Service Account

Copy the credential json file as 'serviceAccountCredentials.json' in root folder.

## Use ##

```php
php readSharedWithMe.php;
```

If everything goes well, a folder will be created with the exact jerarchy including all folders that had been shared in Google Drive 
with the service account email address. This last statement needs to be taken into consideration carefully: The Folders to be
shared need to be done to the "service account email address", not to the user owning the project (or the service account for that matter).

When Sharing a Folder jerarchy it's only needed to share the root folder, not the contained ones (or it's files).

E.G.:

Folder 1    (Shared with the service account email)
   |
   File 1   (No need for sharing)
   |________________
   |               |
   Folder 2      Folder 3   (No need for sharing)
      |
    File 2                  (No need for sharing)
    File 3                  (No need for sharing)


## TODO ##

Make it a class and publish it in https://packagist.org (follow https://www.w3resource.com/php/composer/create-publish-and-use-your-first-composer-package.php)

Improve usage documentation (how to create project in google cloud, service account, etc.)