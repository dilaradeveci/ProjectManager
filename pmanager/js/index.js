class SketchPad {

    constructor($canvas){
        this.canvas = $canvas[0];

        const canvasWidth = $canvas.width();
        const canvasHeight = $canvas.height();
        $canvas.attr("width", canvasWidth);
        $canvas.attr("height", canvasHeight);

        this.context = this.canvas.getContext("2d");

        this.context.lineWidth = 1;
        this.context.strokeStyle = "#000000";
        this.context.lineCap = 'round';

        this.mouseDown = false;

        this.canvas.onmousedown = (e) => {
            this.mouseDown = true;
            this.context.beginPath();            
            this.context.moveTo(e.offsetX, e.offsetY);
        };

        this.canvas.onmousemove = (e) => {
            if(!this.mouseDown){
                return;
            }
            this.context.lineTo(e.offsetX, e.offsetY);
            this.context.stroke();
        };

        this.canvas.onmouseup = (e) => {
			if (!this.mouseDown) {
				return
			}
            this.mouseDown = false;
            this.onShape();
            this.clear();
        };

        this.canvas.onmouseleave = (e) => {
			if (!this.mouseDown) {
				return
			}
            this.mouseDown = false;
            this.onShape();
            this.clear();
        };

        this.clear();
    }

    trimTransparentPixels(padding){
        const imageData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height).data;

        let minX = this.canvas.width;
        let minY = this.canvas.height;
        let maxX = 0;
        let maxY = 0;

        for(let i = 0; i < imageData.length; i += 4){
            // ignore transparent pixels
            if(imageData[i + 3] === 0){
                continue;
            }

            const currX = (i / 4) % this.canvas.width;
            const currY = Math.floor((i / 4) / this.canvas.width);

            if(currX < minX){
                minX = currX;
            }

            if(currY < minY){
                minY = currY;
            }

            if(currX > maxX){
                maxX = currX;
            }

            if(currY > maxY){
                maxY = currY;
            }
        }
		
		

        let trimmedHeight = 1 + maxY - minY;
        let trimmedWidth = 1 + maxX - minX;
		let trimmedSize = Math.max(trimmedHeight,trimmedWidth);

        if(trimmedWidth <= 0 || trimmedHeight <= 0){
            return null;
        }

        padding = padding || 0;
        trimmedWidth += 2 * padding;
        trimmedHeight += 2 * padding;
        minX -= padding;
        minY -= padding;

        const trimmedCanvas = $("<canvas/>")[0];
        trimmedCanvas.width = trimmedSize;
        trimmedCanvas.height = trimmedSize;

        var trimmedContext = trimmedCanvas.getContext("2d");
		trimmedContext.fillStyle = "white";
		trimmedContext.fillRect(0,0, trimmedSize, trimmedSize);
		
		trimmedContext.filter = "blur(1px)";		
        trimmedContext.drawImage(this.canvas, (minX+trimmedWidth/2)-trimmedSize/2, (minY+trimmedHeight/2)-trimmedSize/2, trimmedSize, trimmedSize, 0, 0, trimmedSize, trimmedSize);
		var imgData = trimmedContext.getImageData(0, 0, trimmedSize, trimmedSize);
        var data = imgData.data;

        for(var i = 0; i < data.length; i += 4) {
          // red
          data[i] = 255 - data[i];
          // green
          data[i + 1] = 255 - data[i + 1];
          // blue
          data[i + 2] = 255 - data[i + 2];
        }

        // overwrite original image
        trimmedContext.putImageData(imgData, 0, 0);
      
      
		//$("body").append(trimmedCanvas);
		
        return {
            left: minX,
            top: minY,
            width: trimmedWidth,
            height: trimmedHeight,
            padding,
            shape: trimmedCanvas,
			size: trimmedSize
        };
    }
	
	
	determineDirection(){
		const trimmed = this.trimTransparentPixels(4);
		const quadrant1Canvas = trimmed.shape;
		const quadrant2Canvas = trimmed.shape;
		const quadrant3Canvas = trimmed.shape;
		const quadrant4Canvas = trimmed.shape;
	
		quadrant1Canvas.width = trimmed.width/2;
        quadrant1Canvas.height = trimmed.height/2;
				
		const forwardArrowCanvas = $("<canvas/>")[0];
		forwardArrowCanvas.width = trimmed.width/2;
        forwardArrowCanvas.height = trimmed.height/2;
		
		var forwardArrowContext = forwardArrowCanvas.getContext("2d");
		
		const backwardArrowCanvas = $("<canvas/>")[0];
		backwardArrowCanvas.width = trimmed.width/2;
        backwardArrowCanvas.height = trimmed.height/2;
		
		var backwardArrowContext = backwardArrowCanvas.getContext("2d");		
		
		var quadrant1Context = quadrant1Canvas.getContext("2d");
		backwardArrowContext.drawImage(this.canvas, trimmed.left, trimmed.top, quadrant1Canvas.width, quadrant1Canvas.height, 0, 0, quadrant1Canvas.width, quadrant1Canvas.height);
		
		var quadrant2Context = quadrant2Canvas.getContext("2d");
		forwardArrowContext.drawImage(this.canvas, trimmed.left+quadrant1Canvas.width, trimmed.top, quadrant1Canvas.width, quadrant1Canvas.height, 0, 0, quadrant1Canvas.width, quadrant1Canvas.height);
		
		var quadrant3Context = quadrant3Canvas.getContext("2d");
		forwardArrowContext.drawImage(this.canvas, trimmed.left, trimmed.top+quadrant1Canvas.height, quadrant1Canvas.width, quadrant1Canvas.height, 0, 0, quadrant1Canvas.width, quadrant1Canvas.height);
		
		var quadrant4Context = quadrant4Canvas.getContext("2d");
		backwardArrowContext.drawImage(this.canvas, trimmed.left+quadrant1Canvas.width, trimmed.top+quadrant1Canvas.height, quadrant1Canvas.width, quadrant1Canvas.height, 0, 0, quadrant1Canvas.width, quadrant1Canvas.height);
		
		return {
			forward: forwardArrowCanvas,
			backward: backwardArrowCanvas			
		};
		
	}

    onShape(){
        const shapeData = this.trimTransparentPixels(4);
		if (!shapeData) {
			return
		}
        this.onshape?.(shapeData);
		
    }
	
	preprocess(imgData) {	
		//convert to a tensor 
		let tensor = tf.browser.fromPixels(imgData, 1)
		
		//resize 
		const resized = tf.image.resizeBilinear(tensor, [28, 28]).toFloat()
		
		//normalize 
		const offset = tf.scalar(255.0);
		const normalized = resized.div(offset);
		//We add a dimension to get a batch shape 
		const batched = normalized.expandDims(0)
		return batched
		
	}
	
	
    clear(){
      this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    };
}
/*	
class Arrow {
	constructor(from_task, to_task) {
		this.from_task = from_task;
		this.to_task = to_task;

		this.calculate_path();
		this.draw();
	}

	calculate_path() {
		let start_x =
			this.from_task.offsetLeft + this.from_task.offsetWidth / 2;

		const condition = () =>
			this.to_task.offsetLeft < start_x + 10 &&
			start_x > this.from_task.offsetLeft + 10;

		while (condition()) {
			start_x -= 10;
		}

		const start_y =
			50 +
			22 +
			(10 + 22) *
				this.from_task.task._index +
			10;

		const end_x = this.to_task.offsetLeft - 10 / 2;
		const end_y =
			50 +
			22 / 2 +
			(10 + 22) *
				this.to_task.task._index +
			10;

		const from_is_below_to =
			this.from_task.task._index > this.to_task.task._index;
		const curve = 5;
		const clockwise = from_is_below_to ? 1 : 0;
		const curve_y = from_is_below_to ? -curve : curve;
		const offset = from_is_below_to
			? end_y + 5
			: end_y - 5;

		this.path = `
		M ${start_x} ${start_y}
		V ${offset}
		a ${curve} ${curve} 0 0 ${clockwise} ${curve} ${curve_y}
		L ${end_x} ${end_y}
		m -5 -5
		l 5 5
		l -5 5`;

		if (
			this.to_task.offsetLeft <
			this.from_task.offsetLeft + 10
		) {
			const down_1 = 10 / 2 - curve;
			const down_2 =
				this.to_task.offsetTop +
				this.to_task.getHeight() / 2 -
				curve_y;
			const left = this.to_task.offsetLeft - 10;

			this.path = `
			M ${start_x} ${start_y}
			v ${down_1}
			a ${curve} ${curve} 0 0 1 -${curve} ${curve}
			H ${left}
			a ${curve} ${curve} 0 0 ${clockwise} -${curve} ${curve_y}
			V ${down_2}
			a ${curve} ${curve} 0 0 ${clockwise} ${curve} ${curve_y}
			L ${end_x} ${end_y}
			m -5 -5
			l 5 5
			l -5 5`;
		}
	}

	draw() {
		this.element = createSVG('path', {
			d: this.path,
			'data-from': this.from_task.task.id,
			'data-to': this.to_task.task.id,
		});
	}

	update() {
		this.calculate_path();
		this.element.setAttribute('d', this.path);
	}
}
	*/
$( async () => {
	$('.participants_popup').hide();
	$('.participants_popup2').hide();
	$('.participants_popup3').hide();
	$('.tasks_popup').hide();
	$('.tasks_popup2').hide();
	$('.tasks_popup3').hide();
	$('.second_screen').hide();
		
	document.addEventListener('readystatechange', e => {
	  if (document.readyState !== "complete") {
			first_on =$('.first_screen').is(":visible");
			document.getElementsByClassName("content").forEach(e => e.style.display = "none");
			$("#load")[0].style.display = "flex";
		} else {
			$("#load")[0].style.display = "none";
			document.getElementsByClassName("content").forEach(e => e.style.display = "block");
			document.getElementsByClassName("content1").forEach(e => e.style.display = "grid");
			document.getElementsByClassName("content2").forEach(e => e.style.display = "grid");
			document.getElementsByClassName("content_popup").forEach(e => e.style.display = "flex");
			$('.participants_popup').hide();
			$('.participants_popup2').hide();
			$('.participants_popup3').hide();
			$('.tasks_popup').hide();
			$('.tasks_popup2').hide();
			$('.tasks_popup3').hide();
			$('.second_screen').hide();
			
		}
	});

	
	var addParticipantButton = document.querySelector('.participants_command');
	var closeParticipantsPopup = document.querySelector('.participants_box_command');
	
	var alterParticipantButton = document.querySelector('.participants_command3');
	var closeParticipantsPopup3 = document.querySelector('.participants_box_command3');
	
	var deleteParticipantButton = document.querySelector('.participants_command2');
	var closeParticipantsPopup2 = document.querySelector('.participants_box_command2');
	
	var addTaskButton = document.querySelector('.tasks_command');
	var closeTasksPopup = document.querySelector('.tasks_box_command');
	
	var alterTaskButton = document.querySelector('.tasks_command3');
	var closeTasksPopup3 = document.querySelector('.tasks_box_command3');
	
	var deleteTaskButton = document.querySelector('.tasks_command2');
	var closeTasksPopup2 = document.querySelector('.tasks_box_command2');
	
	
	var taskFullNames = document.querySelector('.tasks_box_body select option');
	
	var formAddTaskButton = document.querySelector('.tasks_box_body button');
	var openChartButton = document.querySelector('.open_chart');
	var openSecondScreenButton = document.querySelector('.open_second');
	

	addParticipantButton.addEventListener('click', () => {
		$('.participants_popup').fadeIn('fast');
	});
	closeParticipantsPopup.addEventListener('click', () => {
		$('.participants_popup').fadeOut('fast');
	});
	alterParticipantButton.addEventListener('click', () => {
		$('.participants_popup3').fadeIn('fast');
	});
	closeParticipantsPopup3.addEventListener('click', () => {
		$('.participants_popup3').fadeOut('fast');
	});
	deleteParticipantButton.addEventListener('click', () => {
		$('.participants_popup2').fadeIn('fast');
	});
	closeParticipantsPopup2.addEventListener('click', () => {
		$('.participants_popup2').fadeOut('fast');
	});
	
	addTaskButton.addEventListener('click', () => {
		$('.tasks_popup').fadeIn('fast');
	});
	closeTasksPopup.addEventListener('click', () => {
		$('.tasks_popup').fadeOut('fast');
	});
	alterTaskButton.addEventListener('click', () => {
		$('.tasks_popup3').fadeIn('fast');
	});
	closeTasksPopup3.addEventListener('click', () => {
		$('.tasks_popup3').fadeOut('fast');
	});
	deleteTaskButton.addEventListener('click', () => {
		$('.tasks_popup2').fadeIn('fast');
	});
	closeTasksPopup2.addEventListener('click', () => {
		$('.tasks_popup2').fadeOut('fast');
	});
	
	openChartButton.addEventListener('click', () => {
		$('.first_screen').fadeOut('fast');
		setTimeout(() => {
			$('.second_screen').fadeIn('fast');
		}, 200);
	});
	openSecondScreenButton.addEventListener('click', () => {
		window.location.replace('index.php');
		
	});
	setInterval(() => {
		if (window.location.href.indexOf('id') > -1) {
			$('.first_screen').hide();
			$('.second_screen').show();
		}
	})


	if (!taskFullNames) {
		formAddTaskButton.style.pointerEvents = 'none';
		formAddTaskButton.style.opacity = 0.5;
	}
	//load the model
	model = await tf.loadLayersModel('model.json')
	//possible_classes
	const classes = ["star","triangle","zigzag"];

	
	// fix canvas heights
    const titleHeight = $(".tasks_head").height();
    $(".drawing_canvas").css({"margin-top": `${titleHeight}px`});

    const getListItemAtPosition = (list, selector, y) => {
        const parentOffset = list.offset();
        let positions = [];
        list.find(selector).each((index, item) => {
            item = $(item);
            positions.push(item.offset().top - parentOffset.top + (item.height() / 2));
        });
        positions = positions.map(pos => Math.abs(pos - y));
        const minPos = Math.min(...positions);
        const minIndex = positions.indexOf(minPos);
        return $(list.find("span")[minIndex]);
    }
	
	
	
	
    const taskCanvas = new SketchPad($("#tasks_canvas"));
    taskCanvas.onshape = (data) => {
        const task = getListItemAtPosition($("#tasks_list"), "span", data.top + data.height / 2);
        data.taskId = task.attr("data-id");
			
		const imgData = taskCanvas.preprocess(data.shape)
		const pred_array = model.predict(imgData).dataSync()
		
		let pred = 0;
		index = 0;
		for (let i = 0; i < pred_array.length; i++) {
			if (pred_array[i] > pred) {
				pred = pred_array[i];
				index = i
			}
		}
		const pred_class = classes[index]
		
		let category = "";
		switch(pred_class) {
		  case "star":
			category = "Important";
			break;
		  case "triangle":
			category = "In Progress";			
			break;
		  case "zigzag":
			category = "Urgent";
			break;
		}
		
		$("#taskid").val(data.taskId);
		$("#taskcategory").val(category);
		$("#save_category").click();
		
    };
	

    const participantCanvas = new SketchPad($("#participants_canvas"));
    participantCanvas.onshape = (data) => {
		
		let direction = participantCanvas.determineDirection(data.shape);
		
		let context = direction.backward.getContext('2d');
		const backwardPixels = context.getImageData(0, 0, direction.backward.width, direction.backward.height).data;
		
		let context2 = direction.forward.getContext('2d');
		const forwardPixels = context2.getImageData(0, 0, direction.forward.width, direction.forward.height).data;
		
		let pix_count_backward = 0;
		let pix_count_forward = 0;
		
		for(let i = 0; i < backwardPixels.length; i += 4){
            if (backwardPixels[i+3]) //a pixel
			{
			   pix_count_backward++;
			}
		}
		
		for(let i = 0; i < forwardPixels.length; i += 4){
            // ignore transparent pixels
            if (forwardPixels[i+3]) //a pixel
			{
			   pix_count_forward++;
			}
		}			
		
		let forwardArrow = true;	
		if(pix_count_forward > pix_count_backward) {
			forwardArrow = true;			
		} else if(pix_count_forward < pix_count_backward) {
			forwardArrow = false; 
		} else {
			forwardArrow = true;
		}	
		
        let task, participant;
        if(forwardArrow){
            task = getListItemAtPosition($("#tasks_list"), "span", data.top + data.height - data.padding);
            participant = getListItemAtPosition($("#participants_list"), "span", data.top + data.padding);
        } else {
            task = getListItemAtPosition($("#tasks_list"), "span", data.top + data.padding);
            participant = getListItemAtPosition($("#participants_list"), "span", data.top + data.height - data.padding);
        }
		
        data.taskId = task.attr("data-id");
        data.participantId = participant.attr("data-id");
		  
		$("#taskid2").val(data.taskId);
		$("#participantid").val(data.participantId);
		$("#save_assignment").click();
		
    };
	/*
	let arrows = [];
	let chart_data = document.getElementsByClassName("chart_data");
	for(let i=0; i < chart_data.length; i++) {
			var id = document.querySelector('a').href;
			const arrow = new Arrow(
				chart_data[i].id,chart_data[i].name
			);
			arrows = arrows.concat(arrows);
	}
		*/        
});