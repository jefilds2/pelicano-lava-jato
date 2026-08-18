<section class="panel-hero compact">
    <div>
        <span class="pill subtle">Operação diária</span>
        <h1>Agendamentos e OS</h1>
        <p>Cadastro de atendimento, cliente, veículo e emissão imediata de OS. A listagem abaixo é filtrada por data.
        </p>
    </div>
</section>

<section class="panel-card" style="margin-bottom: 18px;">
    <div class="section-head">
        <span class="pill subtle">Filtro diário</span>
        <h2>Ver OS e agendamentos por data</h2>
    </div>
    <form method="get" action="/admin/agendamentos" class="form-grid two-columns">
        <label>
            <span>Data</span>
            <input type="date" name="date" value="<?= h($selectedDate) ?>">
        </label>
        <div style="display: flex; align-items: end;">
            <button class="button button-success" type="submit">Filtrar</button>
        </div>
    </form>
</section>

<section class="panel-card" style="margin-bottom: 18px;">
    <?php
    $oldData = $_SESSION['_old'] ?? [];
    $selectedServiceNames = $editingOrder['selected_service_names'] ?? [];
    $selectedOldServiceIds = isset($oldData['service_ids']) && is_array($oldData['service_ids']) ? array_map('strval', $oldData['service_ids']) : [];
    $defaultCity = 'Guanhães - MG';
    $sourceData = $editingOrder ?? $clientPrefill ?? [];
    $formValue = static function (string $key, string $default = '') use ($sourceData): string {
        return old($key, isset($sourceData[$key]) ? (string) $sourceData[$key] : $default);
    };
    $showScheduleForm = $editingOrder !== null || $clientPrefill !== null || $oldData !== [];
    $paymentMethod = $formValue('payment_method', 'PIX');
    $selectedClientId = (int) old('client_id', isset($sourceData['id']) ? (string) $sourceData['id'] : (isset($sourceData['client_id']) ? (string) $sourceData['client_id'] : '0'));
    $selectedClientLabel = '';

    foreach ($clientDirectory as $client) {
        if ($selectedClientId === (int) $client['id']) {
            $selectedClientLabel = $client['name']
                . ($client['phone'] !== '' ? ' - ' . $client['phone'] : '')
                . ($client['city'] !== '' ? ' - ' . $client['city'] : '');
            break;
        }
    }

    $cashChangeValue = $formValue(
        'cash_change_amount',
        isset($editingOrder['cash_change_amount']) && $editingOrder['cash_change_amount'] !== null
            ? number_format((float) $editingOrder['cash_change_amount'], 2, ',', '.')
            : ''
    );
    ?>
    <div class="section-head">
        <span class="pill subtle"><?= $editingOrder ? 'Editar OS' : 'Novo atendimento' ?></span>
        <h2><?= $editingOrder ? 'Atualizar agendamento e OS' : 'Cadastrar e gerar OS' ?></h2>
        <?php if (!$editingOrder): ?>
            <p>Use o botão abaixo para abrir o cadastro de uma nova OS.</p>
        <?php endif; ?>
    </div>
    <?php if (!$editingOrder): ?>
        <button class="button button-primary" type="button" data-schedule-toggle
            aria-expanded="<?= $showScheduleForm ? 'true' : 'false' ?>" aria-controls="schedule-form-panel">
            <?= $showScheduleForm ? 'Fechar novo atendimento' : 'Novo atendimento' ?>
        </button>
    <?php endif; ?>
    <div id="schedule-form-panel" <?= $showScheduleForm ? '' : 'hidden' ?>
        style="<?= $showScheduleForm ? 'margin-top: 18px;' : 'margin-top: 18px; display: none;' ?>">
        <form method="post" class="form-grid two-columns">
            <?= csrf_field() ?>
            <input type="hidden" name="client_id" value="<?= h((string) $selectedClientId) ?>" data-client-id-input>
            <?php if ($editingOrder): ?>
                <input type="hidden" name="schedule_id" value="<?= h((string) $editingOrder['id']) ?>">
                <input type="hidden" name="service_order_id"
                    value="<?= h((string) ($editingOrder['service_order_id'] ?? '0')) ?>">
            <?php endif; ?>
            <section class="field-full form-section form-section-client">
                <div class="form-section-head">
                    <span class="form-section-kicker">Cliente</span>
                    <h3>Informações do cliente</h3>
                    <p>Selecione um cliente antigo para preencher automaticamente os dados básicos.</p>
                </div>
                <div class="form-section-grid two-columns">
                    <label class="field-full">
                        <span>Cliente já atendido</span>
                        <div class="client-picker" data-client-picker>
                            <input type="text" value="<?= h($selectedClientLabel) ?>" placeholder="Novo cliente / buscar por nome, telefone ou cidade"
                                autocomplete="off" data-client-search>
                            <button class="client-picker-clear" type="button" data-client-clear title="Limpar seleção">Limpar</button>
                            <div class="client-picker-results" data-client-results hidden></div>
                        </div>
                    </label>
                    <label>
                        <span>Nome do cliente</span>
                        <input type="text" name="client_name" value="<?= h($formValue('client_name')) ?>" data-client-field="name">
                    </label>
                    <label>
                        <span>Telefone</span>
                        <input type="text" name="client_phone" value="<?= h($formValue('client_phone')) ?>" data-client-field="phone">
                    </label>
                    <label class="field-full">
                        <span>Endereço</span>
                        <input type="text" name="client_address" value="<?= h($formValue('client_address')) ?>" data-client-field="address"
                            data-reset-value="">
                    </label>
                    <label>
                        <span>Bairro</span>
                        <input type="text" name="client_district" value="<?= h($formValue('client_district')) ?>" data-client-field="district"
                            data-reset-value="">
                    </label>
                    <label>
                        <span>Cidade</span>
                        <input type="text" name="client_city" value="<?= h($formValue('client_city', $defaultCity)) ?>" data-client-field="city"
                            data-reset-value="<?= h($defaultCity) ?>">
                    </label>
                    <label>
                        <span>CEP</span>
                        <input type="text" name="client_zipcode" value="<?= h($formValue('client_zipcode')) ?>" data-client-field="zipcode"
                            data-reset-value="">
                    </label>
                </div>
            </section>

            <section class="field-full form-section form-section-vehicle">
                <div class="form-section-head">
                    <span class="form-section-kicker">Veículo</span>
                    <h3>Informações do veículo</h3>
                    <p>Os dados do veículo continuam preenchidos manualmente para cada ordem de serviço.</p>
                </div>
                <div class="form-section-grid two-columns">
                    <label>
                        <span>Marca</span>
                        <input type="text" name="brand" value="<?= h($formValue('brand')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>Modelo</span>
                        <input type="text" name="model" value="<?= h($formValue('model')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>Cor</span>
                        <input type="text" name="color" value="<?= h($formValue('color')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>Placa</span>
                        <input type="text" name="plate" value="<?= h($formValue('plate')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>Motor</span>
                        <input type="text" name="engine" value="<?= h($formValue('engine')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>KM entrada</span>
                        <input type="number" name="km_in" value="<?= h($formValue('km_in', '0')) ?>" data-reset-value="0">
                    </label>
                    <label>
                        <span>KM saída</span>
                        <input type="number" name="km_out" value="<?= h($formValue('km_out', '0')) ?>" data-reset-value="0">
                    </label>
                    <label>
                        <span>Data do agendamento</span>
                        <input type="date" name="scheduled_date" value="<?= h($formValue('scheduled_date', $selectedDate)) ?>"
                            data-reset-value="<?= h($selectedDate) ?>">
                    </label>
                    <label>
                        <span>Horário</span>
                        <input type="time" name="scheduled_time" value="<?= h($formValue('scheduled_time')) ?>" data-reset-value="">
                    </label>
                    <label class="field-full">
                        <span>Reclamado / título da OS</span>
                        <input type="text" name="complaint" value="<?= h($formValue('complaint', 'LIMPEZA')) ?>" data-reset-value="LIMPEZA">
                    </label>
                </div>
            </section>

            <section class="field-full form-section form-section-services">
                <div class="form-section-head">
                    <span class="form-section-kicker">Serviços</span>
                    <h3>Pagamento, observações e itens da OS</h3>
                    <p>Centralize aqui as informações operacionais e os serviços selecionados.</p>
                </div>
                <div class="form-section-grid two-columns">
                    <label>
                        <span>Funcionário responsável</span>
                        <input type="text" name="employee_name" value="<?= h($formValue('employee_name')) ?>" data-reset-value="">
                    </label>
                    <label>
                        <span>Modalidade de pagamento</span>
                        <select name="payment_method" data-payment-method>
                            <option value="PIX" <?= $paymentMethod === 'PIX' ? 'selected' : '' ?>>PIX</option>
                            <option value="CARTÃO" <?= $paymentMethod === 'CARTÃO' ? 'selected' : '' ?>>CARTÃO</option>
                            <option value="DINHEIRO" <?= $paymentMethod === 'DINHEIRO' ? 'selected' : '' ?>>DINHEIRO</option>
                            <option value="CREDIARIO" <?= $paymentMethod === 'CREDIARIO' ? 'selected' : '' ?>>CREDIARIO</option>
                        </select>
                    </label>
                    <label data-cash-change-field <?= $paymentMethod === 'DINHEIRO' ? '' : 'hidden' ?>>
                        <span>Troco</span>
                        <input type="text" name="cash_change_amount" value="<?= h($cashChangeValue) ?>"
                            placeholder="Ex.: 50,00" data-cash-change-input data-reset-value="">
                    </label>
                    <label>
                        <span>Vencimento</span>
                        <input type="date" name="payment_due_date"
                            value="<?= h($formValue('payment_due_date', date('Y-m-d'))) ?>" data-reset-value="<?= h(date('Y-m-d')) ?>">
                    </label>
                    <label class="field-full">
                        <span>Observações</span>
                        <textarea name="notes" rows="3" data-reset-value=""><?= h($formValue('notes')) ?></textarea>
                    </label>
                    <fieldset class="field-full service-selector">
                        <legend>Serviços da OS</legend>
                        <div class="selector-grid">
                            <?php foreach ($services as $service): ?>
                                <label class="checkbox-card">
                                    <?php
                                    $serviceId = (string) $service['id'];
                                    $checked = $selectedOldServiceIds !== []
                                        ? in_array($serviceId, $selectedOldServiceIds, true)
                                        : in_array($service['name'], $selectedServiceNames, true);
                                    ?>
                                    <input type="checkbox" name="service_ids[]" value="<?= h($serviceId) ?>" <?= $checked ? 'checked' : '' ?>>
                                    <span><?= h($service['name']) ?>
                                        <strong><?= format_money((float) $service['base_price']) ?></strong></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </div>
            </section>
            <div class="field-full" style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button class="button button-success"
                    type="submit"><?= $editingOrder ? 'Salvar alterações da OS' : 'Salvar agendamento e gerar OS' ?></button>
                <?php if ($editingOrder): ?>
                    <a class="button button-neutral" href="/admin/agendamentos?date=<?= h($selectedDate) ?>">Cancelar
                        edição</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</section>

<section class="panel-card">
    <?php
    $statusMeta = [
        'em andamento' => ['label' => 'Em andamento', 'class' => 'is-progress'],
        'concluido' => ['label' => 'Concluído', 'class' => 'is-done'],
    ];
    ?>
    <div class="section-head">
        <span class="pill subtle">Histórico</span>
        <h2>Agendamentos e OS de <?= h(format_date_br($selectedDate)) ?></h2>
    </div>
    <p class="mobile-rotate-note">Para visualizar todo o conteúdo desta tabela no celular, vire o aparelho para o modo
        paisagem.</p>
    <div class="table-wrapper table-wrapper-compact">
        <table class="table-compact table-schedules">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Veículo</th>
                    <th>Placa</th>
                    <th>Telefone</th>
                    <th>OS</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($schedules): ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td data-label="Data"><?= h(format_date_br($schedule['scheduled_date'])) ?></td>
                            <td data-label="Cliente"><?= h($schedule['client_name']) ?></td>
                            <td data-label="Veículo"><?= h($schedule['brand'] . ' ' . $schedule['model']) ?></td>
                            <td data-label="Placa"><?= h($schedule['plate']) ?></td>
                            <td data-label="Telefone"><?= h($schedule['client_phone']) ?></td>
                            <td data-label="OS">
                                <?php if (!empty($schedule['service_order_id'])): ?>
                                    <a class="table-link" href="/admin/os/<?= h((string) $schedule['service_order_id']) ?>"
                                        target="_blank" rel="noreferrer">
                                        #<?= h($schedule['order_number']) ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td data-label="Status">
                                <?php if (!empty($schedule['service_order_id'])):
                                    $status = (string) ($schedule['service_order_status'] ?? 'em andamento');
                                    $meta = $statusMeta[$status] ?? $statusMeta['em andamento'];
                                    ?>
                                    <form method="post" action="/admin/os/<?= h((string) $schedule['service_order_id']) ?>/status"
                                        class="inline-form">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="date" value="<?= h($selectedDate) ?>">
                                        <button class="status-toggle <?= h($meta['class']) ?>" type="submit"
                                            title="Clique para alternar o status da OS">
                                            <?= h($meta['label']) ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="status-empty">Sem OS</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Ações">
                                <div class="table-actions">
                                    <a class="action-chip action-chip-edit"
                                        href="/admin/agendamentos?date=<?= h($selectedDate) ?>&edit=<?= h((string) $schedule['id']) ?>">
                                        Editar OS
                                    </a>
                                    <?php if (!empty($schedule['service_order_id'])): ?>
                                        <form method="post"
                                            action="/admin/os/<?= h((string) $schedule['service_order_id']) ?>/excluir"
                                            class="inline-form"
                                            onsubmit="return confirm('Excluir esta OS e o agendamento vinculado?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="date" value="<?= h($selectedDate) ?>">
                                            <button class="action-chip action-chip-danger" type="submit">Excluir OS</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Nenhum agendamento ou OS encontrado nessa data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    (() => {
        const form = document.querySelector('#schedule-form-panel form');
        const toggle = document.querySelector('[data-schedule-toggle]');
        const panel = document.getElementById('schedule-form-panel');
        const paymentMethod = document.querySelector('[data-payment-method]');
        const cashChangeField = document.querySelector('[data-cash-change-field]');
        const cashChangeInput = document.querySelector('[data-cash-change-input]');
        const clientPicker = document.querySelector('[data-client-picker]');
        const clientSearch = document.querySelector('[data-client-search]');
        const clientResults = document.querySelector('[data-client-results]');
        const clientClear = document.querySelector('[data-client-clear]');
        const clientIdInput = document.querySelector('[data-client-id-input]');
        const clientFields = {
            name: document.querySelector('[data-client-field="name"]'),
            phone: document.querySelector('[data-client-field="phone"]'),
            address: document.querySelector('[data-client-field="address"]'),
            district: document.querySelector('[data-client-field="district"]'),
            city: document.querySelector('[data-client-field="city"]'),
            zipcode: document.querySelector('[data-client-field="zipcode"]'),
        };
        const clients = <?= json_encode(array_values($clientDirectory), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        const clientMap = new Map(clients.map((client) => [String(client.id), client]));
        const defaultClientLabel = '';

        if (toggle && panel) {
            toggle.addEventListener('click', () => {
                const expanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', String(!expanded));
                toggle.textContent = expanded ? 'Novo atendimento' : 'Fechar novo atendimento';
                panel.hidden = expanded;
                panel.style.display = expanded ? 'none' : 'block';
            });
        }

        const applyClient = (clientId) => {
            if (!clientIdInput) {
                return;
            }

            const selectedId = String(clientId || '0');
            const client = clientMap.get(selectedId);
            clientIdInput.value = client ? selectedId : '0';

            if (!client) {
                return;
            }

            if (clientSearch) {
                clientSearch.value = [client.name, client.phone, client.city].filter(Boolean).join(' - ');
            }

            Object.entries(clientFields).forEach(([field, element]) => {
                if (!element) {
                    return;
                }

                element.value = client[field] || '';
            });
        };

        const closeClientResults = () => {
            if (clientResults) {
                clientResults.hidden = true;
                clientResults.innerHTML = '';
            }
        };

        const renderClientResults = (term = '') => {
            if (!clientResults) {
                return;
            }

            const normalizedTerm = term.trim().toLowerCase();
            const filtered = normalizedTerm === ''
                ? clients.slice(0, 8)
                : clients.filter((client) => {
                    const haystack = [client.name, client.phone, client.city, client.address]
                        .filter(Boolean)
                        .join(' ')
                        .toLowerCase();

                    return haystack.includes(normalizedTerm);
                }).slice(0, 12);

            if (filtered.length === 0) {
                clientResults.innerHTML = '<button type="button" class="client-result is-empty" disabled>Nenhum cliente encontrado</button>';
                clientResults.hidden = false;
                return;
            }

            clientResults.innerHTML = filtered.map((client) => {
                const meta = [client.phone, client.city, client.address].filter(Boolean).join(' - ');
                return `<button type="button" class="client-result" data-client-result="${client.id}">
                    <strong>${client.name}</strong>
                    <span>${meta || 'Sem dados complementares'}</span>
                </button>`;
            }).join('');

            clientResults.hidden = false;
        };

        if (clientSearch) {
            clientSearch.addEventListener('focus', () => {
                renderClientResults(clientSearch.value);
            });

            clientSearch.addEventListener('input', () => {
                if (clientIdInput) {
                    clientIdInput.value = '0';
                }

                renderClientResults(clientSearch.value);
            });
        }

        if (clientResults) {
            clientResults.addEventListener('click', (event) => {
                if (!(event.target instanceof Element)) {
                    return;
                }

                const target = event.target.closest('[data-client-result]');
                if (!(target instanceof HTMLElement)) {
                    return;
                }

                applyClient(target.dataset.clientResult || '0');
                closeClientResults();
            });
        }

        if (clientClear) {
            clientClear.addEventListener('click', () => {
                closeClientResults();

                if (clientSearch) {
                    clientSearch.value = defaultClientLabel;
                }

                if (clientIdInput) {
                    clientIdInput.value = '0';
                }

                if (form) {
                    form.querySelectorAll('input[type="text"], input[type="number"], input[type="date"], input[type="time"], textarea').forEach((element) => {
                        const resetValue = element.getAttribute('data-reset-value');
                        element.value = resetValue !== null ? resetValue : '';
                    });

                    form.querySelectorAll('input[type="checkbox"]').forEach((element) => {
                        element.checked = false;
                    });

                    form.querySelectorAll('select').forEach((element) => {
                        const field = element;
                        if (field === paymentMethod) {
                            field.value = 'PIX';
                        } else {
                            field.selectedIndex = 0;
                        }
                    });
                }

                syncCashChangeField();
            });
        }

        document.addEventListener('click', (event) => {
            if (!clientPicker || clientPicker.contains(event.target)) {
                return;
            }

            closeClientResults();
        });

        Object.values(clientFields).forEach((element) => {
            if (!element) {
                return;
            }

            element.addEventListener('input', () => {
                if (clientIdInput) {
                    clientIdInput.value = '0';
                }
            });
        });

        if (!paymentMethod || !cashChangeField || !cashChangeInput) {
            if (clientIdInput && clientIdInput.value !== '0') {
                applyClient(clientIdInput.value);
            }
            return;
        }

        const syncCashChangeField = () => {
            const isCash = paymentMethod.value === 'DINHEIRO';
            cashChangeField.hidden = !isCash;

            if (!isCash) {
                cashChangeInput.value = '';
            }
        };

        paymentMethod.addEventListener('change', syncCashChangeField);
        syncCashChangeField();

        if (clientIdInput && clientIdInput.value !== '0') {
            applyClient(clientIdInput.value);
        } else if (clientSearch) {
            clientSearch.value = '';
        }
    })();
</script>
