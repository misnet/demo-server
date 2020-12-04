# 说明
1.先 git clone https://github.com/misnet/kuga-server.git
2.nginx.demo.conf 是对应的nginx配置文件，放到你的nginx的配置目录，记得修改$root_path和  server_name 改成你自己的，如果是你本地访问，可以用demo.api.kuga.wang这个域名，它指向的是127.0.0.1
3.复制config.default.php，另存一份config.php，然后配好与config.default.php不同的内容，示例：
```
<?php
$_CONFIG['dbwrite'] = array(
		'adapter' =>'mysql',
		'port'=>3306,
		'host'=>'mysql',
		'username'=>'root',
		'password'=>'',
		'dbname'=>'kuga',
        'charset'=>'utf8mb4',
        'statsDbname'=>''
);
$_CONFIG['system']['name']    = 'Kuga Platform';
$_CONFIG['system']['software_copyright']    = 'Design by Donny &copy 2016 ';
$_CONFIG['dbread'] = $_CONFIG['dbwrite'];

$_CONFIG['redis']['host'] = 'redis';
return $_CONFIG;
```
4、编写自己的API接口
- 1)、API接口必须先在config/api目录里定义好，系统会从config/api中解析所有json文件，看前端调用的api接口是否有定义，
有就可以访问，没定义就是接口不存在。有关api接口json文件的编写规范见[https://github.com/misnet/apidocs]
- 2)、编写API请参照class/vendor/kuga/openapi-sdk/src/Api目录下的文件写法，可参考https://github.com/misnet/demo-api.git
- 3)、修改class/composer.json和class/composer.beta.json，在require和repositories部分加上自己写的api，例如demo-api

5、运行composer安装项目依赖
- 1)在class目录里有各种环境的composer配置，除了composer.json和composer.beta.json是必须的外，其他的是示例
- 2）composer.beta.json和composer.prod.json不同是beta在require中指定了引用的相关api是beta版，composer.prod.json里引用的相关api是master版
```
   composer.beta.json的require约定：
   "kuga/demo-api": "dev-beta"

   composer.json的require约定：
   "kuga/demo-api": "dev-master"
```
- 3）一般在开发时，你可以用composer.local.json，这文件主要是在repositories里指定你的api引用的相对路径，例如本demo的：
```
    {
        "type": "path",
        "url": "../../demo-api"
    }
```
- 4）了解了以上几种composer.json的区别后，根据自己需要调整，比如在开发阶段，可以copy composer.local.json内容盖掉composer.json的内容，然后运行安装相关依赖：
```
php class/composer.phar install
```

6、通过PostMan测试接口



