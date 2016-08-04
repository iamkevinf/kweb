<?php
class Template{
		private $arrayConfig = array(
		'suffix'      => '.html', 			//设置模板文件
		'templateDir' => 'views/', 	//设置模板所在的文件夹
		'compileDir'  => 'cache',
		'debug'      => false,		//设置编译后存放的目录
		'cache_htm'	  =>  true,		//是否需要编译成静态的html文件
		'suffix_cache'=> '.htm',		//编译后的文件后缀	
		'cache_time'  =>2000,			// 多长时间自动更新
		'php_turn'    =>false,			//是否支持原生的php代码
		'cache_control' => 'control.dat',
		);
		
	private $compileTool;		//编译器
	public $filename;		//模板文件名称
	private $value =array();		//值栈
	static private $instance  = null;	
	public $debug = array();	//调试信息
	public function __construct($arrayConfig =array()){
	        //返回当前UNIX时间戳和微妙数
		$this->debug['begin'] = microtime(true);
		$this->arrayConfig =$arrayConfig+$this->arrayConfig;
		$this->getPath();
		if(!is_dir($this->arrayConfig['templateDir'])){
			exit("template isnt not found");
		}
		if(!is_dir($this->arrayConfig['compileDir'])){
			
			mkdir($this->arrayConfig['compileDir'],0770,true);
		}
	include("compile.class.php");
		//$this->compileTool = new Compile;
	}
	/**
	
			路径处理为绝对路径
	
	*/
	public function getPath(){
		$this->arrayConfig['templateDir'] = strtr(realpath($this->arrayConfig['templateDir']),'\\','/').'/';
		$this->arrayConfig['compileDir'] = strtr(realpath($this->arrayConfig['compileDir']),'\\','/').'/';
	}
	
	/***
	
			单例模式获取模板的实例
	**/
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new Template();
		}
		return self::$instance;
	}
	
	public function setConfig($key,$value = null){
		if(is_array($key)){
			$this->arrayConfig = $key+$this->arrayConfig;
		}else{
			$this->arrayConfig[$key] = $value;
		}
	}
	public function getConfig($key = null){
		if($key){
			return $this->arrayConfig[$key];
		}else{
			return $this->arrayConfig;
		}
		
	}
	
	/**
	
	    注入单个变量
	**/
	public function assign($key,$value){
		$this->value[$key] = $value;
	}
	
	/**
	    注入多个变量
	**/
	public function assignArray($array){
		if(is_array($array)){
				foreach($array as $k => $v){
					$this->value[$k] = $v;
				}
				
		}
	}
	/***
	        获取模板文件的路径
	
	**/
	
	public function path(){
		return $this->arrayConfig['templateDir'].$this->filename.$this->arrayConfig['suffix'];
	}
	/***
			是否需要缓存
	**/
	public function needCache(){
		return $this->arrayConfig['cache_htm'];
	}
	
	/***
				是否需要重新生成缓存文件
	**/
	
	public function reCache($file){
		$flag = false;
		//生成缓存文件
		$cacheFile = $this->arrayConfig['compileDir'].md5(@$filename).'.'.'php';
		//var_dump($cacheFile);
		if($this->arrayConfig['cache_htm']===true){
		
		//设置timeflag （判断当前时间-模板文件上次修改的时间）是否小于设置的缓存时间
		//如果小于则返回TRUE
		
			$timeFlag = (time()-@filemtime($cacheFile))<$this->arrayConfig['cache_time']?
			true:false;
	//1，判断缓存文件是否存在，
	//2，缓存文件是否有内容
	//3，时间是否在设置的缓存时间之内		
			if(!is_file($cacheFile)&&filesize($cacheFile)>1&&$timeFlag){
				$flag = true;
			}else{
				$flag = false;
			}
		}
		return $flag;
	}
	/***
	
	显示模板
	**/
	public function display($file){
		$this->filename =$file;
		if(!is_file($this->path())){
			exit('找不到相对应的模板'.$file);
		}
		$compileFile = $this->arrayConfig['compileDir'].'/'.md5(@$filename).'.php';
		$cacheFile = $this->arrayConfig['compileDir'].md5(@$filename).'.htm';
	//	echo $compileFile;
		//echo $cacheFile;
		if($this->reCache($file)===false){
			$this->debug['cached'] = 'false';
		//	var_dump($compileFile);
			$this->compileTool = new Compile($this->path(),$compileFile,$this->arrayConfig);
			if($this->needCache()){
			//是否需要缓存
				ob_start();
			}
			//函数从数组中把变量导入到当前的符号表中
			extract($this->value,EXTR_OVERWRITE);
			//判断 文件是否存在，生成文件的修改时间是否小于模板文件修改时间
			if(@is_file($compileFile)||filemtime($compileFile)<filemtime($this->path())){
				$this->compileTool->vars = $this->value;
				$this->compileTool->compile();
				//引入文件
				include $compileFile;
			}else{
				include $compileFile;
			}
			if($this->needCache()){
			//如果需要缓存的话
				$message = ob_get_contents();
				//则生成缓存文件
				file_put_contents($cacheFile,$message);
			}
			
		}else{
		//如果缓存文件时间小于设定的时间
		//直接读取缓存文件
			readfile($cacheFile);
			//$this->debug['cached'] = true;
		}
		$this->debug['spend'] = microtime(true) - $this->debug['begin'];
		$this->debug['count'] = count($this->value);
		$this->debug_info();
		
		/*
		var_dump($compileFile);this
		var_dump($this->path());
		if(!is_file($compileFile)){
			mkdir($this->arrayConfig['compileDir']);  //	此处若存在需要判断
			$this->compileTool->compile($this->path(),$compileFile);
			readfile($compileFile);
		}else{
			readfile($compileFile);
		}
		*/
	}
	/***
	
	
	debug 调试函数
	**/
	public function debug_info(){
		//$this->arrayConfig['debug']=false;
		if($this->arrayConfig['debug']===true){
			var_dump($this);
			echo "程序运行日期",date("Y-m-d h:i:s")."</br>";
			echo "模板解析耗时",$this->debug['spend'],'秒'."</br>";
			echo "模板包含标签数目",$this->debug['count']."</br>";
			echo "是否使用静态缓存",$this->debug['cached']."</br>";
			//echo "模板引擎实例参数",var_dump($this->getConfig());
		}
	}
	/******
		清楚缓存的文件
	
	
	*****/
	public function clean($path = null){
		if($path = null){
			$path = $this->arrayConfig['CompileDir'];
			$path = glob($path.'*'.$this->arrayConfig['suffix_cache']);
			//glob 函数返回匹配指定的文件夹目录
			
		}else{
			$path = $this->arrayConfig['compileDir'].md5($path).'.htm';
			foreach((array)$path as $v){
			//删除
				unlink($v);
			}
		}
	}
	
	
	
}
