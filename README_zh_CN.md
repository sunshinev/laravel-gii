# Laravel-Gii 可视化代码生成工具  CRUD +GUI 

[![Packagist Version](https://img.shields.io/packagist/v/sunshinev/laravel-gii)](https://packagist.org/packages/sunshinev/laravel-gii)
[![Travis (.com)](https://img.shields.io/travis/com/sunshinev/laravel-gii)](https://travis-ci.com/sunshinev/laravel-gii/)
![GitHub last commit](https://img.shields.io/github/last-commit/sunshinev/laravel-gii)
![GitHub](https://img.shields.io/github/license/sunshinev/laravel-gii)
![GitHub repo size](https://img.shields.io/github/repo-size/sunshinev/laravel-gii)
![GitHub stars](https://img.shields.io/github/stars/sunshinev/laravel-gii?style=social)
![GitHub forks](https://img.shields.io/github/forks/sunshinev/laravel-gii?style=social)


> Laravel Gii 为中小型项目快速创建管理后台，提供了一种新的可能。使用的过程中，你会发现很轻量，自由度很高，内部实现逻辑简单。
> 
> 特别是熟悉iView的开发者，在通过Gii生成的页面上，可以根据自己的需求自定义页面，通过修改默认Input组件为其他功能丰富的iView组件，可以来构造更加复杂的管理页面。
> 
> 你会发现它没有提供用户登录、权限功能，转而只是提供更加轻量化的页面创建，这点会让很多开发者们感到更加舒适，可以自由灵活的调整、自定义页面，来实现不同的能力。
> 
> 感谢支持，欢迎在Issue提出意见
> 
> 开始体验吧


[![e1daf65668566cd8f7dd417211820a091576311651.jpg](https://github.com/sunshinev/remote_pics/raw/master/e1daf65668566cd8f7dd417211820a091576311651.jpg)](https://sunshinev.github.io/laravel-gii-home/index.html)

[TOC]

## 原理

1. 通过解析MySQL的数据表结构，来提取字段、以及类型，并填充到`stub`模板。
2. 生成对应的Model、Observer、Controller、View、Route等相关项目文件。
3. 根据MySQL表结构生成Model
4. 根据Model生成Controller

## 官网

https://sunshinev.github.io/laravel-gii-home

## 注意
因为是解析MySQL的表结构，并且根据字段生成模板，所以目前生成的Model类时只支持MySQL，但是生成的CRUD管理后台，可以使用支持mongo和MySQL两种connection。

MySQL表结构请保证`id`,`created_at`,`updated_at`三个字段必须存在。

## 安装

### 扩展包

```
Composer require sunshinev/laravel-gii -vvv
```

### 发布
> 该操作会发布assets静态文件，到public目录下

```
php artisan vendor:publish  --tag laravel-gii
```


### 访问
在发布完成后，已经进行了路由的注册，可以通过下面的路由访问Gii页面
```
http:[domain]/gii/model
```


## 操作说明

### 生成Model模型

表单说明

1. 表名称（支持下拉选择）
2. Model类名（想要创建模型类，包含命名空间）
3. 模型继承的父类（如果是Mongo可以继承`Jenssegers\Mongodb\Eloquent\Model`，MySQL用`Illuminate\Database\Eloquent\Model`）

生成的文件列表，蓝色代表全新文件，红色代表已有文件但是存在不同，白色代表已有文件。

比如指定生成的Model命名空间为`App\Models\Admin\Users`，那么生成的目录结构为:
```
    .app
    ├── Models
    │   └── Admin
    │       ├── UsersModel.php
    │       └── Users.php
    └── Observers
        └── Models
            └── Admin
                └── UsersObserver.php

```
通过上面的结构，我们可以发现命名空间与目录之间的关系。

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-model-preview.gif)


### 生成CRUD

CRUD的创建，需要依赖之前创建的模型。

该操作会同时生成：

- route
- controller
- views

表单说明

1. 控制器名称（包含命名空间）
2. 之前创建的模型类

如果指定Controller的类为`App\Http\Controllers\Admin\UsersController` ，以及关联的Model为`App\Models\Admin\Users`，那么生成的目录结构为:
```
    app
    ├── Http
    │   └── Controllers
    │       └── Admin
    │           ├── RenderController.php
    │           └── UsersController.php
    ├── Models
    │   └── Admin
    │       ├── UsersModel.php
    │       └── Users.php
    └── Observers
        └── Models
            └── Admin
                └── UsersObserver.php
```

以及生成的视图文件
```
.resources
    └── views
        └── admin
            ├── layouts
            │   └── default.blade.php
            └── users
                ├── create.blade.php
                ├── detail.blade.php
                ├── edit.blade.php
                └── list.blade.php
```

通过上面的结构，我们可以发现命名空间与目录之间的关系。会发现`admin`实际想当于`modules`，通过模块化的概念，来划分功能。

![85bce766f1a574d97ac931c8b98c29591576222771.jpg](https://github.com/sunshinev/remote_pics/raw/master/85bce766f1a574d97ac931c8b98c29591576222771.jpg)

#### 如何访问CRUD?

CRUD的路由会自动添加到路由文件中，根据Controller的命名空间`App\Http\Controllers\Admin\UsersController`会生成如下的路由，所以请直接访问路由
```
Route::get('/admin/layout', 'Admin\RenderController@index');
Route::get('/admin/layout/render', 'Admin\RenderController@render');
```




## 特性
#### MySQL列表加载
从配置的MySQL数据库中拉取列表
![135efc4b0abc7a7baf67793fb8de30901576222494.jpg](https://github.com/sunshinev/remote_pics/raw/master/135efc4b0abc7a7baf67793fb8de30901576222494.jpg)

#### 文件对比

工具会将新生成的文件与已存在的文件进行差异对比，方便查看修改部分，控制修改范围。

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-generate.gif)

#### 路由追加
新生成的页面，会自动追加到路由配置文件
![b58eb0bd955bafea27540d6227e611731576209355.jpg](https://github.com/sunshinev/remote_pics/raw/master/b58eb0bd955bafea27540d6227e611731576209355.jpg)


## CRUD后台效果

#### 列表页
包含全面的增删查改功能

- 列表
- 分页
- 检索
- 删除+批量删除
- 预览
- 详情
- 编辑

![a7124e651545e7f462e898ffb37704a41576223115.jpg](https://github.com/sunshinev/remote_pics/raw/master/a7124e651545e7f462e898ffb37704a41576223115.jpg)

#### 删除+批量删除
取消按钮放大，防止误删

![fa926f38e95888fd4a3c3aa055d202f41576223165.jpg](https://github.com/sunshinev/remote_pics/raw/master/fa926f38e95888fd4a3c3aa055d202f41576223165.jpg)


#### 行预览
![0b2b1603c043aec9b5cee84a17e26f291576223207.jpg](https://github.com/sunshinev/remote_pics/raw/master/0b2b1603c043aec9b5cee84a17e26f291576223207.jpg)


#### 编辑页面
![bc43b30f13de17e0a2a899a59f647d3a1576223248.jpg](https://github.com/sunshinev/remote_pics/raw/master/bc43b30f13de17e0a2a899a59f647d3a1576223248.jpg)


## 建议

#### 如果想用Mongo怎么办？
如果生成完Model之后，默认的会使用env中配置的connection，如果需要调整，需要修改生成的Model文件。
```php
    // if connection is empty ,use default connection
    protected $connection = '';
```

#### 后台页面如何支持其他组件？
后台页面统一使用iview作为前端框架，目前支持iview4的所有组件，可直接在生成的blade模板文件中添加组件即可。

[iviewui文档](https://www.iviewui.com/docs/introduce)

#### 如何升级页面的iview组件？
生成的CRUD后台使用的是基于[laravel-fe-render](https://github.com/sunshinev/laravel-fe-render)和[base-fe](https://github.com/sunshinev/base-fe) 两个项目，其中`base-fe`是Vue+iview的打包，如下：

```js
import Vue from 'vue'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);
```

可以Fork `base-fe`项目，然后进行iview升级，将生成的dist目录放到`laravel-fe-render`项目的`assets`目录，然后重新发布即可.


#### 关于Gii的iview.min.js
请参考 https://github.com/sunshinev/ViewUI 项目，Fork后做了细微调整


## 相关资料

[https://github.com/sunshinev/laravel-fe-render](https://github.com/sunshinev/laravel-fe-render) 

[https://github.com/sunshinev/base-fe](https://github.com/sunshinev/base-fe) 


[https://github.com/sunshinev/laravel-gii](https://github.com/sunshinev/laravel-gii)
