// /JavaScript/script.js


function checkPassword() {
    var password = document.getElementById("passwordInput").value;

    // Aquí debes comparar la contraseña ingresada con la que quieres validar
    // Puedes utilizar una contraseña hardcoded o, si el sitio es más complejo, una API o base de datos para verificarla.
    if (password === "a") {
        window.location.href = "./html/main.html"; // Redirecciona a la página protegida si la contraseña es correcta.
    } else {
        alert("Contraseña incorrecta. Inténtalo de nuevo.");
    }
}
