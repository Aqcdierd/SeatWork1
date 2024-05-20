<?php
session_start();

if (!isset($_SESSION["todoList"])) {
    $_SESSION["todoList"] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["task"])) {
        echo '<script>alert("Error: there is no data to add in array")</script>';
    } else {
        $taskDetails = array(
            'task' => htmlspecialchars($_POST["task"]),
            'date' => isset($_POST["date"]) ? $_POST["date"] : null,
            'time' => isset($_POST["time"]) ? $_POST["time"] : null
        );
        $_SESSION["todoList"][] = $taskDetails;
    }
}

// Handle task deletion
if (isset($_GET['task'])) {
    $taskToDelete = urldecode($_GET['task']);
    foreach ($_SESSION["todoList"] as $key => $task) {
        if (is_array($task) && isset($task['task']) && $task['task'] === $taskToDelete) {
            unset($_SESSION["todoList"][$key]);
            break;
        }
    }
    // Reset array keys
    $_SESSION["todoList"] = array_values($_SESSION["todoList"]);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">To-Do List</h1>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="task">Task</label>
                        <input type="text" class="form-control" name="task" id="task" placeholder="Enter your task here" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="time">Time</label>
                            <input type="time" class="form-control" name="time" id="time">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Task</button>
                </form>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Tasks</h2>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach($_SESSION["todoList"] as $task): ?>
                    <?php if(is_array($task) && isset($task['task'])): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span><?php echo htmlspecialchars($task['task']); ?></span>
                                <small class="text-muted ml-3">
                                    <?php if ($task['date']): ?>
                                        <i class="far fa-calendar-alt"></i> <?php echo $task['date']; ?>
                                    <?php endif; ?>
                                    <?php if ($task['time']): ?>
                                        <i class="far fa-clock"></i> <?php echo $task['time']; ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <a href="?task=<?php echo urlencode($task['task']); ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
