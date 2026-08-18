<section class="panel-hero compact">
    <div>
        <span class="pill subtle">Configuração</span>
        <h1>Dados da empresa</h1>
        <p>Tudo que você editar aqui reflete na landing page pública.</p>
    </div>
</section>

<section class="panel-card">
    <?php
    $oldServiceIds = $_SESSION['_old']['service_ids'] ?? null;
    $serviceRows = [];

    if (is_array($oldServiceIds)) {
        $oldNames = $_SESSION['_old']['service_names'] ?? [];
        $oldPrices = $_SESSION['_old']['service_prices'] ?? [];
        $oldDeletes = $_SESSION['_old']['service_delete'] ?? [];

        foreach ($oldServiceIds as $index => $serviceId) {
            $serviceRows[] = [
                'id' => (string) $serviceId,
                'name' => (string) ($oldNames[$index] ?? ''),
                'price' => (string) ($oldPrices[$index] ?? ''),
                'delete' => (($oldDeletes[$index] ?? '0') === '1'),
            ];
        }
    } else {
        foreach ($services as $service) {
            $serviceRows[] = [
                'id' => (string) $service['id'],
                'name' => (string) $service['name'],
                'price' => format_money((float) $service['base_price']),
                'delete' => false,
            ];
        }
    }
    ?>
    <form method="post" class="form-grid two-columns">
        <?= csrf_field() ?>
        <label>
            <span>Nome da empresa</span>
            <input type="text" name="name" value="<?= h(old('name', $company['name'] ?? '')) ?>" required>
        </label>
        <label>
            <span>Headline principal</span>
            <input type="text" name="headline" value="<?= h(old('headline', $company['headline'] ?? '')) ?>">
        </label>
        <label class="field-full">
            <span>Descrição institucional</span>
            <textarea name="description" rows="4"><?= h(old('description', $company['description'] ?? '')) ?></textarea>
        </label>
        <label class="field-full">
            <span>Endereço</span>
            <input type="text" name="address" value="<?= h(old('address', $company['address'] ?? '')) ?>" required>
        </label>
        <label class="field-full">
            <span>Link do Google Maps</span>
            <input type="url" name="google_maps_url" value="<?= h(old('google_maps_url', $company['google_maps_url'] ?? '')) ?>" required>
        </label>
        <label>
            <span>WhatsApp</span>
            <input type="text" name="whatsapp" value="<?= h(old('whatsapp', $company['whatsapp'] ?? '')) ?>" required>
        </label>
        <label>
            <span>Telefone</span>
            <input type="text" name="phone" value="<?= h(old('phone', $company['phone'] ?? '')) ?>">
        </label>
        <label>
            <span>CNPJ</span>
            <input type="text" name="cnpj" value="<?= h(old('cnpj', $company['cnpj'] ?? '')) ?>">
        </label>
        <label>
            <span>E-mail</span>
            <input type="email" name="email" value="<?= h(old('email', $company['email'] ?? '')) ?>">
        </label>
        <label>
            <span>Site</span>
            <input type="url" name="site_url" value="<?= h(old('site_url', $company['site_url'] ?? '')) ?>">
        </label>
        <label class="field-full">
            <span>Horário de atendimento</span>
            <input type="text" name="opening_hours" value="<?= h(old('opening_hours', $company['opening_hours'] ?? '')) ?>">
        </label>
        <div class="field-full service-price-panel">
            <div class="section-head" style="margin-bottom: 16px;">
                <span class="pill subtle">Tabela atual</span>
                <h2>Catálogo de serviços</h2>
                <p>Você pode adicionar, excluir, renomear ou alterar valores. O catálogo salvo aqui aparece automaticamente na criação e edição da OS.</p>
            </div>
            <div
                class="field-full"
                data-save-alert
                hidden
                style="margin-bottom: 14px; padding: 14px 16px; border-radius: 16px; border: 1px solid rgba(251, 191, 36, 0.38); background: rgba(245, 158, 11, 0.14); color: #fde68a; font-weight: 700;"
            >
                Voce alterou nome ou valor de servico. Clique em <strong>Salvar dados</strong> para aplicar no catalogo e na criacao da OS.
            </div>
            <div class="field-full" style="display: flex; justify-content: flex-end; margin-bottom: 12px;">
                <button class="button button-primary" type="button" data-add-service>Adicionar serviço</button>
            </div>
            <div class="service-price-grid" data-service-catalog style="grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                <?php foreach ($serviceRows as $index => $service): ?>
                    <div class="service-price-card" data-service-row style="padding: 14px 16px; overflow: hidden; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08); background: linear-gradient(180deg, rgba(103,126,153,0.32), rgba(71,94,122,0.62)); box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);">
                        <input type="hidden" name="service_ids[]" value="<?= h($service['id']) ?>">
                        <input type="hidden" name="service_delete[]" value="<?= $service['delete'] ? '1' : '0' ?>" data-service-delete>
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;">
                            <span class="pill subtle">Serviço</span>
                            <button
                                type="button"
                                data-remove-service
                                style="border: 1px solid rgba(252, 165, 165, 0.42); border-radius: 999px; padding: 10px 16px; background: linear-gradient(180deg, rgba(239, 68, 68, 0.94), rgba(185, 28, 28, 0.94)); color: #fff; font-weight: 800; cursor: pointer; box-shadow: 0 10px 24px rgba(127, 29, 29, 0.32);"
                            >
                                Excluir
                            </button>
                        </div>
                        <div style="display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(160px, 0.9fr); gap: 12px; align-items: end;">
                        <label style="margin: 0;">
                            <span>Nome</span>
                            <input type="text" name="service_names[]" value="<?= h($service['name']) ?>" placeholder="Nome do serviço">
                        </label>
                        <label style="margin: 0;">
                            <span>Valor</span>
                            <input
                                type="text"
                                name="service_prices[]"
                                value="<?= h($service['price']) ?>"
                                inputmode="decimal"
                                data-money-input="true"
                                placeholder="R$ 0,00"
                            >
                        </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <template id="service-row-template">
                <div class="service-price-card" data-service-row style="padding: 14px 16px; overflow: hidden; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08); background: linear-gradient(180deg, rgba(103,126,153,0.32), rgba(71,94,122,0.62)); box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);">
                    <input type="hidden" name="service_ids[]" value="">
                    <input type="hidden" name="service_delete[]" value="0" data-service-delete>
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;">
                        <span class="pill subtle">Novo serviço</span>
                        <button
                            type="button"
                            data-remove-service
                            style="border: 1px solid rgba(252, 165, 165, 0.42); border-radius: 999px; padding: 10px 16px; background: linear-gradient(180deg, rgba(239, 68, 68, 0.94), rgba(185, 28, 28, 0.94)); color: #fff; font-weight: 800; cursor: pointer; box-shadow: 0 10px 24px rgba(127, 29, 29, 0.32);"
                        >
                            Excluir
                        </button>
                    </div>
                    <div style="display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(160px, 0.9fr); gap: 12px; align-items: end;">
                    <label style="margin: 0;">
                        <span>Nome</span>
                        <input type="text" name="service_names[]" value="" placeholder="Nome do serviço">
                    </label>
                    <label style="margin: 0;">
                        <span>Valor</span>
                        <input
                            type="text"
                            name="service_prices[]"
                            value=""
                            inputmode="decimal"
                            data-money-input="true"
                            placeholder="R$ 0,00"
                        >
                    </label>
                    </div>
                </div>
            </template>
        </div>
        <button class="button button-success field-full" type="submit">Salvar dados</button>
    </form>
</section>

<script>
    (() => {
        const catalog = document.querySelector('[data-service-catalog]');
        const addServiceButton = document.querySelector('[data-add-service]');
        const template = document.getElementById('service-row-template');
        const saveAlert = document.querySelector('[data-save-alert]');

        const formatMoney = (value) => {
            const digits = value.replace(/\D/g, '');
            const cents = digits === '' ? 0 : Number.parseInt(digits, 10);

            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            }).format(cents / 100);
        };

        const showSaveAlert = () => {
            if (!saveAlert) {
                return;
            }

            saveAlert.hidden = false;
        };

        const bindMoneyMask = (input) => {
            // O campo aceita qualquer digitação do usuário e normaliza para real brasileiro.
            // Isso evita inconsistência entre o que é salvo no banco e o que entra na OS.
            input.addEventListener('input', () => {
                input.value = formatMoney(input.value);
                showSaveAlert();
            });

            input.addEventListener('blur', () => {
                input.value = formatMoney(input.value);
            });

            if (input.value !== '') {
                input.value = formatMoney(input.value);
            }
        };

        document.querySelectorAll('[data-money-input="true"]').forEach(bindMoneyMask);

        if (!catalog || !addServiceButton || !template) {
            return;
        }

        const bindRemoveButton = (row) => {
            const removeButton = row.querySelector('[data-remove-service]');
            const deleteInput = row.querySelector('[data-service-delete]');
            const serviceId = row.querySelector('input[name="service_ids[]"]');

            if (!removeButton || !deleteInput) {
                return;
            }

            removeButton.addEventListener('click', () => {
                if ((serviceId?.value ?? '') === '') {
                    row.remove();
                    return;
                }

                const deleted = deleteInput.value === '1';
                deleteInput.value = deleted ? '0' : '1';
                row.style.opacity = deleted ? '1' : '0.48';
                row.style.transform = deleted ? 'scale(1)' : 'scale(0.98)';
                removeButton.textContent = deleted ? 'Excluir' : 'Restaurar';
                removeButton.style.background = deleted
                    ? 'linear-gradient(180deg, rgba(34, 197, 94, 0.94), rgba(21, 128, 61, 0.94))'
                    : 'linear-gradient(180deg, rgba(239, 68, 68, 0.94), rgba(185, 28, 28, 0.94))';
                removeButton.style.color = '#fff';
                removeButton.style.borderColor = deleted ? 'rgba(187, 247, 208, 0.42)' : 'rgba(252, 165, 165, 0.42)';
                showSaveAlert();
            });
        };

        addServiceButton.addEventListener('click', () => {
            const fragment = template.content.cloneNode(true);
            const row = fragment.querySelector('[data-service-row]');
            const priceInput = row.querySelector('[data-money-input="true"]');

            if (priceInput) {
                bindMoneyMask(priceInput);
            }

            if (row) {
                bindRemoveButton(row);
                row.querySelectorAll('input[name="service_names[]"]').forEach((input) => {
                    input.addEventListener('input', showSaveAlert);
                });
            }

            catalog.appendChild(fragment);
            showSaveAlert();
        });

        catalog.querySelectorAll('[data-service-row]').forEach((row) => {
            bindRemoveButton(row);
            row.querySelectorAll('input[name="service_names[]"]').forEach((input) => {
                input.addEventListener('input', showSaveAlert);
            });

            const deleteInput = row.querySelector('[data-service-delete]');
            const removeButton = row.querySelector('[data-remove-service]');
            if (deleteInput?.value === '1') {
                row.style.opacity = '0.48';
                row.style.transform = 'scale(0.98)';
                if (removeButton) {
                    removeButton.textContent = 'Restaurar';
                    removeButton.style.background = 'linear-gradient(180deg, rgba(34, 197, 94, 0.94), rgba(21, 128, 61, 0.94))';
                    removeButton.style.color = '#fff';
                    removeButton.style.borderColor = 'rgba(187, 247, 208, 0.42)';
                }
            }
        });
    })();
</script>
