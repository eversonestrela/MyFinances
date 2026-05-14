<?php
$title      = 'Nova Despesa — MyFinances';
$seoNoIndex = true;
ob_start();
?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Nova Despesa Parcelada</h5></div>
                <div class="card-body">
                    <form method="POST" action="/despesas/store" id="formDespesa">
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>

                        <!-- Categoria -->
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">
                                Categoria <span class="text-danger">*</span>
                            </label>
                            <?php if (empty($categorias)): ?>
                                <div class="alert alert-warning py-2 mb-0">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Nenhuma categoria cadastrada.
                                    <a href="/categorias" target="_blank">Criar categorias</a>
                                </div>
                                <input type="hidden" name="categoria_id" value="">
                            <?php else: ?>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione uma categoria...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat->id ?>">
                                            <?= htmlspecialchars($cat->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Tipo de Parcelamento -->
                        <div class="mb-4">
                            <label class="form-label d-block">Tipo de Parcelamento</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_parcelamento" id="tipo_dividir" value="dividir" checked>
                                <label class="form-check-label" for="tipo_dividir">
                                    <strong>Dividir Valor Total</strong> - O valor será dividido igualmente entre os meses
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="tipo_parcelamento" id="tipo_fixa" value="fixa">
                                <label class="form-check-label" for="tipo_fixa">
                                    <strong>Parcela Fixa</strong> - Mesmo valor todo mês
                                </label>
                            </div>
                        </div>

                        <!-- Campos para Dividir Valor -->
                        <div id="campos_dividir">
                            <div class="mb-3">
                                <label for="valor_total" class="form-label">Valor Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control money-input" id="valor_total" name="valor_total" placeholder="0,00" required>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Parcela Fixa -->
                        <div id="campos_fixa" style="display: none;">
                            <div class="mb-3">
                                <label for="valor_parcela_fixa" class="form-label">Valor da Parcela Mensal</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control money-input" id="valor_parcela_fixa" name="valor_parcela_fixa" placeholder="0,00">
                                </div>
                            </div>
                        </div>

                        <!-- Período -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_fim" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
                            </div>
                        </div>

                        <div class="alert alert-info" id="info_dividir">
                            <i class="bi bi-info-circle"></i> As parcelas serão calculadas automaticamente dividindo o valor total pelos meses
                        </div>
                        <div class="alert alert-info" id="info_fixa" style="display: none;">
                            <i class="bi bi-info-circle"></i> Será criada uma parcela com o mesmo valor para cada mês do período
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Salvar</button>
                            <a href="/despesas" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                        <div class="mb-4">
                            <label class="form-label d-block">Tipo de Parcelamento</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_parcelamento" id="tipo_dividir" value="dividir" checked>
                                <label class="form-check-label" for="tipo_dividir">
                                    <strong>Dividir Valor Total</strong> - O valor será dividido igualmente entre os meses
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="tipo_parcelamento" id="tipo_fixa" value="fixa">
                                <label class="form-check-label" for="tipo_fixa">
                                    <strong>Parcela Fixa</strong> - Mesmo valor todo mês
                                </label>
                            </div>
                        </div>

                        <!-- Campos para Dividir Valor -->
                        <div id="campos_dividir">
                            <div class="mb-3">
                                <label for="valor_total" class="form-label">Valor Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control money-input" id="valor_total" name="valor_total" placeholder="0,00" required>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Parcela Fixa -->
                        <div id="campos_fixa" style="display: none;">
                            <div class="mb-3">
                                <label for="valor_parcela_fixa" class="form-label">Valor da Parcela Mensal</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control money-input" id="valor_parcela_fixa" name="valor_parcela_fixa" placeholder="0,00">
                                </div>
                            </div>
                        </div>

                        <!-- Período -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_fim" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
                            </div>
                        </div>

                        <div class="alert alert-info" id="info_dividir">
                            <i class="bi bi-info-circle"></i> As parcelas serão calculadas automaticamente dividindo o valor total pelos meses
                        </div>
                        <div class="alert alert-info" id="info_fixa" style="display: none;">
                            <i class="bi bi-info-circle"></i> Será criada uma parcela com o mesmo valor para cada mês do período
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Salvar</button>
                            <a href="/despesas" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Alternar campos conforme tipo de parcelamento
const radioDividir = document.getElementById('tipo_dividir');
const radioFixa = document.getElementById('tipo_fixa');
const camposDividir = document.getElementById('campos_dividir');
const camposFixa = document.getElementById('campos_fixa');
const infoDividir = document.getElementById('info_dividir');
const infoFixa = document.getElementById('info_fixa');

function toggleTipoParcelamento() {
    if (radioFixa.checked) {
        camposDividir.style.display = 'none';
        camposFixa.style.display = 'block';
        infoDividir.style.display = 'none';
        infoFixa.style.display = 'block';
        document.getElementById('valor_total').removeAttribute('required');
        document.getElementById('valor_parcela_fixa').setAttribute('required', 'required');
    } else {
        camposDividir.style.display = 'block';
        camposFixa.style.display = 'none';
        infoDividir.style.display = 'block';
        infoFixa.style.display = 'none';
        document.getElementById('valor_total').setAttribute('required', 'required');
        document.getElementById('valor_parcela_fixa').removeAttribute('required');
    }
}

radioDividir.addEventListener('change', toggleTipoParcelamento);
radioFixa.addEventListener('change', toggleTipoParcelamento);

// Validação antes de submeter
document.getElementById('formDespesa').addEventListener('submit', function(e) {
    // Converter vírgulas para pontos antes de enviar
    const moneyInputs = this.querySelectorAll('.money-input');
    moneyInputs.forEach(input => {
        // Só processar campos visíveis e com valor
        const isVisible = input.offsetParent !== null;
        if (input.value && isVisible) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.value = input.value.replace(/\./g, '').replace(',', '.');
            this.appendChild(hiddenInput);
            input.removeAttribute('name');
        } else if (!isVisible) {
            // Remover name de campos ocultos para não enviá-los
            input.removeAttribute('name');
        }
    });
});
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/app.php'; ?>
