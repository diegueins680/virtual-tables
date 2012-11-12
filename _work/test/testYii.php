<?php
$configFile = $_SERVER['DOCUMENT_ROOT'].'/_inc/config.main.php';
require_once ($configFile);
Yii::import('zii.widgets.grid.CGridColumn');
class CounterColumn extends CGridColumn 
{
	private $i = 0;
	
	public function init()
	{
		$pager=$this->grid->dataProvider->pagination;
		if($pager->currentPage==0) 
		{
			$this->i=0;
		}
		else 
		{
			$this->i=$pager->currentPage*$pager->pageSize;
		}
	}
	
	public function renderDataCellContent($row, $data)
	{
		$this->i++;
		echo $this->i;
	}
}
?>