# cal-container

## 说明

* 基于反射实现的php容器项目，包含自动注入，静态注册，动态注册，动态传参等功能

* 遵循PSR-11容器规范

## 安装

```composer
composer require 'cal-container/cal-container'
```

## 使用说明


## 参考:
### [关于PSR](https://learnku.com/index.php/docs/psr/about-psr/1613)
* [php-fig 容器规范](https://github.com/php-fig/container)
* [PSR-11 容器接口](https://learnku.com/index.php/docs/psr/psr-11-container/1621)
* [PSR-11 容器接口 - 说明文档](https://learnku.com/index.php/docs/psr/psr-11-container-meta/1622)

### 开源项目参考
* [php-di](https://github.com/PHP-DI/PHP-DI)
* [silexphp/Pimple](https://github.com/silexphp/Pimple)
* [laravel-container](https://github.com/illuminate/container)

### 反射api
* [Reflection](https://www.php.net/manual/zh/class.reflection.php)
    * [ReflectionClass](https://www.php.net/manual/zh/class.reflectionclass.php)
    * [ReflectionObject](https://www.php.net/manual/zh/class.reflectionobject.php)
    * [ReflectionMethod](https://www.php.net/manual/zh/class.reflectionmethod.php)
    * [ReflectionFunction](https://www.php.net/manual/zh/class.reflectionfunction.php)
    * [ReflectionProperty](https://www.php.net/manual/zh/class.reflectionproperty.php)

### 测试
* [phpunit](http://www.phpunit.cn/)


## plan

### 注解实现
* `@dataProvider` 数据提供
* `@decorator` 装饰器
* `@exception` 异常处理
* `@final` final处理(在所有注解处理完成，函数返回前)
* `@tag(...)` tag数据
* aop实现(利用装饰器特征)
