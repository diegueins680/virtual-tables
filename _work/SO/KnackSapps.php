<?php
    $requiredPower = 65;
    $productA = ['amount' => 0, 'cost' => 50, 'powerDelivered' => 5];
    $productB = ['amount' => 0, 'cost' => 80, 'powerDelivered' => 9];
    $productC = ['amount' => 0, 'cost' => 140, 'powerDelivered' => 15];
    $products = [$productA , $productB, $productC];
    $increment = 1;
    $threshold = 1;
    $solutions = [];
    class Optimizer
    {
    	private static $solutions = [];
    	
    	private static $currentAmounts = [];
    	
    	private $threshold;
    	
    	private $requiredPower;
    	
    	public function total($prods, $attribute = 'cost')
    	{
    		$result = 0;
    		foreach($prods as $key => $product)
    		{
    			$result += $product['amount'] * $product[$attribute];
    		}
    		return $result;
   		}
    	
    	public function isWithinThreshold($solution)
    	{
    		$total = $this->total($solution, 'powerDelivered');
    		if($total >= $value2 - $threshold && $total <= $value2 + $threshold)
	   	 	{
	    		return true;
	    	}
    	}
    	public function __invoke(&$productsArray, $currentProductIndex, $requiredPower, $thresHold)
    	{
    		foreach($productsArray as $key => $product)
    		{
    			self::$currentAmounts[$key] = $product['amount'];
    		}
    		if(isset($productsArray[$currentProductIndex + 1]))
    		{
    			$this($productsArray, $currentProductIndex + 1, $requiredPower, $thresHold);
    		}
    		
    		
    		$this->threshold = $thresHold;
    		$this->requiredPower = $requiredPower;
    		$productsArray[$currentProductIndex]['amount'] = 0;
    		while($this->total($productsArray, 'powerDelivered') <= $requiredPower + $this->threshold)
    		{
    			if($this->isWithinThreshold($productsArray))
    			{
    				foreach($productsArray as $key => $product)
    				{
    					foreach($product as $attribKey => $attribute)
    					{
    						$possibleSolution[$attribKey] = $attribute;
    					}
    				}
    				self::$solutions[] = $possibleSolution;
    			}
    			else
    			{
    				$productsArray[$currentProductIndex]['amount'] = $productsArray[$currentProductIndex]['amount'] + $increment;
    			}
    		}
    		
    		if(isset($productsArray[$currentProductIndex + 1]))
    		{
    		//	$this($productsArray[$currentProductIndex]['amount'] + $increment;
    		}
    		else
    		{
    			
    		}
    		$productsArray[$currentProductIndex][] = $productsArray[$currentProductIndex] + $increment;
    		
    		return $resultArray;
    		$currentProduct = array_shift($productsArray);
    		var_dump($currentProduct);
    		if(count($productsArray)>0)
    		{
    			$this($productsArray);
    		}
    		else
    		{
    			return;
    		}
    		foreach($productsArray as $key => $product)
    		{
    			$productsArray['amount'] = 0;
    		}
    		$currentProduct = array_shift($productsArray);
    		var_dump($currentProduct);
    		
    		$resultingArray[$cost()] = array_map($getMinimumCosts, $productsArray);
    	}
    }
    $myFunction = new Optimizer();
    $solution = $myFunction($products, 0, $requiredPower, $threshold);
    while($productA['amount'] * $productA['powerDelivered'] < $requiredPower)
    {
    	$productC['amount'] = 0;
    	while($productB['amount'] * $productB['powerDelivered'] < $requiredPower)
    	{
    		$productC['amount'] = 0;
	    	while($productC['amount'] * $productC['powerDelivered'] < $requiredPower)
	    	{
	    		if($productA['amount'] * $productA['powerDelivered'] + $productB['amount'] * $productB['powerDelivered'] + $productC['amount'] * $productC['powerDelivered'] > $requiredPower + $threshold)
	    		{
	    			break;
	    		}
	    		$power = $productA['powerDelivered'] * $productA['amount'] + $productB['powerDelivered'] * $productB['amount'] + $productC['powerDelivered'] * $productC['amount'];
	    		if(isWithinThreshold($power, $requiredPower, $threshold))
		    	{
			        //var_dump($productA['powerDelivered'] * $productA['amount'] + $productB['powerDelivered'] * $productB['amount'] + $productC['powerDelivered'] * $productC['amount']);
	    			$cost = $productA['amount'] * $productA['cost'] + $productB['amount'] * $productB['cost'] + $productC['amount'] * $productC['cost'];
	    			$solutions[number_format($cost,10,'.','')] = ['cost' => $cost, 'power' => $power, 'qA' => $productA['amount'], 'qB' => $productB['amount'], 'qC' => $productC['amount']];
	    		}
	    		$productC['amount'] = $productC['amount'] + $increment;
    		}
    		$productB['amount'] = $productB['amount'] + $increment;
    	}
    	$productA['amount'] = $productA['amount'] + $increment;
    }
    ksort($solutions, SORT_NUMERIC);
    $minimumCost = array_shift($solutions);
    var_dump($solutions);
    //checks if $value1 is within $value2 +- $threshold

    