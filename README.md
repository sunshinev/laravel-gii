# Laravel-Gii Visual code generation tool CRUD + GUI

[![Packagist Version](https://img.shields.io/packagist/v/sunshinev/laravel-gii)](https://packagist.org/packages/sunshinev/laravel-gii)
[![Travis (.com)](https://img.shields.io/travis/com/sunshinev/laravel-gii)](https://travis-ci.com/sunshinev/laravel-gii/)
![GitHub last commit](https://img.shields.io/github/last-commit/sunshinev/laravel-gii)
![GitHub](https://img.shields.io/github/license/sunshinev/laravel-gii)
![GitHub repo size](https://img.shields.io/github/repo-size/sunshinev/laravel-gii)
![GitHub stars](https://img.shields.io/github/stars/sunshinev/laravel-gii?style=social)
![GitHub forks](https://img.shields.io/github/forks/sunshinev/laravel-gii?style=social)

It is suitable for rapid B-side background development, and generates corresponding Model, Observer, Controller, View, Route and other related project files according to the MySQL table structure.

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-preview.gif)
[中文文档](https://github.com/sunshinev/laravel-gii/blob/master/README_zh_CN.md)

[TOC]

## Installation

### Expansion Pack

```
Composer require sunshinev/laravel-gii -vvv
```

### Post
> This operation will publish assets static files to the public directory

```
php artisan vendor: publish
```
select
`Tag: laravel-gii`


### Visit
`http: [domain]/gii/model`


## Instructions


### Generate a Model with One Click

Form description

1. Table name (support drop-down selection)
2. Model class name (want to create model classes, including namespaces)
3. Parent class of model inheritance (If it is Mongo, it can inherit `Jenssegers\Mongodb\Eloquent\Model`, MySQL uses` Illuminate\Database\Eloquent\Model`)


The generated file list, blue represents a new file, red represents an existing file but different, and white represents an existing file.

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/success.png)

### Generate CRUD files with one click

The creation of CRUD depends on the previously created model.

This operation will also generate:

- route
- controller
- views

Form description

1. Controller name (including namespace)
2. The model class created earlier

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/controller.png)

#### File comparison

The tool will compare the newly generated file with the existing file, which is convenient for viewing the modified part and controlling the scope of the modification.

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-generate.gif)

#### Route Append
Newly generated pages are automatically appended to the routing configuration file
![b58eb0bd955bafea27540d6227e611731576209355.jpg](https://github.com/sunshinev/remote_pics/raw/master/b58eb0bd955bafea27540d6227e611731576209355.jpg)


## CRUD background effects

#### List
Includes comprehensive additions, deletions, and changes

- List
- Pagination
- Retrieve
- Delete + batch delete
- Preview
- Details
- Edit

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_list.png)
#### delete + batch delete
Cancel button zoom in to prevent accidental deletion

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_delete.png)

#### Row Preview
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_view.png)

#### Edit page
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_edit.png)

## Suggest

#### What if you want to use Mongo?
If the Model is generated, the connection configured in the env will be used by default. If you need to adjust, you need to modify the generated Model file.
```php
    // if connection is empty, use default connection
    protected $ connection = '';
```

#### How do background pages support other components?
The background page uniformly uses iview as the front-end framework. Currently, all components of iview4 are supported. You can add components directly to the generated blade template file.

[iviewuidocument](https://www.iviewui.com/docs/introduce)

#### How to upgrade the iview component of the page?
The generated CRUD background is based on [laravel-fe-render](https://github.com/sunshinev/laravel-fe-render) and [base-fe](https://github.com/sunshinev/base -fe) Two projects, of which `base-fe` is a package of Vue + iview, as follows:

```js
import Vue from 'vue'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use (ViewUI);
```

You can Fork the `base-fe` project, and then upgrade the iview. Place the generated dist directory in the` assets` directory of the `laravel-fe-render` project, and then republish it.



## Relevant information

[https://github.com/sunshinev/laravel-fe-render](https://github.com/sunshinev/laravel-fe-render)

[https://github.com/sunshinev/base-fe](https://github.com/sunshinev/base-fe)


[https://github.com/sunshinev/laravel-gii](https://github.com/sunshinev/laravel-gii)