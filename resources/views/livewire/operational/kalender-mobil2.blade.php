<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-50">
                            <h5 class="mb-0">List Mobil</h5>
                        </div>
                        <div class="col-50">
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-3">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @php
        dd($data);
    @endphp --}}
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Default view set to timeGridWeek
        timeZone: 'local',            // Set timezone to local
        editable: true,               // Allow events to be edited
        selectable: true,             // Allow dates to be selected
        nowIndicator: true,           // Show a line indicating the current time
        headerToolbar: {              // Customize the toolbar
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [                     // Define custom events
            {
                title: 'Meeting',
                start: new Date(),     // Start time set to now
                description: 'Discuss project progress', // Custom description
                color: 'blue'          // Custom color for the event
            },
            {
                title: 'Conference',
                start: new Date().setDate(new Date().getDate() + 1), // Start time set to tomorrow
                description: 'Annual conference event',
                color: 'green'
            }
        ],
        eventClick: function(info) {   // Event handler for clicking on an event
            alert('Event: ' + info.event.title + '\n' +
                  'Description: ' + info.event.extendedProps.description);
        }
    });

    calendar.render();
});
</script>



<style>
        * {
            /* box-sizing: border-box; */
        }

        .col-20 {
            float: left;
            width: 20%;
        }

        .col-50 {
            float: left;
            width: 50%;
        }

        .col-80 {
            float: left;
            width: 80%;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .btn {
            width: 100%;
        }
    </style>
