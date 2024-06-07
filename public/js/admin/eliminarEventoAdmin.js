const API_KEY = 'AIzaSyBeO-QZJcbxKhEMX36u8F1Y6NMLfKaneG0';
const DISCOVERY_DOC = 'https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest';
const SCOPES = 'https://www.googleapis.com/auth/calendar.events';
const CALENDAR_ID = '9ef91fff653b7047cf39af1c78267393a89572ee3da55cb2e96632495e0ce6e4@group.calendar.google.com';

let gapiInited = false;
let gisInited = false;
let accessToken = '';

document.addEventListener("DOMContentLoaded", function() {
    accessToken = document.querySelector('input[name="google-calendar-access-token"]').value;
    if (!gapiInited) {
        gapi.load('client', initializeGapiClient);
    } else {
        tryLoadAccessToken();
        showContent();
    }
});

function gapiLoaded() {
    gapi.load('client', initializeGapiClient);
}

function gisLoaded() {
    gisInited = true;
    if (gapiInited) {
        tryLoadAccessToken();
        showContent();
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
            showContent();
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

function showContent() {
    document.getElementById('loading-message').style.display = 'none';
    document.querySelector('.citas-pendientes').style.display = 'block';
}

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

async function eliminarCita(form) {
    const fechaCompleta = form.querySelector('input[name="cita-fecha"]').value.trim();
    const fecha = fechaCompleta.split(' ')[0]; // Solo obtener la parte de la fecha
    const horaInicio = form.querySelector('input[name="cita-hora"]').value.trim();

    console.log('Fecha:', fecha);
    console.log('Hora inicio:', horaInicio);

    const horaFin = sumar30Minutos(horaInicio);
    console.log('Hora fin:', horaFin);

    if (fecha.includes('-')) {
        console.log('Formato de fecha correcto:', fecha);
    } else {
        console.error('Formato de fecha incorrecto:', fecha);
    }

    // Verificación de la variable 'fecha' justo después de su extracción
    console.log('Valor de la fecha después de trim:', fecha);

    const [year, month, day] = fecha.split('-').map(Number);
    const [hourStart, minuteStart] = horaInicio.split(':').map(Number);
    const [hourEnd, minuteEnd] = horaFin.split(':').map(Number);

    console.log('Year:', year, 'Month:', month, 'Day:', day);
    console.log('Hour Start:', hourStart, 'Minute Start:', minuteStart);
    console.log('Hour End:', hourEnd, 'Minute End:', minuteEnd);

    if (isNaN(year) || isNaN(month) || isNaN(day) || isNaN(hourStart) || isNaN(minuteStart) || isNaN(hourEnd) || isNaN(minuteEnd)) {
        console.error('One or more date parts are NaN');
        return;
    }

    try {
        console.log('Construyendo fechas:');
        const fechaInicio = new Date(year, month - 1, day, hourStart, minuteStart, 0);
        const fechaFin = new Date(year, month - 1, day, hourEnd, minuteEnd, 0);

        console.log('Fecha inicio:', fechaInicio.toString());
        console.log('Fecha fin:', fechaFin.toString());

        if (isNaN(fechaInicio.getTime()) || isNaN(fechaFin.getTime())) {
            throw new RangeError('Invalid Date');
        }

        const response = await gapi.client.calendar.events.list({
            calendarId: CALENDAR_ID,
            timeMin: fechaInicio.toISOString(),
            timeMax: fechaFin.toISOString(),
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
                console.log('Evento eliminado del calendario de Google');

                form.submit();
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


