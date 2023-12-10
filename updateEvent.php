<?php
session_start();

// Database connection file
require 'db-connect.php';

$error = "";
$success = "";

// Fetch All Events
$stmt = $pdo->prepare("SELECT * FROM wdv341_events");
$stmt->execute();
$eventsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Update Event
if (isset($_POST['updateEvent'])) {
    $recid = $_POST['event_id'];

    // Extract posted form data
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventPresenter = $_POST['eventPresenter'];
    $eventDate = $_POST['eventDate']; // Ensure this is in 'YYYY-MM-DD' format
    $eventTime = $_POST['eventTime']; // Ensure this is in 'HH:MM:SS' format

    // Update the event data in the database
    $updateStmt = $pdo->prepare("UPDATE wdv341_events SET events_name = ?, events_description = ?, events_presenter = ?, events_date = ?, events_time = ? WHERE events_id = ?");
    if ($updateStmt->execute([$eventName, $eventDescription, $eventPresenter, $eventDate, $eventTime, $recid])) {
        $success = "Event updated successfully!";
    } else {
        $error = "Error updating event.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Event</title>
</head>
<body>
<h1>Instructions:</h1>
<p>To update an event, make changes to the fields and then click the "Update" button next to the event you want to update. (you may have to refresh the page to see the updated results)</p>
    <!-- Link to go back to homepage -->
    <p><a href="homepage.php">Back to Homepage</a></p>
<?php
if ($error != "") {
    echo "<p>Error: $error</p>";
}
if ($success != "") {
    echo "<p>$success</p>";
}

// Display the table of events
if (!empty($eventsData)) {
    echo '<table border="1">';
    echo '<tr><th>Event Name</th><th>Event Description</th><th>Event Presenter</th><th>Event Date</th><th>Event Time</th><th>Action</th></tr>';
    foreach ($eventsData as $event) {
        echo '<form action="updateEvent.php" method="post">';
        echo '<input type="hidden" name="event_id" value="' . $event['events_id'] . '">';
        echo '<tr>';
        echo '<td><input type="text" name="eventName" value="' . htmlspecialchars($event['events_name']) . '"></td>';
        echo '<td><textarea name="eventDescription">' . htmlspecialchars($event['events_description']) . '</textarea></td>';
        echo '<td><input type="text" name="eventPresenter" value="' . htmlspecialchars($event['events_presenter']) . '"></td>';
        echo '<td><input type="date" name="eventDate" value="' . htmlspecialchars($event['events_date']) . '"></td>';
        echo '<td><input type="time" name="eventTime" value="' . htmlspecialchars($event['events_time']) . '"></td>';
        echo '<td><input type="submit" name="updateEvent" value="Update"></td>';
        echo '</tr>';
        echo '</form>';
    }
    echo '</table>';
} else {
    echo "<p>No events found.</p>";
}
?>
</body>
</html>
