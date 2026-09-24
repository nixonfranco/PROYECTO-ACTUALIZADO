<?php
session_start();
require_once "conexion.php";

// 1) LÓGICA: se ejecuta antes de imprimir cualquier HTML (redirects, formularios, permisos)
require __DIR__ . "/includes/logica/01_sesion_y_perfil.php";
require __DIR__ . "/includes/logica/02_admin.php";
require __DIR__ . "/includes/logica/03_mensajes_y_dominios.php";
require __DIR__ . "/includes/logica/04_formularios_post.php";
?>
<?php include __DIR__ . '/includes/head.php'; ?>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <?php include __DIR__ . '/includes/secciones/inicio.php'; ?>
        <?php include __DIR__ . '/includes/secciones/menu.php'; ?>
        <?php include __DIR__ . '/includes/secciones/nutricion.php'; ?>
        <?php include __DIR__ . '/includes/secciones/registro.php'; ?>
        <?php include __DIR__ . '/includes/secciones/login.php'; ?>
        <?php include __DIR__ . '/includes/secciones/noticias.php'; ?>
        <?php include __DIR__ . '/includes/secciones/juegos.php'; ?>
        <?php include __DIR__ . '/includes/secciones/admin.php'; ?>
    </main>

<?php include __DIR__ . '/includes/chatbot.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
<?php include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
