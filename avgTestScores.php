<?php

	/*
	 *Name: Christopher Dancarlo Danan
	 *Created: April 2, 2015
	 *Modified: April 3, 2015
	 *Purpose: Remake of 431 hw 3's code.  This script calculates the average and standard deviations of the 
	 			midterm and final scores for the course.
	 *References:
	 	-When to use population vs. sample standard deviation
		https://statistics.laerd.com/statistical-guides/measures-of-spread-standard-deviation.php
		-mysql standard deviaton
		https://dev.mysql.com/doc/refman/5.0/en/group-by-functions.html#function_stddev-pop
	*/

	class ScoresStats{

		private $avgMidtermScores = 0;
		private $avgFinalScores = 0;
		private $sdMidtermScores = 0;
		private $sdFinalScores = 0;

		function __construct(){
			$link = mysqli_connect("127.0.0.1", "root", "root", "hw2");

			if(mysqli_connect_errno()){
				echo "Connection failed: " . mysqli_connect_error();
				exit();
			}

			//echo "Successfully connected to database 'hw2'\n";

			//Query database for the average and standard deviation of the midterm and final scores.
			$query = "SELECT AVG(midterm_score), STDDEV_POP(midterm_score), AVG(final_score), STDDEV_POP(final_score) FROM course_score;";

			//Send in the query to the db.
			$result = $link->query($query) or die("ERROR: " . mysqli_error($link));

			if($result->num_rows === 0){
				//If no rows returned in result set, then there are no scores recorded.
				echo "There are no scores recorded in the database. Please record scores first, then try again.\n";
			} else{
				//Store result set into a numerated index array.
				$row = $result->fetch_row();

				$this->avgMidtermScores = $row[0];
				$this->sdMidtermScores = $row[1];
				$this->avgFinalScores = $row[2];
				$this->sdFinalScores = $row[3];
			}

			$result->free();
			mysqli_close($link);
		}//End of constructor.

		//Purpose: Show a list of the midterm and final scores.
		public function showScores(){
			$link = mysqli_connect("127.0.0.1", "root", "root", "hw2");

			if(mysqli_connect_errno()){
				echo "Connection failed: " . mysqli_connect_error();
				exit();
			}

			//Reference for continuing long string on new lines: http://www.phptherightway.com/pages/The-Basics.html
			$query = "SELECT S.cwid, S.fname, S.lname, C.midterm_score, C.final_score "
					. "FROM student S, course_score C "
					. "WHERE S.cwid = C.student_id";
			$result = $link->query($query) or die("ERROR: " . mysqli_error($link));

			if($result->num_rows === 0){
				echo "<p>No records found</p>";
			} else{
				while($row = mysqli_fetch_array($result)){
					echo "<tr>";
					echo "<td>" . $row["cwid"] . "</td>";
					echo "<td>" . $row["fname"] . "</td>";
					echo "<td>" . $row["lname"] . "</td>";
					echo "<td>" . $row["midterm_score"] . "</td>";
					echo "<td>" . $row["final_score"] . "</td>";
					echo "</tr>";
				}
			}

			$result->free();
			$link->close();
		}
		//Purpose: Output average midterm score.
		public function showAverageMidtermScore(){
			echo "Average of the midterm scores is " . number_format($this->avgMidtermScores, 2) . "\n";
		}

		//Purpose: Output the average final score.
		public function showAverageFinalScore(){
			echo "Average of the final scores is " . number_format($this->avgFinalScores, 2) . "\n";
		}

		//Purpose: Output the population standard deviation of the midterm scores.
		public function showSDMidtermScore(){
			echo "The standard deviation of the midterm scores is " . number_format($this->sdMidtermScores, 2) . "\n";
		}

		//Purpose: Output the population standard deviation of the final scores.
		public function showSDFinalScore(){
			echo "The standard deviation of the final scores is " . number_format($this->sdFinalScores, 2) . "\n";
		}
	}//End class ScoresStats.


	$scores = new ScoresStats();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Midterm and final score averages and standard deviations</title>
		<meta charset="utf-8" />
		<meta name="author" content="Christopher Dancarlo Danan" />
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<header>
			<h1>Statistics for Midterm and Final</h1>
		</header>
		<main>
			<h2>List of the midterm and final scores for each student</h2>
			<table class="studentsTable">
				<thead>
					<tr>
						<th>CWID</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Midterm Score</th>
						<th>Final Score</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$scores->showScores();
					?>
				</tbody>
			</table>
			<div class="results">
				<?php
					echo "<p class='average' id='avgMidterm'>" . $scores->showAverageMidtermScore() . "</p>";
					echo "<p class='average' id='avgFinal'>" . $scores->showAverageFinalScore() . "</p>";
					echo "<p class='sd' id='sdMidterm'>" . $scores->showSDMidtermScore() . "</p>";
					echo "<p class='sd' id='sdFinal'>" . $scores->showSDFinalScore() . "</p>";

					/*
					t
					fflvd
					*/
				?>
			</div>
		</main>
		<footer>
		</footer>
	</body>
</html>