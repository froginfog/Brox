<?php
//主体设置
define("TIMEZONE", "Asia/Shanghai");
define("URL_ROOT", "/brox/"); //网站所在目录，不可用__DIR__替代,默认为'/'
define("ROOT", __DIR__);//网站根目录
define("UPLOAD_FOLDER", __DIR__."/uploads"); //上传文件所在目录
define('PRIVATE_KEY', 'vurtne'); //加密用
define('L10N', true);//启用本地化

//记录日志会占用大量空间
define("ACCESS_LOG", false);

//数据库配置
define("DB_TYPE" , "mysql");
define("DB_HOST", "localhost");
define("DB_NAME", "fucker");
define("DB_USER", "root");
define("DB_PWD", "root");
define("DB_PORT", "3306");
define("DB_PREFIX", "");
define("DB_CHARSET", "utf8");

//smarty设置
define("SM_LEFT_DELIMITER", "{");
define("SM_RIGHT_DELIMITER", "}");
define("SM_TEMPLATE_DIR", __DIR__."/templates/tpl");
define("SM_COMPILE_DIR", __DIR__."/templates/tpl_c");
define("SM_CACHE_DIR",  __DIR__."/templates/cache");
define("SM_PLUGIN_DIR",  __DIR__."/plugins");
define("SM_CACHEING", false);
define("SM_CACHE_LIFETIME", 120);

//
define("WARTERMARK", "./uploads/wartermark.png");