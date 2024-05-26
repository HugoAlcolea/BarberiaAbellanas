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
}

function initializeGapiClient() {
    gapi.client.init({
        apiKey: API_KEY,
        discoveryDocs: ['https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest'],
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

document.addEventListener('DOMContentLoaded', function () {
    if (!gapiInited) {
        gapiLoaded();
    } else {
        tryLoadAccessToken();
    }

    document.getElementById('cita-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const nombreUsuario = formData.get('nombreUsuario');
        const apellidoUsuario = formData.get('apellidoUsuario');
        const fecha = formData.get('fecha');
        const hora = formData.get('hora');
        const barbero = formData.get('barbero');
        const servicio = formData.get('servicio');
        const accessToken = document.getElementById('google-calendar-access-token').value;

        const eventDetails = {
            summary: `${nombreUsuario} ${apellidoUsuario}`,
            description: `Barbero: ${barbero}, Servicio: ${servicio}`,
            start: {
                dateTime: `${fecha}T${hora}:00`,
                timeZone: 'Europe/Madrid',
            },
            end: {
                dateTime: `${fecha}T${hora}:30`,
                timeZone: 'Europe/Madrid',
            },
            sendNotifications: true,
        };

        if (accessToken) {
            insertEvent(accessToken, eventDetails)
                .then((success) => {
                    if (success) {
                        document.getElementById('processing-message').style.display = 'none';
                        document.getElementById('download-form').style.display = 'block';
                        document.getElementById('cita-form').style.display = 'none';
                    } else {
                        document.getElementById('processing-message').style.display = 'block';
                    }
                })
                .catch((error) => {
                    console.error('Error al insertar el evento:', error);
                });
        } else {
            console.error('El token de acceso no está disponible.');
        }
        
    });
});

async function insertEvent(accessToken, eventDetails) {
    try {
        if (!gapi.client || !gapi.client.calendar) {
            console.error('La API de cliente de Google no está inicializada.');
            return false;
        }

        gapi.client.setToken({ access_token: accessToken });

        const selectedDate = new Date(eventDetails.start.dateTime);
        const startDateTime = selectedDate.toISOString();
        const endDateTime = new Date(selectedDate.getTime() + 30 * 60000).toISOString();

        const response = await gapi.client.calendar.events.list({
            'calendarId': CALENDAR_ID,
            'timeMin': startDateTime,
            'timeMax': endDateTime,
            'showDeleted': false,
            'singleEvents': true,
            'orderBy': 'startTime'
        });

        const events = response.result.items;

        if (events.length > 0) {
            alert('Ya hay un evento programado en esa hora.');
            return false;
        } else {
            const insertResponse = await gapi.client.calendar.events.insert({
                'calendarId': CALENDAR_ID,
                'resource': eventDetails,
            });

            if (insertResponse.status === 200) {
                return true;
            } else {
                throw new Error('Hubo un error al programar la cita');
            }
        }
    } catch (error) {
        alert('Hubo un error al programar la cita');
        console.error('Error al programar la cita:', error);
        return false;
    }
}
