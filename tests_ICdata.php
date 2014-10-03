<?php
	session_start();
	require "inputChecker.php";
	global $IC;
	
	//===To debug uncomment the following lines, to reset loading each time===///	
	unset($_SESSION['ICtimeout']);
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^//
	if (isset($_SESSION['ICtimeout']) && isset($_SESSION['ICdata']))
	{			
		if ($_SESSION['ICtimeout'] + 10 * 60 < time())
		{
			unset($_SESSION['ICtimeout']);
			unset($_SESSION['ICdata']);
			//===You can add aditionaly logout functions here===//
			
			
			//===END===//
			
			session_destroy();
		} else
		{
			$IC = $_SESSION['ICdata'];
		}
	} else
	{
		//===LISTS===//
		$truthValues = {"true","1","T"};
		$falseValues = {"false","0","0.0","","null","-1"};
		
		//===BEGIN OF INPUTS===//
		//===ADD ALL DATA INPUTS HERE===//
		//===Default values have to be valid===//
		
			//Text
		addInput("user",1,null,"Username",0,0,3,15);
		addInput("pass",1,null,"Password",0,0,3,15);
		addInput("email",1,null,null,0,1,6,50);
		
			//Numbers
		$legal_age = new number_check(16,99);
		addInput("age",1,null,null,$legal_age);
		$currency = new number_check(0, 500000, true, 2, ".", " ");
		addInput("salary",0,"0","Salary",$currency);
		
			//Image
		addImage("profilepic",true,"Your profile picture", 2000, ["png","jpg"], "images/");
		
		//===END OF INPUTS==//
		$_SESSION['ICdata'] = $IC;
		$_SESSION['ICtimeout'] = time();
	}
	
?>