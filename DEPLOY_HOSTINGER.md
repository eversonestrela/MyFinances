# 🚀 Deploy na Hostinger - Passo a Passo

## 📋 Informações de Acesso

### FTP
- **Host**: `ftp://212.85.29.75`
- **Usuário**: `u728578014.organizagrana.gotasistemas.com.br`
- **Senha**: [sua senha FTP]
- **Porta**: `21`
- **Pasta**: `public_html`

### Banco de Dados
- **Host**: `localhost`
- **Database**: `u728578014_organizagrana`
- **Usuário**: `u728578014_sa`
- **Senha**: `Agenda@2035`

---

## 🔧 Passo 1: Preparar Arquivos Localmente

### 1.1 Renomear arquivo de configuração
```powershell
Copy-Item .env.hostinger .env
```

### 1.2 Verificar que os arquivos estão corretos
- ✅ `.env` com credenciais da Hostinger
- ✅ `database/schema.sql` atualizado
- ✅ Todos os arquivos da aplicação

---

## 📤 Passo 2: Upload via FTP (FileZilla Recomendado)

### 2.1 Instalar FileZilla (se não tiver)
- Download: https://filezilla-project.org/download.php?type=client

### 2.2 Conectar no FTP
1. Abrir FileZilla
2. Host: `ftp://212.85.29.75`
3. Usuário: `u728578014.organizagrana.gotasistemas.com.br`
4. Senha: [sua senha FTP]
5. Porta: `21`
6. Clicar em "Conexão Rápida"

### 2.3 Fazer Upload dos Arquivos

**IMPORTANTE:** Navegar até a pasta `public_html` no lado direito (servidor)

Fazer upload de TODAS estas pastas/arquivos:
```
📁 public_html/
├── 📁 app/              ← Upload completo
├── 📁 database/         ← Upload completo
├── 📁 public/           ← Upload completo
│   ├── 📁 assets/
│   ├── .htaccess       ← IMPORTANTE!
│   └── index.php
├── 📁 routes/
├── 📁 storage/          ← Criar e dar permissão 755
├── 📁 views/
├── .env                ← Usar .env.hostinger
└── .htaccess           ← IMPORTANTE!
```

**NÃO FAZER UPLOAD:**
- ❌ `.git/`
- ❌ `docker-compose.yml`
- ❌ `Dockerfile`
- ❌ `README.md` (opcional)
- ❌ `node_modules/` (se existir)

---

## 🗄️ Passo 3: Configurar Banco de Dados

### 3.1 Acessar phpMyAdmin da Hostinger
1. Ir para: https://hpanel.hostinger.com
2. Fazer login
3. Clicar em "Bancos de Dados" → "phpMyAdmin"

### 3.2 Importar Schema
1. Selecionar database: `u728578014_organizagrana`
2. Clicar na aba "Importar"
3. Escolher arquivo: `database/schema.sql`
4. Clicar em "Executar"

✅ **Isso vai criar:**
- Todas as tabelas
- Usuário admin (email: admin@myfinances.com, senha: admin123)

---

## 🔐 Passo 4: Configurar Permissões

### Via File Manager da Hostinger ou FTP:

1. Pasta `storage/` → Permissão `755` (rwxr-xr-x)
2. Subpastas de `storage/` → Permissão `755`

**No FileZilla:**
- Clicar direito na pasta → "Permissões de arquivo" → Valor numérico: `755`

---

## ✅ Passo 5: Testar Aplicação

### 5.1 Acessar a URL
https://organizagrana.gotasistemas.com.br

### 5.2 Fazer Login
- **Email**: admin@myfinances.com
- **Senha**: admin123

### 5.3 Verificar Funcionalidades
- ✅ Dashboard carregando
- ✅ Criar receitas (única e recorrente)
- ✅ Criar despesas (dividir valor e parcela fixa)
- ✅ Criar dívidas variáveis
- ✅ Gráficos funcionando

---

## 🐛 Troubleshooting

### Erro 500 - Internal Server Error
- Verificar se `.htaccess` foi enviado
- Verificar permissões da pasta `storage/`
- Verificar `.env` com credenciais corretas

### Erro de Conexão com Banco
- Verificar credenciais no `.env`
- Verificar se schema.sql foi importado
- Testar conexão no phpMyAdmin

### Página em branco
- Ativar `APP_DEBUG=true` temporariamente no `.env`
- Ver logs de erro no hPanel → "Arquivos" → "logs"

### CSS não carrega
- Verificar se pasta `public/assets/` foi enviada
- Limpar cache do navegador (Ctrl + Shift + R)

---

## 📝 Checklist Final

Antes de considerar o deploy completo:

- [ ] Todos os arquivos enviados via FTP
- [ ] `.env` configurado com dados da Hostinger
- [ ] `database/schema.sql` importado no phpMyAdmin
- [ ] Pasta `storage/` com permissão 755
- [ ] Site acessível na URL
- [ ] Login funcionando
- [ ] Dashboard exibindo corretamente
- [ ] Receitas, despesas e dívidas funcionando

---

## 🎉 Deploy Concluído!

Sua aplicação MyFinances está rodando em produção na Hostinger!

**Próximos passos:**
1. Alterar senha do admin
2. Criar novos usuários se necessário
3. Fazer backup regular do banco de dados
4. Monitorar logs de erro

---

**Suporte:** Se tiver algum problema, verifique os logs de erro no hPanel ou ative o debug temporariamente.
