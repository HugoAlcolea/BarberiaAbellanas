const API_KEY = 'AIzaSyBeO-QZJcbxKhEMX36u8F1Y6NMLfKaneG0';
const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';
const SCOPES = 'https://www.googleapis.com/auth/calendar.events';
const CALENDAR_ID = '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com';

let gapiInited = false;
let gisInited = false;
let accessToken = document.getElementById('google-calendar-access-token').value;

function gapiLoaded() {
    gapi.load('client', initializeGapiClient);
}

function gisLoaded() {
    gisInited = true;
    if (gapiInited) {
        tryLoadAccessToken();
    }
}

function initializeGapiClient() {
    gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: [DISCOVERY_DOC],
    }).then(function () {
        gapiInited = true;
        if (gisInited) {
            tryLoadAccessToken();
        }
    }, function (error) {
        console.error('Error al inicializar el cliente de Google API:', error);
    });
}

function tryLoadAccessToken() {
    if (accessToken) {
        gapi.client.setToken({ access_token: accessToken });
    } else {
        console.error('El token de acceso no está disponible.');
    }
}

document.addEventListener("DOMContentLoaded", function() {
    if (!gapiInited) {
        gapiLoaded();
    } else {
        tryLoadAccessToken();
    }
});

function sumar30Minutos(hora) {
    const [horas, minutos] = hora.split(':').map(Number);
    let totalMinutos = horas * 60 + minutos + 30;
    let nuevaHora = Math.floor(totalMinutos / 60);
    const nuevosMinutos = totalMinutos % 60;
    if (nuevaHora >= 24) {
        nuevaHora -= 24;
    }
    return `${nuevaHora.toString().padStart(2, '0')}:${nuevosMinutos.toString().padStart(2, '0')}:00`;
}




async function eliminarCita() {
    const fecha = document.getElementById('cita-fecha').value;
    const horaInicio = document.getElementById('cita-hora').value;

    console.log('Fecha:', fecha);
    console.log('Hora inicio:', horaInicio);

    const horaFin = sumar30Minutos(horaInicio);
    console.log('Hora fin:', horaFin);

    const [year, month, day] = fecha.split('-');
    const [hourStart, minuteStart, secondStart] = horaInicio.split(':');
    const [hourEnd, minuteEnd, secondEnd] = horaFin.split(':');

    try {
        console.log('Construyendo fechas:');
        console.log('Fecha inicio:', new Date(year, month - 1, day, hourStart, minuteStart, secondStart));
        console.log('Fecha fin:', new Date(year, month - 1, day, hourEnd, minuteEnd, secondEnd));

        const response = await gapi.client.calendar.events.list({
            calendarId: CALENDAR_ID,
            timeMin: new Date(year, month - 1, day, hourStart, minuteStart, secondStart).toISOString(),
            timeMax: new Date(year, month - 1, day, hourEnd, minuteEnd, secondEnd).toISOString(),
            singleEvents: true,
            orderBy: 'startTime'
        });

        const events = response.result.items;

        console.log('Eventos encontrados:', events);

        if (events && events.length > 0) {
            const event = events[0];
        
            if (event) {
                await gapi.client.calendar.events.delete({
                    calendarId: CALENDAR_ID,
                    eventId: event.id
                });
                console.log('Evento eliminado');
                document.getElementById('delete-form').submit();
            } else {
                console.error('Evento no encontrado');
            }
        } else {
            console.error('No se encontraron eventos en el calendario para la fecha y hora especificadas');
        }     
    } catch (error) {
        console.error('Error al buscar o eliminar el evento:', error);
    }
}



