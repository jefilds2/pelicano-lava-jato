<section class="panel-hero">
    <div>
        <span class="pill subtle">Resumo Operacional</span>
        <h1>Painel administrativo do Pelicano</h1>
        <p>Visão diária da operação. O painel agora trabalha por data, com filtro do dia e recuperação de histórico.</p>
    </div>
    <div class="panel-hero-actions">
        <form method="get" action="/admin/dashboard" class="form-grid" style="gap: 10px;">
            <input type="date" name="date" value="<?= h($selectedDate) ?>">
            <button class="button button-success" type="submit">Filtrar dia</button>
        </form>
    </div>
</section>

<?php if ($availableDates): ?>
<?php endif; ?>

<section class="stats-grid">
    <article class="stat-card">
        <span>Data</span>
        <strong><?= h((string) $stats['agendamentos_hoje']) ?></strong>
        <p>Agendamentos em <?= h(format_date_br($selectedDate)) ?>.</p>
    </article>
    <article class="stat-card">
        <span>Clientes</span>
        <strong><?= h((string) $stats['clientes']) ?></strong>
        <p>Base já criada no sistema.</p>
    </article>
    <article class="stat-card">
        <span>Veículos</span>
        <strong><?= h((string) $stats['veiculos']) ?></strong>
        <p>Veículos vinculados a clientes.</p>
    </article>
    <article class="stat-card">
        <span>OS</span>
        <strong><?= h((string) $stats['os_emitidas']) ?></strong>
        <p>Ordens de serviço já emitidas.</p>
    </article>
</section>

<section class="panel-card" style="margin-top: 18px;">
    <div class="section-head">
        <span class="pill subtle">Lançamentos</span>
        <h2>Agendamentos de <?= h(format_date_br($selectedDate)) ?></h2>
    </div>
    <p class="mobile-rotate-note">Para visualizar todo o conteúdo desta tabela no celular, vire o aparelho para o modo paisagem.</p>
    <div class="table-wrapper table-wrapper-compact">
        <table class="table-compact">
            <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Veículo</th>
                <th>Placa</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($todaySchedules): ?>
                <?php foreach ($todaySchedules as $schedule): ?>
                    <tr>
                        <td data-label="Data"><?= h(format_date_br($schedule['scheduled_date'])) ?></td>
                        <td data-label="Cliente"><?= h($schedule['client_name']) ?></td>
                        <td data-label="Veículo"><?= h($schedule['brand'] . ' ' . $schedule['model']) ?></td>
                        <td data-label="Placa"><?= h($schedule['plate']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum agendamento encontrado nessa data.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
