<?php
	require "inputChecker.php";
	global $IC;
	session_start();
	//===To debug uncomment the following line, to reset loading each time===///
	//unset($_SESSION['ICtimeout']);
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^//
	if (isset($_SESSION['ICtimeout']) && isset($_SESSION['ICdata']))
	{			
		if ($_SESSION['ICtimeout'] + 10 * 60 < time())
		{
			unset($_SESSION['ICtimeout']);
			unset($_SESSION['ICdata']);
			//===You can add aditionaly logout functions here===//
			
			
			//===END===//
		} else
		{
			$IC = $_SESSION['ICdata'];
		}
	} else
	{
		//===LISTS===//
		
		//===BEGIN OF INPUTS===//
		//===ADD ALL DATA INPUTS HERE===//
		//===Default values have to be valid===//		
		

		//===END OF INPUTS==//
		
		$_SESSION['ICdata'] = $IC;
		$_SESSION['ICtimeout'] = time();
	}
	
?>