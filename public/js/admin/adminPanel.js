document.addEventListener('DOMContentLoaded', function () {
    const activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        openTab({ currentTarget: document.querySelector(`button[onclick="openTab(event, '${activeTab}')"]`) }, activeTab);
    } else {
        document.getElementById("Tab1").style.display = "block";
    }

    const rows = document.querySelectorAll('.row');
    const tableScroll = document.querySelector('.table-scroll');
    const tableScrollHeight = tableScroll.offsetHeight;

    rows.forEach((row, index) => {
        setTimeout(() => {
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });

    const blurUserInfo = document.querySelector('.blur-userInfo');
    if (blurUserInfo) {
        blurUserInfo.addEventListener('click', function () {
            this.style.display = 'none';
            const userInfoDiv = document.getElementById('userInfoTemplate');
            if (userInfoDiv) {
                userInfoDiv.style.display = 'none';
            }
        });
    }
});


function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
    localStorage.setItem('activeTab', tabName);
}




function showUserInfo(userId) {
    fetch(`./admin/user/${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.id;
            document.getElementById('is_admin').value = data.is_admin ? '1' : '0';
            document.getElementById('name').value = data.name;
            document.getElementById('surname').value = data.surname;
            document.getElementById('username').value = data.username;
            document.getElementById('phone').value = data.phone;
            document.getElementById('date_of_birth').value = data.date_of_birth;
            document.getElementById('gender').value = data.gender;
            document.getElementById('email').value = data.email;

            const userInfoDiv = document.getElementById('userInfoTemplate');
            userInfoDiv.style.display = 'block';

            const blurUserInfoDiv = document.querySelector('.blur-userInfo');
            if (blurUserInfoDiv) {
                blurUserInfoDiv.style.display = 'block';
            }
        })
        .catch(error => console.error('Error al obtener la información del usuario:', error));
}

function searchUser() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();

    fetch(`/admin/search-users?search=${encodeURIComponent(filter)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(users => {
            const tableBody = document.querySelector('.table-scroll');
            tableBody.innerHTML = '';

            if (users.length > 0) {
                users.forEach(user => {
                    const row = document.createElement('div');
                    row.classList.add('row', 'row-table-scroll');
                    row.setAttribute('onclick', `showUserInfo('${user.id}')`);

                    const idCell = document.createElement('div');
                    idCell.classList.add('cell');
                    idCell.textContent = user.id;

                    const nameCell = document.createElement('div');
                    nameCell.classList.add('cell');
                    nameCell.textContent = `${user.name} ${user.surname}`;

                    const imageCell = document.createElement('div');
                    imageCell.classList.add('cell');

                    const profileImagePath = `storage/profile_images/${user.profile_image}`;
                    const img = document.createElement('img');
                    img.src = profileImagePath;
                    img.alt = `Perfil de ${user.username}`;
                    img.onerror = () => img.src = 'storage/profile_images/default.jpg';

                    imageCell.appendChild(img);

                    row.appendChild(idCell);
                    row.appendChild(nameCell);
                    row.appendChild(imageCell);

                    tableBody.appendChild(row);
                });
            } else {
                const noResultsRow = document.createElement('div');
                noResultsRow.classList.add('row', 'row-table-scroll');
                noResultsRow.textContent = 'No se encontraron resultados';
                tableBody.appendChild(noResultsRow);
            }
        })
        .catch(error => console.error('Error al buscar usuarios:', error));
}


function updateUser(event) {
    event.preventDefault();

    const userId = document.getElementById('userId').value;
    const isAdmin = document.getElementById('is_admin').value;
    const name = document.getElementById('name').value;
    const surname = document.getElementById('surname').value;
    const username = document.getElementById('username').value;
    const phone = document.getElementById('phone').value;
    const dateOfBirth = document.getElementById('date_of_birth').value;
    const gender = document.getElementById('gender').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const profileImage = document.getElementById('profile_image').files[0];

    const formData = new FormData();
    formData.append('is_admin', isAdmin);
    formData.append('name', name);
    formData.append('surname', surname);
    formData.append('username', username);
    formData.append('phone', phone);
    formData.append('date_of_birth', dateOfBirth);
    formData.append('gender', gender);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('confirm_password', confirmPassword);
    if (profileImage) {
        formData.append('profile_image', profileImage);
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`./admin/user/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al actualizar los datos del usuario');
        }
        window.location.href = window.location.href;
    })
    .catch(error => {
        console.error('Error al actualizar los datos del usuario:', error);
        alert(`Hubo un error al actualizar los datos del usuario: ${error.message}`);
    });
}





function deleteUser(userId) {
    if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
        fetch(`/admin/user/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo eliminar el usuario');
            }
            return response.json();
        })
        .then(data => {
            console.log('Usuario eliminado:', data);
            window.location.reload(); 
        })
        .catch(error => {
            console.error('Error al eliminar el usuario:', error);
            alert(`Hubo un error al eliminar el usuario: ${error.message}`);
        });
    }
}

function deletePhoto(photoId) {
    console.log('ID de la foto:', photoId);
    if (confirm("¿Estás seguro de que quieres eliminar esta foto?")) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/admin/delete-photo/${photoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            },
        })        
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo eliminar la foto');
            }
            return response.json();
        })
        .then(data => {
            console.log('Foto eliminada:', data);
            window.location.reload();
        })
        .catch(error => {
            console.error('Error al eliminar la foto:', error);
            alert(`Hubo un error al eliminar la foto: ${error.message}`);
        });
    }
}

function filtrarCitas() {
    var fechaSeleccionada = document.getElementById('fecha').value;
    var citas = document.getElementsByClassName('cita-fecha');
    
    for (var i = 0; i < citas.length; i++) {
        var citaFecha = citas[i].getAttribute('data-cita-fecha');
        if (citaFecha.startsWith(fechaSeleccionada)) {
            citas[i].parentNode.style.display = 'table-row';
        } else {
            citas[i].parentNode.style.display = 'none';
        }
    }
}

function limpiarFiltro() {
    var citas = document.getElementsByClassName('cita-fecha');
    document.getElementById('fecha').value = '';

    for (var i = 0; i < citas.length; i++) {
        citas[i].parentNode.style.display = 'table-row';
    }
}

function filterCitas() {
    const searchValue = document.getElementById('search').value;
    fetch(`/admin/search-facturacion?search=${searchValue}`)
        .then(response => response.json())
        .then(data => {
            const citasTableBody = document.getElementById('citasTableBody');
            citasTableBody.innerHTML = '';
            data.citas.forEach(cita => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${cita.id}</td>
                    <td>${cita.usuario.name} ${cita.usuario.surname}</td>
                    <td>${new Date(cita.fecha).toLocaleDateString()}</td>
                    <td>${cita.dinero_cobrado}€</td>
                `;
                citasTableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error:', error));
}

