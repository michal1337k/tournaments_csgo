//var countDownDate = new Date("Jan 5, 2024 15:37:25").getTime();

var Ayear = document.getElementById('year').value;
var Amonth = document.getElementById('month').value;
var Aday = document.getElementById('day').value;
var Ahour = document.getElementById('hour').value;
var Aminute = document.getElementById('minute').value;
var Asecond = document.getElementById('second').value;
var countDownDate = new Date(+Ayear, +Amonth - 1, +Aday, +Ahour, +Aminute, +Asecond);

var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  if(days > 0)
  {
    document.getElementById("demo").innerHTML = "Start za <b class='bbb'>" + days + "</b> dni <b class='bbb'>" + hours + "</b>h <b class='bbb'>"
    + minutes + "</b>m <b class='bbb'>" + seconds + "</b>s ";
  }
  else {
    document.getElementById("demo").innerHTML = "Start za <b class='bbb'>" + hours + "</b>h <b class='bbb'>"
    + minutes + "</b>m <b class='bbb'>" + seconds + "</b>s ";
  }
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "<b class='bbb'>Turniej wystartował!</b>";
  }
}, 1000);

