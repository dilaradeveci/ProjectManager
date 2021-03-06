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
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Manager</title>		
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    </head>
	
    <body>
		<div class="contents">
        <div class="first_screen content content1">
            <div class="project_details">
			
                <form action="" method="post">
                    <input type="text" name="projectname" placeholder="Project Name" value="<?php if (isset($_POST['saveprojectdetails'])){ echo $_POST['projectname'];}?>" required>
                    <input type="date" name="projectstartdate" placeholder="Start Date" value="<?php if (isset($_POST['saveprojectdetails'])){ echo $_POST['projectstartdate'];}?>" required>
                    <input type="date" name="projectenddate" placeholder="End Date" value="<?php if (isset($_POST['saveprojectdetails'])){ echo $_POST['projectenddate'];}?>" required>
                    <button name="saveprojectdetails">Save Details</button>
                </form>
            </div>
            <div class="container-1">
			
				<canvas id ="tasks_canvas"  class="drawing_canvas"></canvas>	
				<form id="category_form" class="invisible_form" method="post"> 
					<input type="text" id="taskid" name="taskid" required>
					<input type="text" id="taskcategory" name="taskcategory" required>
					<button id="save_category" name="save_category">Save Category</button>
				</form>
				
                <div class="tasks">
                    <div class="tasks_head">
                        <div class="tasks_title">
                            Tasks
                        </div>
                        <div class="tasks_command">
                            <i class="fa-solid fa-plus"></i>
                        </div>
						<div class="tasks_command tasks_command2", id="task_minus">
                            <i class="fa-solid fa-minus"></i>
                        </div>
						<div class="tasks_command tasks_command2 tasks_command3", id="task_gear">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                    </div>
                    <div id="tasks_list" class="tasks_body">
                        <?php
                        $query = $db->query("SELECT id, task FROM tasks GROUP BY task", PDO::FETCH_ASSOC);
                        
                        if ($query->rowCount()) {
                            foreach ($query as $row) {
                                echo '<span data-id=' . $row['id']. '>' . $row['task'] . '</span>';
                            }
                        }
                        ?>
                    </div>
                    <button name="open_chart" class="open_chart">Gantt Chart Screen</button>
                </div>
				<canvas id="participants_canvas" class="drawing_canvas"></canvas>
				<form id="assign_form" class="invisible_form" method="post"> 
					<input type="text" id="taskid2" name="taskid2" required>
					<input type="text" id="participantid" name="participantid" required>
					<button id="save_assignment" name="save_assignment">Save Assignment</button>
				</form>			
                <div class="participants">
                    <div class="participants_head">
                        <div class="participants_title">
                            Participants
                        </div>
                        <div class="participants_command">
                            <i class="fa-solid fa-plus"></i>
                        </div>
						<div class="participants_command participants_command2", id="participant_minus">
                            <i class="fa-solid fa-minus"></i>
                        </div>
						<div class="participants_command participants_command2 participants_command3", id="participant_gear">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                    </div>
                    <div id="participants_list" class="participants_body">
                        <?php
                        $query = $db->query("SELECT DISTINCT * FROM participants", PDO::FETCH_ASSOC);
                        
                        if ($query->rowCount()) {
                            foreach ($query as $row) {
                                echo '<span data-id=' . $row['id']. '>' . $row['fullname'] . '</span>';
                            }
                        }
                        ?>
						
                    </div>
				
                </div>
				
            </div>
        </div>
       <div class="second_screen content content2" id="content2">
            <div class="container-2">
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
                            $query = $db->query("SELECT * FROM tasks GROUP BY task ORDER BY startdate ASC", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    $id = $row['id'];
                                    $nameTask = $row['task'];
									$taskCategory = $row['category'];
									$dependency =  $row['dependency'];
                                    $startdateTask = substr($row['startdate'], 5, 2);
                                    $enddateTask = substr($row['enddate'], 5, 2);
									
									$background = "#4fc3f7";
									if ($taskCategory == "Urgent"){
										$background = "#ed4747";
									} else if($taskCategory == "Important"){
										$background = "#f2d346";
									} else if($taskCategory == "In Progress"){
										$background = "#b7de5d";
									}

                                    if ($startdateTask == '01') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style=" background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 80px; background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 160px; background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 240px; background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $id. '" name="' . $dependency. '"><span style="margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $id. '" name="' . $dependency. '"><span style="margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 560px; background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 640px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 720px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '02') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 800px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '01') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-right: 880px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '02') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 560px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 640px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 720px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '02') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 80px; margin-right: 800px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '03') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 560px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 640px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '03') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 160px;margin-right: 720px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '04') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 560px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '04') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 240px;margin-right: 640px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '05') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '05') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 320px;margin-right: 560px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '06') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '06') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 400px;margin-right: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '07') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 480px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '08') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '08') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;margin-right: 320px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '07') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 560px;margin-right: 400px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '09') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 640px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 640px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 640px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '09') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 640px;margin-right: 240px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '10') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 720px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 720px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '10') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 720px;margin-right: 160px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '11') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 800px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                        if ($enddateTask == '11') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 800px;margin-right: 80px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                    if ($startdateTask == '12') {
                                        if ($enddateTask == '12') {
                                            echo '<a href="?id=' . $id . '" id="' . $id. '" name="' . $dependency. '"><span style="margin-left: 880px;background-color: '. $background . ';" class="chart_data">' . $nameTask . '</span></a>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <button class="open_second">Tasks Screen</button>
                    </div>
                </div>
                <div class="details">
                    <?php
                    if (isset($_GET['id'])) {
                        $requestId = $_GET['id'];
                    
                        $stmt = $db->prepare("SELECT task, participants, startdate, enddate, category, dependency FROM tasks WHERE id = ?");
                        
                        $stmt->execute([$requestId]); 
                        $row = $stmt->fetch();
						
						$stmt = $db->prepare("SELECT DISTINCT participants FROM tasks WHERE task = (SELECT task FROM tasks WHERE id = ?);");                        
                        $stmt->execute([$requestId]); 
                        $all_participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
						$assignees = [];
						// display the publisher name
						foreach ($all_participants as $participant) {
							array_push($assignees, $participant['participants']);
						}
						//echo '<script>console.log('. $assignees[0] .');</script>';
						$assignees2=[];
						$size =count($assignees) ;
						if (count($assignees) == 0) {
							echo '<script>console.log('. $size.');</script>';
							$assignees2= "-";
						} else {
							foreach($assignees as $assignee) {
								$stmt = $db->prepare("SELECT DISTINCT fullname FROM participants WHERE id = ?");
								$stmt->execute([$assignee]); 
								$rowName = $stmt->fetch();
								if (!empty($rowName['fullname'])) {
									array_push($assignees2, $rowName['fullname']);
								}
							}
							$assignees2= implode(", ",$assignees2);
						}
						
						$stmt = $db->prepare("SELECT DISTINCT dependency FROM tasks WHERE task = (SELECT task FROM tasks WHERE id = ?);");                        
                        $stmt->execute([$requestId]); 
                        $all_dependencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						$dependencies = [];
						// display the publisher name
						foreach ($all_dependencies as $dependency) {
							array_push($dependencies, $dependency['dependency']);
						}
						$dependencies2=[];
						if ($dependencies[0] == NULL) {
							$dependencies2= "-";
						} else {
							foreach($dependencies as $depend) {
								$stmt = $db->prepare("SELECT task FROM tasks WHERE id = ?");
								$stmt->execute([$depend]); 
								$rowName2 = $stmt->fetch();
								array_push($dependencies2, $rowName2['task']);
							}
							$dependencies2= implode(", ",$dependencies2);
						}				
						
						
                        
                        echo '<div class="details_head">Selected Task: ' . $row['task'] . '</div>';
                        ?>
                        <div class="details_body">
                            <span>
                                Assignee: <?php echo $assignees2; ?>
                            </span>
                            <span>
                                Start Date: <?php echo $row['startdate']; ?>
                            </span>
                            <span>
                                End Date: <?php echo $row['enddate']; ?>
                            </span>
                            <span>
                                Category: <?php echo $row['category']; ?>
                            </span>
							<span>
                                Dependency: <?php echo $dependencies2; ?>
                            </span>
                        </div>
                        <?php
                    } else {
                        ?>
						
                        <div class="details_head" style="display:none;">
                        </div>
                        <div class="details_body" style="display:none;">
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="tasks_popup content content_popup" style="overflow-x:auto; and overflow-y:auto">
            <div class="tasks_box">
                <div class="tasks_box_head">
                    <div class="box_title">
                        Add Task
                    </div>
                    <div class="tasks_box_command">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="tasks_box_body">
                    <form action="" method="post">
						<span class="label">Task Name</span>
                        <input type="text" placeholder="Task Name" name="taskname" required>
                        <span class="label">Start Date</span>
                        <input type="date" placeholder="Start Date" name="startdate" required>
                        <span class="label">End Date</span>
                        <input type="date" placeholder="End Date" name="enddate" required>
                        <span class="label">Participations</span>
                        <select class="names_select" name="taskfullnames[]" multiple>
                            <?php
                            $query = $db->query("SELECT DISTINCT * FROM participants", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['fullname'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <span class="label">Task Category</span>
                        <select name="category">
                            <option>Important</option>
							 <option>Urgent</option>
							  <option>In Progress</option>
                            <option selected>Normal</option>
                        </select>
						<span class="label">Dependency</span>
                         <select class="names_select2" name="dependenttaskfullnames">
							<option></option>
                            <?php
                            $query = $db->query("SELECT id, task FROM tasks GROUP BY task", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['task'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <button name="addtask">Add Task</button>
                    </form>
                </div>
            </div>
        </div>
		<div class="tasks_popup3 content content_popup" style="overflow-x:auto; and overflow-y:auto">
            <div class="tasks_box">
                <div class="tasks_box_head">
                    <div class="box_title">
                        Alter Task
                    </div>
                    <div class="tasks_box_command3">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="tasks_box_body">
                    <form action="" method="post">
						<span class="label">Task Name</span>
                        <input type="text" placeholder="Task Name" name="tasknamecheck" required>
						<span class="label">Start Date</span>
						<input type="checkbox" name="startdatecheck" onclick="dynInput1(this);" />
						<p id="startdatecheck"></p>
						<script type="text/javascript">
						 function dynInput1(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("startdatenew");
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("startdatecheck").appendChild(div);
							document.getElementById("startdatenew").style.display = "flex";
						  } else {
							document.getElementById(cbox.name).remove();
							document.getElementById("startdatenew").style.display = "none";
						  }
						}
						</script>
						<input type="date" placeholder="Start Date" name="startdatenew" id="startdatenew" style="display: none;">
						<span class="label">End Date</span>
						<input type="checkbox" name="enddatecheck" onclick="dynInput2(this);" />
						<p id="enddatecheck"></p>
						<script type="text/javascript">
						 function dynInput2(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("enddatenew");
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("enddatecheck").appendChild(div);
							document.getElementById("enddatenew").style.display = "flex";
						  } else {
							document.getElementById(cbox.name).remove();
							document.getElementById("enddatenew").style.display = "none";
						  }
						}
						</script>
						<input type="date" placeholder="End Date" name="enddatenew" id="enddatenew" style="display: none;">
                        <span class="label">Add Participants</span>
						<input type="checkbox" name="participantscheck" onclick="dynInput7(this);" />
						<p id="participantscheckp"></p>
						<script type="text/javascript">
						 function dynInput7(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("altertaskfullnames");							
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("participantscheckp").appendChild(div);
							document.getElementById("altertaskfullnames").style.display = "flex";
						  } else {
							document.getElementById(cbox.name).remove();
							var input = document.getElementById("altertaskfullnames");
							document.getElementById("altertaskfullnames").style.display = "none";							
						  }
						}
						</script>	
						<select class="names_select" name="altertaskfullnames[]" id="altertaskfullnames" style="display: none;" multiple>
                            <?php
                            $query = $db->query("SELECT DISTINCT * FROM participants", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['fullname'] . '</option>';
                                }
                            }
                            ?>
                        </select>
						<span class="label">Remove Participants</span>
						<input type="checkbox" name="participantscheck2" onclick="dynInput8(this);" />
						<p id="participantscheckp2"></p>
						<script type="text/javascript">
						 function dynInput8(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("altertaskfullnames2");							
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("participantscheckp2").appendChild(div);
							document.getElementById("altertaskfullnames2").style.display = "flex";
						  } else {
							document.getElementById(cbox.name).remove();
							var input = document.getElementById("altertaskfullnames2");
							document.getElementById("altertaskfullnames2").style.display = "none";							
						  }
						}
						</script>	
						<select class="names_select" name="altertaskfullnames2[]" id="altertaskfullnames2" style="display: none;" multiple>
                            <?php
                            $query = $db->query("SELECT DISTINCT * FROM participants", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['fullname'] . '</option>';
                                }
                            }
                            ?>
                        </select>						
                        <span class="label">Task Category</span>
						<input type="checkbox" name="taskcategorycheck" onclick="dynInput3(this);" />
						<p id="taskcategorycheck"></p>
						<script type="text/javascript">
						 function dynInput3(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("categorynew");
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("taskcategorycheck").appendChild(div);
							document.getElementById("categorynew").style.display = "flex";							
						  } else {
							document.getElementById(cbox.name).remove();							
							document.getElementById("categorynew").style.display = "none";
						  }
						}
						</script>
						<select name="categorynew" id="categorynew" style="display:none;">
                            <option>Important</option>
							 <option>Urgent</option>
							  <option>In Progress</option>
                            <option selected>Normal</option>
                        </select>
						<span class="label">Dependency</span>
						<input type="checkbox" name="taskdependencycheck" onclick="dynInput6(this);" />
						<p id="dependencycheck"></p>
						<script type="text/javascript">
						 function dynInput6(cbox) {
						  if (cbox.checked) {
							var input = document.getElementById("alterdependenttaskfullnames");								
							var div = document.createElement("div");
							div.id = cbox.name;
							div.appendChild(input);
							document.getElementById("dependencycheck").appendChild(div);
							document.getElementById("alterdependenttaskfullnames").style.display = "flex";
						  } else {
							document.getElementById(cbox.name).remove();
							var input = document.getElementById("alterdependenttaskfullnames");
							document.getElementById("alterdependenttaskfullnames").style.display = "none";							
						  }
						}
						</script>
                         <select class="names_select2" name="alterdependenttaskfullnames" id="alterdependenttaskfullnames" style="display: none;">
							<option></option>
                            <?php
                            $query = $db->query("SELECT id, task FROM tasks GROUP BY task", PDO::FETCH_ASSOC);
                            
                            if ($query->rowCount()) {
                                foreach ($query as $row) {
                                    echo '<option>' . $row['task'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <button name="altertask">Alter Task</button>
                    </form>
                </div>
            </div>
        </div>
		<div class="tasks_popup2 content content_popup" id="tasks_popup2">
            <div class="tasks_box">
                <div class="tasks_box_head">
                    <div class="box_title">
                        Delete Task
                    </div>
                    <div class="tasks_box_command2">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="tasks_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="Task Name" name="taskname2" required>
                        <button name="deletetask">Delete Task</button>
                    </form>
                </div>
            </div>
        </div>
		<div class="participants_popup3 content_popup content">
            <div class="participants_box">
                <div class="participants_box_head">
                    <div class="box_title">
                        Alter Participant
                    </div>
                    <div class="participants_box_command3">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="participants_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="Existing username" name="username">
						<input type="text" placeholder="New username" name="username2">
                        <button name="alterparticipant">Alter Participant</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="participants_popup content_popup content">
            <div class="participants_box">
                <div class="participants_box_head">
                    <div class="box_title">
                        Add Participant
                    </div>
                    <div class="participants_box_command">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="participants_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="Enter a unique username" name="fullname">
                        <button name="addparticipant">Add Participant</button>
                    </form>
                </div>
            </div>
        </div>
		<div class="participants_popup2 content content_popup" id="participants_popup2">
            <div class="participants_box">
                <div class="participants_box_head">
                    <div class="box_title">
                        Delete Participant
                    </div>
                    <div class="participants_box_command2">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="participants_box_body">
                    <form action="" method="post">
                        <input type="text" placeholder="username" name="username">
                        <button name="deleteparticipant">Delete Participant</button>
                    </form>
                </div>
            </div>
        </div>
		</div>
		<div id="load" class="center"></div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"> </script>
		<script src="js/index.js"></script>

		
    </body>
</html>


<?php
// Process for adding participants
if (isset($_POST['addparticipant'])) {
    $fullnameInject = $_POST['fullname'];

    $dataP = [
        'fullname' => $fullnameInject
    ];

	try {			
			$sql = 'SELECT COUNT(1) FROM participants WHERE fullname = :fullname';
			$stmt = $db->prepare($sql);
			$stmt->execute($dataP);
			$row = $stmt->fetch();
			echo '<script>console.log('. $row[0] .');</script>';
			if ($row[0] == 0) {
				$sql = 'INSERT INTO participants (
					fullname
				) VALUES (
					:fullname
				)';
				$stmt = $db->prepare($sql);
				$stmt->execute($dataP);
				header('Refresh:0');
			} else {
				echo '<script>alert("Participant already exists, try again.")</script>';
			}
	}		
	catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

// Process for deleting participants
if (isset($_POST['deleteparticipant'])) {
    $fullnameInject = $_POST['username'];

    $dataP = [
        'fullname' => $fullnameInject
    ];
	
    try {
		
		$sql = 'SELECT COUNT(1) FROM participants WHERE fullname = :fullname';
        $stmt = $db->prepare($sql);
		$stmt->execute($dataP);
        $row = $stmt->fetch();
		echo '<script>console.log('. $row[0] .');</script>';
		if ($row[0] > 0	) {
			$sql = 'DELETE FROM participants WHERE fullname = :fullname';
			$stmt = $db->prepare($sql);
			$stmt->execute($dataP);
			header('Refresh:0');
		} else {
			echo '<script>alert("Participant does not exists, try again.")</script>';
		}
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

//Process for altering a participant
if (isset($_POST['alterparticipant'])) {
    $existingInject = $_POST['username'];
	$newInject = $_POST['username2'];

    $dataP = [
        'existingname' => $existingInject		
    ];
	
	$dataP2 = [
        'existingname' => $existingInject,
		'newname' => $newInject		
    ];
	
	$dataP3 = [
		'newname' => $newInject		
    ];
	
    try {
		
		$sql = 'SELECT COUNT(1) FROM participants WHERE fullname = :existingname';
        $stmt = $db->prepare($sql);
		$stmt->execute($dataP);
        $row = $stmt->fetch();
		echo '<script>console.log('. $row[0] .');</script>';
		if ($row[0] > 0	) {
			$sql = 'SELECT COUNT(1) FROM participants WHERE fullname = :newname';
			$stmt = $db->prepare($sql);
			$stmt->execute($dataP3);
			$row = $stmt->fetch();
			echo '<script>console.log('. $row[0] .');</script>';
			if ($row[0] == 0) {
				$sql = 'UPDATE participants SET fullname = :newname WHERE fullname = :existingname';
				$stmt = $db->prepare($sql);
				$stmt->execute($dataP2);
				header('Refresh:0');
			} else {
				echo '<script>alert("Participant already exists, try again.")</script>';
			}
		} else {
			echo '<script>alert("Participant does not exists, try again.")</script>';
		}			
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}


// Process for add tasks
if (isset($_POST['addtask'])) {
    $tasknameInject = $_POST['taskname'];
    $startdateInject = $_POST['startdate'];
    $enddateInject = $_POST['enddate'];
    $categoryInject = $_POST['category'];
	
	
    $startdateInjectBefore = (int)substr($startdateInject, 5, 2);
    $enddateInjectBefore = (int)substr($enddateInject, 5, 2);

	$startdateInjectBefore2 = (int)substr($startdateInject, 8, 2);
    $enddateInjectBefore2 = (int)substr($enddateInject, 8, 2);
	
	$startdateInjectBefore3 = (int)substr($startdateInject, 0, 4);
    $enddateInjectBefore3 = (int)substr($enddateInject, 0, 4);
    if ($startdateInjectBefore > $enddateInjectBefore) {
        echo 'Error: End date must not be earlier than start date';
    } else {
        if (isset($_POST['taskfullnames'])) {
			$dataFN = [];
            foreach ($_POST['taskfullnames'] as $taskfullnames) {
				$stmt = $db->prepare("SELECT id FROM participants WHERE fullname = '" . $taskfullnames . "'");
				try{
					$stmt->execute();
				}catch(PDOException $e){
					//some logging function
					 echo 'Error: ' . $e->getMessage();
				}
				//loop through each row
				while($result=$stmt->fetch(PDO::FETCH_ASSOC)){
					//select column by key and use
					array_push($dataFN, $result['id']);
				}
            }
			$taskfullnamesInject = $dataFN;
        } else {
			$dataFN = [];
			array_push($dataFN, '-');
            $taskfullnamesInject = $dataFN;         
        }

		if ($_POST['dependenttaskfullnames'] != '') {
			try{
				$stmt = $db->prepare("SELECT id FROM tasks WHERE task = '" . $_POST['dependenttaskfullnames'] . "'");
				$dataFN = [];
				$stmt->execute();
				while($result=$stmt->fetch(PDO::FETCH_ASSOC)){
					//select column by key and use
					array_push($dataFN, $result['id']);
				}
				$dependencyInject = $dataFN[0];
			}catch(PDOException $e){
				echo 'Error: ' . $e->getMessage();
			}
			
		} else {
			$dependencyInject = '';
		}
		
		if($taskfullnamesInject[0] == '-') {
			if($dependencyInject == '') {				
				$dataT = [
					'task' => $tasknameInject,
					'startdate' => $startdateInject,
					'enddate' => $enddateInject,
					'category' => $categoryInject
				];
				try {
					$dataTC = [
						'task' => $tasknameInject
					];
					$sql1 = 'SELECT COUNT(1) FROM tasks WHERE task = :task';
					$stmt = $db->prepare($sql1);
					$stmt->execute($dataTC);
					$row = $stmt->fetch();
					if ($row[0] == 0) {
						$sql = 'INSERT INTO tasks (
							task,
							startdate,
							enddate,
							category
						) VALUES (
							:task,
							:startdate,
							:enddate,
							:category
						)';
						$stmt = $db->prepare($sql);
						$stmt->execute($dataT);
						header('Refresh:0');
					} else {
						echo '<script>alert("Task already exists, try again.")</script>';
					}
				} catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
			} else {
				$startdateInjectBefore = (int)substr($startdateInject, 5, 2);
				$enddateInjectBefore = (int)substr($enddateInject, 5, 2);
				$dataT = [
					'task' => $tasknameInject,
					'startdate' => $startdateInject,
					'enddate' => $enddateInject,
					'category' => $categoryInject,
					'dependency' => $dependencyInject
				];
				try {
					$dataTC = [
						'task' => $tasknameInject
					];
					$sql1 = 'SELECT COUNT(1) FROM tasks WHERE task = :task';
					$stmt = $db->prepare($sql1);
					$stmt->execute($dataTC);
					$row = $stmt->fetch();
					if ($row[0] == 0) {
						$sql = 'INSERT INTO tasks (
							task,
							startdate,
							enddate,
							category,
							dependency
						) VALUES (
							:task,
							:startdate,
							:enddate,
							:category,
							:dependency
						)';
						$stmt = $db->prepare($sql);
						$stmt->execute($dataT);
						header('Refresh:0');
					} else {
						echo '<script>alert("Task already exists, try again.")</script>';
					}
				} catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
				
			}
		} else {
			if($dependencyInject == '') {	
				try {
					$dataTC2 = [
						'task' => $tasknameInject
					];
					$sql1 = 'SELECT COUNT(1) FROM tasks WHERE task = :task';
					$stmt = $db->prepare($sql1);
					$stmt->execute($dataTC2);
					$row = $stmt->fetch();
					if ($row[0] == 0) {
						foreach ($taskfullnamesInject as $taskfullnameInject) {
							$dataT = [
								'task' => $tasknameInject,
								'participants' => $taskfullnameInject,
								'startdate' => $startdateInject,
								'enddate' => $enddateInject,
								'category' => $categoryInject];
							$sql = 'INSERT INTO tasks (
								task,
								participants,
								startdate,
								enddate,
								category
							) VALUES (
								:task,
								:participants,
								:startdate,
								:enddate,
								:category
							)';
							$stmt = $db->prepare($sql);
							$stmt->execute($dataT);
							header('Refresh:0');
						}
					} else {
						echo '<script>alert("Task already exists, try again.")</script>';
					}
				} catch (PDOException $e) {
					echo 'Error: ' . $e->getMessage();
				}	
			} else {

				try {
					$dataTC2 = [
						'task' => $tasknameInject
					];
					$sql1 = 'SELECT COUNT(1) FROM tasks WHERE task = :task';
					$stmt = $db->prepare($sql1);
					$stmt->execute($dataTC2);
					$row = $stmt->fetch();
					if ($row[0] == 0) {
						foreach ($taskfullnamesInject as $taskfullnameInject) {
							$dataT = [
								'task' => $tasknameInject,
								'participants' => $taskfullnameInject,
								'startdate' => $startdateInject,
								'enddate' => $enddateInject,
								'category' => $categoryInject,
								'dependency' => $dependencyInject
								];
							$sql = 'INSERT INTO tasks (
								task,
								participants,
								startdate,
								enddate,
								category,
								dependency
							) VALUES (
								:task,
								:participants,
								:startdate,
								:enddate,
								:category,
								:dependency
							)';
							$stmt = $db->prepare($sql);
							$stmt->execute($dataT);
							header('Refresh:0');
						}
					} else {
						echo '<script>alert("Task already exists, try again.")</script>';
					}
				} catch (PDOException $e) {
					echo 'Error: ' . $e->getMessage();
				}
			}				
		}
    }
}

//Process for altering task
if (isset($_POST['altertask'])) {
	$tasknameInject = $_POST['tasknamecheck'];
	
	$dataP = ['taskname' => $tasknameInject];
	$sql = 'SELECT COUNT(1) FROM tasks WHERE task = :taskname';
	$stmt = $db->prepare($sql);
	$stmt->execute($dataP);
	$row = $stmt->fetch();
	if ($row[0] > 0	) {
			if(isset($_POST['startdatecheck'])) {
				$startdateInject = $_POST['startdatenew'];
				$dataP = [
					'taskname' => $tasknameInject,
					'startdate' => $startdateInject
				];
				try {
					$sql = 'UPDATE tasks SET startdate = :startdate WHERE task = :taskname ';
					$stmt = $db->prepare($sql);
					$stmt->execute($dataP);
				}  catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
				}
			}
			if(isset($_POST['enddatecheck'])) {
				  $enddateInject = $_POST['enddatenew'];
				  $dataP = [
					'taskname' => $tasknameInject,
					'enddate' => $enddateInject
				];
				try {
					$sql = 'UPDATE tasks SET enddate = :enddate WHERE task = :taskname ';
					$stmt = $db->prepare($sql);
					$stmt->execute($dataP);
				}  catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
				}
			}
			if(isset($_POST['participantscheck'])) {
				  $participantInject = $_POST['altertaskfullnames'];
				try {
					foreach($participantInject as $participantInject) {
						$dataPP = [
						'fullname' => $participantInject
						];
						$sql = 'SELECT COUNT(*) FROM tasks WHERE participants=(SELECT id FROM participants WHERE fullname = :fullname)';
						$stmt = $db->prepare($sql);
						$stmt->execute($dataPP);
						$row = $stmt->fetch();
						if (count($row) != 0) {
							$dataP = [
								'taskname' => $tasknameInject,
								'participants' => $participantInject
							];						
							
							$sql = 'INSERT INTO tasks (task, startdate, participants, enddate, category, dependency)
								SELECT task, startdate, (SELECT id FROM participants WHERE fullname = :participants), enddate, category, dependency
								  FROM tasks
								 WHERE id = (SELECT id FROM tasks WHERE task = :taskname LIMIT 1)';
							$stmt = $db->prepare($sql);
							$stmt->execute($dataP);
						}
					}
				}  catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
				}
				  
			}
			if(isset($_POST['participantscheck2'])) {
				  $participantInject = $_POST['altertaskfullnames2'];
				try {
					foreach($participantInject as $participantInject) {
						$dataPP = [
						'fullname' => $participantInject
						];
						$sql = 'SELECT COUNT(*) FROM tasks WHERE participants=(SELECT id FROM participants WHERE fullname = :fullname)';
						$stmt = $db->prepare($sql);
						$stmt->execute($dataPP);
						$row = $stmt->fetch();
						if (count($row) == 0) {
							$dataP = [
								'participants' => $participantInject
							];						
							
							$sql = 'DELETE FROM tasks WHERE participants = (SELECT id FROM participants WHERE fullname = :participants)';
							$stmt = $db->prepare($sql);
							$stmt->execute($dataP);
								}
					}
				}catch (PDOException $e) {
					echo 'Error: ' . $e->getMessage();
				}
			}
			if(isset($_POST['taskcategorycheck'])) {
				  $categoryInject = $_POST['categorynew'];
				  $dataP = [
					'taskname' => $tasknameInject,
					'category' => $categoryInject
				];
				try {
					$sql = 'UPDATE tasks SET category = :category WHERE task = :taskname ';
					$stmt = $db->prepare($sql);
					$stmt->execute($dataP);
				}  catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
				}
			}	
			if(isset($_POST['taskdependencycheck'])) {
				  $dependencyInject = $_POST['alterdependenttaskfullnames'];
				  	$dataP = [
				  'taskname' => $tasknameInject,
					'dependency' => $dependencyInject
				];
				try {
					$sql = 'UPDATE
						tasks t1 INNER JOIN tasks t2
						ON t1.task = :taskname AND t2.task = :dependency
					SET t1.dependency = t2.id ';
					$stmt = $db->prepare($sql);
					$stmt->execute($dataP);
				}  catch (PDOException $e) {
						echo 'Error: ' . $e->getMessage();
				}
			}
	
	} else {
		echo '<script>alert("Task does not exists, try again.")</script>';
	}
	
}

// Process for deleting tasks
if (isset($_POST['deletetask'])) {
    $taskInject = $_POST['taskname2'];

    $dataP = [
        'taskname' => $taskInject
    ];

    try {
		
		$sql = 'SELECT COUNT(1) FROM tasks  WHERE task = :taskname';
        $stmt = $db->prepare($sql);
		$stmt->execute($dataP);
        $row = $stmt->fetch();
		if ($row[0] > 0	) {
			$sql = 'DELETE FROM tasks WHERE task = :taskname';
        $stmt = $db->prepare($sql);
        $stmt->execute($dataP);
        header('Refresh:0');
		} else {
			echo '<script>alert("Task does not exists, try again.")</script>';
		}
    } catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}	
}

//Process for changing tasks according to drawings
if (isset($_POST['save_category'])) {
	
    $taskid = $_POST['taskid'];
    $taskcategory = $_POST['taskcategory'];
	try {
		$dataTT = [
		'taskid' => $taskid
		];
		$sql = 'SELECT COUNT(*) FROM tasks WHERE task = (SELECT task FROM tasks WHERE id = :taskid)';
		$stmt = $db->prepare($sql);
		$stmt->execute($dataTT);
		$row = $stmt->fetch();
		if ($row[0] > 0) {
			try{
				$dataT = [
				'taskid' => $taskid,
				'category' => $taskcategory
				];
				$sql = 'UPDATE tasks t1 INNER JOIN tasks t2 on t1.task = t2.task INNER JOIN tasks t3 ON t3.id=t1.id
					SET t1.category=:category,  t2.category=:category,  t3.category=:category
					WHERE t3.id = :taskid';
				$stmt = $db->prepare($sql);
				$stmt->execute($dataT);
				echo "<script>console.log('here');</script>";	
			}  catch (PDOException $e) {
					echo 'Error: ' . $e->getMessage();
			}
		echo "<script>console.log('here');</script>";	
	}  
	}catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
}

//Process for assigning tasks according to drawings
if (isset($_POST['save_assignment'])) {
	
    $taskid2 = $_POST['taskid2'];
    $participantid = $_POST['participantid'];
	
	$dataT = [
            'taskid2' => $taskid2,
            'participantid' => $participantid
        ];

    try {
		$sql = 'SELECT COUNT(1) FROM tasks  WHERE task = (SELECT task FROM tasks WHERE id = :taskid2) AND participants = :participantid';
        $stmt = $db->prepare($sql);
		$stmt->execute($dataT);
		$row = $stmt->fetch();
		if ($row[0] == 0) {
			try {
			$sql2 = 'INSERT INTO tasks (task, startdate, participants, enddate, category, dependency)
			SELECT task, startdate, :participantid, enddate, category, dependency
			FROM tasks
			WHERE task = (SELECT task from tasks WHERE id = :taskid2) and id = :taskid2';
			$stmt = $db->prepare($sql2);
			$stmt->execute($dataT);
			echo "<script>console.log('here2');</script>";	
			}catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
		}
	}catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}
}
?>