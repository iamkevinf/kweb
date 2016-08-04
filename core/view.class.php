<?php
class View
{
    public function assign($var, $value)
	{
		if(is_array($var))
        {
            $this->vars = array_merge($this->vars, $var);
        }
        else
        {
            $this->vars[$var] = $value;
		}
    }
    public function display($file)
	{
		include(ROOT_PATH . '/core/template.class.php');

		$template = Template::getInstance();
		$template->assignArray($this->vars);
		$template->display($file);
    }
}
?>
