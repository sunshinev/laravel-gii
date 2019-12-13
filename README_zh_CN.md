# Laravel-Gii 可视化代码生成工具  CRUD +GUI 

[![Packagist Version](https://img.shields.io/packagist/v/sunshinev/laravel-gii)](https://packagist.org/packages/sunshinev/laravel-gii)
[![Travis (.com)](https://img.shields.io/travis/com/sunshinev/laravel-gii)](https://travis-ci.com/sunshinev/laravel-gii/)
![GitHub last commit](https://img.shields.io/github/last-commit/sunshinev/laravel-gii)
![GitHub](https://img.shields.io/github/license/sunshinev/laravel-gii)
![GitHub repo size](https://img.shields.io/github/repo-size/sunshinev/laravel-gii)
![GitHub stars](https://img.shields.io/github/stars/sunshinev/laravel-gii?style=social)
![GitHub forks](https://img.shields.io/github/forks/sunshinev/laravel-gii?style=social)

适用于快速B端后台开发，根据MySQL的表结构生成对应的Model、Observer、Controller、View、Route等相关项目文件

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-model-preview.gif)

[TOC]

## 安装

### 扩展包

```
Composer require sunshinev/laravel-gii -vvv
```

### 发布
> 该操作会发布assets静态文件，到public目录下

```
php artisan vendor:publish
```
选择
`Tag: laravel-gii`


### 访问
`http:[domain]/gii/model`


## 操作说明


### 一键生成Model模型

表单说明

1. 表名称（支持下拉选择）
2. Model类名（想要创建模型类，包含命名空间）
3. 模型继承的父类（如果是Mongo可以继承`Jenssegers\Mongodb\Eloquent\Model`，MySQL用`Illuminate\Database\Eloquent\Model`）


生成的文件列表，蓝色代表全新文件，红色代表已有文件但是存在不同，白色代表已有文件。

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/success.png)

### 一键生成CRUD文件

CRUD的创建，需要依赖之前创建的模型。

该操作会同时生成：

- route
- controller
- views

表单说明

1. 控制器名称（包含命名空间）
2. 之前创建的模型类

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/controller.png)

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

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_list.png)
#### 删除+批量删除
取消按钮放大，防止误删

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_delete.png)

#### 行预览
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_view.png)

#### 编辑页面
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_edit.png)

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



## 相关资料

[https://github.com/sunshinev/laravel-fe-render](https://github.com/sunshinev/laravel-fe-render) 

[https://github.com/sunshinev/base-fe](https://github.com/sunshinev/base-fe) 


[https://github.com/sunshinev/laravel-gii](https://github.com/sunshinev/laravel-gii)
