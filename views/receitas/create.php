<?php
$title = 'Nova Receita - MyFinances';
ob_start();
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Nova Receita</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/receitas/store" id="formReceita">
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>

                        <!-- Tipo de Receita -->
                        <div class="mb-4">
                            <label class="form-label d-block">Tipo de Receita</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_receita" id="tipo_unica" value="unica" checked>
                                <label class="form-check-label" for="tipo_unica">
                                    <strong>Receita Única</strong> - Lançamento único
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="tipo_receita" id="tipo_recorrente" value="recorrente">
                                <label class="form-check-label" for="tipo_recorrente">
                                    <strong>Receita Recorrente</strong> - Valor fixo mensal
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control money-input" id="valor" name="valor" placeholder="0,00" required>
                            </div>
                        </div>

                        <!-- Campo para receita única -->
                        <div id="campo_unica">
                            <div class="mb-3">
                                <label for="data_recebimento" class="form-label">Data de Recebimento</label>
                                <input type="date" class="form-control" id="data_recebimento" name="data_recebimento" required>
                            </div>
                        </div>

                        <!-- Campos para receita recorrente -->
                        <div id="campos_recorrente" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_recebimento_rec" class="form-label">Data Início</label>
                                    <input type="date" class="form-control" id="data_recebimento_rec" name="data_recebimento_rec">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="data_fim" class="form-label">Data Fim</label>
                                    <input type="date" class="form-control" id="data_fim" name="data_fim">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" id="info_unica">
                            <i class="bi bi-info-circle"></i> Será criada uma receita única na data especificada
                        </div>
                        <div class="alert alert-info" id="info_recorrente" style="display: none;">
                            <i class="bi bi-info-circle"></i> Será criada uma receita com o mesmo valor para cada mês do período
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Salvar
                            </button>
                            <a href="/receitas" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Alternar campos conforme tipo de receita
const radioUnica = document.getElementById('tipo_unica');
const radioRecorrente = document.getElementById('tipo_recorrente');
const campoUnica = document.getElementById('campo_unica');
const camposRecorrente = document.getElementById('campos_recorrente');
const infoUnica = document.getElementById('info_unica');
const infoRecorrente = document.getElementById('info_recorrente');

function toggleTipoReceita() {
    if (radioRecorrente.checked) {
        campoUnica.style.display = 'none';
        camposRecorrente.style.display = 'block';
        infoUnica.style.display = 'none';
        infoRecorrente.style.display = 'block';
        document.getElementById('data_recebimento').removeAttribute('required');
        document.getElementById('data_recebimento_rec').setAttribute('required', 'required');
        document.getElementById('data_fim').setAttribute('required', 'required');
    } else {
        campoUnica.style.display = 'block';
        camposRecorrente.style.display = 'none';
        infoUnica.style.display = 'block';
        infoRecorrente.style.display = 'none';
        document.getElementById('data_recebimento').setAttribute('required', 'required');
        document.getElementById('data_recebimento_rec').removeAttribute('required');
        document.getElementById('data_fim').removeAttribute('required');
    }
}

radioUnica.addEventListener('change', toggleTipoReceita);
radioRecorrente.addEventListener('change', toggleTipoReceita);

// Validação antes de submeter
document.getElementById('formReceita').addEventListener('submit', function(e) {
    // Unificar campos de data
    if (radioRecorrente.checked) {
        const dataRecRec = document.getElementById('data_recebimento_rec').value;
        if (dataRecRec) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'data_recebimento';
            hiddenInput.value = dataRecRec;
            this.appendChild(hiddenInput);
        }
    }

    // Converter vírgulas para pontos nos campos monetários
    const moneyInputs = this.querySelectorAll('.money-input');
    moneyInputs.forEach(input => {
        const isVisible = input.offsetParent !== null;
        if (input.value && isVisible) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.value = input.value.replace(/\./g, '').replace(',', '.');
            this.appendChild(hiddenInput);
            input.removeAttribute('name');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/app.php';
?>
