function borrarLogo() {
  const input = document.getElementById('logo');
  const preview = document.getElementById('logo-preview');
  input.value = "";
  preview.src = "#";
  preview.style.display = "none";
}