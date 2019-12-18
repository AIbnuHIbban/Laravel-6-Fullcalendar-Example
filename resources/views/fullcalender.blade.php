<!DOCTYPE html>
<html>
<head>
    <title>Laravel Fullcalender</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script>
    $(document).ready(function () {

        var SITEURL = "{{url('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: SITEURL + "fullcalendar",
            displayEventTime: true,
            editable: true,
            eventRender: function (event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                var title = prompt('Event Title:');

                if (title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                    $.ajax({
                        url: "{{ url('fullcalendar/create') }}",
                        data: {
                            title : title,
                            start : start,
                            end   : end,
                        },
                        type: "POST",
                        success: function (data) {
                            displayMessage("Added Successfully");
                        },
                        error: function (jqXHR, exception){
                            alert(jqXHR.responseText);
                        }
                    });
                    calendar.fullCalendar('renderEvent',
                    {
                        title: title,
                        start: start,
                        end: end,
                        allDay: allDay
                    },true);
                }
                calendar.fullCalendar('unselect');
            },

            eventDrop: function (event, delta) {
                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                        $.ajax({
                            url: SITEURL + 'fullcalendar/update',
                            data: 'title=' + event.title + '&amp;start=' + start + '&amp;end=' + end + '&amp;id=' + event.id,
                            type: "POST",
                            success: function (response) {
                                displayMessage("Updated Successfully");
                            }
                        });
                    },
            eventClick: function (event) {
                var deleteMsg = confirm("Do you really want to delete?");
                if (deleteMsg) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + 'fullcalendar/delete',
                        data: "&amp;id=" + event.id,
                        success: function (response) {
                            if(parseInt(response) > 0) {
                                $('#calendar').fullCalendar('removeEvents', event.id);
                                displayMessage("Deleted Successfully");
                            }
                        }
                    });
                }
            }

        });
    });

    function displayMessage(message) {
        $(".response").html("<div class='success'>"+message+"</div>");
        setInterval(function() { $(".success").fadeOut(); }, 1000);
    }
</script>
<body>

    <div class="container">
        <div class="response"></div>
        <div id='calendar'></div>  
    </div>
</body>
</html>