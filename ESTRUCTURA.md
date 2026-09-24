# Estructura del proyecto

Copia el contenido de esta carpeta junto a tus archivos actuales (`conexion.php`,
`config_chat.php`, `guardar_*.php`, `procesar_chat.php`, imágenes…). Reemplaza `pagina.php`.
Ya no necesitas el `pagina.css` original.

```
pagina.php                      ← ahora solo "arma" la página (≈35 líneas)
datos/
  tarjetas_nutricion.php        ← textos de las tarjetas de Educación Nutricional
includes/
  logica/                       ← PHP que corre ANTES de mostrar HTML
    01_sesion_y_perfil.php      logout, perfil, visitas, es_admin, historial de juegos
    02_admin.php                crear/editar/eliminar usuarios y partidas
    03_mensajes_y_dominios.php  mensajes flash y dominios de correo permitidos
    04_formularios_post.php     reparte los POST a → post/registro.php, login.php, perfil.php
  head.php  header.php  panel_perfil.php  chatbot.php  footer.php  scripts.php
  secciones/                    ← una pestaña por archivo
    inicio.php  menu.php  nutricion.php  registro.php  login.php
    noticias.php  juegos.php  admin.php
    menu/semana_1..4.php   nutricion/{tarjetas_educativas,semaforo,calculadora_imc,quiz,ultraprocesados}.php
    juegos/{plato,adivina,memoria,atrapa}.php   admin/{usuarios,juegos}.php
css/   01-base.css … 19-admin-y-bloqueado.css   (el número es el orden de carga)
js/    navegacion, utilidades_ui, nutricion_imc, nutricion_quiz, noticias,
       juegos_comun, juego_*, menu_movil, perfil, chatbot
```

Notas
- El orden de carga de CSS y JS está en `includes/head.php` y `includes/scripts.php`.
- El CSS del chatbot estaba duplicado (dos bloques idénticos salvo comentarios); dejé solo uno.
- Los estilos que estaban dentro de `<style>` en `pagina.php` ahora son `css/13` a `css/19`.
