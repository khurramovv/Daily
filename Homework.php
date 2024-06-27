<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Schedule</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form action="Homework.php" method="post">
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

            foreach ($days as $day) {
                echo "<div class='day'>";
                echo "<h3>$day</h3>";
                echo "<div class='form-group'>";
                echo "<label for='arrivedAt[$day]'>Arrived at:</label>";
                echo "<input type='datetime-local' id='arrivedAt[$day]' name='arrivedAt[$day]' required>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='leavedAt[$day]'>Leaved at:</label>";
                echo "<input type='datetime-local' id='leavedAt[$day]' name='leavedAt[$day]' required>";
                echo "</div>";
                echo "</div>";
            }
            ?>
            <button type="submit">Send</button>
        </form>

        <div class="results">
            <?php
            class WorkDay {
                public $day;
                public $arrivedAt;
                public $leavedAt;
                private $workSchedule = 540;

                public function __construct($day, $arrivedAt, $leavedAt) {
                    $this->day = $day;
                    $this->arrivedAt = new DateTime($arrivedAt);
                    $this->leavedAt = new DateTime($leavedAt);
                }

                public function calculateWorkTime() {
                    $interval = $this->arrivedAt->diff($this->leavedAt);
                    return $interval;
                }

                public function calculateTotalWorkOffTime() {
                    $workTime = $this->calculateWorkTime();
                    $totalMinutes = $workTime->h * 60 + $workTime->i;

                    if ($totalMinutes <= $this->workSchedule) {
                        return $this->workSchedule - $totalMinutes;
                    } else {
                        return $totalMinutes - $this->workSchedule;
                    }
                }

                public function getFormattedWorkTime() {
                    return $this->calculateWorkTime()->format('%H:%I');
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                foreach ($days as $day) {
                    if (isset($_POST["arrivedAt"][$day], $_POST["leavedAt"][$day])) {
                        $workDay = new WorkDay($day, $_POST["arrivedAt"][$day], $_POST["leavedAt"][$day]);
                        $workOffTime = $workDay->calculateTotalWorkOffTime();

                        echo "<div class='result-item'>";
                        echo "<p><strong>$day:</strong></p>";
                        echo "<p><strong>Work duration:</strong> " . $workDay->getFormattedWorkTime() . "</p>";
                        echo "<p><strong>Debt:</strong> $workOffTime minutes</p>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
