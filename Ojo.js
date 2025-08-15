function togglePassword(inputId, el) {
  const input = document.getElementById(inputId);
  const icon = el.querySelector('ion-icon');
  if (input.type === "password") {
    input.type = "text";
    icon.name = "eye-outline";
  } else {
    input.type = "password";
    icon.name = "eye-off-outline";
  }
}