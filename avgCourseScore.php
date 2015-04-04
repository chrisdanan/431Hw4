<?php

	/*
	 *Name: Christopher Dancarlo Danan
	 *Created: April 2, 2015
	 *Modified: April 3, 2015
	 *Purpose: Remake of 431 hw 3's code.  This script calculates a student's grade in the course based on the grades
	 			stored in the database.
	*/

	//Constants for the weights in the course.
	define("ATTENDANCE_WEIGHT", 0.05);
	define("ASSIGNMENT_WEIGHT", 0.20);
	define("PROJECT_WEIGHT", 0.20);
	define("MIDTERM_WEIGHT", 0.25);
	define("FINAL_WEIGHT", 0.30);

	class StudentScores{

		//Unweighted scores.
		public $attendanceScore = 0;
		public $avgAssignmentScore = 0;
		public $projectScore = 0;
		public $midtermScore = 0;
		public $finalScore = 0;
		//Weighted scores.
		public $wattendanceScore = 0;
		public $wavgAssignmentScore = 0;
		public $wprojectScore = 0;
		public $wmidtermScore = 0;
		public $wfinalScore = 0;
		//Total grade.
		private $totalGrade = 0;

		function __construct($cwid){
			$link = mysqli_connect("127.0.0.1", "root", "root", "hw2");

			if(mysqli_connect_errno()){
				echo "Connection failed: " . mysqli_connect_error();
				exit();
			}

			//echo "Successfully connected to database 'hw2'\n";
	
			//Get the course scores for the student.
			$query = "SELECT * FROM course_score WHERE student_id = " . $cwid . ";";

			//Send in the query to MySQL and get the results.
			$result = $link->query($query) or die("ERROR: " . mysqli_error($link));

			//Reference for handling empty result sets: http://stackoverflow.com/questions/11292468/check-if-value-exists-in-mysql
			if($result->num_rows === 0){
				echo "<p>No student exists with cwid " . $cwid . "</p>";
				exit();
			} else{
				//Store the result in an associative array so we can access the information via keys.
				$row = $result->fetch_assoc();

				//Store the results into variables.
				$this->attendanceScore = $row["attendance_score"];
				$this->projectScore = $row["term_project_score"];
				$this->midtermScore = $row["midterm_score"];
				$this->finalScore = $row["final_score"];
			}

			//Free the result set.
			$result->free();

			//Get the average of the homework assignments for the student.
			$query = "SELECT AVG(H.score) FROM homework_score AS H, student AS S WHERE H.student_id = S.cwid AND S.cwid = " . $cwid . ";";
			$result = $link->query($query) or die("ERROR: " . mysqli_error($link));

			if($result->num_rows === 0){
				echo "<p>No student exists with cwid " . $cwid . "</p>";
			} else{
				$row = $result->fetch_row();

				//Only average is returned by result set, so it will be in $row[0].
				$this->avgAssignmentScore = $row[0];
			}

			$result->free();

			$link->close();

			$this->calcWeights();
			$this->calcTotalGrade();
		} //End constructor.

		//Purpose: Calculate the weighted scores for the student.
		private function calcWeights(){
			$this->wattendanceScore = $this->attendanceScore * ATTENDANCE_WEIGHT;
			$this->wavgAssignmentScore = $this->avgAssignmentScore * ASSIGNMENT_WEIGHT;
			$this->wprojectScore = $this->projectScore * PROJECT_WEIGHT;
			$this->wmidtermScore = $this->midtermScore * MIDTERM_WEIGHT;
			$this->wfinalScore = $this->finalScore * FINAL_WEIGHT;
		}

		//Purpose: Calculate the total score for the student.
		private function calcTotalGrade(){

			$this->totalGrade = $this->wattendanceScore + $this->wavgAssignmentScore + $this->wprojectScore + $this->wmidtermScore + $this->wfinalScore;
		}

		//Purpose: Output the total grade for the student.
		public function outputGrade($cwid){
			//Reference for number format: http://stackoverflow.com/questions/12435556/format-a-float-to-two-decimal-places
			echo "<p>Total grade in the class for student " . $cwid . " is " . number_format($this->totalGrade, 2) . "%</p>";
		}
	}

	//Get cwid entered by user.
	$cwid = $_POST["cwid"];

	$studentScore = new StudentScores($cwid);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Student Report</title>
		<meta charset="utf-8" />
		<meta name="author" content="Christopher Dancarlo Danan" />
		<link rel="stylesheet" text="text/css" href="style.css" />
	</head>
	<body>
		<header>
			<h1>Student Report for CWID: <?php echo $cwid ?></h1>
		</header>
		<main>
			<table class="studentsTable">
				<thead>
					<tr>
						<th id="emptyCell"></th>
						<th>Score</th>
						<th>Weighted Score</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Attendance</td>
						<td><?php echo number_format($studentScore->attendanceScore, 2);?></td>
						<td><?php echo number_format($studentScore->wattendanceScore, 2);?></td>
					</tr>
					<tr>
						<td>Assignments</td>
						<td><?php echo number_format($studentScore->avgAssignmentScore, 2);?></td>
						<td><?php echo number_format($studentScore->wavgAssignmentScore, 2);?></td>
					</tr>
					<tr>
						<td>Project</td>
						<td><?php echo number_format($studentScore->projectScore, 2);?></td>
						<td><?php echo number_format($studentScore->wprojectScore, 2);?></td>
					</tr>
					<tr>
						<td>Midterm</td>
						<td><?php echo number_format($studentScore->midtermScore, 2);?></td>
						<td><?php echo number_format($studentScore->wmidtermScore, 2);?></td>
					</tr>
					<tr>
						<td>Final</td>
						<td><?php echo number_format($studentScore->finalScore, 2);?></td>
						<td><?php echo number_format($studentScore->wfinalScore, 2);?></td>
					</tr>
				</tbody>
			</table>

			<div class="results">
				<?php $studentScore->outputGrade($cwid); ?>
			</div>
		</main>
		<footer>
		</footer>
	</body>
</html>