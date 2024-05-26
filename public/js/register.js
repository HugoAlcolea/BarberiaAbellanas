// /public/js/register.js

const circles = document.querySelectorAll(".circle"),
  progressBar = document.querySelector(".indicator"),
  prevButton = document.getElementById("prev"),
  nextButton = document.getElementById("next"),
  registerButton = document.getElementById("register"),
  formSteps = document.querySelectorAll(".form-steps .step");

let currentStep = 1;

const showError = (step, message) => {
  const errorMessage = document.getElementById(`error-step-${step}`);
  errorMessage.textContent = message;
  errorMessage.classList.add("active");
  errorMessage.style.height = "auto"; 
};

const hideError = (step) => {
  const errorMessage = document.getElementById(`error-step-${step}`);

  if (errorMessage) {
    errorMessage.style.height = "0px"; 
    errorMessage.classList.remove("active"); 
  }
};



const updateSteps = () => {
  circles.forEach((circle, index) => {
    circle.classList[`${index < currentStep ? "add" : "remove"}`]("active");
  });

  progressBar.style.width = `${((currentStep - 1) / (circles.length - 1)) * 100}%`;

  prevButton.style.display = currentStep === 1 ? "none" : "block";
  nextButton.style.display = currentStep === circles.length ? "none" : "block";
  registerButton.style.display = currentStep === circles.length ? "block" : "none";

  formSteps.forEach((step) => {
    step.style.display = "none";
  });

  formSteps[currentStep - 1].style.display = "block";

  hideError(currentStep);
};

prevButton.addEventListener("click", (event) => {
  event.preventDefault();

  currentStep = Math.max(currentStep - 1, 1);
  updateSteps();
});

nextButton.addEventListener("click", (event) => {
  event.preventDefault();

  const inputs = formSteps[currentStep - 1].querySelectorAll("input");

  const isCurrentStepValid = Array.from(inputs).every((input) => input.value.trim() !== "");

  if (isCurrentStepValid) {
    currentStep = Math.min(currentStep + 1, circles.length);
    updateSteps();
  } else {
    showError(currentStep, "Por favor, completa todos los campos antes de continuar.");
  }
});

updateSteps();
