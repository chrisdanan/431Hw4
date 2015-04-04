<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Course site</title>
		<meta charset="utf-8" />
		<meta name="author" content="Christopher Dancarlo Danan" />
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<header>
			<h1>Welcome</h1>
		</header>
		<main>
			<div class="topContent">
				<h2>Students enrolled in the course</h2>
				<div class="students">
					<table class="studentsTable">
						<thead>
							<tr>
								<th class="cwid">CWID</th>
								<th class="fname">First Name</th>
								<th class="lname">Last Name</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$link = mysqli_connect("127.0.0.1", "root", "root", "hw2");

								if(mysqli_connect_errno()){
									echo "Connect failed: " . mysqli_connect_error();
									exit();
								}

								$result = $link->query("SELECT cwid, fname, lname FROM student;");

								if($result->num_rows === 0){
									//Empty result set returned.
									echo "<p>No student records were found</p>";
								} else{
									//Reference for while-loop: http://stackoverflow.com/questions/14928604/php-and-mysqli-html-table-from-database
									while($row = mysqli_fetch_array($result)){
										echo "<tr>";
										echo "<td class='cwid'>" . $row["cwid"] . "</td>";
										echo "<td class='fname'>" . $row["fname"] . "</td>";
										echo "<td class='lname'>" . $row["lname"] . "</td>";
										echo "</tr>";
									}
								}

								$result->free();
								$link->close();
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="bottomContent">
				<div class="studentGrade">
					<div class="formHeader studentGradeHeader">
						<h2>Calculate grade for student in the course</h2>
					</div>
					<form action="avgCourseScore.php" method="post">
						<!--Reference for label tag: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/label-->
						<label for="inputCWIDField">Input Student's CWID</label>
						<input type="text" id="inputCWIDField" name="cwid" maxlength="5" />
						<input type="submit" id="inputCWIDSubmit" />
					</form>
				</div>
				<div class="stats">
					<div class="formHeader statsHeader">
						<h2>Calculate average and standard deviation of midterm and final scores in the course</h2>
					</div>
					<div id="avgTestScoresForm">
						<form action="avgTestScores.php" method="post">
							<input type="submit" id="getStatsSubmit" />
						</form>
					</div>
				</div>
			</div>
		</main>
		<footer>
		</footer>
	</body>
</html>