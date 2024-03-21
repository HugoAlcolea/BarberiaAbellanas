// /public/js/register.js

// DOM Elements
const circles = document.querySelectorAll(".circle"),
  progressBar = document.querySelector(".indicator"),
  prevButton = document.getElementById("prev"),
  nextButton = document.getElementById("next"),
  registerButton = document.getElementById("register"),
  formSteps = document.querySelectorAll(".form-steps .step");

let currentStep = 1;

// Función para mostrar mensajes de error
const showError = (step, message) => {
  const errorMessage = document.getElementById(`error-step-${step}`);
  errorMessage.textContent = message;
  errorMessage.classList.add("active"); // Agregar la clase active
  errorMessage.style.height = "auto"; // Establecer el height a "auto" cuando está activo
};

// Función para ocultar mensajes de error
const hideError = (step) => {
  const errorMessage = document.getElementById(`error-step-${step}`);

  // Verificar si el elemento existe antes de intentar manipularlo
  if (errorMessage) {
    errorMessage.style.height = "0px"; // Establecer el height a 0px cuando está oculto
    errorMessage.classList.remove("active"); // Quitar la clase active
  }
};



// function that updates the current step and updates the DOM
const updateSteps = () => {
  // loop through all circles and add/remove "active" class based on their index and current step
  circles.forEach((circle, index) => {
    circle.classList[`${index < currentStep ? "add" : "remove"}`]("active");
  });

  // update progress bar width based on current step
  progressBar.style.width = `${((currentStep - 1) / (circles.length - 1)) * 100}%`;

  // hide/show the "Next" and "Prev" buttons based on current step
  prevButton.style.display = currentStep === 1 ? "none" : "block";
  nextButton.style.display = currentStep === circles.length ? "none" : "block";
  registerButton.style.display = currentStep === circles.length ? "block" : "none";

  // hide all form steps
  formSteps.forEach((step) => {
    step.style.display = "none";
  });

  // show the current form step
  formSteps[currentStep - 1].style.display = "block";

  // hide error message when switching steps
  hideError(currentStep);
};

// add click event listener to the "Prev" button
prevButton.addEventListener("click", (event) => {
  // Evitar el comportamiento predeterminado del botón (enviar el formulario)
  event.preventDefault();

  currentStep = Math.max(currentStep - 1, 1);
  updateSteps();
});

// add click event listener to the "Next" button
nextButton.addEventListener("click", (event) => {
  // Evitar el comportamiento predeterminado del botón (enviar el formulario)
  event.preventDefault();

  // Get the form inputs of the current step
  const inputs = formSteps[currentStep - 1].querySelectorAll("input");

  // Validar campos
  const isCurrentStepValid = Array.from(inputs).every((input) => input.value.trim() !== "");

  if (isCurrentStepValid) {
    currentStep = Math.min(currentStep + 1, circles.length);
    updateSteps();
  } else {
    showError(currentStep, "Por favor, completa todos los campos antes de continuar.");
  }
});

// initialize the steps
updateSteps();
