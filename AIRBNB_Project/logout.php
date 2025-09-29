<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");  // Redirecționează către pagina principală după logout
exit;
?>
