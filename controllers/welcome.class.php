<?php
class Welcome extends Controller
{
	public function index()
	{
		$model = $this->LoadModel('user');  //载入模型
  		$res    = $model->Query("SELECT * FROM user"); //查询表, 这里查询你有的表就行了
		$row    = $model->fetch($res, 'array');  //处理结果集返回数组

		$this->assign('title', $row['username']);
		$this->assign('body', 'my zy! lalala ;)');
        $this->display('index');
	}

	public function show()
	{
		echo '方法名称Show';
	}
}
?>

