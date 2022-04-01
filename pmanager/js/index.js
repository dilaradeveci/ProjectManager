$('.participants_popup').hide();
$('.tasks_popup').hide();
$('.second_screen').hide();


var addParticipantButton = document.querySelector('.participants_command');
var closeParticipantsPopup = document.querySelector('.participants_box_command');
var addTaskButton = document.querySelector('.tasks_command');
var closeTasksPopup = document.querySelector('.tasks_box_command');
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
addTaskButton.addEventListener('click', () => {
    $('.tasks_popup').fadeIn('fast');
});
closeTasksPopup.addEventListener('click', () => {
    $('.tasks_popup').fadeOut('fast');
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


