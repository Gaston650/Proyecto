
function previewLogo(event) {
  const input = event.target;
  const preview = document.getElementById('logo-preview');
  if (input.files && input.files[0]) {
    preview.src = URL.createObjectURL(input.files[0]);
    preview.style.display = 'block';
  } else {
    preview.src = '#';
    preview.style.display = 'none';
  }
}
