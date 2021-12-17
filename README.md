## 
## Install for Lumen

**1.** Open file `bootstrap/app.php` and add new service provider

```
$app->register(\ItDevgroup\LaravelDeveloperDocs\Provider\DeveloperDocsServiceProvider::class);
```
Uncommented strings

```
$app->withFacades();
```

Added after **$app->configure('app');**

```
$app->configure('developer_docs');
```

**2.** Run commands

For creating config file

```
php artisan developer:docs:publish  --tag=config
```

## Install for laravel

**1.** Open file **config/app.php** and search

```
    'providers' => [
        ...
    ]
```

Add to section

```
        \ItDevgroup\LaravelDeveloperDocs\Provider\DeveloperDocsServiceProvider::class,
```

Example

```
    'providers' => [
        ...
        \ItDevgroup\LaravelDeveloperDocs\Provider\DeveloperDocsServiceProvider::class,
    ]
```

**2.** Run commands

For creating config file

```
php artisan vendor:publish --provider="ItDevgroup\LaravelDeveloperDocs\Provider\DeveloperDocsServiceProvider" --tag=config
```

## ENV variables

File .env

Enable documentation (1 - enabled, 0 - disabled)

```
DEVELOPER_DOCS_ENABLE=1
```

Route for documentation (default: http://{{HOST}}/developer-docs). Need to remove env if default path doesn't change

```
DEVELOPER_DOCS_ROUTE_PREFIX=developer-docs
```

## Create documentation files

Default folder: developer-docs

1. in the main folder and all child folders, there should always be a file `index.html`
   
2. each folder is a category which should always have an index.html file

3. all files must contain a title tag on the first line, 2 and further lines must be content

4. all links to internal pictures and internal pages of the documentation must be indicated relative to the root folder of the documentation

Example file structure:

- /developer-docs/index.html
- /developer-docs/about.html
- /developer-docs/other.html
- /developer-docs/migration/index.html
- /developer-docs/migration/seeds.html
- /developer-docs/controller/index.html
- /developer-docs/controller/resource.html
- /developer-docs/controller/context/index.html
- /developer-docs/controller/context/api.html
- /developer-docs/controller/context/dashboard.html

Example file content:

```
<title>Main</title>
<p>text</p>
<img src="img/1.jpeg">
<img src="http://google.com/img/1.jpeg">
<a href="migration/seeds.html">Internal link</a>
<a href="http://google.com">External link</a>
```
