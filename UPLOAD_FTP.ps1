# ============================================
# Script PowerShell para Upload FTP
# ============================================
# ATENÇÃO: Substitua [SUA_SENHA_FTP] pela senha real

$ftpServer = "ftp://212.85.29.75"
$ftpUser = "u728578014.organizagrana.gotasistemas.com.br"
$ftpPass = "[SUA_SENHA_FTP]"  # ← INSERIR SENHA AQUI
$localPath = "C:\GIT\MyFinances"
$remotePath = "/public_html"

Write-Host "🚀 Iniciando upload para Hostinger..." -ForegroundColor Green

# Função para upload de arquivo
function Upload-File {
    param($localFile, $remoteFile)
    
    try {
        $uri = "$ftpServer$remoteFile"
        $webclient = New-Object System.Net.WebClient
        $webclient.Credentials = New-Object System.Net.NetworkCredential($ftpUser, $ftpPass)
        $webclient.UploadFile($uri, $localFile)
        Write-Host "✅ Enviado: $remoteFile" -ForegroundColor Green
    }
    catch {
        Write-Host "❌ Erro ao enviar: $remoteFile" -ForegroundColor Red
        Write-Host $_.Exception.Message
    }
}

Write-Host "⚠️  ATENÇÃO: Este script é básico. Recomendamos usar FileZilla!" -ForegroundColor Yellow
Write-Host "FileZilla: https://filezilla-project.org/download.php" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pressione CTRL+C para cancelar ou Enter para continuar..."
Read-Host

# Arquivos essenciais
Write-Host "📤 Enviando arquivos principais..." -ForegroundColor Cyan

# Copiar .env.hostinger para .env antes de enviar
Copy-Item "$localPath\.env.hostinger" "$localPath\.env" -Force
Write-Host "✅ .env configurado para Hostinger" -ForegroundColor Green

# Upload de arquivos principais
Upload-File "$localPath\.env" "$remotePath/.env"
Upload-File "$localPath\.htaccess" "$remotePath/.htaccess"

Write-Host ""
Write-Host "✅ Upload concluído!" -ForegroundColor Green
Write-Host ""
Write-Host "⚠️  IMPORTANTE: Para upload completo, use FileZilla:" -ForegroundColor Yellow
Write-Host "1. Conecte no FTP com as credenciais fornecidas" -ForegroundColor White
Write-Host "2. Navegue até public_html/" -ForegroundColor White
Write-Host "3. Envie todas as pastas: app/, database/, public/, routes/, views/" -ForegroundColor White
Write-Host "4. Não envie: .git/, docker-compose.yml, Dockerfile" -ForegroundColor White
Write-Host ""
Write-Host "📖 Veja instruções completas em DEPLOY_HOSTINGER.md" -ForegroundColor Cyan
