<?php
class Controller
{
	protected $view = NULL;

	public function __construct()
    {
        //实例化上面写到的视图类
        $this->view = new View();
    }
    public function assign($var, $value = '')
    {
        $this->view->assign($var, $value);
    }
    public function display($file)
    {
        $this->view->display($file);
    }

	public function Run()
	{
		$this->Analysis(); //开始解析URL获得请求的控制器和方法
		$control = $_GET['c'];
		$action = $_GET['a'];
		//这里构造出控制器文件的路径
		$controlFile = ROOT_PATH . '/controllers/' . $control . '.class.php';

		if(!file_exists($controlFile)) //如果文件不存在提示错误, 否则引入
		{
			exit('控制器不存在' . $controlFile);
		}

		include($controlFile);

		$class = ucwords($control); //将控制器名称中的每个单词首字母大写,来当作控制器的类名
		if(!class_exists($class)) //判断类是否存在, 如果不存在提示错误
		{
			exit('为定义的控制器类' . $class);
		}

		$instance = new $class(); //否则创建实例
		if(!method_exists($instance, $action)) //判断实例$instance中是否存在$action方法, 不存在则提示错误
		{
			exit('不存在的方法' . $action);
		}

		$instance->$action();
	}

	protected function Analysis()
	{
		global $C; //包含全局配置数组, 这个数组是在Config.ph文件中定义的
		if($C['URL_MODE'] == 1) //如果URL模式为1 那么就在GET中获取控制器, 也就是说url地址是这种的[url=http://localhost/index.php?c]http://localhost/index.php?c[/url]=控制器&a=方法
		{
			$control = !empty($_GET['c']) ? trim($_GET['c']) : '';
			$action = !empty($_GET['a']) ? trim($_GET['a']) : '';
		}
		else if($C['URL_MODE'] == 2) //如果为2 那么就是使用PATH_INFO模式, 也就是url地址是这样的 [url=http://localhost/index.php/]http://localhost/index.php/[/url]控制器/方法/其他参数
		{
			if(isset($_SERVER['PATH_INFO']))
			{
				//$_SERVER['PATH_INFO']URL地址中文件名后的路径信息, 不好理解, 来看看例子
				//比如你现在的URL是 [url=http://www.php100.com/index.php]http://www.php100.com/index.php[/url] 那么你的$_SERVER['PATH_INFO']就是空的
				//但是如果URL是 [url=http://www.php100.com/index.php/abc/123]http://www.php100.com/index.php/abc/123[/url]
				//现在的$_SERVER['PATH_INFO']的值将会是 index.php文件名称后的内容 /abc/123/
				$path = trim($_SERVER['PATH_INFO'], '/');
				$paths = explode('/', $path);
				$control = array_shift($paths);
				$action = array_shift($paths);
			}
		}
		//这里判断控制器的值是否为空, 如果是空的使用默认的
		$_GET['c'] = $control = !empty($control) ? $control : $C['DEFAULT_CONTROL'];
		//和上面一样
		$_GET['a'] = $action = !empty($action) ? $action : $C['DEFAULT_ACTION'];
	}

	protected function LoadModel($modelName)
	{
	  $modelFile = ROOT_PATH . '/models/' . $modelName . '.class.php';   //构造模型文件路径
	  !file_exists($modelFile) && exit('模型' . $modelName . '不存在');  //如果模型文件不存在提示错误
	  include($modelFile);              //存在, 则引入
	  $class = ucwords($modelName);           //获得模型类名
	  !class_exists($class) && exit('模型' . $modelName . '未定义');;  //判断是否定义了模型类, 如果没有提示错误
	  $model = new $class();             //实例化模型类
	  return $model;               //返回实例
	}
}
?>
