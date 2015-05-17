<?php
error_reporting(0);
// 定义ThinkPHP框架路径
define('THINK_PATH', dirname(__FILE__).'/../ThinkPHP/');
//定义项目名称
define('APP_NAME', 'Project');
//定义项目路径
define('APP_PATH', dirname(__FILE__).'/../Project/');
// 项目配置目录 线上Conf/ 测试Conf_TEST/ 开发Conf_DEV/
define('CONF_PATH',APP_PATH.'Conf/');
//开启调试模式
define('APP_DEBUG', true);
// 如果代理是转发过来的请求，使用代理的host
define('APP_URL', isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? 'http://'.$_SERVER['HTTP_X_FORWARDED_HOST'].'/q' : (isset($_SERVER['HTTP_HOST']) ? 'http://'.$_SERVER['HTTP_HOST'] : ''));
//加载框架入文件
require THINK_PATH.'ThinkPHP.php';
