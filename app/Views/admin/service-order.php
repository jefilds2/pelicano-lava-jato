<main class="os-sheet">
    <?php
    $statusLabel = ($order['status'] ?? 'em andamento') === 'concluido' ? 'Concluído' : 'Em andamento';
    $cashChangeAmount = isset($order['cash_change_amount']) ? (float) $order['cash_change_amount'] : 0.0;
    ?>
    <section class="os-header">
        <div class="os-company">
            <h1><?= h($order['company_name']) ?></h1>
            <p><?= h($order['company_address']) ?></p>
            <p>Tel: <?= h($order['company_phone']) ?></p>
            <?php if (!empty($order['company_cnpj'])): ?>
                <p>CNPJ: <?= h($order['company_cnpj']) ?></p>
            <?php endif; ?>
            <p>Site: <?= h($order['company_site']) ?></p>
        </div>
        <div class="os-title">
            <strong>Recibo de OS</strong>
            <span>Página 1 de 1</span>
        </div>
    </section>

    <section class="os-meta os-grid-4">
        <div><span>OS</span><strong><?= h($order['order_number']) ?></strong></div>
        <div><span>Status</span><strong><?= h($statusLabel) ?></strong></div>
        <div><span>Abertura</span><strong><?= h(format_datetime_br($order['opened_at'])) ?></strong></div>
        <div><span>Fechamento</span><strong><?= h(format_datetime_br($order['closed_at'])) ?></strong></div>
        <div><span>KM Entrada / Saída</span><strong><?= h((string) $order['km_in']) ?> / <?= h((string) $order['km_out']) ?></strong></div>
    </section>

    <section class="os-block">
        <h2>Cliente</h2>
        <div class="os-grid-2">
            <div><span>Nome</span><strong><?= h($order['client_name']) ?></strong></div>
            <div><span>Telefone</span><strong><?= h($order['client_phone']) ?></strong></div>
            <div><span>Endereço</span><strong><?= h($order['client_address']) ?></strong></div>
            <div><span>Bairro</span><strong><?= h($order['client_district']) ?></strong></div>
            <div><span>Cidade</span><strong><?= h($order['client_city']) ?></strong></div>
            <div><span>CEP</span><strong><?= h($order['client_zipcode']) ?></strong></div>
        </div>
    </section>

    <section class="os-block">
        <h2>Veículo</h2>
        <div class="os-grid-4">
            <div><span>Marca</span><strong><?= h($order['brand']) ?></strong></div>
            <div><span>Modelo</span><strong><?= h($order['model']) ?></strong></div>
            <div><span>Cor</span><strong><?= h($order['color']) ?></strong></div>
            <div><span>Placa</span><strong><?= h($order['plate']) ?></strong></div>
            <div><span>Motor</span><strong><?= h($order['engine']) ?></strong></div>
            <div><span>Reclamado</span><strong><?= h($order['complaint']) ?></strong></div>
        </div>
    </section>

    <section class="os-block">
        <table class="os-table">
            <thead>
            <tr>
                <th>Serviço</th>
                <th>Quant.</th>
                <th>V. Unit</th>
                <th>Desc.</th>
                <th>Acrésc.</th>
                <th>Total</th>
                <th>Funcionário</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= h($item['service_name']) ?></td>
                    <td><?= h((string) $item['quantity']) ?></td>
                    <td><?= h(number_format((float) $item['unit_price'], 2, ',', '.')) ?></td>
                    <td><?= h(number_format((float) $item['discount_amount'], 2, ',', '.')) ?></td>
                    <td><?= h(number_format((float) $item['addition_amount'], 2, ',', '.')) ?></td>
                    <td><?= h(number_format((float) $item['total_amount'], 2, ',', '.')) ?></td>
                    <td><?= h($item['employee_name']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="os-summary">
        <div><span>Quantidade Serviços</span><strong><?= h((string) count($items)) ?></strong></div>
        <div><span>Total de Serviços</span><strong><?= format_money((float) $order['service_total']) ?></strong></div>
        <div><span>Parcela</span><strong>1</strong></div>
        <div><span>Vencimento</span><strong><?= h(format_date_br($order['payment_due_date'])) ?></strong></div>
        <div><span>Modalidade</span><strong><?= h($order['payment_method']) ?></strong></div>
        <?php if (($order['payment_method'] ?? '') === 'DINHEIRO' && $cashChangeAmount > 0): ?>
            <div><span>Troco</span><strong><?= format_money($cashChangeAmount) ?></strong></div>
        <?php endif; ?>
    </section>

    <section class="os-total-line">
        <div>Total Bruto: <strong><?= format_money((float) $order['gross_total']) ?></strong></div>
        <div>Descontos: <strong><?= format_money((float) $order['discount_total']) ?></strong></div>
        <div>Acréscimos: <strong><?= format_money((float) $order['addition_total']) ?></strong></div>
        <div>Total: <strong><?= format_money((float) $order['final_total']) ?></strong></div>
        <div>Saldo a pagar: <strong><?= format_money((float) $order['final_total']) ?></strong></div>
    </section>

    <section class="os-signatures">
        <div>
            <span><?= h($order['client_name']) ?></span>
            <strong>Cliente</strong>
        </div>
        <div>
            <span><?= h($order['company_name']) ?></span>
            <strong>Empresa</strong>
        </div>
    </section>

    <section class="os-observation">
        <strong>Observação Geral:</strong>
        <p><?= nl2br(h($order['notes'])) ?></p>
    </section>

    <section class="print-actions no-print">
        <a class="button button-neutral" href="/admin/dashboard">Voltar ao painel</a>
        <a class="button button-secondary" href="/admin/agendamentos?date=<?= h($order['scheduled_date']) ?>&edit=<?= h((string) $order['schedule_id']) ?>">Editar OS</a>
        <button class="button button-primary" type="button" onclick="window.print()">Imprimir OS</button>
    </section>
</main>
