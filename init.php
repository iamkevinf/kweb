<?php
header("Content-type:text/html;charset=utf-8");
!defined('ROOT_PATH') && define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
require ROOT_PATH . '/core/config.php'; //引入配置文件
require ROOT_PATH . '/core/controller.class.php'; //引入控制器类文件
require ROOT_PATH . '/core/view.class.php'; //视图类文件
require ROOT_PATH . '/core/model.class.php'; //模型类文件
?>
