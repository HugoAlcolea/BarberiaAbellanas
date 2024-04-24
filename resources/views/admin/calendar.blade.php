<!DOCTYPE html>
<html>

<head>
    <title>Google Calendar API Quickstart</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin/calendar.css') }}">
    <script src="{{ asset('js/admin/calendar.js') }}"></script>
</head>

<body>
    <div style="text-align: center; padding-top: 20px;">
        <h1>Google Calendar</h1>
        <a href="{{ route('admin.mainTable') }}" class="back-button">
            <img src="{{ asset('img/arrow-icon.png') }}" alt="Volver al panel del admin">
        </a>
        <button class="tablink" id="authorize_button" onclick="handleAuthClick()">Authorize</button>
        <button class="tablink" id="signout_button" style="display: none;" onclick="handleSignoutClick()">Sign
            Out</button>
    </div>
    <div class='container' id="calendar-container" style="display: none;">
        <div class='row justify-content-center'>
            <div class='col-md-12'>
                <div class='card'>
                    <div class='card-body'>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" id="eventId">
                        <label for="userSelect">Usuario:</label>
                        <select class="form-control" id="userSelect">
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="eventTime">Event Time</label>
                        <select class="form-control" id="eventTime" name="eventTime" required></select>
                    </div>
                    <div>
                        <label for="barberoSelect">Barbero:</label>
                        <select class="form-control" id="barberoSelect">
                            @foreach($barberos as $barbero)
                            <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="servicioSelect">Servicio:</label>
                        <select class="form-control" id="servicioSelect">
                            @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: none;">
                        <label for="date">Event Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div style="display: none;">
                        <label for="eventDescription">Event Description</label>
                        <textarea placeholder="Enter Event Description" class="form-control" id="eventDescription"
                            name="eventDescription"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-auto" style="display: none" id="deleteEventBtn"
                        onclick="deleteEvent()">Delete Event
                    </button>
                    <button type="button" class="btn btn-secondary" id="closeModalBtn"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChangesBtn"
                        onclick="submitEventFormData()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
    <script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>

</html>