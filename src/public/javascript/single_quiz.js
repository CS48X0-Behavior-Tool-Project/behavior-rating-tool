countDownDate = 0;
if (countDownDate) {
    countDownDate = new Date(countDownDate);
} else {
    countDownDate = new Date();
    localStorage.setItem('startDate', countDownDate);
}
// Update the count down every 1 second
var x = setInterval(function() {
    // Get todays date and time
    var now = new Date().getTime();
    // Find the distance between now an the count down date
    var distance = now - countDownDate.getTime();
    // Time calculations for days, hours, minutes and seconds
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML =  hours.toLocaleString('en-US', {minimumIntegerDigits: 2}) + ":" + minutes.toLocaleString('en-US', {minimumIntegerDigits: 2}) + ":" + seconds.toLocaleString('en-US', {minimumIntegerDigits: 2});
}, 1000);
