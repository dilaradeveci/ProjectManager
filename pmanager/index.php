<?php
ob_start();
$dsn = 'mysql:dbname=pmanager;host=localhost;charset=utf8';
$user = 'root';
$pass = '';

try {
    $db = new PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

if (isset($_POST['saveprojectdetails'])) {
    $projectname = $_POST['projectname'];
    $projectstartdate = $_POST['projectstartdate'];
    $projectenddate = $_POST['projectenddate'];
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf8">
        <title>Project Manager</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    </head>
    <body>
        <div class="first_screen">
            <div class="project_details">
                <form action="" method="post">
                    <input type="text" name="projectname" placeholder="Project Name" required>
                    <input type="date" name="projectstartdate" placeholder="Start Date" required>
                    <input type="date" name="projectenddate" placeholder="End Date" required>
                    <button name="saveprojectdetails">Save Details</button>
                </form>
            </div>
            <div class="container">
                <div class="tasks">
                    <div class="tasks_head">
                        <div class="tasks_title">
                            Tasks
                        </div>
                        <div class="tasks_command">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                    <div class="tasks_body">
                        <?php
                        $query = $db->query("SELECT * FROM tasks", PDO::FETCH_ASSOC);
                        
                        if ($query->rowCount()) {
                            foreach ($query as $row) {
                                echo '<span>' . $row['task'] . '</span>';
                            }
                        }
                        ?>
                    </div>
                    <button class="open_chart">Open Chart Screen</button>
                </div>
                <div class="participants">
                    <div class="participants_head">
                        <div class="participants_title">
                            Participants
                        </div>
                        <div class="participants_command">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                    <div class="participants_body">
                        <?php
                        $query = $db->query("SELECT * FROM participants", PDO::FETCH_ASSOC);
                        
                        if ($query->rowCount()) {
                            foreach ($query as $row) {
                                echo '<span>' . $row['fullname'] . '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="second_screen">
            <div class="container">
                <div class="charts">
                    <div class="chart_box">
                        <div class="chart_box_head">
                            <div class="month">
                                1
                            </div>
                            <div class="month">
                                2
                            </div>
                            <div class="month">
                                3
                            </div>
                            <div class="month">
                                4
                            </div>
                            <div class="month">
                                5
                            </div>
                            <div class="month">
                                6
                            </div>
                            <div class="month">
                                7
                            </div>
                            <div class="month">
                                8
                            </div>
                            <div class="month">
                                9
                            </div>
                            <div class="month">
                                10
                            </div>
                            <div class="month">
                                11
                            </div>
                            <div class="month">
                                12
                            </div>
                        </div>
                        <div class="chart_box_body">
                            <?php
                            $query = $db->query("SELECT * FROM tasks ORDER BY startdate ASC", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    $id = $row['id'];
                                    $nameTask = $row['task'];
                                    $startdateTask = substr($row['startdate'], 5, 2);
                                    $enddateTask = substr($row['enddate'], 5, 2);

                                    if ($startdateTask == '01') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 640px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 720px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '02') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 800px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '01') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-right: 880px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '02') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 640px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 720px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '02') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 80px; margin-right: 800px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '03') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 640px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 160px;margin-right: 720px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '04') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 240px;margin-right: 640px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '05') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 320px;margin-right: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '06') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 400px;margin-right: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '07') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 480px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '08') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;margin-right: 320px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 560px;margin-right: 400px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '09') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 640px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 640px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 640px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 640px;margin-right: 240px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '10') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 720px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 720px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 720px;margin-right: 160px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '11') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 800px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 800px;margin-right: 80px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '12') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '"><span style="margin-left: 880px;" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <button class="open_second">Back To Tasks Screen</button>
                    </div>
                </div>
                <div class="details">
                    <?php
                    if (isset($_GET['id'])) {
                        $requestId = $_GET['id'];
                    
                        $stmt = $db->prepare("SELECT task, participants, startdate, enddate, important FROM tasks WHERE id = ?");
                        
                        $stmt->execute([$requestId]); 
                        $row = $stmt->fetch();
                        
                        echo '<div class="details_head">Selected Task: ' . $row['task'] . '</div>';
                        ?>
                        <div class="details_body">
                            <span>
                                Assignee: <?php echo $row['participants']; ?>
                            </span>
                            <span>
                                Start Date: <?php echo $row['startdate']; ?>
                            </span>
                            <span>
                                End Date: <?php echo $row['enddate']; ?>
                            </span>
                            <span>
                                Important: <?php echo $row['important']; ?>
                            </span>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="details_head">
                        </div>
                        <div class="details_body">
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="tasks_popup">
            <div class="tasks_box">
                <div class="tasks_box_head">
                    <div class="box_title">
                        Add Task Screen
                    </div>
                    <div class="tasks_box_command">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="tasks_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="Task Name" name="taskname" required>
                        <span class="label">Start Date</span>
                        <input type="date" placeholder="Start Date" name="startdate" required>
                        <span class="label">End Date</span>
                        <input type="date" placeholder="End Date" name="enddate" required>
                        <span class="label">Participations</span>
                        <select class="names_select" name="taskfullnames[]" multiple>
                            <?php
                            $query = $db->query("SELECT * FROM participants", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['fullname'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="label">Important Task</span>
                        <select name="important">
                            <option>Yes</option>
                            <option selected>No</option>
                        </select>
                        <button name="addtask">Add Task</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="participants_popup">
            <div class="participants_box">
                <div class="participants_box_head">
                    <div class="box_title">
                        Add Participant Screen
                    </div>
                    <div class="participants_box_command">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="participants_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="Full Name" name="fullname">
                        <button name="addparticipant">Add Participant</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </body>
</html>


<?php
// Process for add participants
if (isset($_POST['addparticipant'])) {
    $fullnameInject = $_POST['fullname'];

    $dataP = [
        'fullname' => $fullnameInject
    ];

    try {
        $sql = 'INSERT INTO participants (
            fullname
        ) VALUES (
            :fullname
        )';
        $stmt = $db->prepare($sql);
        $stmt->execute($dataP);
        header('Refresh:0');
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Process for add tasks
if (isset($_POST['addtask'])) {
    $tasknameInject = $_POST['taskname'];
    $startdateInject = $_POST['startdate'];
    $enddateInject = $_POST['enddate'];
    $importantInject = $_POST['important'];

    $startdateInjectBefore = (int)substr($startdateInject, 5, 2);
    $enddateInjectBefore = (int)substr($enddateInject, 5, 2);

    if ($startdateInjectBefore > $enddateInjectBefore) {
        echo 'Error: End date must not be earlier than start date';
    } else {
        if ($_POST['taskfullnames'] == '') {
            $taskfullnamesInject = '-';
        } else {
            $dataFN = [];
            
            foreach ($_POST['taskfullnames'] as $taskfullnames) {
                array_push($dataFN, $taskfullnames);
            }
            $taskfullnamesInject = implode(', ', $dataFN);
        }
    
        $dataT = [
            'task' => $tasknameInject,
            'participants' => $taskfullnamesInject,
            'startdate' => $startdateInject,
            'enddate' => $enddateInject,
            'important' => $importantInject
        ];
    
        try {
            $sql = 'INSERT INTO tasks (
                task,
                participants,
                startdate,
                enddate,
                important
            ) VALUES (
                :task,
                :participants,
                :startdate,
                :enddate,
                :important
            )';
            $stmt = $db->prepare($sql);
            $stmt->execute($dataT);
            header('Refresh:0');
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>
