<?php
class Report
{
	public function __construct()
	{
	}
	
	public function generate($data, $generationFunction)
	{
		return $this->$generationFunction($data);
	}
	
	private function generatePOSReport($data)
	{
	} 
}
?>