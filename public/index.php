<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = request_method();

if ($method === 'POST') {
    verify_csrf();
}

ensure_service_order_status_column();
ensure_service_order_cash_change_column();

try {
    route($uri, $method);
} catch (Throwable $exception) {
    http_response_code(500);
    echo '<h1>Erro interno</h1>';
    echo '<pre>' . h($exception->getMessage()) . '</pre>';
}

function route(string $uri, string $method): void
{
    if ($uri === '/') {
        show_home();
        return;
    }

    if ($uri === '/admin/login' && $method === 'GET') {
        render('admin/login', ['title' => 'Login do painel'], 'auth-layout');
        return;
    }

    if ($uri === '/admin/login' && $method === 'POST') {
        login_admin();
        return;
    }

    if ($uri === '/admin/logout') {
        Auth::logout();
        flash('success', 'Sessão encerrada.');
        redirect('/admin/login');
    }

    if ($uri === '/admin/forgot-password' && $method === 'GET') {
        render('admin/forgot-password', ['title' => 'Recuperar acesso'], 'auth-layout');
        return;
    }

    if ($uri === '/admin/forgot-password' && $method === 'POST') {
        handle_forgot_password();
        return;
    }

    if ($uri === '/admin/reset-password' && $method === 'GET') {
        render('admin/reset-password', ['title' => 'Nova senha'], 'auth-layout');
        return;
    }

    if ($uri === '/admin/reset-password' && $method === 'POST') {
        handle_reset_password();
        return;
    }

    if ($uri === '/admin' || $uri === '/admin/dashboard') {
        require_login();
        show_dashboard();
        return;
    }

    if ($uri === '/admin/empresa' && $method === 'GET') {
        require_login();
        show_company_form();
        return;
    }

    if ($uri === '/admin/empresa' && $method === 'POST') {
        require_login();
        update_company();
        return;
    }

    if ($uri === '/admin/agendamentos' && $method === 'GET') {
        require_login();
        show_schedules();
        return;
    }

    if ($uri === '/admin/agendamentos' && $method === 'POST') {
        require_login();
        create_schedule();
        return;
    }

    if ($uri === '/admin/clientes' && $method === 'GET') {
        require_login();
        show_clients();
        return;
    }

    if ($uri === '/admin/clientes' && $method === 'POST') {
        require_login();
        save_client_record();
        return;
    }

    if (preg_match('#^/admin/clientes/(\d+)/excluir$#', $uri, $matches) && $method === 'POST') {
        require_login();
        delete_client_record((int) $matches[1]);
        return;
    }

    if (preg_match('#^/admin/os/(\d+)$#', $uri, $matches) && $method === 'GET') {
        require_login();
        show_os((int) $matches[1]);
        return;
    }

    if (preg_match('#^/admin/os/(\d+)/status$#', $uri, $matches) && $method === 'POST') {
        require_login();
        toggle_service_order_status((int) $matches[1]);
        return;
    }

    if (preg_match('#^/admin/os/(\d+)/excluir$#', $uri, $matches) && $method === 'POST') {
        require_login();
        delete_service_order((int) $matches[1]);
        return;
    }

    http_response_code(404);
    echo 'Página não encontrada.';
}

function show_home(): void
{
    $company = company_data();
    $services = default_services_catalog();
    $media = site_media();
    $pageTitle = 'Lava Jato em Guanhães MG | Pelicano Lava-Jato';
    $metaDescription = 'Lava jato em Guanhães MG com lavagem simples, lavagem detalhada, limpeza interna, higienização de bancos, polimento automotivo, enceramento e agendamento pelo WhatsApp.';

    render('home', compact('company', 'services', 'media', 'pageTitle', 'metaDescription'));
}

function login_admin(): void
{
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    store_old($_POST);

    if ($username === '' || $password === '') {
        flash('error', 'Preencha usuário e senha.');
        redirect('/admin/login');
    }

    if (!Auth::attempt($username, $password)) {
        flash('error', 'Credenciais inválidas.');
        redirect('/admin/login');
    }

    clear_old();
    redirect('/admin/dashboard');
}

function handle_forgot_password(): void
{
    $email = trim($_POST['email'] ?? '');
    store_old($_POST);

    if ($email === '') {
        flash('error', 'Informe o e-mail cadastrado.');
        redirect('/admin/forgot-password');
    }

    $token = Auth::createResetToken($email);

    if ($token === null) {
        flash('error', 'Nenhum administrador com esse e-mail.');
        redirect('/admin/forgot-password');
    }

    $link = config()['app_url'] . '/admin/reset-password?token=' . urlencode($token);

    if (config()['mail']['enabled']) {
        try {
            Mailer::sendResetLink($email, $link);
            flash('success', 'E-mail de redefinição enviado com sucesso.');
        } catch (Throwable $exception) {
            flash('error', 'Falha ao enviar e-mail: ' . $exception->getMessage());
        }
    } else {
        // Em ambiente local sem SMTP configurado, o link aparece em tela para teste.
        flash('success', 'Token gerado. Use este link para redefinir a senha: ' . $link);
    }

    clear_old();
    redirect('/admin/forgot-password');
}

function handle_reset_password(): void
{
    $token = trim($_POST['token'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirm = (string) ($_POST['password_confirmation'] ?? '');
    store_old($_POST);

    if ($token === '' || $password === '' || $passwordConfirm === '') {
        flash('error', 'Preencha todos os campos.');
        redirect('/admin/reset-password?token=' . urlencode($token));
    }

    if ($password !== $passwordConfirm) {
        flash('error', 'A confirmação da senha não confere.');
        redirect('/admin/reset-password?token=' . urlencode($token));
    }

    if (strlen($password) < 8) {
        flash('error', 'Use uma senha com no mínimo 8 caracteres.');
        redirect('/admin/reset-password?token=' . urlencode($token));
    }

    if (!Auth::resetPassword($token, $password)) {
        flash('error', 'Token inválido ou expirado.');
        redirect('/admin/reset-password?token=' . urlencode($token));
    }

    clear_old();
    flash('success', 'Senha atualizada. Faça login novamente.');
    redirect('/admin/login');
}

function show_dashboard(): void
{
    $selectedDate = selected_admin_date();
    $stats = [
        'agendamentos_hoje' => count_schedules_by_date($selectedDate),
        'clientes' => (int) db()->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
        'veiculos' => (int) db()->query('SELECT COUNT(*) FROM vehicles')->fetchColumn(),
        'os_emitidas' => (int) db()->query('SELECT COUNT(*) FROM service_orders')->fetchColumn(),
    ];

    $todaySchedules = fetch_schedules_with_orders($selectedDate);
    $availableDates = schedule_history_dates();

    render('admin/dashboard', compact('stats', 'todaySchedules', 'selectedDate', 'availableDates') + ['title' => 'Painel administrativo']);
}

function show_company_form(): void
{
    ensure_default_services_catalog();
    render('admin/company', [
        'title' => 'Dados da empresa',
        'company' => company_data(),
        'services' => db()->query('SELECT id, name, description, base_price FROM services ORDER BY id ASC')->fetchAll(),
    ]);
}

function show_clients(): void
{
    $editingClient = null;

    if (!empty($_GET['edit'])) {
        $editingClient = find_client_by_id((int) $_GET['edit']);
    }

    render('admin/clients', [
        'title' => 'Clientes',
        'clients' => fetch_clients_overview(),
        'editingClient' => $editingClient,
    ]);
}

function save_client_record(): void
{
    $clientId = (int) ($_POST['client_id'] ?? 0);
    $payload = [
        'client_name' => trim($_POST['client_name'] ?? ''),
        'client_phone' => trim($_POST['client_phone'] ?? ''),
        'client_address' => trim($_POST['client_address'] ?? ''),
        'client_district' => trim($_POST['client_district'] ?? ''),
        'client_city' => trim($_POST['client_city'] ?? 'Guanhães - MG'),
        'client_zipcode' => trim($_POST['client_zipcode'] ?? ''),
    ];

    store_old($_POST);

    if ($payload['client_name'] === '') {
        flash('error', 'Informe pelo menos o nome do cliente.');
        redirect('/admin/clientes' . ($clientId > 0 ? '?edit=' . $clientId : ''));
    }

    if ($clientId > 0) {
        update_client($clientId, $payload);
        clear_old();
        flash('success', 'Cliente atualizado com sucesso.');
        redirect('/admin/clientes');
    }

    insert_client($payload);
    clear_old();
    flash('success', 'Cliente cadastrado com sucesso.');
    redirect('/admin/clientes');
}

function delete_client_record(int $clientId): void
{
    $client = find_client_by_id($clientId);

    if (!$client) {
        flash('error', 'Cliente não encontrado para exclusão.');
        redirect('/admin/clientes');
    }

    $stmt = db()->prepare(
        'SELECT
            (SELECT COUNT(*) FROM schedules WHERE client_id = :client_id) AS schedules_count,
            (SELECT COUNT(*) FROM vehicles WHERE client_id = :client_id) AS vehicles_count'
    );
    $stmt->execute(['client_id' => $clientId]);
    $usage = $stmt->fetch() ?: ['schedules_count' => 0, 'vehicles_count' => 0];

    if ((int) $usage['schedules_count'] > 0 || (int) $usage['vehicles_count'] > 0) {
        flash('error', 'Este cliente não pode ser excluído porque já possui vínculos com veículos ou atendimentos.');
        redirect('/admin/clientes');
    }

    $deleteClient = db()->prepare('DELETE FROM clients WHERE id = :id');
    $deleteClient->execute(['id' => $clientId]);

    flash('success', 'Cliente excluído com sucesso.');
    redirect('/admin/clientes');
}

function update_company(): void
{
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'headline' => trim($_POST['headline'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'google_maps_url' => trim($_POST['google_maps_url'] ?? ''),
        'whatsapp' => trim($_POST['whatsapp'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'cnpj' => trim($_POST['cnpj'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'site_url' => trim($_POST['site_url'] ?? ''),
        'opening_hours' => trim($_POST['opening_hours'] ?? ''),
    ];
    $servicePayload = normalize_company_service_payload($_POST);

    store_old($data + $servicePayload);

    foreach (['name', 'address', 'google_maps_url', 'whatsapp'] as $required) {
        if ($data[$required] === '') {
            flash('error', 'Preencha os campos obrigatórios da empresa.');
            redirect('/admin/empresa');
        }
    }

    foreach ($servicePayload['service_names'] as $index => $serviceName) {
        $markedDelete = (($servicePayload['service_delete'][$index] ?? '0') === '1');
        $price = trim($servicePayload['service_prices'][$index] ?? '');
        $isBlankNewRow = $serviceName === ''
            && $price === ''
            && trim($servicePayload['service_ids'][$index] ?? '') === '';

        if ($markedDelete || $isBlankNewRow) {
            continue;
        }

        if ($serviceName === '') {
            flash('error', 'Todo serviço salvo precisa ter nome.');
            redirect('/admin/empresa');
        }
    }

    db()->beginTransaction();

    try {
        $companyExists = (int) db()->query('SELECT COUNT(*) FROM company WHERE id = 1')->fetchColumn() > 0;

        if ($companyExists) {
            $sql = 'UPDATE company
                    SET name = :name, headline = :headline, description = :description, address = :address,
                        google_maps_url = :google_maps_url, whatsapp = :whatsapp, phone = :phone, cnpj = :cnpj,
                        email = :email, site_url = :site_url, opening_hours = :opening_hours, updated_at = NOW()
                    WHERE id = 1';

            db()->prepare($sql)->execute($data);
        } else {
            $sql = 'INSERT INTO company
                    (id, name, headline, description, address, google_maps_url, whatsapp, phone, cnpj, email, site_url, opening_hours, updated_at)
                    VALUES
                    (1, :name, :headline, :description, :address, :google_maps_url, :whatsapp, :phone, :cnpj, :email, :site_url, :opening_hours, NOW())';

            db()->prepare($sql)->execute($data);
        }

        sync_company_services($servicePayload);

        db()->commit();
    } catch (Throwable $exception) {
        db()->rollBack();
        throw $exception;
    }

    clear_old();
    flash('success', 'Dados da empresa e catálogo de serviços atualizados.');
    redirect('/admin/empresa');
}

function normalize_company_service_payload(array $input): array
{
    return [
        'service_ids' => is_array($input['service_ids'] ?? null) ? $input['service_ids'] : [],
        'service_names' => is_array($input['service_names'] ?? null) ? $input['service_names'] : [],
        'service_prices' => is_array($input['service_prices'] ?? null) ? $input['service_prices'] : [],
        'service_delete' => is_array($input['service_delete'] ?? null) ? $input['service_delete'] : [],
    ];
}

function sync_company_services(array $servicePayload): void
{
    $updateService = db()->prepare(
        'UPDATE services
         SET name = :name, base_price = :base_price, active = 1
         WHERE id = :id'
    );
    $insertService = db()->prepare(
        'INSERT INTO services (name, description, base_price, active)
         VALUES (:name, "", :base_price, 1)'
    );
    $deleteService = db()->prepare('DELETE FROM services WHERE id = :id');

    foreach ($servicePayload['service_names'] as $index => $serviceName) {
        $serviceId = (int) ($servicePayload['service_ids'][$index] ?? 0);
        $name = trim((string) $serviceName);
        $price = trim((string) ($servicePayload['service_prices'][$index] ?? ''));
        $markedDelete = (($servicePayload['service_delete'][$index] ?? '0') === '1');
        $isBlankNewRow = $serviceId === 0 && $name === '' && $price === '';

        if ($markedDelete) {
            if ($serviceId > 0) {
                $deleteService->execute(['id' => $serviceId]);
            }
            continue;
        }

        if ($isBlankNewRow) {
            continue;
        }

        $payload = [
            'name' => $name,
            'base_price' => $price === '' ? 0 : normalize_money_input($price),
        ];

        if ($serviceId > 0) {
            $updateService->execute($payload + ['id' => $serviceId]);
            continue;
        }

        $insertService->execute($payload);
    }
}

function show_schedules(): void
{
    ensure_default_services_catalog();
    $selectedDate = selected_admin_date();
    $services = services_data();
    $schedules = fetch_schedules_with_orders($selectedDate);
    $clientDirectory = fetch_client_directory();
    $editingOrder = null;
    $clientPrefill = null;

    if (!empty($_GET['edit'])) {
        $editingOrder = find_schedule_for_edit((int) $_GET['edit']);
    }

    if ($editingOrder === null && !empty($_GET['client'])) {
        $clientPrefill = find_client_by_id((int) $_GET['client']);
    }

    render('admin/schedules', compact('services', 'schedules', 'selectedDate', 'editingOrder', 'clientDirectory', 'clientPrefill') + ['title' => 'Agendamentos']);
}

function selected_admin_date(): string
{
    $date = trim($_GET['date'] ?? '');

    if ($date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1) {
        return $date;
    }

    return date('Y-m-d');
}

function count_schedules_by_date(string $date): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM schedules WHERE scheduled_date = :scheduled_date');
    $stmt->execute(['scheduled_date' => $date]);

    return (int) $stmt->fetchColumn();
}

function fetch_schedules_with_orders(string $date): array
{
    $stmt = db()->prepare(
        'SELECT s.*, c.name AS client_name, c.phone AS client_phone, v.brand, v.model, v.plate,
                so.id AS service_order_id, so.order_number, so.status AS service_order_status
         FROM schedules s
         INNER JOIN clients c ON c.id = s.client_id
         INNER JOIN vehicles v ON v.id = s.vehicle_id
         LEFT JOIN service_orders so ON so.schedule_id = s.id
         WHERE s.scheduled_date = :scheduled_date
         ORDER BY s.scheduled_time ASC, s.id DESC'
    );
    $stmt->execute(['scheduled_date' => $date]);

    return $stmt->fetchAll();
}

function fetch_client_directory(): array
{
    $rows = db()->query(
        'SELECT id, name, phone, address, district, city, zipcode
         FROM clients
         ORDER BY id DESC'
    )->fetchAll();

    $directory = [];
    $seen = [];

    foreach ($rows as $row) {
        $nameKey = strtolower(trim((string) ($row['name'] ?? '')));
        $phoneKey = slug_phone((string) ($row['phone'] ?? ''));
        $identity = $phoneKey !== '' ? $phoneKey : $nameKey;

        if ($identity === '' || isset($seen[$identity])) {
            continue;
        }

        $seen[$identity] = true;
        $directory[] = [
            'id' => (int) $row['id'],
            'name' => (string) ($row['name'] ?? ''),
            'phone' => (string) ($row['phone'] ?? ''),
            'address' => (string) ($row['address'] ?? ''),
            'district' => (string) ($row['district'] ?? ''),
            'city' => (string) ($row['city'] ?? ''),
            'zipcode' => (string) ($row['zipcode'] ?? ''),
        ];
    }

    usort(
        $directory,
        static fn(array $a, array $b): int => strcasecmp($a['name'], $b['name'])
    );

    return $directory;
}

function fetch_clients_overview(): array
{
    return db()->query(
        'SELECT c.*,
                COUNT(DISTINCT v.id) AS vehicles_count,
                COUNT(DISTINCT s.id) AS schedules_count
         FROM clients c
         LEFT JOIN vehicles v ON v.client_id = c.id
         LEFT JOIN schedules s ON s.client_id = c.id
         GROUP BY c.id
         ORDER BY c.id DESC'
    )->fetchAll();
}

function schedule_history_dates(): array
{
    return db()->query(
        'SELECT DISTINCT scheduled_date
         FROM schedules
         ORDER BY scheduled_date DESC
         LIMIT 30'
    )->fetchAll(PDO::FETCH_COLUMN);
}

function create_schedule(): void
{
    if (!empty($_POST['schedule_id'])) {
        update_schedule();
        return;
    }

    $payload = schedule_payload();
    store_old($_POST);

    if (!validate_schedule_payload($payload)) {
        redirect(schedule_form_redirect_path(null, $payload['scheduled_date']));
    }

    db()->beginTransaction();

    try {
        $clientId = resolve_client_id($payload);
        $vehicleId = insert_vehicle($clientId, $payload);
        $scheduleId = insert_schedule($clientId, $vehicleId, $payload);
        $selectedServices = fetch_services_by_ids($payload['service_ids']);
        $summary = create_service_order($scheduleId, $selectedServices, $payload);

        db()->commit();
        clear_old();
        flash('success', 'Agendamento criado. OS #' . $summary['order_number'] . ' pronta para impressão.');
        redirect('/admin/os/' . $summary['id']);
    } catch (Throwable $exception) {
        db()->rollBack();
        throw $exception;
    }
}

function update_schedule(): void
{
    $payload = [
        ...schedule_payload(),
        'schedule_id' => (int) ($_POST['schedule_id'] ?? 0),
        'service_order_id' => (int) ($_POST['service_order_id'] ?? 0),
    ];
    store_old($_POST);

    if ($payload['schedule_id'] <= 0) {
        flash('error', 'OS inválida para edição.');
        redirect(schedule_form_redirect_path(null, $payload['scheduled_date']));
    }

    if (!validate_schedule_payload($payload)) {
        redirect(schedule_form_redirect_path($payload['schedule_id'], $payload['scheduled_date']));
    }

    db()->beginTransaction();

    try {
        $schedule = find_schedule_for_edit($payload['schedule_id']);
        if (!$schedule) {
            throw new RuntimeException('Agendamento não encontrado para edição.');
        }

        $originalClientId = (int) $schedule['client_id'];
        $clientId = resolve_client_id($payload, $originalClientId);

        update_vehicle((int) $schedule['vehicle_id'], $clientId, $payload);
        update_schedule_record($payload['schedule_id'], $clientId, (int) $schedule['vehicle_id'], $payload);
        $selectedServices = fetch_services_by_ids($payload['service_ids']);
        $serviceOrderId = $payload['service_order_id'] > 0 ? $payload['service_order_id'] : (int) ($schedule['service_order_id'] ?? 0);

        if ($serviceOrderId > 0) {
            update_service_order($serviceOrderId, $selectedServices, $payload);
        } else {
            $summary = create_service_order($payload['schedule_id'], $selectedServices, $payload);
            $serviceOrderId = $summary['id'];
        }

        if ($clientId !== $originalClientId) {
            cleanup_client_if_unused($originalClientId);
        }

        db()->commit();
        clear_old();
        flash('success', 'OS atualizada com sucesso.');
        redirect('/admin/os/' . $serviceOrderId);
    } catch (Throwable $exception) {
        db()->rollBack();
        throw $exception;
    }
}

function schedule_payload(): array
{
    return [
        'client_id' => (int) ($_POST['client_id'] ?? 0),
        'client_name' => trim($_POST['client_name'] ?? ''),
        'client_phone' => trim($_POST['client_phone'] ?? ''),
        'client_address' => trim($_POST['client_address'] ?? ''),
        'client_district' => trim($_POST['client_district'] ?? ''),
        'client_city' => trim($_POST['client_city'] ?? 'Guanhães - MG'),
        'client_zipcode' => trim($_POST['client_zipcode'] ?? ''),
        'brand' => trim($_POST['brand'] ?? ''),
        'model' => trim($_POST['model'] ?? ''),
        'color' => trim($_POST['color'] ?? ''),
        'plate' => trim($_POST['plate'] ?? ''),
        'engine' => trim($_POST['engine'] ?? ''),
        'km_in' => trim($_POST['km_in'] ?? '0'),
        'km_out' => trim($_POST['km_out'] ?? '0'),
        'scheduled_date' => trim($_POST['scheduled_date'] ?? date('Y-m-d')),
        'scheduled_time' => trim($_POST['scheduled_time'] ?? ''),
        'complaint' => trim($_POST['complaint'] ?? 'LIMPEZA'),
        'notes' => trim($_POST['notes'] ?? ''),
        'payment_method' => trim($_POST['payment_method'] ?? 'PIX'),
        'cash_change_amount' => trim($_POST['cash_change_amount'] ?? ''),
        'payment_due_date' => trim($_POST['payment_due_date'] ?? ''),
        'employee_name' => trim($_POST['employee_name'] ?? ''),
        'service_ids' => $_POST['service_ids'] ?? [],
    ];
}

function validate_schedule_payload(array $payload): bool
{
    return true;
}

function schedule_form_redirect_path(?int $scheduleId, string $date): string
{
    $query = ['date' => $date !== '' ? $date : date('Y-m-d')];

    if ($scheduleId !== null && $scheduleId > 0) {
        $query['edit'] = (string) $scheduleId;
    }

    return '/admin/agendamentos?' . http_build_query($query);
}

function fetch_services_by_ids(array $serviceIds): array
{
    $filteredIds = array_values(array_filter(array_map('intval', $serviceIds)));

    if ($filteredIds === []) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($filteredIds), '?'));
    $stmt = db()->prepare("SELECT * FROM services WHERE id IN ($placeholders)");
    $stmt->execute($filteredIds);

    return $stmt->fetchAll();
}

function find_schedule_for_edit(int $scheduleId): ?array
{
    $stmt = db()->prepare(
        'SELECT s.*, c.name AS client_name, c.phone AS client_phone, c.address AS client_address,
                c.district AS client_district, c.city AS client_city, c.zipcode AS client_zipcode,
                v.brand, v.model, v.color, v.plate, v.engine, v.km_in, v.km_out,
                so.id AS service_order_id, so.order_number, so.payment_method, so.cash_change_amount,
                so.payment_due_date, so.employee_name
         FROM schedules s
         INNER JOIN clients c ON c.id = s.client_id
         INNER JOIN vehicles v ON v.id = s.vehicle_id
         LEFT JOIN service_orders so ON so.schedule_id = s.id
         WHERE s.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $scheduleId]);
    $schedule = $stmt->fetch();

    if (!$schedule) {
        return null;
    }

    $itemsStmt = db()->prepare('SELECT service_name FROM service_order_items WHERE service_order_id = :id');
    $itemsStmt->execute(['id' => (int) ($schedule['service_order_id'] ?? 0)]);
    $schedule['selected_service_names'] = $itemsStmt->fetchAll(PDO::FETCH_COLUMN);

    return $schedule;
}

function find_client_by_id(int $clientId): ?array
{
    $stmt = db()->prepare(
        'SELECT id, name AS client_name, phone AS client_phone, address AS client_address,
                district AS client_district, city AS client_city, zipcode AS client_zipcode
         FROM clients
         WHERE id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $clientId]);
    $client = $stmt->fetch();

    return $client ?: null;
}

function insert_client(array $payload): int
{
    $insertClient = db()->prepare(
        'INSERT INTO clients (name, phone, address, district, city, zipcode, created_at)
         VALUES (:name, :phone, :address, :district, :city, :zipcode, NOW())'
    );
    $insertClient->execute([
        'name' => $payload['client_name'],
        'phone' => $payload['client_phone'],
        'address' => $payload['client_address'],
        'district' => $payload['client_district'],
        'city' => $payload['client_city'],
        'zipcode' => $payload['client_zipcode'],
    ]);

    return (int) db()->lastInsertId();
}

function resolve_client_id(array $payload, ?int $currentClientId = null): int
{
    $selectedClientId = (int) ($payload['client_id'] ?? 0);

    if ($selectedClientId > 0) {
        update_client($selectedClientId, $payload);
        return $selectedClientId;
    }

    if ($currentClientId !== null && $currentClientId > 0) {
        update_client($currentClientId, $payload);
        return $currentClientId;
    }

    return insert_client($payload);
}

function update_client(int $clientId, array $payload): void
{
    $stmt = db()->prepare(
        'UPDATE clients
         SET name = :name, phone = :phone, address = :address, district = :district, city = :city, zipcode = :zipcode
         WHERE id = :id'
    );
    $stmt->execute([
        'id' => $clientId,
        'name' => $payload['client_name'],
        'phone' => $payload['client_phone'],
        'address' => $payload['client_address'],
        'district' => $payload['client_district'],
        'city' => $payload['client_city'],
        'zipcode' => $payload['client_zipcode'],
    ]);
}

function insert_vehicle(int $clientId, array $payload): int
{
    $insertVehicle = db()->prepare(
        'INSERT INTO vehicles (client_id, brand, model, color, plate, engine, km_in, km_out, created_at)
         VALUES (:client_id, :brand, :model, :color, :plate, :engine, :km_in, :km_out, NOW())'
    );
    $insertVehicle->execute([
        'client_id' => $clientId,
        'brand' => $payload['brand'],
        'model' => $payload['model'],
        'color' => $payload['color'],
        'plate' => strtoupper($payload['plate']),
        'engine' => $payload['engine'],
        'km_in' => (int) $payload['km_in'],
        'km_out' => (int) $payload['km_out'],
    ]);

    return (int) db()->lastInsertId();
}

function update_vehicle(int $vehicleId, int $clientId, array $payload): void
{
    $stmt = db()->prepare(
        'UPDATE vehicles
         SET client_id = :client_id, brand = :brand, model = :model, color = :color,
             plate = :plate, engine = :engine, km_in = :km_in, km_out = :km_out
         WHERE id = :id'
    );
    $stmt->execute([
        'id' => $vehicleId,
        'client_id' => $clientId,
        'brand' => $payload['brand'],
        'model' => $payload['model'],
        'color' => $payload['color'],
        'plate' => strtoupper($payload['plate']),
        'engine' => $payload['engine'],
        'km_in' => (int) $payload['km_in'],
        'km_out' => (int) $payload['km_out'],
    ]);
}

function insert_schedule(int $clientId, int $vehicleId, array $payload): int
{
    $insertSchedule = db()->prepare(
        'INSERT INTO schedules
            (client_id, vehicle_id, scheduled_date, scheduled_time, status, complaint, notes, created_at)
         VALUES
            (:client_id, :vehicle_id, :scheduled_date, :scheduled_time, :status, :complaint, :notes, NOW())'
    );
    $insertSchedule->execute([
        'client_id' => $clientId,
        'vehicle_id' => $vehicleId,
        'scheduled_date' => $payload['scheduled_date'],
        'scheduled_time' => $payload['scheduled_time'] ?: null,
        'status' => 'agendado',
        'complaint' => $payload['complaint'],
        'notes' => $payload['notes'],
    ]);

    return (int) db()->lastInsertId();
}

function update_schedule_record(int $scheduleId, int $clientId, int $vehicleId, array $payload): void
{
    $stmt = db()->prepare(
        'UPDATE schedules
         SET client_id = :client_id, vehicle_id = :vehicle_id, scheduled_date = :scheduled_date,
             scheduled_time = :scheduled_time, status = :status, complaint = :complaint, notes = :notes
         WHERE id = :id'
    );
    $stmt->execute([
        'id' => $scheduleId,
        'client_id' => $clientId,
        'vehicle_id' => $vehicleId,
        'scheduled_date' => $payload['scheduled_date'],
        'scheduled_time' => $payload['scheduled_time'] ?: null,
        'status' => 'agendado',
        'complaint' => $payload['complaint'],
        'notes' => $payload['notes'],
    ]);
}

function create_service_order(int $scheduleId, array $services, array $payload): array
{
    $subtotal = 0.0;

    foreach ($services as $service) {
        $subtotal += (float) $service['base_price'];
    }

    $orderNumber = (string) ((int) db()->query('SELECT IFNULL(MAX(CAST(order_number AS UNSIGNED)), 0) + 1 FROM service_orders')->fetchColumn());

    $insertOrder = db()->prepare(
        'INSERT INTO service_orders
            (schedule_id, order_number, opened_at, closed_at, km_in, km_out, complaint, notes,
             service_total, gross_total, discount_total, addition_total, final_total, status,
             payment_method, cash_change_amount, payment_due_date, employee_name, created_at)
         VALUES
            (:schedule_id, :order_number, NOW(), NULL, :km_in, :km_out, :complaint, :notes,
             :service_total, :gross_total, 0, 0, :final_total, :status, :payment_method, :cash_change_amount,
             :payment_due_date, :employee_name, NOW())'
    );

    $insertOrder->execute([
        'schedule_id' => $scheduleId,
        'order_number' => $orderNumber,
        'km_in' => (int) $payload['km_in'],
        'km_out' => (int) $payload['km_out'],
        'complaint' => $payload['complaint'],
        'notes' => $payload['notes'],
        'service_total' => $subtotal,
        'gross_total' => $subtotal,
        'final_total' => $subtotal,
        'status' => 'em andamento',
        'payment_method' => $payload['payment_method'],
        'cash_change_amount' => normalize_cash_change_amount($payload),
        'payment_due_date' => $payload['payment_due_date'] ?: $payload['scheduled_date'],
        'employee_name' => $payload['employee_name'],
    ]);

    $serviceOrderId = (int) db()->lastInsertId();

    insert_service_order_items($serviceOrderId, $services, $payload['employee_name']);

    return ['id' => $serviceOrderId, 'order_number' => $orderNumber];
}

function update_service_order(int $serviceOrderId, array $services, array $payload): void
{
    $subtotal = 0.0;

    foreach ($services as $service) {
        $subtotal += (float) $service['base_price'];
    }

    $stmt = db()->prepare(
        'UPDATE service_orders
         SET km_in = :km_in, km_out = :km_out, complaint = :complaint, notes = :notes,
             service_total = :service_total, gross_total = :gross_total, discount_total = 0,
             addition_total = 0, final_total = :final_total, payment_method = :payment_method,
             cash_change_amount = :cash_change_amount, payment_due_date = :payment_due_date, employee_name = :employee_name,
             closed_at = CASE
                WHEN status = "concluido" THEN COALESCE(closed_at, NOW())
                ELSE NULL
             END
         WHERE id = :id'
    );
    $stmt->execute([
        'id' => $serviceOrderId,
        'km_in' => (int) $payload['km_in'],
        'km_out' => (int) $payload['km_out'],
        'complaint' => $payload['complaint'],
        'notes' => $payload['notes'],
        'service_total' => $subtotal,
        'gross_total' => $subtotal,
        'final_total' => $subtotal,
        'payment_method' => $payload['payment_method'],
        'cash_change_amount' => normalize_cash_change_amount($payload),
        'payment_due_date' => $payload['payment_due_date'] ?: $payload['scheduled_date'],
        'employee_name' => $payload['employee_name'],
    ]);

    $deleteItems = db()->prepare('DELETE FROM service_order_items WHERE service_order_id = :id');
    $deleteItems->execute(['id' => $serviceOrderId]);

    insert_service_order_items($serviceOrderId, $services, $payload['employee_name']);
}

function insert_service_order_items(int $serviceOrderId, array $services, string $employeeName): void
{

    $insertItem = db()->prepare(
        'INSERT INTO service_order_items
            (service_order_id, service_name, quantity, unit_price, discount_amount, addition_amount, total_amount, employee_name)
         VALUES
            (:service_order_id, :service_name, :quantity, :unit_price, 0, 0, :total_amount, :employee_name)'
    );

    foreach ($services as $service) {
        $insertItem->execute([
            'service_order_id' => $serviceOrderId,
            'service_name' => $service['name'],
            'quantity' => 1,
            'unit_price' => $service['base_price'],
            'total_amount' => $service['base_price'],
            'employee_name' => $employeeName,
        ]);
    }
}

function show_os(int $serviceOrderId): void
{
    $stmt = db()->prepare(
        'SELECT so.*, s.scheduled_date, s.scheduled_time, c.name AS client_name, c.phone AS client_phone,
                c.address AS client_address, c.district AS client_district, c.city AS client_city, c.zipcode AS client_zipcode,
                v.brand, v.model, v.color, v.plate, v.engine, company.name AS company_name, company.address AS company_address,
                company.phone AS company_phone, company.cnpj AS company_cnpj, company.email AS company_email, company.site_url AS company_site
         FROM service_orders so
         INNER JOIN schedules s ON s.id = so.schedule_id
         INNER JOIN clients c ON c.id = s.client_id
         INNER JOIN vehicles v ON v.id = s.vehicle_id
         LEFT JOIN company ON company.id = 1
         WHERE so.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $serviceOrderId]);
    $order = $stmt->fetch();

    if (!$order) {
        http_response_code(404);
        echo 'OS não encontrada.';
        return;
    }

    $itemsStmt = db()->prepare('SELECT * FROM service_order_items WHERE service_order_id = :id ORDER BY id ASC');
    $itemsStmt->execute(['id' => $serviceOrderId]);
    $items = $itemsStmt->fetchAll();

    render('admin/service-order', [
        'title' => 'Ordem de serviço',
        'order' => $order,
        'items' => $items,
    ], 'print-layout');
}

function toggle_service_order_status(int $serviceOrderId): void
{
    $redirectDate = trim($_POST['date'] ?? date('Y-m-d'));

    $stmt = db()->prepare('SELECT id, status FROM service_orders WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $serviceOrderId]);
    $order = $stmt->fetch();

    if (!$order) {
        flash('error', 'OS não encontrada para alterar status.');
        redirect('/admin/agendamentos?date=' . urlencode($redirectDate));
    }

    $currentStatus = (string) ($order['status'] ?? 'em andamento');
    $nextStatus = $currentStatus === 'concluido' ? 'em andamento' : 'concluido';

    $update = db()->prepare(
        'UPDATE service_orders
         SET status = :status,
             closed_at = CASE
                WHEN :status = "concluido" THEN NOW()
                ELSE NULL
             END
         WHERE id = :id'
    );
    $update->execute([
        'id' => $serviceOrderId,
        'status' => $nextStatus,
    ]);

    flash('success', 'Status da OS alterado para ' . $nextStatus . '.');
    redirect('/admin/agendamentos?date=' . urlencode($redirectDate));
}

function delete_service_order(int $serviceOrderId): void
{
    $redirectDate = trim($_POST['date'] ?? date('Y-m-d'));

    $stmt = db()->prepare(
        'SELECT so.id, so.schedule_id, s.client_id, s.vehicle_id
         FROM service_orders so
         INNER JOIN schedules s ON s.id = so.schedule_id
         WHERE so.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $serviceOrderId]);
    $order = $stmt->fetch();

    if (!$order) {
        flash('error', 'OS não encontrada para exclusão.');
        redirect('/admin/agendamentos?date=' . urlencode($redirectDate));
    }

    db()->beginTransaction();

    try {
        $deleteItems = db()->prepare('DELETE FROM service_order_items WHERE service_order_id = :id');
        $deleteItems->execute(['id' => $serviceOrderId]);

        $deleteOrder = db()->prepare('DELETE FROM service_orders WHERE id = :id');
        $deleteOrder->execute(['id' => $serviceOrderId]);

        $deleteSchedule = db()->prepare('DELETE FROM schedules WHERE id = :id');
        $deleteSchedule->execute(['id' => (int) $order['schedule_id']]);

        cleanup_vehicle_if_unused((int) $order['vehicle_id']);
        cleanup_client_if_unused((int) $order['client_id']);

        db()->commit();
        flash('success', 'OS excluída com sucesso.');
    } catch (Throwable $exception) {
        db()->rollBack();
        throw $exception;
    }

    redirect('/admin/agendamentos?date=' . urlencode($redirectDate));
}

function cleanup_vehicle_if_unused(int $vehicleId): void
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM schedules WHERE vehicle_id = :vehicle_id');
    $stmt->execute(['vehicle_id' => $vehicleId]);

    if ((int) $stmt->fetchColumn() > 0) {
        return;
    }

    $deleteVehicle = db()->prepare('DELETE FROM vehicles WHERE id = :id');
    $deleteVehicle->execute(['id' => $vehicleId]);
}

function cleanup_client_if_unused(int $clientId): void
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM schedules WHERE client_id = :client_id');
    $stmt->execute(['client_id' => $clientId]);

    if ((int) $stmt->fetchColumn() > 0) {
        return;
    }

    $deleteClient = db()->prepare('DELETE FROM clients WHERE id = :id');
    $deleteClient->execute(['id' => $clientId]);
}

function normalize_cash_change_amount(array $payload): ?float
{
    if (($payload['payment_method'] ?? '') !== 'DINHEIRO') {
        return null;
    }

    $value = trim((string) ($payload['cash_change_amount'] ?? ''));

    if ($value === '') {
        return null;
    }

    return normalize_money_input($value);
}

function ensure_service_order_status_column(): void
{
    static $checked = false;

    if ($checked) {
        return;
    }

    $checked = true;

    $columnExists = db()->query("SHOW COLUMNS FROM service_orders LIKE 'status'")->fetch();

    if (!$columnExists) {
        db()->exec("ALTER TABLE service_orders ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'em andamento' AFTER final_total");
    }

    db()->exec("UPDATE service_orders SET status = 'em andamento' WHERE status IS NULL OR status = ''");
}

function ensure_service_order_cash_change_column(): void
{
    static $checked = false;

    if ($checked) {
        return;
    }

    $checked = true;

    $columnExists = db()->query("SHOW COLUMNS FROM service_orders LIKE 'cash_change_amount'")->fetch();

    if (!$columnExists) {
        db()->exec("ALTER TABLE service_orders ADD COLUMN cash_change_amount DECIMAL(10,2) NULL AFTER payment_method");
    }
}
