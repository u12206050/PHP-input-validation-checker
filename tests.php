<!DOCTYPE>
<html>
	<head>
	</head>
	<body>		
		<form action="#" method="post" enctype="multipart/form-data">
			<label for="user">Username:</label>
			<input type="text" name="user"><br>
			<label for="pass">Password:</label>
			<input type="password" name="pass"><br>
			<label for="email">Email</label>
			<input type="text" name="email"><br>
			<label for="age">Age:</label>
			<input type="number" name="age"><br>
			<label for="salary">Salary:</label>
			<input type="number" name="salary"><br>
			<label for="profilepic">Profile Picture:</label>
			<input type="file" name="profilepic"><br>
			<input type="submit" value="submit">
		</form>
		<?php
			require "tests_ICdata.php";	
			$errors = load_IC(["user","pass","email","age","salary","profilepic"]);
			if ($errors)
			{				
				echo "<h3 style='color: red;'>Error: <hr>";
				foreach ($errors as $key => $error)
				{
					echo "{$key}: {$error} <br>";
				}
				echo "</h3>";
			}
			else
			{
				echo "<h3 style='color: blue;'>Everything is good, your details are</h3>";
				echo "<h2 style='color: green;'>Username: ".getIC('user')."</h2>";
				echo "<h2 style='color: green;'>Password: ".getIC('pass')."</h2>";
				echo "<h2 style='color: green;'>Email: ".getIC('email')."</h2>";
				echo "<h2 style='color: green;'>Age: ".getIC('age')."</h2>";
				echo "<h2 style='color: green;'>Salary: R ".getIC('salary')." </h2>";	
				echo "<h2 style='color: green;'>".getICdesc('profilepic');
				echo ": <img width='200px' src='".getIC('profilepic')."' alt='".getIC('profilepic')."'> </h2>";	
			}
		?>
	</body>
</html>