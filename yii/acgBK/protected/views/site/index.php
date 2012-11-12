<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
function curPageURL() {
	$pageURL = $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'];
	return $pageURL;
}
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p><a href="<?php echo ('http://'.curPageURL().'/yii/acgDev/index.php?r=hopData');?>">HOP sales management.</a></p>
