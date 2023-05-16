<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */

 include  'modules/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded

    if (isset($_FILES['csv']) && $_FILES['csv']['error'] === UPLOAD_ERR_OK) {
        $csvFile = $_FILES['csv']['tmp_name']; // Temporary location of the uploaded file
        $csvFileName = $_FILES['csv']['name']; // Original name of the uploaded file
       // die($csvFileName);
        // Call the addproperty1() function and pass the CSV file name
        addPropertyFromCSV($csvFileName);

        // Process the CSV file here
        // Example: Read the CSV file using fgetcsv() function and perform necessary operations

        // Move the uploaded file to a desired location
        // Example: move_uploaded_file($csvFile, 'path/to/destination/' . $csvFileName);

        echo "CSV file uploaded and processed successfully.";
    } else {
        echo "Error uploading the CSV file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Property CSV Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 500px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input[type=file] {
            margin-bottom: 10px;
        }

        .form-container input[type=submit] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Property CSV Upload</h2>
    <div class="form-container">
        <form action="process_csv.php" method="post" enctype="multipart/form-data">
            <label for="csv">Select CSV File:</label>
            <input type="file" id="csv" name="csv" accept=".csv" required>
            <input type="submit" value="Upload">
        </form>
    </div>
</body>
</html>
