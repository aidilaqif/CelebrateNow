<?php
use PhpOffice\PhpSpreadsheet\IOFactory;

$uploadMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
        $excelFilePath = $_FILES['excelFile']['tmp_name'];

        // Use PhpSpreadsheet to read the Excel file
        require_once 'vendor/autoload.php'; // Include the library file

        // Load the Excel file
        $spreadsheet = IOFactory::load($excelFilePath);

        // Get the active sheet
        $worksheet = $spreadsheet->getActiveSheet();

        // Get the highest row and column indexes
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        // Get the attribute headers from the first row of the Excel file
        $headers = [];
        for ($column = 'A'; $column <= $highestColumn; $column++) {
            $cellValue = $worksheet->getCell($column . '1')->getValue();
            $headers[] = $cellValue;
        }

        // Get the data from the Excel file
        $studentData = [];

        // Skip the header row while reading the data
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            for ($column = 'A'; $column <= $highestColumn; $column++) {
                $cellValue = $worksheet->getCell($column . $row)->getFormattedValue();
                $rowData[] = $cellValue;
            }
            $studentData[] = $rowData;
        }
        $uploadMessage = "Excel file uploaded successfully.";
    } else {
        $uploadMessage = "Error uploading the Excel file.";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Student Information</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Trebuchet MS';
            background-color: #f7f7f7;
            margin: 0 auto;
            padding: 20px;
            background-image: url('backgroundImg.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }


        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            color: #555;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        textarea {
            height: 80px;
        }

        input[type="submit"] {
            background-color: #a6c8e5;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #8db4d7;
        }

        .message {
            margin-top: 10px;
            padding: 10px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            color: #555;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .upload-form {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .upload-form label {
            display: block;
            margin-bottom: 5px;
        }

        .upload-form input[type="file"] {
            display: none;
        }

        .upload-form .custom-file-upload {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4caf50;
            color: #ffffff;
            border-radius: 3px;
            cursor: pointer;
        }

        .upload-form .custom-file-upload:hover {
            background-color: #45a049;
        }

        .upload-form .file-name {
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h1>Student Information</h1>

    <div class="container">
        <div class="message">
            <?php echo $uploadMessage; ?>
        </div>

        <?php if (!empty($studentData)): ?>
            <table id="studentTable">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th><?php echo $header; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studentData as $row): ?>
                        <tr>
                            <?php foreach ($row as $cellValue): ?>
                                <td><?php echo $cellValue; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <form class="upload-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <label for="excelFile">Upload Excel File:</label>
        <label class="custom-file-upload">
            <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls">
            Select File
        </label>
        <span class="file-name"></span>
        <input type="submit" value="Upload">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#studentTable').DataTable();

            // Show selected file name
            $('#excelFile').change(function() {
                var fileName = $(this).val().split('\\').pop();
                $('.file-name').text(fileName);
            });
        });
    </script>
</body>

</html>
