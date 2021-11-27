function setTimer(leftMinutes, leftSeconds)
{
    if (leftSeconds < 10) leftSeconds = '0' + leftSeconds;
    $('.timer_mins').text(leftMinutes);
    $('.timer_secs').text(leftSeconds);
}

$(document).ready(function () {

    $('.point_select').on('change', function() {
        $.post('/get_scooters', { _token: $('body').attr('data-token'), point_id : $(this).val() }, function(data) {
            let scooters = JSON.parse(data);
            let count = scooters.length;
            $('.scooter_select').html('');

            if (count) {
                for (let i = 0; i < count; i++) {
                    let scooter = scooters[i];
                    $('.scooter_select').append($('<option>', {
                        value: scooter['id'],
                        text: scooter['num']
                    }));
                }
            }
        });
    });

    var future_date = new Date(Date.parse($('.timer_cd').text()));
    future_date.setMinutes(future_date.getMinutes() + 15);
    var current_date = new Date($.now());
    var left = new Date(future_date - current_date);
    var leftMinutes = left.getMinutes();
    var leftSeconds = left.getSeconds();

    setTimer(leftMinutes, leftSeconds);

    var interval = setInterval(function () {
        if (!leftMinutes && !leftSeconds) {
            $('.booked_text').addClass('hidden');
            $('.status_st').text('Просрочен');
            clearInterval(interval);
        }
        if (!leftSeconds) {
            leftSeconds = 60;
            leftMinutes--;
        }
        leftSeconds--;
        setTimer(leftMinutes, leftSeconds);
    }, 1000);

});
