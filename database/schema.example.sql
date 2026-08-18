CREATE TABLE company (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    headline VARCHAR(255) NULL,
    description TEXT NULL,
    address VARCHAR(255) NOT NULL,
    google_maps_url VARCHAR(500) NOT NULL,
    whatsapp VARCHAR(30) NOT NULL,
    phone VARCHAR(30) NULL,
    cnpj VARCHAR(30) NULL,
    email VARCHAR(255) NULL,
    site_url VARCHAR(255) NULL,
    opening_hours VARCHAR(255) NULL,
    updated_at DATETIME NULL
);

CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email_verified_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NULL
);

CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NULL,
    district VARCHAR(120) NULL,
    city VARCHAR(120) NULL,
    zipcode VARCHAR(20) NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE vehicles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    brand VARCHAR(120) NOT NULL,
    model VARCHAR(120) NOT NULL,
    color VARCHAR(80) NULL,
    plate VARCHAR(20) NOT NULL,
    engine VARCHAR(120) NULL,
    km_in INT NOT NULL DEFAULT 0,
    km_out INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_vehicles_client FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    base_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    scheduled_date DATE NOT NULL,
    scheduled_time TIME NULL,
    status VARCHAR(50) NOT NULL,
    complaint VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_schedules_client FOREIGN KEY (client_id) REFERENCES clients(id),
    CONSTRAINT fk_schedules_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

CREATE TABLE service_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    schedule_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL,
    opened_at DATETIME NOT NULL,
    closed_at DATETIME NULL,
    km_in INT NOT NULL DEFAULT 0,
    km_out INT NOT NULL DEFAULT 0,
    complaint VARCHAR(255) NULL,
    notes TEXT NULL,
    service_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    gross_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    discount_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    addition_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    final_total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(30) NOT NULL DEFAULT 'em andamento',
    payment_method VARCHAR(80) NULL,
    cash_change_amount DECIMAL(10,2) NULL,
    payment_due_date DATE NULL,
    employee_name VARCHAR(120) NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_service_orders_schedule FOREIGN KEY (schedule_id) REFERENCES schedules(id)
);

CREATE TABLE service_order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_order_id INT NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    addition_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    employee_name VARCHAR(120) NULL,
    CONSTRAINT fk_service_order_items_order FOREIGN KEY (service_order_id) REFERENCES service_orders(id)
);

CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    token VARCHAR(120) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_tokens_admin FOREIGN KEY (admin_id) REFERENCES admins(id)
);
