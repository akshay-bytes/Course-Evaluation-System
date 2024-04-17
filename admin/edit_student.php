<?php
include 'db_connect.php';

// Check if $_GET['id'] is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
	// Fetch data from the database
	$qry = $conn->query("SELECT * FROM student_list where id = " . $_GET['id']);

	// Check if the query was successful and it returned at least one row
	if ($qry && $qry->num_rows > 0) {
		// Fetch the first row as an associative array
		$result = $qry->fetch_array();

		// Check if $result is not null
		if ($result !== null) {
			// Iterate over the result and assign each key-value pair to a variable
			foreach ($result as $k => $v) {
				$$k = $v;
			}

			// Include the file to display the form with pre-filled data
			include 'new_student.php';
		} else {
			echo "Error: No data found for the specified ID.";
		}
	} else {
		echo "Error: Query failed or no data found for the specified ID.";
	}
} else {
	echo "Error: ID parameter is missing or invalid.";
}
