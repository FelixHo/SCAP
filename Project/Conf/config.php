<?php
return array(
	    //'配置项'=>'配置值'
		'DB_TYPE'   => 'mysql', // 数据库类型

		'DB_HOST'   => '119.29.22.179', // 服务器地址

		'DB_NAME'   => 'scap', // 数据库名

		'DB_USER'   => 'root', // 用户名

		'DB_PWD'    => 'CWVhQeE9PkYnCB', // 密码 tae

		'DB_PORT'   => 3306, // 端口

		'DB_PREFIX' => 'app_', // 数据库表前缀

		'DB_CHARSET'=> 'utf8', // 字符集
		
		'UPLOAD_ROOT_PATH' => APP_PATH.'../Public/',
		
	    //机器人配置
		'ROBOT_API_URL' => "http://www.tuling123.com/openapi/api",

		"ROBOT_API_KEY" => "e1de45112fb01196cf4c41d6ba76bc8f",
		
		/* 模板可用常量 */
		'TMPL_PARSE_STRING' =>  array(

        '__CSS__'    =>  APP_URL.'/css',

		'__JS__'     =>  APP_URL.'/js',

        '__ASSETS__' =>  APP_URL.'/assets',

        '__IMAGE__'  =>  APP_URL.'/image',

        '__HOME__'   =>  APP_URL.'/Home',

        '__UPLOAD__' =>  APP_URL.'/upload',
        ),
);