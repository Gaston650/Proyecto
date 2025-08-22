<?php
session_start();     

if (
    !isset($_SESSION['user_id']) &&
    !isset($_SESSION['empresa_id']) &&
    !isset($_SESSION['user_nombre'])
) {
    header("Location: ../VistaSesion/inicioSesion.php?error=Debes iniciar sesión primero.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Home - cliente</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
   <header>
       <nav>
           <div class="usuario-info">
           <?php 
               $img = (isset($_SESSION['user_image']) && !empty($_SESSION['user_image']))
                   ? $_SESSION['user_image']
                   : '../../IMG/perfil-vacio.png'; 
           ?>
            <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
                <div class="foto-perfil" style="background-image: url('<?php echo htmlspecialchars($img); ?>');"></div>
            </a>
           <span class="nombre-usuario">
           <?php
               if (isset($_SESSION['user_nombre'])) {
                   echo htmlspecialchars($_SESSION['user_nombre']);
               } elseif (isset($_SESSION['nombre'])) {
                   echo htmlspecialchars($_SESSION['nombre']);
               } elseif (isset($_SESSION['nombre_empresa'])) {
                   echo htmlspecialchars($_SESSION['nombre_empresa']);
               } else {
                   echo 'usuario';
               }
           ?>
           </span>
       </div>
        <div class="nav-links">
                    <ul>
                    <li><a href="home.php">Inicio</a></li>
                    <li><a href="../VistaServicios/servicios.php">Servicios</a></li>
                    <li><a href="../VistaHistorial/historial.php">Historial</a></li>
                    <li><a href="../VistaHistorial/historial.php">Reservas</a></li>
                </ul>
        </div>
       </nav>
   </header>

   <main>
       
        <!-- Sección Hero -->
        <section class="hero">
               <h2>Transformamos ideas en soluciones digitales</h2>
               <p>En ClickSoft te conectamos con los mejores proveedores de servicios de forma rapida, segura y personalizada. Descubre, compara y reserva lo que necesitas, desde cualquier lugar y cualquier momento.</p>
       </section>

     <!-- Sección Sobre Nosotros -->
<section class="sobre-nosotros">
    <div class="contenedor">
        <div class="texto">
            <h2>Sobre Nosotros</h2>
            <p>
                En <strong>ClickSoft</strong> creemos en la transformación digital como un puente 
                para mejorar la vida de las personas. Somos una empresa dedicada a conectar clientes 
                con proveedores de servicios de confianza en un entorno simple, ágil y seguro. 
                Nuestra plataforma te permite encontrar lo que necesitás en pocos pasos, evitando 
                complicaciones y ahorrando tiempo.
            </p>
            <p>
                Con una visión innovadora y un fuerte compromiso con la calidad, buscamos ofrecer 
                experiencias personalizadas que se adapten a cada necesidad. Desde pequeños servicios 
                hasta soluciones empresariales, estamos convencidos de que la tecnología puede acercar 
                a las personas con quienes realmente pueden ayudarlas.
            </p>
        </div>
        <div class="imagen">
            <img src="../../IMG/sobre-nosotros.png" alt="Ilustración sobre ClickSoft">
        </div>
    </div>
</section>


       <!-- Sección Valores -->
<section class="valores">
    <h2 class="titulo-valores">¿Por qué elegir ClickSoft?</h2>
    <!-- Valor 1 -->
    <div class="valor">
        <div class="texto">
            <h2>🔒 Seguridad</h2>
            <p>
                Protegemos tus datos con los más altos estándares de seguridad,
                para que realices tus reservas con total confianza.
            </p>
        </div>
        <div class="imagen">
            <img src="../../IMG/security.png" alt="Seguridad" />
        </div>
    </div>

    <!-- Valor 2 -->
    <div class="valor invertido">
        <div class="texto">
            <h2>⚡ Rapidez</h2>
            <p>
                Encontrá y contratá servicios en segundos gracias a nuestro sistema 
                rápido, intuitivo y accesible desde cualquier dispositivo.
            </p>
        </div>
        <div class="imagen">
            <img src="../../IMG/fast.png" alt="Rapidez" />
        </div>
    </div>

    <!-- Valor 3 -->
    <div class="valor">
        <div class="texto">
            <h2>🤝 Confianza</h2>
            <p>
                Conectamos clientes con proveedores de calidad, asegurando experiencias 
                positivas y un servicio en el que podés confiar.
            </p>
        </div>
        <div class="imagen">
            <img src="../../IMG/trust.png" alt="Confianza" />
        </div>
    </div>
</section>

               <!-- Sección Contacto Rápido -->
<section class="contacto-rapido">
    <h2>Contacto rápido</h2>
    <p>¿Tienes dudas o quieres saber más? Completa el formulario y nos pondremos en contacto contigo.</p>
    <form>
        <div class="form-row">
            <input type="text" placeholder="Tu nombre" required>
            <input type="email" placeholder="Tu correo" required>
        </div>
        <div class="form-row">
            <textarea placeholder="Tu mensaje" required></textarea>
        </div>
        <button type="submit">Enviar mensaje</button>
    </form>
</section>
</main>

    <!-- Footer -->
<footer class="footer">
    <div class="footer-contenido">
        <div class="footer-info">
            <h3>ClickSoft</h3>
            <p>Soluciones digitales para la gestión de reservas y servicios.</p>
            <p><strong>Dirección:</strong> Bv. José Batlle y Ordóñez 3570, 12000 Montevideo</p>
            <p><strong>Email:</strong> ClickSoft@gmail.com</p>
            <p><strong>Teléfono:</strong> 094546348</p>
            <p>&copy; 2025 ClickSoft. Todos los derechos reservados.</p>
        </div>
        <div class="footer-links">
            <h4>Enlaces útiles</h4>
            <ul>
                <li>
                    <a href="#">Inicio</a>
                    <a href="#">Servicios</a>
                    <a href="#">Historial</a>
                    <a href="#">Reservas</a>
                </li>
            </ul>
        </div>
        <div class="footer-instagram">
            <h4>Equipo en Instagram</h4>
            <div class="instagram-logos">
                <a href="https://www.instagram.com/gastonmoreira_ok/" target="_blank" title="Gastón Moreira">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram 1">
                </a>
                <a href="https://www.instagram.com/facu.carpellino28/" target="_blank" title="Facundo Carpellino">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram 2">
                </a>
                <a href="https://www.instagram.com/diegomachado12_/" target="_blank" title="Diego Machado">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram 3">
                </a>
                <a href="https://www.instagram.com/s.porimi/" target="_blank" title="Sebastian Balduvino">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram 4">
                </a>
                <a href="https://www.instagram.com/bunta860/" target="_blank" title="Bruno Orihuela">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram 5">
                </a>
            </div>
        </div>
    </div>
</footer>

<script src="verPagina.js"></script>
</body>
</html>