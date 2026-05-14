# SEO — MyFinances

## O que foi implementado

### 1. Meta tags dinâmicas (todas as páginas)

O arquivo `views/layouts/app.php` agora gera automaticamente:

| Tag | Descrição |
|-----|-----------|
| `<title>` | Título otimizado por página |
| `meta description` | Descrição única por página |
| `meta keywords` | Palavras-chave por página |
| `meta robots` | `index,follow` em páginas públicas · `noindex,nofollow` em páginas privadas |
| `link canonical` | URL canônica para evitar conteúdo duplicado |
| Open Graph (`og:*`) | Preview ao compartilhar no WhatsApp, Facebook, LinkedIn |
| Twitter Card | Preview ao compartilhar no Twitter/X |
| `link manifest` | PWA manifest |
| `meta theme-color` | Cor da barra do browser em mobile |
| Schema.org JSON-LD | Dados estruturados para o Google |
| Google Analytics 4 | Tracking via `GA_MEASUREMENT_ID` no `.env` |

---

### 2. Como personalizar SEO de uma página

Antes do `ob_start()`, defina as variáveis que quiser sobrescrever:

```php
<?php
$title          = 'Título da Página — MyFinances';
$seoDescription = 'Descrição clara com palavras-chave (entre 150-160 caracteres).';
$seoKeywords    = 'palavra-chave 1, palavra-chave 2, palavra-chave 3';
$seoImage       = $appUrl . '/assets/img/og-image-personalizado.png'; // opcional
$seoNoIndex     = false; // true = noindex (páginas privadas/logadas)
ob_start();
?>
```

Variáveis disponíveis:

| Variável | Padrão | Descrição |
|----------|--------|-----------|
| `$seoTitle` | Valor de `$title` | Título para `<title>` e Open Graph |
| `$seoDescription` | Descrição global | `meta description` e og:description |
| `$seoKeywords` | Keywords globais | `meta keywords` |
| `$seoImage` | `/assets/img/og-image.svg` | Imagem para compartilhamento social |
| `$seoUrl` | URL atual | Canonical e og:url |
| `$seoType` | `website` | Tipo Open Graph (`article`, `profile`, etc.) |
| `$seoNoIndex` | `false` | `true` → `noindex, nofollow` |
| `$schemaJsonLd` | `null` | JSON-LD Schema.org personalizado |

---

### 3. Schema.org — Dados Estruturados

A landing page (`/`) inclui dois schemas:

- **SoftwareApplication** — descreve o MyFinances como aplicativo para o Google
- **FAQPage** — as perguntas frequentes aparecem no Google como rich snippets

Para adicionar schema em outras páginas:

```php
<?php
$schemaJsonLd = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebPage',
    'name'     => 'Título da Página',
    'description' => 'Descrição da página',
], JSON_UNESCAPED_UNICODE);
ob_start();
?>
```

---

### 4. Arquivos criados/alterados

| Arquivo | Descrição |
|---------|-----------|
| `views/layouts/app.php` | Meta tags dinâmicas completas + GA4 |
| `views/landing/index.php` | Landing page pública otimizada para SEO |
| `app/Controllers/PublicController.php` | Controller das páginas públicas |
| `routes/web.php` | Rotas `/` e `/sitemap.xml` |
| `public/robots.txt` | Instrui crawlers do Google |
| `public/sitemap.xml.php` | Sitemap XML dinâmico |
| `public/manifest.json` | PWA manifest |
| `public/assets/img/favicon.svg` | Favicon SVG gradiente |
| `public/assets/img/og-image.svg` | Imagem Open Graph 1200×630 |
| `public/.htaccess` | Compressão GZIP, cache, headers de segurança |
| `.env` | Adicionado `APP_NAME` e `GA_MEASUREMENT_ID` |
| `.env.example` | Documentado `GA_MEASUREMENT_ID` |
| Todas as views privadas | `$seoNoIndex = true` adicionado |

---

### 5. Ativar Google Analytics 4

1. Acesse [analytics.google.com](https://analytics.google.com)
2. Crie uma propriedade Web
3. Copie o **Measurement ID** (formato: `G-XXXXXXXXXX`)
4. No arquivo `.env`, defina:
   ```
   GA_MEASUREMENT_ID=G-XXXXXXXXXX
   ```
5. O script GA4 será injetado automaticamente em todas as páginas

---

### 6. Indexar no Google Search Console

1. Acesse [search.google.com/search-console](https://search.google.com/search-console)
2. Adicione a propriedade com a URL do seu domínio
3. Verifique a propriedade (método recomendado: tag HTML)
   - Adicione o `meta name="google-site-verification"` nas variáveis do layout
4. Envie o sitemap: `https://seudominio.com/sitemap.xml`
5. Solicite indexação da URL principal `/`

---

### 7. Atualizar APP_URL para produção

No servidor de produção, altere no `.env`:
```
APP_URL=https://seudominio.com
```

Isso atualiza automaticamente canonical, og:url, og:image e todos os links absolutos.

---

### 8. Performance (Core Web Vitals)

O `.htaccess` já inclui:
- **GZIP** para HTML, CSS, JS, JSON, SVG
- **Cache 1 ano** para imagens, fontes e ícones
- **Cache 1 semana** para CSS e JS
- **Preconnect** ao CDN do Bootstrap/Chart.js
- Headers de segurança (X-Frame-Options, X-Content-Type-Options, etc.)

---

### 9. Adicionar novas páginas otimizadas

Ao criar uma nova view pública:

1. Defina `$seoTitle`, `$seoDescription`, `$seoKeywords` no topo do arquivo
2. Use heading tags semânticas: `<h1>` principal único, `<h2>` para seções, `<h3>` para subitens
3. Adicione ao `sitemap.xml.php` uma nova `<url>` apontando para a nova rota
4. Defina `$seoNoIndex = false` (ou omita — o padrão já é público)

---

*Documentação gerada automaticamente — MyFinances SEO*
