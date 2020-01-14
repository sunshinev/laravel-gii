# Laravel-Gii Visual code generation tool CRUD + GUI

>Laravel GII provides a new possibility for small and medium-sized projects to quickly create management background. In the process of using, you will find that it is very light and has a high degree of freedom.
>
>In particular, developers who are familiar with iView can customize the page according to their own needs and change the default input to other rich iView components
>
>Thank you for your support ^^

[![Packagist Version](https://img.shields.io/packagist/v/sunshinev/laravel-gii)](https://packagist.org/packages/sunshinev/laravel-gii)
[![Travis (.com)](https://img.shields.io/travis/com/sunshinev/laravel-gii)](https://travis-ci.com/sunshinev/laravel-gii/)
![GitHub last commit](https://img.shields.io/github/last-commit/sunshinev/laravel-gii)
![GitHub](https://img.shields.io/github/license/sunshinev/laravel-gii)
![GitHub repo size](https://img.shields.io/github/repo-size/sunshinev/laravel-gii)
![GitHub stars](https://img.shields.io/github/stars/sunshinev/laravel-gii?style=social)
![GitHub forks](https://img.shields.io/github/forks/sunshinev/laravel-gii?style=social)

It is suitable for rapid B-side background development, and generates corresponding Model, Observer, Controller, View, Route and other related project files according to the MySQL table structure.

[中文文档](https://github.com/sunshinev/laravel-gii/blob/master/README_zh_CN.md)
[![e1daf65668566cd8f7dd417211820a091576311651.jpg](https://github.com/sunshinev/remote_pics/raw/master/e1daf65668566cd8f7dd417211820a091576311651.jpg)](https://sunshinev.github.io/laravel-gii-home/index_en.html)


[TOC]

## Installation

### Expansion Pack

```
composer require sunshinev/laravel-gii -vvv
```

### Post
> This operation will publish assets static files to the public directory

```
php artisan vendor:publish  --tag laravel-gii
```


### Visit
`http: [domain]/gii/model`


## Instructions

### Generate Model

Form description

1. Table name (support drop-down selection)
2. Model class name (want to create model classes, including namespaces)
3. Parent class of model inheritance (If it is Mongo, it can inherit `Jenssegers \ Mongodb \ Eloquent \ Model`, MySQL uses` Illuminate \ Database \ Eloquent \ Model`)


The generated file list, blue represents a new file, red represents an existing file but different, and white represents an existing file.

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-model-preview.gif)


### Generate CRUD

The creation of CRUD depends on the previously created model.

This operation will also generate:

- route
- controller
- views

Form description

1. Controller name (including namespace)
2. The model class created earlier

![85bce766f1a574d97ac931c8b98c29591576222771.jpg](https://github.com/sunshinev/remote_pics/raw/master/85bce766f1a574d97ac931c8b98c29591576222771.jpg)

## Features
#### MySQL list loading
Pull list from configured MySQL database
![135efc4b0abc7a7baf67793fb8de30901576222494.jpg](https://github.com/sunshinev/remote_pics/raw/master/135efc4b0abc7a7baf67793fb8de30901576222494.jpg)

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

![a7124e651545e7f462e898ffb37704a41576223115.jpg](https://github.com/sunshinev/remote_pics/raw/master/a7124e651545e7f462e898ffb37704a41576223115.jpg)

#### delete + batch delete
Cancel button zoom in to prevent accidental deletion

![fa926f38e95888fd4a3c3aa055d202f41576223165.jpg](https://github.com/sunshinev/remote_pics/raw/master/fa926f38e95888fd4a3c3aa055d202f41576223165.jpg)


#### Row Preview
![0b2b1603c043aec9b5cee84a17e26f291576223207.jpg](https://github.com/sunshinev/remote_pics/raw/master/0b2b1603c043aec9b5cee84a17e26f291576223207.jpg)


#### Edit page
![bc43b30f13de17e0a2a899a59f647d3a1576223248.jpg](https://github.com/sunshinev/remote_pics/raw/master/bc43b30f13de17e0a2a899a59f647d3a1576223248.jpg)


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
