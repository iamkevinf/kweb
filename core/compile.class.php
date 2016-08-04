<?php

	class Compile{
		private $template;	//��������ļ�
		private $content;	//��Ҫ�滻���ı�
		private $comfile;		//������ �ļ�
		private $left = '{';		
		private $right = '}';
		private $value =array();  // ֵջ
		private $phpTurn;
		private $T_P = 	array();
		private $T_R = array();
		
		
		public function __construct($template,$compileFile,$config){
			//echo $template;
			//echo $compileFile;
			$this->template = $template;
			$this->comfile = $compileFile;
			$this->content = file_get_contents($template);
			if($config['php_turn']===false){
				//echo "123";
				//$this->T_R[]="";
			}
			//echo "123";
			//����ƥ�� {$xxx} ��ʽ
			$this->T_P[]="#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}#";
			$this->T_R[]="<?php echo \$this->value['\\1'];?>";
		}
		public function compile(){
			$this->c_var2();
			//$this->c_staticFile();
			//var_dump($this);
			file_put_contents($this->comfile,$this->content);
		}
		public function c_var2(){
			$this->content = preg_replace($this->T_P,$this->T_R,$this->content);
		}
		public function c_staticFile(){
			$this->content =preg_replace('#\{\!(.*?)\!\}#','<script src =\\1'.'?t='.time().'></script>',$this->content);
		}
		public function __set($name,$value){
			$this->$name = $value;
			
		}
		public function __get($name){
			return $this->$name;
			
		}
	}
