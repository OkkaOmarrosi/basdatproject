<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Kalender Pesanan</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @php
        dd($data);
    @endphp --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: generateEvents(),
                eventOverlap: false
            });
            calendar.render();
        });

        function generateEvents() {
            $data = @json($data);

            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            var eventData = [
                @foreach ($data as $item)
                    {
                        title:  '{{ $item->kode.' [ NOPOL = '.$item->nopol }} ]',
                        start_date: '{{ $item->tgl_mulai }}',
                        end_date: '{{ $item->tgl_selesai }}'
                    },
                @endforeach
            ];

            var events = [];

            eventData.forEach(function(event) {
                events.push({
                    title: event.title,
                    start: event.start_date,
                    end: event.end_date,
                    backgroundColor: getRandomColor(),
                    // borderColor: getRandomColor()
                });
            });

            console.log(events, 'events');
            return events;
        }
    </script>
</div>
