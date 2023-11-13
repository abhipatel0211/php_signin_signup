<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <title>Simple Authentication Example</title>
</head>
<body>

    <div class="container">

        <h1>Welcome to the Home Page</h1>

        <?php
        session_start();
        require_once "conn.php";

        if (isset($_SESSION['username']) && isset($_COOKIE['PHPSESSID'])) {
            echo '<p>Hello, ' . $_SESSION['username'] . '!</p>';
            echo '<form id="logoutForm" action="api.php" method="post">
                    <input type="hidden" name="logout" value="true">
                    <button type="submit">Logout</button>
                </form>';
        } else {
            header("Location: login.php");
            exit();
        }
        ?>

        <table border="1" id="dataTable">
            <tr>
                <th>S.No</th>
                <th>Random ID</th>
                <th>Generated date & time</th>
            </tr>
        </table>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            function addRowToTable(sNo, randomID, generatedDateTime) {
                var table = document.querySelector('table');
                var row = table.insertRow(-1);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                cell1.innerHTML = sNo;
                cell2.innerHTML = randomID; // Add randomID to the "Random ID" column
                cell3.innerHTML = generatedDateTime; // Add generatedDateTime to the "Generated date & time" column
            }

            function generateRandomID(count) {
                $.ajax({
                    url: 'api.php',
                    type: 'POST',
                    data: { generateRandomID: true },
                    dataType: 'json',
                    success: function (data) {
                        addRowToTable(count, data.randomID, new Date().toLocaleString());
                    },
                    error: function (xhr, status, error) {
                        console.error("Error in AJAX request:", status, error);
                    }
                });
            }
            generateRandomID(1);
            // Automatic trigger every 10 seconds for 6 times
            var count = 2;
            var intervalId = setInterval(function () {
                if (count <= 6) {
                    generateRandomID(count);
                    count++;
                } else {
                    clearInterval(intervalId);
                }
            }, 10000);
        </script>


    </div>

</body>
</html>
