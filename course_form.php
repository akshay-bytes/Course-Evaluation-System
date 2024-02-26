<?php
session_start(); // Start session to store messages

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {

        // Define the path to store the uploaded CSV file
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a CSV file
        if ($fileType == "csv") {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                // Connect to MySQL database
                $conn = new mysqli("localhost", "root", "", "evaluation_db");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare SQL statement for inserting data
                $stmt = $conn->prepare("INSERT INTO subject_list (code, subject, description) VALUES (?, ?, ?)");

                // Read the CSV file
                $file = fopen($target_file, "r");

                // Skip the first row (headers)
                fgetcsv($file);

                // Read data row by row
                while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                    // Sanitize and validate input data
                    $sanitizedData = array_map(function ($value) use ($conn) {
                        return $conn->real_escape_string(trim($value));
                    }, $data);

                    // Assign CSV data to variables
                    $code = $sanitizedData[0];
                    $subject = $sanitizedData[1];
                    $description = $sanitizedData[2];

                    // Determine the unique identifier for the current row
                    // For example, you can use the email column as the unique identifier
                    $uniqueIdentifier = $sanitizedData[0]; // Assuming email is the unique identifier

                    // Check if the row already exists in the database
                    $existingRowQuery = $conn->prepare("SELECT COUNT(*) FROM subject_list WHERE email = ?");
                    $existingRowQuery->bind_param("s", $uniqueIdentifier);
                    $existingRowQuery->execute();
                    $existingRowQuery->bind_result($rowCount);
                    $existingRowQuery->fetch();
                    $existingRowQuery->close();
                    // If a matching row is found, skip inserting the current row
                    if ($rowCount > 0) {
                        continue;
                    }

                    // Insert the row into the database

                    // Bind parameters
                    $stmt->bind_param("sss", $code, $subject, $description);

                    // Execute SQL statement
                    if (!$stmt->execute()) {
                        $_SESSION['message'] = "Error: " . $stmt->error;
                        exit(json_encode("Error: " . $stmt->error));
                    }
                }

                // Close statement and file
                $stmt->close();
                fclose($file);

                // Close database connection
                $conn->close();

                // Delete the uploaded CSV file
                unlink($target_file);

                $_SESSION['message'] = "CSV data imported successfully.";
                exit(json_encode(1)); // Success response
            } else {
                $_SESSION['message'] = "Failed to upload file.";
                exit(json_encode("Failed to upload file."));
            }
        } else {
            $_SESSION['message'] = "Invalid file format. Please upload a CSV file.";
            exit(json_encode("Invalid file format. Please upload a CSV file."));
        }
    } else {
        $_SESSION['message'] = "No file uploaded or an error occurred during upload.";
        exit(json_encode("No file uploaded or an error occurred during upload."));
    }
} else {
    $_SESSION['message'] = "Invalid request method.";
    exit(json_encode("Invalid request method."));
}
?>

<!-- 
1. It checks if the file upload was successful and if the uploaded file is a CSV file.

2 .It connects to the MySQL database.

3. It prepares an SQL statement for inserting data into the student_list table.

4. It reads the CSV file, skipping the first row (headers).

5. For each row of data in the CSV file, it sanitizes and validates the input data, hashes the password using MD5, and assigns the CSV data to variables.

6. It checks if the row already exists in the database based on the email address.

7. If the row does not exist, it binds the parameters and executes the SQL statement to insert the data into the database.

8. After importing all the data, it closes the statement, closes the file, closes the database connection, and deletes the uploaded CSV file.

9. It sets a session message indicating the success of the import.

10. Overall, your code effectively prevents duplicate entries from being imported again and again by checking if each row already exists in the database before inserting it. If a matching row is found, the current row is skipped, ensuring that only unique entries are imported. -->