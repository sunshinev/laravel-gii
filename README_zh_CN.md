# laravel-gii

GIT:[https://github.com/sunshinev/laravel-gii](https://github.com/sunshinev/laravel-gii)

适用于快速B端后台开发

根据MySQL的表结构生成对应的Model、Observer、Controller、View、Route等相关项目文件，通过简单点击鼠标即可自动创建完整的CRUD后台。


![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/controller.png)
[TOC]

## 安装之前需了解

项目创建生成的模板需要依赖于[《github:laravel-fe-render》](https://github.com/sunshinev/laravel-fe-render) 项目，作为模板解析。

后台页面依赖项目编译后的app.js [《github:base-fe》](https://github.com/sunshinev/base-fe) 

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


## 使用


### 创建Model模型

#### 表单说明
1. 表名称（支持下拉选择）
2. Model类名（想要创建模型类，包含命名空间）
3. 模型继承的父类（如果是Mongo可以继承`Jenssegers\Mongodb\Eloquent\Model`，MySQL用`Illuminate\Database\Eloquent\Model`）


生成的文件列表，蓝色代表全新文件，红色代表已有文件但是存在不同，白色代表已有文件。

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/success.png)

### 创建CRUD

CRUD的创建，需要依赖之前创建的模型。

该操作会同时生成：

- route
- controller
- views

#### 表单说明

1. 控制器名称（包含命名空间）
2. 之前创建的模型类

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/controller.png)

### 文件差异对比
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/diff2.png)

### 最终文件内容
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/viewfile.png)


## 创建后后台页面

### 列表页
该页面包含能力：

- 列表
- 分页
- 检索
- 删除+批量删除
- 预览
- 详情
- 编辑

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_list.png)
### 删除+批量删除
取消按钮放大，防止误删

![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_delete.png)

### 行预览
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_view.png)

### 编辑页面
![image](https://github.com/sunshinev/remote_pics/raw/master/laravel-gii/bg/bg_edit.png)

## 相关问题

1. 如果生成完Model之后，默认的会使用env中配置的connection，如果需要调整，请修改Model文件。
