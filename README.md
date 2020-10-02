# indipetae

# Installation

The theme has separate installation steps for PHP and Javascript. The PHP step is necessary in order to run the theme. The Javascript step is necessary only if you will be making changes to Javascript.

### PHP

Install the [Composer](https://getcomposer.org/download/) dependency manager and use it to install PHP dependencies:

```shell script
php composer.phar install
```

### Javascript

This step is only necessary if you plan to make changes to the Javascript in the *src/js* directory.

Install the [Yarn](https://classic.yarnpkg.com/en/docs/install/) dependency manager and use it to install Javascript dependencies:

```shell script
yarn install
```

## Developing

### PHP

In Omeka, logic that can't be put in plugins will invariably end up in PHP files in the view. To keep things under control, try to confine logic as much as possible to PHP-only files in the *src/php* directory instead of mixing PHP and HTML in View files.

### Javascript
Javascript modules are stored in *src/js* and are bundled into a single file (*javascripts/indipetae.bundle.js*) for deployment. The theme uses [webpack](https://webpack.js.org/) to bundle Javascript  If you plan to make changes to the Javascript in the *src/javascript* directory, you'll need to run webpack:

```shell script
yarn start
``` 