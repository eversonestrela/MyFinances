/**
 * MyFinances - Custom JavaScript
 * Scripts personalizados para a aplicação
 */

// Espera o DOM carregar completamente
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // Auto-fechar alertas após 5 segundos
    // ============================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // ============================================
    // Formatar campos de moeda
    // ============================================
    const moneyInputs = document.querySelectorAll('input[type="number"][step="0.01"]');
    moneyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    // ============================================
    // Confirmação antes de excluir
    // ============================================
    const deleteLinks = document.querySelectorAll('a[href*="/delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault();
            }
        });
    });

    // ============================================
    // Destacar item ativo no menu
    // ============================================
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-item');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.startsWith(href) && href !== '/') {
            link.classList.add('active');
        } else if (currentPath === '/' && href === '/dashboard') {
            link.classList.add('active');
        }
    });

    // ============================================
    // Máscaras de input
    // ============================================
    
    // Máscara para valores monetários
    function formatMoney(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }

    // Formatar campos de moeda automaticamente
    const moneyInputs = document.querySelectorAll('.money-input');
    moneyInputs.forEach(input => {
        // Formatar ao digitar
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            
            if (value === '') {
                this.value = '';
                return;
            }
            
            // Converter para centavos
            value = parseInt(value);
            
            // Formatar como moeda brasileira
            const formatted = (value / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            this.value = formatted;
        });

        // Formatar ao sair do campo
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.value = '0,00';
            }
        });

        // Limpar ao focar se for 0,00
        input.addEventListener('focus', function() {
            if (this.value === '0,00') {
                this.value = '';
            }
        });
    });

    // Inicializar campos de moeda vazios
    moneyInputs.forEach(input => {
        if (input.value === '') {
            input.value = '';
        }
    });

    // Máscara para data
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        // Definir data mínima como hoje para evitar datas passadas (quando necessário)
        if (input.hasAttribute('data-min-today')) {
            const today = new Date().toISOString().split('T')[0];
            input.setAttribute('min', today);
        }
    });

    // ============================================
    // Validação de formulários
    // ============================================
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Validar senhas iguais
            const senha = form.querySelector('input[name="senha"]');
            const confirmarSenha = form.querySelector('input[name="confirmar_senha"]');
            
            if (senha && confirmarSenha) {
                if (senha.value !== confirmarSenha.value) {
                    e.preventDefault();
                    alert('As senhas não conferem!');
                    confirmarSenha.focus();
                    return false;
                }
            }

            // Validar data fim maior que data início
            const dataInicio = form.querySelector('input[name="data_inicio"]');
            const dataFim = form.querySelector('input[name="data_fim"]');
            
            if (dataInicio && dataFim) {
                if (new Date(dataFim.value) <= new Date(dataInicio.value)) {
                    e.preventDefault();
                    alert('A data de fim deve ser posterior à data de início!');
                    dataFim.focus();
                    return false;
                }
            }
        });
    });

    // ============================================
    // Tooltips do Bootstrap
    // ============================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ============================================
    // Preview de imagem antes do upload
    // ============================================
    const fotoInput = document.querySelector('input[type="file"][name="foto"]');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validar tipo
                if (!file.type.startsWith('image/')) {
                    alert('Por favor, selecione uma imagem válida');
                    this.value = '';
                    return;
                }
                
                // Validar tamanho (máx 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB');
                    this.value = '';
                    return;
                }
            }
        });
    }

    // ============================================
    // Smooth scroll para âncoras
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ============================================
    // Atualizar relógio em tempo real (se existir)
    // ============================================
    function updateClock() {
        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            clockElement.textContent = timeString;
        }
    }
    
    updateClock();
    setInterval(updateClock, 1000);

    // ============================================
    // Função auxiliar para formatação
    // ============================================
    window.MyFinances = {
        formatMoney: function(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value);
        },
        
        formatDate: function(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        },
        
        showLoading: function() {
            // Adicionar overlay de loading
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            `;
            overlay.innerHTML = '<div class="spinner"></div>';
            document.body.appendChild(overlay);
        },
        
        hideLoading: function() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    };

    console.log('MyFinances App loaded successfully! 💰');
});
