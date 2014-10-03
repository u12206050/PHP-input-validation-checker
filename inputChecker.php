<?php		
	define("MAX", 999999999);
	define("MIN", -999999999);	
	$IC = array();
	
	abstract class special_check
	{
		public $data = null;
		public $error = null;
		
		public function special_check(){}
		
		public function load($data)
		{
			$this->error = "validation has not been instansiated";
			return false;			
		}
	}
	
	//Checks if falseValues are set, if $data exists therin then $this->data = $false (what ever value it is set to)
	//NOTE: Never gets an error because all values can be cast to bool, unless strick checking is done whereby both
	// truthValues and falseValues have been added.
	class boolean_check extends special_check
	{
		protected $truthValues = null;
		protected $falseValues = null;
		protected $trueV = null;
		protected $falseV = null;
	
		public function boolean_check($trueV = true,$falseV = false, $truthValues = null, $falseValues = null)
		{
			$this->truthValues = $truthValues;
			$this->falseValues = $falseValues;
			$this->trueV = $trueV;
			$this->falseV = $falseV;
		}
		
		public function load($data)
		{
			if ($this->truthValues && $this->falseValues) 			//Strict testing
			{
				if (in_array($data, $this->truthValues, true))
				{
					$this->data = $this->trueV;
					return true;
				} else
				if (in_array($data, $this->falseValues, true))
				{
					$this->data = $this->falseV;
					return true;
				} else
				{
					$this->error = "invalid boolean value";
					return false;
				}
			} 
			
			if ($this->truthValues)							// 1st part semi-strict testing
			{
				if (in_array($data, $this->truthValues, true))
				{
					$this->data = $this->trueV;
				} else
					$this->data = $this->falseV;
				return true;
			} 
			if ($this->falseValues)							// 2nd part semi-strict testing
			{
				if (in_array($data, $this->falseValues, true))
				{
					$this->data = $this->falseV;
					return true;
				} else
					$this->data = $this->trueV;
				return true;
			}
			
			if ((bool)$data)								//No testing only casting to bool
			{
				$this->data = $this->trueV;
			} else			
				$this->data = $this->falseV;
			
			return true;
		}
	}
	
	class date_check extends special_check
	{
		
	}
	
	class choice_check extends special_check
	{
		protected $validChoices = array();
		protected $allowMulti = false;
		
		public function choice_check($validChoices, $allowMulti = false)
		{
			$this->$validChoices = $validChoices;
			if (isset($allowMulti))
				$this->allowMulti = (bool)$allowMulti;
		}
		
		public function load($data)
		{
			if (allowMulti)
			{
				foreach($data as $choice)
				{
					if (!in_array($data, $this->validChoices, true))
					{
						$this->error = "{$choice} is an invalid choice";
						return false;
					}
				}
			} else
			{
				if (in_array($data, $this->validChoices, true))
				{
					$this->data = $data;
					return true;
				}
			}			
			$this->error = "invalid choice";
			return false;						
		}
	}
	
	class number_check extends special_check
	{		
		protected $format = false;
		protected $min = MIN;
		protected $max = MAX;
		protected $decimals = 2;
		protected $dec_point = '.';
		protected $thousands_sep = '';		
		
		public function number_check($min = MIN, $max = MAX, $format = false, $decimals = 2, $dec_point = null, $thousands_sep = null)
		{
			$this->min = $min;
			$this->max = $max;
			$this->format = (bool)$format;
			if (!empty($decimals) && is_numeric($decimals))
				$this->decimals = $decimals;	
			if (isset($dec_point))
				$this->dec_point = $dec_point;
			if (isset($thousands_sep))
				$this->thousands_sep = $thousands_sep;			
		}
		
		public function load($data)
		{
			$this->error = null;
			$this->data = null;
			if (is_nan($data))
			{
				$this->error = "must be a number, only [0-9] and [.] are allowed";
				return false;
			}			
			if ($data < $this->min)
			{
				$this->error = "may not be less than {$this->min}";
				return false;
			}	
			if ($data > $this->max)
			{
				$this->error = "may not be more than {$this->max}";
				return false;
			}	
			
			if ($this->format)
			{
				$data = 0.01 + $data;
				$data = $data - 0.01;
				$data = number_format($data, $this->decimals, $this->dec_point, $this->thousands_sep);				
			}
			$this->data = $data;
			return true;
		}
	}
	
	class image_data
	{
		protected $Name = "";
		public $Description = "";
		
		protected $Required = false;
		protected $maxSize = 0;
		protected $extensions = null;
		protected $uploadTo = "";
		
		public $data = "";
		public $error = null;
		
		public function image_data($name, $req, $desc, $maxSize, $extensions, $uploadTo)//in kb eg. 1mb = 1000kb
		{
			$this->Name = $name;
			$this->Required = (bool)$req;
			if (empty($desc))
				$this->Description = $name;
			else
				$this->Description = $desc;	
			
			$this->maxSize = $maxSize*1024;//Converts to kilibits
			$this->extensions = $extensions;	
			$this->uploadTo = $uploadTo;			
		}
		
		public function load()
		{
			$this->error = null;
			$this->data = null;
			$file = $this->Name;
			if (isset($_FILES[$file]))
			{			
				if ($_FILES[$file]["error"] > 0)
				{
					$this->error = "Uploading {$this->Description} encountered an error: " . $_FILES["file"]["error"];
					return false;
				}
				else
				{	
					$name =  $_FILES[$file]["name"];
					$type = $_FILES[$file]["type"];
					$size = $_FILES[$file]["size"];
					echo $name . $type . $size;
					if ($size > $this->maxSize)
					{
						$this->error = $this->Description . " exceeds the max size of ".$this->maxSize/1024 ."kb";
						return false;
					}
					$temp1 = explode(".",$name);
					$temp2 = explode("/",$type);
					$extension1 = strtolower(end($temp1));
					$extension2 = strtolower(end($temp2));
					if (empty($this->extensions) || (in_array($extension1, $this->extensions, true) && in_array($extension2, $this->extensions, true)))
					{
						$date = date_create();
						$TIMESTAMP = date_timestamp_get($date);
						$this->data = "{$this->uploadTo}{$TIMESTAMP}.{$extension1}";
						move_uploaded_file($_FILES[$file]["tmp_name"], $this->data);
						return true;
					} else
					{
						$this->error = $this->Description . " is not a valid image type, only ".json_encode($extensions)." are allowed";
						return false;
					}
					
				}
			} else	
			{				
				if ($this->Required)
				{
					$this->error = $this->Description . " is required";
					return false;
				}
			}
		}
	}
	
	class webdata
	{
		protected $Name = "";
		public $Description = "";
		protected $DefaultVal = null;		
			
		protected $Required = false;
		protected $isSpecial = null;//or new number_check();
		protected $isEmail = false;
		protected $minL = 0;
		protected $maxL = null;
		
		public $data = null;	
		public $error = null;	
		
		public function webdata($name, $req, $default, $desc, $isSpecial, $isE, $minL, $maxL)
		{
			$this->Name = $name;
			$this->Required = (bool)$req;
			$this->DefaultVal = $default;
			if (empty($desc))
				$this->Description = $name;
			else
				$this->Description = $desc;	
			if (isset($isN))
				$this->isSpecial = $isN;//object of number_check
			if (isset($isE))
				$this->isEmail = (bool)$isE;		
			if (isset($minL))
				$this->minL = $minL;	
			if (isset($maxL))
				$this->maxL = $maxL;	
		}
		
		public function load()
		{
			$this->error = null;
			$data = null;
			$this->data = $data;
			if (isset($_REQUEST[$this->Name]))
			{
				$data = trim($_REQUEST[$this->Name]);
				if (empty($data))
				{
					if ($this->Required)
					{
						$this->error = $this->Description . " is required";
						return false;
					}
					$data = $this->DefaultVal;
				}
				
				if ($this->isSpecial)
				{
					if ($this->isSpecial->load($data))
						$data = $this->isNumber->data;
					else
					{
						$this->error = $this->Description .  " {$this->isSpecial->error}";
						return false;
					}
				} else
				{						
					if ($this->minL > strlen($data))
					{
						$this->error = $this->Description . " is to short, minumum {$this->minL} characters";
						return false;
					}
					
					if (isset($this->maxL) && strlen($data) > $this->maxL)
					{
						$this->error = $this->Description . " is too long, maximum {$this->maxL} characters long";
						return false;
					}
					if ($this->isEmail)
					{
						$clean_email = filter_var($data,FILTER_SANITIZE_EMAIL);
						if ($data !== $clean_email || !filter_var($data,FILTER_VALIDATE_EMAIL))
						{
							$this->error = $this->Description . " is an invalid email address";
							return false;
						}
						$data = $clean_email;
					}
				}
				
			} else
			{
				if ($this->Required)
				{
					$this->error = $this->Description . " is required";
					return false;
				}
				$data = $this->DefaultVal;
			}
			$this->data = $data;
			return true;
		}
	}
	
	//SETUP FUNCTIONS
	function addInput($name, $req = false, $default = null, $desc = null, $isSpecial = null, $isE = null, $minL = null, $maxL = null)
	{		
		global $IC;
		$IC[$name] = new webdata($name, $req, $default, $desc, $isSpecial, $isE, $minL, $maxL);
	}
	
	function addImage($name, $req = false, $desc = null, $maxSize = null, $extensions = null, $uploadTo = "")
	{		
		global $IC;
		$IC[$name] = new image_data($name, $req, $desc, $maxSize, $extensions, $uploadTo);
	}
	
	//USAGE FUNCTIONS
	//Load the data from $_Request for the specified fields
	function load_IC($fields)
	{
		global $IC;
		echo "Before loading: ".json_encode($IC);
		$errors = array();
		if ($fields == "*")
		{
			foreach ($IC as $key => $value)
			{
				if ($value->load() === false)
				{
					$errors[$key] = $value->error;
				}
			}
		} else
		{		
			foreach ($fields as $field)
			{
				if (isset($IC[$field]))
				{
					if ($IC[$field]->load() === false)
					{
						$errors[$field] = $IC[$field]->error;
					}
				} else
					$errors[$field] = "{$field} does not exist on server";
			}
		}
		echo "After loading: ".json_encode($IC);
		return $errors;
	}
	
	//Retrieve the field's loaded data
	function getIC($field)
	{
		global $IC;
		return $IC[$field]->data;
	}
	//Retrieve the field's error message
	function getICerror($field)
	{
		global $IC;
		return $IC[$field]->error;
	}
	//Retrieve the field's description
	function getICdesc($field)
	{
		global $IC;
		return $IC[$field]->Description;
	}
?>