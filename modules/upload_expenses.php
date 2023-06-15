<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */

 include  'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded

    if (isset($_FILES['csv'])) {
        $fname = $_FILES['csv']['tmp_name'];
    echo $expense;
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
    <h2> Expenses Upload To ERP</h2>
    <div class="form-container">
        <form action="upload_tenants.php" method="post" enctype="multipart/form-data">
            <label for="csv">Select CSV File:</label>
            <input type="file" id="csv" name="csv" accept=".csv" required>
            <input type="submit" value="Upload">
        </form>
    </div>
</body>
</html>

