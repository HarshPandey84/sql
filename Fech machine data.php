<?php
include "header.php";
include "database.php";

// Get shop number from URL
$shop_no = isset($_GET['shop_no']) ? intval($_GET['shop_no']) : 0;
if ($shop_no <= 0 || $shop_no >12 ) {
    echo "Invalid shop number.";
    exit();
}

// Fetch machines for the specified shop
$query = "SELECT * FROM machines WHERE shop_no = $shop_no";
$result = $conn->query($query);
if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machines in Shop <?php echo htmlspecialchars($shop_no); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', function(event) {
                var isValid = true;
                var machineName = document.getElementById('machineName');
                var value = document.getElementById('value');
                var data = document.getElementById('data');

                clearErrors(); // Clear previous errors

                if (!machineName.value.trim()) {
                    showError(machineName, 'Machine Name is required');
                    isValid = false;
                }
                if (!value.value.trim()) {
                    showError(value, 'Value is required');
                    isValid = false;
                }
                if (!data.value.trim()) {
                    showError(data, 'Data is required');
                    isValid = false;
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent the form from submitting
                }
            });

            function showError(input, message) {
                var error = document.createElement('div');
                error.className = 'invalid-feedback';
                error.innerText = message;
                input.classList.add('is-invalid');
                input.parentElement.appendChild(error);
            }

            function clearErrors() {
                var errors = document.querySelectorAll('.invalid-feedback');
                errors.forEach(function(error) {
                    error.remove();
                });
                var inputs = document.querySelectorAll('.is-invalid');
                inputs.forEach(function(input) {
                    input.classList.remove('is-invalid');
                });
            }
        });
    </script>
</head>
<body>
<div class="content">  <!--for footer adjust-->
    <h1 style="color:white; margin-bottom:50px">
    Machines in Shop <?php echo htmlspecialchars($shop_no); ?>
    </h1>

    <div class="container">
        <div class="box1">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Machine</button>
            <?php endif; ?>
        </div>

        <table class="table table-hover table-bordered table-striped">
            <thead>
                <tr>
                    <th>Machine Name</th>
                    <th>Value</th>
                    <th>Data</th>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                    <th colspan="2" >Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['machine_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['value']); ?></td>
                    <td><?php echo htmlspecialchars($row['data']); ?></td>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                    <td>
                        <a class="btn btn-success" href="edit_machine.php?id=<?php echo $row['id']; ?>">Edit</a>
                        </td>
                    <td>
                        <a class="btn btn-danger" href="delete_machine.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this machine?');">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <?php
if (isset($_GET["msg"])) {
    echo '<div class="text-success text-center mt-3">' . htmlspecialchars($_GET["msg"]) . '</div>';
}
?>


        </div> 
        <!-- Modal -->
        <form action="insert.php" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="machineName">Machine Name</label>
                        <input type="text" id="machineName" name="m" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="value">Value</label>
                        <input type="text" id="value" name="v" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="data">Data</label>
                        <input type="text" id="data" name="d" class="form-control">
                    </div>
                    <input type="hidden" name="sno" value="<?php echo htmlspecialchars($shop_no); ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" name="sub" class="btn btn-success" value="ADD">
            </div>
        </div>
    </div>
</div>
</form>
</div>
<?php
include "footer.html";
?>
</body>
</html>

<?php $conn->close(); 
?>
