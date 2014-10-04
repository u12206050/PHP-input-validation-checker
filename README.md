
Library: PHP Input Validation Checker
==========================================================================================================
Developer: Gerard A Lamusse
Email: gerardlamo@live.com
Date: 02 October 2014 v.01
==========================================================================================================
This library can be used to check all your input from client side, if they are valid.
==========================================================================================================
WARNING: This library does not sanitize any input due to the new PDO techniques available.

::SETUP
Add all your web inputs via the following commands into the file ICdata.php:
	
	addInput	(
				Name of field 			- text,
				isRequired 			- boolean,
				default value to use if empty	- text NOTE: won't be used if isRequired = true, Default values have to be valid,
					[
						description of the field	- text,
						'null' if not special_check	- special_check object NOTE: can be any extended special_check object ,
						isEmail 			- boolean,
						minumum length def=0	      	- number,
						maximum length		         - number
					]
			);
	addImage	(
				Name of field			- text,
				isRequired			- boolean,
					[
						description of the image	- text,
						maximum size of image in kb	- number NOTE: PHP has an upload size limit aswell,							
						allowable extensions		- array of text NOTE: NULL allows any file,
						uploadToPath including '\'	- text					
					]
			);
			
	::PREDEFINED SPECIAL CHECK OBJECTS
	
	$obj = boolean_check([$trueV text/boolean,$falseV text/boolean, $truthValues array(), $falseValues array()]);
		::EXAMPLE true/false value arrays, mostly same as default, thus can exclude.
			$truthValues = {"true","1","T"};
			$falseValues = {"false","0","0.0","F","null","-1"};
			Warning -1 is considered TRUE, like any other non-zero (whether negative or positive) number, by default.
	
	$obj = choice_check($validChoices array(), $allowMulti boolean);
	
	$obj = date_check() !!! Not yet implemented
	
	$obj = new number_check	(
							$min def=MIN	- number,
							$max def=MAX	- number, 
							[
								format number		        - boolean,
								No. of decimals def=2	  - number,
								decimal character def=.	- char,
								thousands seperator	    - char/text
							]
						);	
		NOTE: Number format uses the  number_format()  function, and includes at least two decimal values if set.
	
	
==========================================================================================================
::EXAMPLE SETUP WITHIN "ICdata.php":
<pre>
	<!--FOR TEXT VALUES-->
		addInput("user",1,null,"Username",0,0,3,15);
  		addInput("pass",1,null,"Password",0,0,3,15);
  		addInput("email",1,null,null,0,1,6,50);
	
	<!--FOR SPECIAL (number) VALUES-->
		$legal_age = new number_check(16,99);
		 addInput("age",1,null,null,$legal_age);
		$currency = new number_check(0, 500000, true, 2, ".", " ");
	  	addInput("salary",0,"0","Salary",$currency);
	  
	<!--FOR FILES-->
	  	addImage("profilepic",true,"Your profile picture", 2000, ["png","jpg"], "images/");
</pre>	
==========================================================================================================
::USAGE
To access and load web inputs call the, first require the  ICdata.php  file then call the load_IC(args)  function where args is an array of field names you want to load eg  ["user","pass"] or  "*"  will load all values specified in IC.
If the return of  load_IC(args)  is null, then all values have been loaded and can be accessed via  getIC(field name)->data 
eg.  getIC("user")->data 
If however load_IC does not return null, then it contains an array of errors.	These errors are stored according to key-value with key being the field name and the value of it the errors message as found in the  getIC(field name)->error  field.
==========================================================================================================
::EXAMPLE USAGE:
<pre>
	require "ICdata.php";	
	$errors = load_IC(["user","pass"]);
	if ($errors)
	{
		echo "Error: <hr>";
		foreach ($errors as $error)
		{
			echo $error . "<br>";
		}
		
		<!--or you can say-->
		
		echo "Error: <hr>";
		foreach ($errors as $key => $error)
		{
			echo "{$key}: {$error} <br>";
		}
	}
	else
	{
		$username = getIC("user")->data;
		$password = getIC("pass")->data; 
		do something.
	}
</pre>
==========================================================================================================
