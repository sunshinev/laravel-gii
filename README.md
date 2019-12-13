# Laravel-Gii 可视化代码生成工具  CRUD +GUI 

[![Packagist Version](https://img.shields.io/packagist/v/sunshinev/laravel-gii)](https://packagist.org/packages/sunshinev/laravel-gii)
[![Travis (.com)](https://img.shields.io/travis/com/sunshinev/laravel-gii)](https://travis-ci.com/sunshinev/laravel-gii/)
![GitHub last commit](https://img.shields.io/github/last-commit/sunshinev/laravel-gii)
![GitHub](https://img.shields.io/github/license/sunshinev/laravel-gii)
![GitHub repo size](https://img.shields.io/github/repo-size/sunshinev/laravel-gii)
![GitHub stars](https://img.shields.io/github/stars/sunshinev/laravel-gii?style=social)
![GitHub forks](https://img.shields.io/github/forks/sunshinev/laravel-gii?style=social)

适用于快速B端后台开发，根据MySQL的表结构生成对应的Model、Observer、Controller、View、Route等相关项目文件

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-preview.gif)

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

文件对比
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/gii-generate.gif)



## CRUD后台效果

#### 列表页
该页面包含能力：

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

## 相关问题

1. 如果生成完Model之后，默认的会使用env中配置的connection，如果需要调整，请修改Model文件。

## 需了解

项目创建生成的模板需要依赖于[《github:laravel-fe-render》](https://github.com/sunshinev/laravel-fe-render) 项目，作为模板解析。

后台页面依赖项目编译后的app.js [《github:base-fe》](https://github.com/sunshinev/base-fe) 

[https://github.com/sunshinev/laravel-gii](https://github.com/sunshinev/laravel-gii)
