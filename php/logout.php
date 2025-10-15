<?php
// logout.php destruindo a sessão
session_start();
session_unset();
session_destroy();
header('Location: ../site/login.php');
exit;