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
                    </div>
                    <div id="tasks_list" class="tasks_body">
                        <?php
                        $query = $db->query("SELECT * FROM tasks", PDO::FETCH_ASSOC);
                        
                        if ($query->rowCount()) {
                            foreach ($query as $row) {
                                echo '<span data-id=' . $row['id']. '>' . $row['task'] . '</span>';
                            }
                        }
                        ?>
                    </div>
                    <button class="open_chart">Gantt Chart Screen</button>
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
                    </div>
                    <div id="participants_list" class="participants_body">
                        <?php
                        $query = $db->query("SELECT * FROM participants", PDO::FETCH_ASSOC);
                        
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
                        <button class="open_second">Tasks Screen</button>
                    </div>
                </div>
                <div class="details">
                    <?php
                    if (isset($_GET['id'])) {
                        $requestId = $_GET['id'];
                    
                        $stmt = $db->prepare("SELECT task, participants, startdate, enddate, category FROM tasks WHERE id = ?");
                        
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
                                Category: <?php echo $row['category']; ?>
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
        <div class="tasks_popup content content_popup">
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
                        <span class="label">Task Category</span>
                        <select name="category">
                            <option>Important</option>
							 <option>Urgent</option>
							  <option>In Progress</option>
                            <option selected>Normal</option>
                        </select>
                        <button name="addtask">Add Task</button>
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
                        <input type="text" placeholder="Full Name" name="fullname">
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
                        <input type="text" placeholder="Full Name" name="fullname">
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

// Process for deleting participants
if (isset($_POST['deleteparticipant'])) {
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

// Process for add tasks
if (isset($_POST['addtask'])) {
    $tasknameInject = $_POST['taskname'];
    $startdateInject = $_POST['startdate'];
    $enddateInject = $_POST['enddate'];
    $categoryInject = $_POST['category'];

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
            'category' => $categoryInject
        ];
    
        try {
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
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
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
			echo '<script>alert("Participant does not exists, try again.")</script>';
		}
    } catch (PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}	
}

//Process for changing tasks according to drawings
if (isset($_POST['save_category'])) {
	
    $taskid = $_POST['taskid'];
    $taskcategory = $_POST['taskcategory'];
	
	$dataT = [
            'taskid' => $taskid,
            'category' => $taskcategory
        ];
	
	try {
		$sql = 'UPDATE tasks SET category = :category WHERE id = :taskid ';
		$stmt = $db->prepare($sql);
		
		$stmt->execute($dataT);
		echo "<script>console.log('here');</script>";
		
	}  catch (PDOException $e) {
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
		$sql = 'UPDATE tasks SET participants = (SELECT fullname FROM participants WHERE id = :participantid) WHERE id = :taskid2 ';
		$stmt = $db->prepare($sql);
		
		$stmt->execute($dataT);
		echo "<script>console.log('here2');</script>";		
	}catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
}
?>