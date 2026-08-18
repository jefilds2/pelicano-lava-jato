<?php
$oldData = $_SESSION['_old'] ?? [];
$defaultCity = 'Guanhães - MG';
$formValue = static function (string $key, string $default = '') use ($editingClient): string {
    return old($key, isset($editingClient[$key]) ? (string) $editingClient[$key] : $default);
};
?>
<section class="panel-hero compact">
    <div>
        <span class="pill subtle">Base de Clientes</span>
        <h1>Cadastro e consulta de clientes</h1>
        <p>Cadastre clientes diretamente nesta área e mantenha a base pronta para uso nas ordens de serviço.</p>
    </div>
</section>

<section class="panel-card" style="margin-bottom: 18px;">
    <div class="section-head">
        <span class="pill subtle"><?= $editingClient ? 'Editar cliente' : 'Novo cliente' ?></span>
        <h2><?= $editingClient ? 'Atualizar cadastro de cliente' : 'Cadastrar cliente sem gerar OS' ?></h2>
    </div>
    <form method="post" class="form-grid two-columns">
        <?= csrf_field() ?>
        <?php if ($editingClient): ?>
            <input type="hidden" name="client_id" value="<?= h((string) $editingClient['id']) ?>">
        <?php endif; ?>
        <label>
            <span>Nome do cliente</span>
            <input type="text" name="client_name" value="<?= h($formValue('client_name')) ?>">
        </label>
        <label>
            <span>Telefone</span>
            <input type="text" name="client_phone" value="<?= h($formValue('client_phone')) ?>">
        </label>
        <label class="field-full">
            <span>Endereço</span>
            <input type="text" name="client_address" value="<?= h($formValue('client_address')) ?>">
        </label>
        <label>
            <span>Bairro</span>
            <input type="text" name="client_district" value="<?= h($formValue('client_district')) ?>">
        </label>
        <label>
            <span>Cidade</span>
            <input type="text" name="client_city" value="<?= h($formValue('client_city', $defaultCity)) ?>">
        </label>
        <label>
            <span>CEP</span>
            <input type="text" name="client_zipcode" value="<?= h($formValue('client_zipcode')) ?>">
        </label>
        <div class="field-full" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <button class="button button-success" type="submit"><?= $editingClient ? 'Salvar cliente' : 'Cadastrar cliente' ?></button>
            <?php if ($editingClient): ?>
                <a class="button button-neutral" href="/admin/clientes">Cancelar edição</a>
            <?php endif; ?>
        </div>
    </form>
</section>

<section class="panel-card">
    <div class="section-head">
        <span class="pill subtle">Clientes</span>
        <h2>Clientes cadastrados</h2>
        <p>Visualize rapidamente a base já criada e edite o cadastro quando precisar.</p>
    </div>
    <div class="client-list-toolbar">
        <label class="field-full">
            <span>Filtrar clientes</span>
            <div class="client-picker client-picker-highlight">
                <input type="text" placeholder="Buscar por nome, telefone, endereço, cidade ou CEP" autocomplete="off"
                    data-client-list-filter>
            </div>
        </label>
    </div>
    <div class="table-wrapper table-wrapper-compact">
        <table class="table-compact table-clients">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>Veículos</th>
                    <th>Atendimentos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($clients): ?>
                    <?php foreach ($clients as $client): ?>
                        <?php
                        $searchText = trim(implode(' ', [
                            (string) ($client['name'] ?? ''),
                            (string) ($client['phone'] ?? ''),
                            (string) ($client['address'] ?? ''),
                            (string) ($client['district'] ?? ''),
                            (string) ($client['city'] ?? ''),
                            (string) ($client['zipcode'] ?? ''),
                        ]));
                        ?>
                        <tr data-client-row data-search="<?= h(strtolower($searchText)) ?>">
                            <td data-label="Cliente"><?= h($client['name']) ?></td>
                            <td data-label="Telefone"><?= h($client['phone']) ?></td>
                            <td data-label="Endereço"><?= h(trim(($client['address'] ?? '') . ($client['district'] ? ' - ' . $client['district'] : ''))) ?></td>
                            <td data-label="Cidade"><?= h(trim(($client['city'] ?? '') . ($client['zipcode'] ? ' - ' . $client['zipcode'] : ''))) ?></td>
                            <td data-label="Veículos"><?= h((string) $client['vehicles_count']) ?></td>
                            <td data-label="Atendimentos"><?= h((string) $client['schedules_count']) ?></td>
                            <td data-label="Ações">
                                <div class="table-actions table-actions-clients">
                                    <div class="action-stack">
                                        <a class="action-chip action-chip-edit" href="/admin/clientes?edit=<?= h((string) $client['id']) ?>">Editar</a>
                                        <form method="post" action="/admin/clientes/<?= h((string) $client['id']) ?>/excluir"
                                            class="inline-form action-delete-form" onsubmit="return confirm('Excluir este cliente?');">
                                            <?= csrf_field() ?>
                                            <button class="action-chip action-chip-danger" type="submit">Excluir</button>
                                        </form>
                                    </div>
                                    <a class="action-chip action-chip-neutral action-chip-block" href="/admin/agendamentos?client=<?= h((string) $client['id']) ?>">Usar OS</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nenhum cliente cadastrado ainda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="table-empty-filter" data-client-list-empty hidden>Nenhum cliente encontrado para esse filtro.</p>
</section>

<script>
    (() => {
        const filterInput = document.querySelector('[data-client-list-filter]');
        const rows = Array.from(document.querySelectorAll('[data-client-row]'));
        const emptyState = document.querySelector('[data-client-list-empty]');

        if (!filterInput || rows.length === 0) {
            return;
        }

        const syncRows = () => {
            const term = filterInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const haystack = row.getAttribute('data-search') || '';
                const matches = term === '' || haystack.includes(term);
                row.hidden = !matches;

                if (matches) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.hidden = visibleCount > 0;
            }
        };

        filterInput.addEventListener('input', syncRows);
    })();
</script>
