const toggle = document.getElementById('menu-toggle');
const nav = document.getElementById('nav-links');

toggle.addEventListener('click', () => {
nav.classList.toggle('show');
toggle.classList.toggle('active');
});