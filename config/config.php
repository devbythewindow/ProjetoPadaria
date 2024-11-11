<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'projetopadaria');

define('SITE_URL', 'http://localhost/ProjetoPadaria');
define('ADMIN_EMAIL', 'admin@example.com');

// segurança
define('CSRF_TOKEN_SECRET', 'your-secret-key');
define('PASSWORD_PEPPER', 'your-pepper-string');

// login
define('LOG_FILE', __DIR__ . '/../logs/app.log');

// Configurações de cache
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hora

// Configurações de internacionalização
define('DEFAULT_LANGUAGE', 'pt_BR');
?>