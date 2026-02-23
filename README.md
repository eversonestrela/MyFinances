# 💰 MyFinances - Sistema de Controle Financeiro Pessoal

Sistema completo de controle de finanças pessoais desenvolvido em PHP Vanilla, MySQL e Bootstrap 5. Aplicação moderna, responsiva e otimizada para uso mobile.

## 📋 Funcionalidades

- ✅ **Autenticação de Usuários**
  - Login e registro
  - Senha criptografada (password_hash)
  - Sessão segura
  - Upload de foto de perfil

- 💵 **Gestão de Receitas**
  - Cadastro de receitas
  - Edição e exclusão
  - Filtro por período

- 💳 **Gestão de Despesas Parceladas**
  - Cadastro automático de parcelas
  - Cálculo automático entre datas
  - Controle de status de pagamento
  - Visualização por mês

- ⚠️ **Gestão de Dívidas Variáveis**
  - Dívidas com valores diferentes por mês
  - CRUD completo

- 📊 **Dashboard Financeiro**
  - Saldo do mês
  - Total de receitas
  - Total de despesas
  - Total de dívidas
  - Gráfico dos últimos 6 meses (Chart.js)
  - Últimas movimentações

- 👤 **Perfil do Usuário**
  - Alterar dados pessoais
  - Alterar senha
  - Upload de foto de perfil

## 🏗️ Arquitetura

Projeto segue princípios de **Clean Architecture** e **SOLID**:

```
/app
  /Controllers      # Controladores da aplicação
  /Models          # Modelos de dados
  /Services        # Lógica de negócio
  /Repositories    # Acesso ao banco de dados
  /Core            # Classes fundamentais (Router, Request, etc)
/public            # Ponto de entrada e assets públicos
  /assets
    /css
    /js
  index.php
/views             # Templates HTML
/config            # Arquivos de configuração
/database          # Scripts SQL
/routes            # Definição de rotas
/storage           # Arquivos de upload
```

## 🚀 Instalação

### Opção 1: Desenvolvimento Local (Docker) - Recomendado

#### Pré-requisitos
- Docker
- Docker Compose

#### Passos

1. **Clone o repositório**
```bash
cd c:\GIT
git clone [url-do-repositorio] MyFinances
cd MyFinances
```

2. **Configure o ambiente**
```bash
# O arquivo .env já está configurado para Docker
# Você pode editar se necessário
```

3. **Inicie os containers**
```bash
docker-compose up -d
```

4. **Acesse a aplicação**
- **Aplicação**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
  - Usuário: root
  - Senha: root

5. **Credenciais de teste**
- Email: admin@myfinances.com
- Senha: admin123

### Opção 2: Servidor Local (XAMPP/WAMP)

#### Pré-requisitos
- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Apache com mod_rewrite habilitado

#### Passos

1. **Clone o repositório na pasta do servidor**
```bash
# Para XAMPP
cd C:\xampp\htdocs
git clone [url] MyFinances

# Para WAMP
cd C:\wamp64\www
git clone [url] MyFinances
```

2. **Crie o banco de dados**
```bash
# Acesse o MySQL
mysql -u root -p

# Execute o schema
mysql -u root -p < database/schema.sql
```

Ou importe o arquivo `database/schema.sql` pelo phpMyAdmin.

3. **Configure o .env**
```env
DB_HOST=localhost
DB_NAME=myfinances
DB_USER=root
DB_PASS=sua_senha

APP_URL=http://localhost/MyFinances
APP_ENV=development
```

4. **Acesse a aplicação**
```
http://localhost/MyFinances
```

### Opção 3: Hospedagem Compartilhada (Hostinger)

#### Passos

1. **Faça upload dos arquivos**
   - Faça upload de todos os arquivos via FTP/cPanel File Manager
   - Coloque na pasta `public_html` ou equivalente

2. **Configure o .env**
```env
DB_HOST=localhost
DB_NAME=seu_database
DB_USER=seu_usuario
DB_PASS=sua_senha

APP_URL=https://seudominio.com
APP_ENV=production
```

3. **Importe o banco de dados**
   - Acesse o phpMyAdmin do painel de controle
   - Crie um banco de dados
   - Importe o arquivo `database/schema.sql`

4. **Ajuste permissões**
```bash
chmod -R 755 storage/
```

5. **Configure o .htaccess na raiz**

Se a aplicação não estiver na raiz do domínio, ajuste o `.htaccess`:

```apache
RewriteEngine On
RewriteRule ^$ public/ [L]
RewriteRule (.*) public/$1 [L]
```

## 🔐 Segurança

- ✅ Senhas criptografadas com `password_hash()`
- ✅ Proteção contra SQL Injection (PDO prepared statements)
- ✅ Proteção contra XSS (htmlspecialchars)
- ✅ Validação de inputs
- ✅ Sessões seguras com regeneração de ID
- ✅ Upload de arquivos validado

## 📱 Responsividade

- Interface totalmente responsiva
- **Mobile First**: otimizado para celular
- Menu inferior estilo app mobile
- Cards e layouts adaptáveis
- Bootstrap 5

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 8.2 (Vanilla)
- **Banco de Dados**: MySQL 8.0
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Gráficos**: Chart.js
- **Ícones**: Bootstrap Icons
- **Docker**: PHP 8.2-Apache, MySQL 8.0, phpMyAdmin

## 📂 Estrutura do Banco de Dados

### Tabelas

- `usuarios` - Dados dos usuários
- `receitas` - Receitas/proventos
- `despesas` - Despesas parceladas (cabeçalho)
- `despesa_parcelas` - Parcelas individuais
- `dividas_variaveis` - Dívidas com valores variáveis

## 🎨 Funcionalidades da Interface

- Dashboard com cards informativos
- Gráfico interativo dos últimos 6 meses
- Lista de últimas movimentações
- CRUD completo para todas as entidades
- Filtros por período
- Mensagens flash de sucesso/erro
- Confirmação antes de excluir
- Loading states

## 🔄 Comandos Docker Úteis

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Reiniciar aplicação
docker-compose restart app

# Acessar shell do container
docker-compose exec app bash

# Acessar MySQL
docker-compose exec db mysql -u root -p
```

## 📝 Variáveis de Ambiente (.env)

```env
# Database
DB_HOST=localhost          # Host do banco (localhost ou IP)
DB_NAME=myfinances        # Nome do banco de dados
DB_USER=root              # Usuário do banco
DB_PASS=                  # Senha do banco

# Application
APP_URL=http://localhost:8000  # URL da aplicação
APP_ENV=development            # development ou production

# Session
SESSION_NAME=myfinances_session
SESSION_LIFETIME=7200          # 2 horas em segundos
```

## 🐛 Solução de Problemas

### Erro de conexão com banco de dados
- Verifique as credenciais no `.env`
- Confirme que o MySQL está rodando
- Teste a conexão manualmente

### Erro 404 nas rotas
- Verifique se o mod_rewrite está habilitado
- Confirme que o `.htaccess` está presente
- Verifique as permissões dos arquivos

### Erro ao fazer upload de foto
- Verifique permissões da pasta `storage/uploads/profile`
- Chmod 755 ou 777 se necessário
- Confirme que a pasta existe

### Página em branco
- Habilite exibição de erros no `php.ini`
- Verifique os logs do Apache/PHP
- Configure `APP_ENV=development` no `.env`

## 📄 Licença

Este projeto é de código aberto e está disponível sob a licença MIT.

## 👨‍💻 Desenvolvedor

Desenvolvido como projeto de demonstração de Clean Architecture em PHP Vanilla.

## 🎯 Roadmap Futuro

- [ ] Exportação de relatórios em PDF
- [ ] Gráficos adicionais
- [ ] Categorias para receitas/despesas
- [ ] Múltiplas contas bancárias
- [ ] Metas financeiras
- [ ] Notificações por email
- [ ] API REST
- [ ] Testes automatizados

## 📞 Suporte

Para dúvidas ou problemas:
1. Verifique a seção de solução de problemas
2. Revise a documentação
3. Abra uma issue no repositório

---

**Desenvolvido com ❤️ e PHP Vanilla**
