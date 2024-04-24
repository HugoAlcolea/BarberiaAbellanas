const CLIENT_ID = '797807977635-tj16ch52cvv067t6j14j6m1968c7cqml.apps.googleusercontent.com';
const API_KEY = 'AIzaSyBeO-QZJcbxKhEMX36u8F1Y6NMLfKaneG0';
const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';
const SCOPES = 'https://www.googleapis.com/auth/calendar.events';

let tokenClient;
let gapiInited = false;
let gisInited = false;
let calendar;

function gapiLoaded() {
    gapi.load('client', initializeGapiClient);
}

function initializeGapiClient() {
    gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: [DISCOVERY_DOC],
    }).then(async () => {
        gapiInited = true;
        maybeEnableButtons();

        const token = getCookie('token_user');
        if (token) {
            gapi.auth.setToken({
                'access_token': token
            });
            await handleToken(token);
            await listUpcomingEvents();
        } else {
            document.getElementById('authorize_button').style.display = 'block';
        }
    }).catch((error) => {
        console.error('Error initializing GAPI client: ', error);
    });
}

function gisLoaded() {
    tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: CLIENT_ID,
        scope: SCOPES,
        callback: '',
    });
    gisInited = true;
    maybeEnableButtons();
}

function maybeEnableButtons() {
    if (gapiInited && gisInited) {
        document.getElementById('authorize_button').style.visibility = 'visible';
    }
}

function maybeEnableForm() {
    const token = getCookie('token_user');
    if (token && $('#date').val()) {
        $('#eventModal').modal('show');
    }
}

async function handleAuthClick() {
    tokenClient.callback = async (resp) => {
        if (resp.error !== undefined) {
            throw (resp);
        }
        document.getElementById('signout_button').style.display = 'block';
        document.getElementById('authorize_button').style.display = 'none';
        setCookie('token_user', resp.access_token, 1);
        await handleToken(resp.access_token);
        await listUpcomingEvents();
    };

    if (gapi.client.getToken() === null) {
        tokenClient.requestAccessToken({ prompt: 'consent' });
    } else {
        tokenClient.requestAccessToken({ prompt: '' });
    }
}

function handleSignoutClick() {
    const token = getCookie('token_user');
    if (token !== null) {
        deleteCookie('token_user');
        document.getElementById('authorize_button').style.display = 'block';
        document.getElementById('signout_button').style.display = 'none';
        document.getElementById('calendar').style.display = 'none';
        $('#eventModal').modal('hide');

        window.location.reload();
    }
}

async function listUpcomingEvents() {
    try {
        const response = await gapi.client.calendar.events.list({
            'calendarId': '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com',
            'timeZone': 'Europe/Madrid',
            'showDeleted': false,
            'singleEvents': true,
            'orderBy': 'startTime',
        });
        const events = response.result.items;

        let fcEvents = [];

        events.forEach(event => {
            fcEvents.push({
                title: event.summary,
                start: event.start.dateTime || event.start.date,
                id: event.id,
                extendedProps: {
                    description: event.description
                }
            });
        });

        var calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: new Date(),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'es',
            events: fcEvents,
            dayHeaderFormat: { weekday: 'long' },
            weekText: 'Sem',
            firstDay: 1,
        });

        calendar.render();
        calendar.on('dateClick', handleDayClick);
        calendar.on('eventClick', handleEventClick);

    } catch (err) {
        console.error('Error listing events: ', err);
    }
}

$(document).ready(function () {
    $('#closeModalBtn').click(function () {
        $('#eventModal').modal('hide');
    });

    $('#deleteEventBtn').click(async function () {
        const eventId = $('#eventId').val();
        if (eventId) {
            try {
                await deleteEvent(eventId);
                $('#eventModal').modal('hide');
                await listUpcomingEvents();
            } catch (error) {
                console.error('Error deleting event: ', error);
            }
        }
    });
});

const availableTimes = [
    '09:00', '09:30',
    '10:00', '10:30',
    '11:00', '11:30',
    '12:00', '12:30',
    '13:00',
    '16:00', '16:30',
    '17:00', '17:30',
    '18:00', '18:30',
    '19:00', '19:30',
    '20:00'
];

function generateTimeOptions(date) {
    let options = '<option disabled selected>-- Selecciona una Hora --</option>';

    availableTimes.forEach(time => {
        if (isTimeAvailable(date, time)) {
            options += `<option value="${time}">${time}</option>`;
        } else {
            options += `<option value="${time}" disabled>${time} (No disponible)</option>`;
        }
    });

    return options;
}

async function isTimeAvailable(date, time) {
    const events = await getEventsForDate(date);
    const selectedTime = moment(`${date}T${time}`);
    const endSelectedTime = selectedTime.clone().add(30, 'minutes');

    for (const event of events) {
        const eventStart = moment(event.start.dateTime);
        const eventEnd = moment(event.end.dateTime);

        if ((selectedTime.isSameOrAfter(eventStart) && selectedTime.isBefore(eventEnd)) ||
            (endSelectedTime.isAfter(eventStart) && endSelectedTime.isSameOrBefore(eventEnd))) {
            return false;
        }
    }

    return true; 
}

let selectedDate;

async function handleDateClick(info) {
    selectedDate = info.dateStr;
    $('#date').val(selectedDate);
    $('#eventTime').html(generateTimeOptions(selectedDate));
    $('#eventModal').modal('hide'); 
    resetEventModal();
    $('#eventModal').modal('show');
}

async function handleDayClick(info) {
    selectedDate = info.dateStr;
    $('#date').val(selectedDate);
    if (!$('#eventTime').val()) {
        $('#eventTime').html(generateTimeOptions(selectedDate));
    }
    $('#eventModal').modal('hide'); 
    resetEventModal();
    $('#eventModal').modal('show'); 
}

function resetEventModal() {
    $('#eventId').val('');
    $('#eventName').val('');
    $('#eventTime').val('');
    $('#eventDescription').val('');
    $('#deleteEventBtn').hide();
}

async function createEvent(eventData) {
    try {
        const response = await gapi.client.calendar.events.insert({
            'calendarId': '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com',
            'resource': eventData,
        });
        console.log('Event created: ', response);
        $('#eventId').val('');
        return response;
    } catch (error) {
        console.error('Error creating event: ', error);
        throw error;
    }
}

async function updateEvent(eventId, eventData) {
    try {
        const response = await gapi.client.calendar.events.update({
            'calendarId': '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com',
            'eventId': eventId,
            'resource': eventData,
        });
        console.log('Event updated: ', response);
        $('#eventId').val('');
        return response;
    } catch (error) {
        console.error('Error updating event: ', error);
        throw error;
    }
}

async function deleteEvent(eventId) {
    try {
        await gapi.client.calendar.events.delete({
            'calendarId': '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com',
            'eventId': eventId,
        });
        console.log('Event deleted');
    } catch (error) {
        console.error('Error deleting event: ', error);
        throw error;
    }
}

function handleEventClick(info) {
    const event = info.event;
    selectedDate = moment(event.start).format('YYYY-MM-DD');
    $('#eventId').val(event.id || '');
    $('#eventName').val(event.title);
    $('#eventDescription').val(event.extendedProps.description || '');
    $('#date').val(selectedDate);
    const eventTime = moment(event.start).format('HH:mm');
    $('#eventTime').html(generateTimeOptions(selectedDate));
    $('#eventTime').val(eventTime);

    if (event.id) {
        $('#deleteEventBtn').show();
    } else {
        $('#deleteEventBtn').hide();
    }

    $('#eventModal').modal('show');
}

async function submitEventFormData() {
    const token = getCookie('token_user');
    if (!token) {
        alert('Debes iniciar sesión primero.');
        return;
    }

    const eventId = $('#eventId').val();
    const selectedTime = $('#eventTime').val();
    const selectedDateTime = moment(`${selectedDate}T${selectedTime}:00`);

    const userId = $('#userSelect').val();
    const barberId = $('#barberoSelect').val();
    const servicioId = $('#servicioSelect').val();

    const userName = $('#userSelect option:selected').text();
    const barberName = $('#barberoSelect option:selected').text();

    const servicioName = $('#servicioSelect option:selected').text();

    const description = `Barbero: ${barberName}, Servicio: ${servicioName}`;

    const eventData = {
        summary: userName, 
        description: description,
        start: {
            dateTime: selectedDateTime.format(),
            timeZone: 'Europe/Madrid',
        },
        end: {
            dateTime: selectedDateTime.clone().add(30, 'minutes').format(),
            timeZone: 'Europe/Madrid',
        },
    };

    try {
        if (!(await isTimeAvailable(eventId, selectedDateTime, selectedDateTime.clone().add(30, 'minutes')))) {
            alert('La hora seleccionada no está disponible. Por favor, elige otra hora.');
            return;
        }

        if (eventId) {
            await updateEvent(eventId, eventData);
        } else {
            await createEvent(eventData);
        }

        $('#eventModal').modal('hide');
        await listUpcomingEvents();
    } catch (error) {
        alert('Ocurrió un error al guardar el evento.');
    }
}

async function getEventsForDate(date) {
    try {
        const response = await gapi.client.calendar.events.list({
            'calendarId': '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com',
            'timeMin': moment(date).startOf('day').toISOString(),
            'timeMax': moment(date).endOf('day').toISOString(),
            'timeZone': 'Europe/Madrid',
            'showDeleted': false,
            'singleEvents': true,
            'orderBy': 'startTime',
        });
        return response.result.items || [];
    } catch (err) {
        console.error('Error obteniendo eventos: ', err);
        return [];
    }
}

function setCookie(name, value, days) {
    var expires = '';
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + (value || '') + expires + '; path=/; SameSite=None; Secure';
}

function getCookie(name) {
    var nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function deleteCookie(name) {
    document.cookie = name + '=; Max-Age=-99999999; path=/;';
}

async function handleToken(token) {
    document.getElementById('authorize_button').style.display = 'none';
    document.getElementById('signout_button').style.display = 'block';
    document.getElementById('calendar-container').style.display = 'block';

    await listUpcomingEvents();
    if (calendar) {
        calendar.render();
    }
}

$('#eventModal').on('shown.bs.modal', function () {
    const eventId = $('#eventId').val();

    if (!eventId) {
        $('#eventName').val('');
        $('#eventDescription').val('');
        $('#eventTime').html(generateTimeOptions(selectedDate));
        $('#date').val('');
        $('#deleteEventBtn').hide();
    }
});

$('#eventModal').on('hide.bs.modal', function () {
    $('#eventName').val('');
    $('#eventDescription').val('');
    $('#eventTime').val('');
    $('#date').val('');
    $('#deleteEventBtn').hide();
});