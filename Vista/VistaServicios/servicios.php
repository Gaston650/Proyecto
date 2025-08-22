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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios</title>
    <link rel="stylesheet" href="servicios.css">
</head>
<body>
      <header>
       <nav>
           <div class="usuario-info">
           <?php 
               $img = (isset($_SESSION['user_image']) && !empty($_SESSION['user_image']))
                   ? $_SESSION['user_image']
                   : '../../img/perfil-vacio.png';
           ?>
            <a href="../vistaEditarPerfil/editarPerfil.php" title="Editar perfil">
                <div class="foto-perfil" style="background-image: url(<?php echo htmlspecialchars($img); ?>);"></div>
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

       <div class="notificaciones">
              <a href="#" title="Ver Notificaciones">
                <ion-icon name="notifications-outline"></ion-icon>
                <span class="contador">0</span>
              </a>
       </div>
           <div class="nav-links">
                <ul>
                    <li><a href="../VistaPrincipal/home.php">Inicio</a></li>
                    <li><a href="servicios.php">Servicios</a></li>
                    <li><a href="../VistaHistorial/historial.php">Historial</a></li>
                    <li><a href="../VistaHistorial/historial.php">Reservas</a></li>
                </ul>
           </div>
       </nav>
   </header>

            <h2 class="titulo-servicios">Nuestros Servicios Disponibles</h2>
            <p class="subtitulo-servicios">Explora los servicios que puedes contratar fácilmente en ClickSoft.</p>

            <div class="filtros">
                <input type="text" placeholder="Buscar servicio..." class="buscador">
                <select class="filtro-zona">
                    <option value="">Todas las zonas</option>
                    <option value="montevideo">Montevideo</option>
                    <option value="canelones">Canelones</option>
                    <option value="maldonado">Maldonado</option>
                    <option value="rivera">Rivera</option>
                    <option value="rio-negro">Río Negro</option>
                    <option value="paysandu">Paysandú</option>
                    <option value="cerro-largo">Cerro Largo</option>
                    <option value="treinta-y-tres">Treinata y Tres</option>
                    <option value="lavalleja">Lavalleja</option>
                    <option value="artigas">Artigas</option>
                    <option value="salto">Salto</option>
                    <option value="durazno">Durazno</option>
                    <option value="tacuarembo">Tacuarembo</option>
                    <option value="florida">Florida</option>
                    <option value="flores">Flores</option>
                    <option value="colonia">Colonia</option>
                    <option value="rocha">Rocha</option>
                    <option value="san-jose">San José</option>
                </select>
                <select class="filtro-categoria">
                    <option value="">Todas las categorías</option>
                    <option value="limpieza">Limpieza</option>
                    <option value="jardineria">Jardinería</option>
                    <option value="tecnologia">Tecnología</option>
                </select>
            </div>
   
            <div class="servicios-grid">
              <div class="servicio-card">
                <img src="" alt="Foto del servicio" class="servicio-img">
                <h3>Titulo del servicio</h3>
                <p>Descripcion del servicio.</p>
                <p><strong>Precio:</strong> $500</p>
                <p><strong>Disponibilidad:</strong> Lunes a viernes</p>
                <p><strong>Proveedor:</strong>Nombre del proveedor</p>
                <button class="btn-contratar">Contratar</button>
                 <button class="btn-favorito">Favorito</button>
              </div>

                <div class="servicio-card">
                    <img src="" alt="Foto del servicio" class="servicio-img">
                    <h3>Titulo del servicio</h3>
                    <p>Descripcion del servicio.</p>
                    <p><strong>Precio:</strong> $500</p>
                    <p><strong>Disponibilidad:</strong> Lunes a viernes</p>
                    <p><strong>Proveedor:</strong>Nombre del proveedor</p>
                    <button class="btn-contratar">Contratar</button>
                    <button class="btn-favorito">Favorito</button>
                </div>
            </div>  


<script src="../VistaPrincipal/verPagina.js"></script>   
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>