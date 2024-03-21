document.addEventListener('DOMContentLoaded', function () {
    const tabsContainer = document.querySelector('.tabs-container');

    tabsContainer.addEventListener('mouseenter', function () {
        this.style.width = '300px';
    });

    tabsContainer.addEventListener('mouseleave', function () {
        this.style.width = '150px';
    });

    document.getElementById("Tab2").style.display = "block";

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
            userInfoDiv.style.display = 'none';
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
}

function showUserInfo(userId) {
    fetch(`./admin/user/${userId}`)
        .then(response => response.json())
        .then(data => {
            const userInfoDiv = document.getElementById('userInfoTemplate');
            const defaultImage = "./storage/profile_images/default.jpg";

            const profileImagePath = `../storage/profile_images/${data.profile_image}`;
            const profileImageURL = new URL(profileImagePath, location.href).href;

            const userInfoHTML = `
                <img src="${profileImageURL}" alt="Imagen de perfil" class="user-info-img">
                <svg class="img-edit" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#000000"
                        d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z" />
                </svg>
                <p><b>Nombre:</b> ${data.name}</p>
                <p><b>Apellido:</b> ${data.surname}</p>
                <p><b>Nombre de usuario:</b> ${data.username}</p>
                <p><b>Email:</b> ${data.email}</p>
                <p><b>Teléfono:</b> ${data.phone}</p>
                <p><b>Fecha de nacimiento:</b> ${data.date_of_birth}</p>
                <p><b>Género:</b> ${data.gender}</p>
                <p><b>Es administrador:</b> ${data.is_admin ? 'Sí' : 'No'}</p>
            `;

            userInfoDiv.innerHTML = userInfoHTML;
            userInfoDiv.style.display = 'block';

            const blurUserInfoDiv = document.querySelector('.blur-userInfo');
            if (blurUserInfoDiv) {
                blurUserInfoDiv.style.display = 'block';
            }

            const profileImage = document.querySelector('.user-info-img');
            if (profileImage) {
                profileImage.addEventListener('mouseenter', function () {
                    const editIcon = document.querySelector('.img-edit');
                    if (editIcon) {
                        editIcon.style.display = 'block';
                    }
                });

                profileImage.addEventListener('mouseleave', function () {
                    const editIcon = document.querySelector('.img-edit');
                    if (editIcon) {
                        editIcon.style.display = 'none';
                    }
                });
            }
        })
        .catch(error => console.error('Error al obtener la información del usuario:', error));
}

function logout() {
    window.location.href = "{{ route('logout') }}";
}
