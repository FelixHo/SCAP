<?php
//sae专项配置
$st =   new SaeStorage();
return array(
		//'配置项'=>'配置值'
		'WECHAT_CONFIG'=>array(
				
				'WECHAT_TOKEN'              =>  'RRev06fPAoHGxbf3folYSSo7iRkW',
				
				'WECHAT_APPID'              =>  'wxebeae596240e2741',
				
				'WECHAT_APPSECRET'          =>  '3c6f98d82721172c34a00027853659f6',
				
				'WECHAT_ENCODING_AES_KEY'   =>  '0z584GLdpHlqRRev06fPAoHGxbf3folYSSo7iRkWZhV',
				
				'WECHAT_ACCESS_TOKEN_KEY'   =>  'wechat_access_token_key',
		),
		
		'FILE_UPLOAD_TYPE' => 'Sae',
		
		'UPLOAD_ROOT_PATH' => './public/',
		
		/* 模板可用常量 */
		'TMPL_PARSE_STRING' =>  array(

            '__CSS__'       =>  $st->getUrl('public','css'),

		    '__JS__'        =>  $st->getUrl('public','js'),

            '__ASSETS__'    =>  $st->getUrl('public','assets'),

            '__IMAGE__'     =>  $st->getUrl('public','image'),

            '__HOME__'      =>  APP_URL.'/Home',

            '__UPLOAD__'    =>  $st->getUrl('public','upload'),
        ),
);