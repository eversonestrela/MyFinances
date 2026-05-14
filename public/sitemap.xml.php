<?php
/**
 * Sitemap XML dinâmico
 * Gera o sitemap apenas das páginas públicas
 */
header('Content-Type: application/xml; charset=UTF-8');

$appUrl  = 'https://myfinances.app';
$today   = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- Página principal / Landing Page -->
    <url>
        <loc><?= $appUrl ?>/</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Login -->
    <url>
        <loc><?= $appUrl ?>/login</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Cadastro -->
    <url>
        <loc><?= $appUrl ?>/register</loc>
        <lastmod><?= $today ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>

</urlset>
