<!DOCTYPE html>
<html>
<head>
    <title>Upload Invoices and Receipts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px #ccc;
        }
        h1 {
            text-align: center;
            margin-top: 0;
        }
        label {
            display: block;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type=file] {
            margin-bottom: 10px;
        }
       .btn, input[type=submit] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a class="btn" href="/modules/templates/invoice.csv">download template</a>
        <h1>Upload Receipts</h1>
        <form method="post" action="modules/upload_receipts.php" enctype="multipart/form-data">
            <label for="receipt_file">Upload Receipt CSV file:</label>
            <input type="file" id="receipt_file" name="receipt_file" accept=".csv">
            <input type="submit" value="Upload Receipts">
            <?php if(isset($receipt_error)): ?>
            <div class="error"><?= $receipt_error ?></div>
            <?php endif; ?>
            <?php if(isset($receipt_success)): ?>
            <div class="success"><?= $receipt_success ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
