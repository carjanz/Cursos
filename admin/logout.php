<?php
// Página de logout
session_start();
session_destroy();
header('Location: ' . SITE_URL);
exit;
