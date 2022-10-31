var deadline = moment.tz("2018-06-18 10:00", "Europe/Zagreb");


$('#countdown').countdown(deadline.toDate(), function(event) {
    $(this).html(event.strftime('<div class="Countdown-holder"> <span class="Countdown-number">%D</span> <div class="Countdown-info">days</div> </div><div class="Countdown-holder"> <span class="Countdown-number">%H</span> <div class="Countdown-info">hours</div> </div><div class="Countdown-holder"> <span class="Countdown-number">%M</span> <div class="Countdown-info">minutes</div> </div><div class="Countdown-holder"> <span class="Countdown-number">%S</span> <div class="Countdown-info">seconds</div> </div>'));
});



// var sun = document.getElementById('sun');
// TweenMax.to(sun, 40, {y:"100%",ease:Power2.easeOut});