<?php

defined ('_JEXEC') or die('Resticted Aceess');


jimport('joomla.application.component.helper');

class JFormFieldSpgenerator extends JFormField{

	protected $type = 'Spgenerator';

	protected function getInput()
	{
		$id = "generator";
		$class = "btn btn-success fa fa-cogs";
		return '<a name=' . $this->name . ' id='. $id . ' class="btn btn-success"'.' href="javascript:"' . '>Generate</a>';

	}
}
