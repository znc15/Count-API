<h1 align="center">
  <br>
  <a href="https://www.countapi.cc" alt="logo"><img src="https://raw.githubusercontent.com/znc15/Count-API/main/image/icon.png" width="150"/>
  </a>
  <br>
  <a href="https://www.countapi.cc" alt="logo"><img src="https://github.com/znc15/Count-API/blob/main/image/Count%20API.png?raw=true" width="150"/>
  </a>
  <br>
</h1>

<p align="center">Monitor your website visits at all times</p>

<p align="center">
  <a href="https://www.countapi.cc">官网</a> •
  <a href="https://demo.countapi.cc">演示</a> •
  <a href="https://docs.countapi.cc">文档</a> •
  <a href="https://github.com/znc15/Count-API/releases">下载</a> •
  <a href="https://t.me/Count_API">Telegram</a> •
  <a href="https://github.com/znc15/Count-API?tab=MIT-1-ov-file">License</a>
</p>

<p align="center">
  <a href="http://php.net">
  <img src="https://img.shields.io/badge/PHP->=7.3-orange.svg" alt="GitHub Test Workflow"></a>
  <a href="https://github.com/znc15/Count-API?tab=MIT-1-ov-file">
  <img src="https://img.shields.io/badge/MIT License-yellowgreen.svg"></a>
  <a href="https://github.com/znc15/Count-API">
  <img src="https://img.shields.io/github/languages/code-size/znc15/Count-API?color=blueviolet" /></a>
  <a href="https://github.com/znc15/Count-API">
  <img src="https://img.shields.io/github/v/release/znc15/Count-API?include_prereleases&style=flat-square"/></a>
  <a href="https://github.com/znc15/Count-API/commits/">
  <img src="https://img.shields.io/github/last-commit/znc15/Count-API"/></a>
</p>
 

![看不见图片请使用代理](https://raw.githubusercontent.com/znc15/Count-API/main/image/1.png)
 
  
![看不见图片请使用代理](https://github.com/znc15/Count-API/blob/main/image/2.png?raw=true)

> 不要下载beta版本的程序，没有进行安装测试等，运行将严重影响使用
>
> 发现 bug 请提交 [issues](https://github.com/znc15/Count-API/issues) (提问前建议阅读[提问的智慧](https://github.com/ryanhanwu/How-To-Ask-Questions-The-Smart-Way/blob/main/README-zh_CN.md))  
> 
> 有任何想法、建议、或分享，请移步 [Telegram](https://t.me/Count_API)
> 
### 📌 TODO
* [ ] 支持配置Redis缓存
* [ ] 自动推送每周总结
* [ ] 自动计算流量，而不是通过固定流量计算
* [ ] 自由度极高的多用户管理
* [ ] 后台界面
* [ ] 支持设置对象存储存储自动创建的JS文件
* [ ] 支持在线增量更新、跨版本更新

## :sparkles: 已经支持
* [x] 基础前后端PHP代码
* [x] 数据库支持：`MySQL 5.7+`
* [x] 前台用户自己管理链接
* [x] 登录，注册，找回密码，退出登录
* [x] 自助修改密码
* [x] 自动获取版本
* [x] 退出账号
* [x] 简单的流量显示

## :hammer_and_wrench: 部署

从 [Releases 界面](https://github.com/znc15/Count-API/releases) 下载整合包

```shell
#检查PHP版本是否为7.3以上到7.4，Mysql版本是否为5.7
php -v

# 解压文件到网站的根目录
tar -zxvf Count-API_Version_Chinese.tar.gz

# 设置好伪静态
if (!-e $request_filename) {
    rewrite  ^(.*)$  /index.php?s=$1  last;
    break;
}

# 如果网站运行发生和GepIP或者无法发信，请运行以下代码
composer require geoip2/geoip2
composer require phpmailer/phpmailer
```

其他问题请看[文档](https://docs.countapi.cc)

## :alembic: 技术栈

* [PHP](http://php.net) + [Bootstrap](https://getbootstrap.com/)


## :scroll: License

* MIT License
