# laravel-gii

[TOC]

可视化模型、控制器、视图生成工具

## 介绍

Laravel-gii 根据MySQL的表结构生成对应的Model、Observer、Controller、View、Route等相关项目文件，通过简单点击鼠标即可自动创建完整的CRUD后台。

并且支持Connection类型为MySQL和Mongodb的Model。


## 安装之前

项目创建生成的模板需要依赖于[《github:laravel-fe-render》](https://github.com/sunshinev/laravel-fe-render) 项目，作为模板解析。

## 安装

### 安装包
```
composer require sunshinev/laravel-gii -vvv
```


### 发布文件
> 该操作会发布assets静态文件，到public目录下

```
php artisan vendor:publish
```
选择
`[x] Provider: Sunshinev\Gii\Providers\GiiServiceProvider`



### 添加路由
```
Route::any('/gii/model','\Sunshinev\Gii\Controllers\ModelController@index');
Route::any('/gii/crud','\Sunshinev\Gii\Controllers\CrudController@index');
```

### 然后访问吧
`http:[domain]/gii/model`

