-- ============================================================
-- HEALIX - Smart Hospital Management System
-- Database: healix
-- Description: Full schema with tables and sample data
-- ============================================================

CREATE DATABASE IF NOT EXISTS healix;
USE healix;

-- ============================================================
-- TABLE: patients
-- Stores all registered patient information
-- ============================================================
CREATE TABLE IF NOT EXISTS patients (
    patient_id     INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100)    NOT NULL,
    age            INT             NOT NULL,
    gender         ENUM('Male','Female','Other') NOT NULL,
    phone          VARCHAR(20)     NOT NULL UNIQUE,
    blood_group    VARCHAR(5),
    address        TEXT,
    email          VARCHAR(100),
    created_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: doctors
-- Stores all registered doctor information
-- ============================================================
CREATE TABLE IF NOT EXISTS doctors (
    doctor_id      INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100)    NOT NULL,
    specialty      VARCHAR(100)    NOT NULL,
    phone          VARCHAR(20)     NOT NULL UNIQUE,
    email          VARCHAR(100),
    available_days VARCHAR(100)    DEFAULT 'Mon-Fri',
    created_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLE: appointments
-- Links patients with doctors for a scheduled visit
-- ============================================================
CREATE TABLE IF NOT EXISTS appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id     INT             NOT NULL,
    doctor_id      INT             NOT NULL,
    app_date       DATE            NOT NULL,
    app_time       TIME            DEFAULT '10:00:00',
    status         ENUM('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
    reason         VARCHAR(255),
    created_at     TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id)  REFERENCES doctors(doctor_id)  ON DELETE CASCADE
);

-- ============================================================
-- TABLE: prescriptions
-- Stores medication and advice given after an appointment
-- ============================================================
CREATE TABLE IF NOT EXISTS prescriptions (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id  INT            NOT NULL UNIQUE,
    medicines       TEXT           NOT NULL,
    notes           TEXT,
    created_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE: bills
-- Stores billing information linked to a patient
-- ============================================================
CREATE TABLE IF NOT EXISTS bills (
    bill_id         INT AUTO_INCREMENT PRIMARY KEY,
    patient_id      INT             NOT NULL,
    amount          DECIMAL(10,2)   NOT NULL,
    payment_status  ENUM('Unpaid','Paid','Partial') DEFAULT 'Unpaid',
    description     VARCHAR(255),
    bill_date       DATE            DEFAULT (CURRENT_DATE),
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE
);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

INSERT INTO patients (name, age, gender, phone, blood_group, address, email) VALUES
('Emma Johnson',    22, 'Female', '01711111111', 'A+',  'London, UK',      'emma@email.com'),
('Sophia Williams', 21, 'Female', '01722222222', 'B+',  'New York, USA',   'sophia@email.com'),
('Olivia Brown',    23, 'Female', '01733333333', 'O+',  'Toronto, Canada', 'olivia@email.com'),
('James Carter',    35, 'Male',   '01744444444', 'AB+', 'Sydney, AUS',     'james@email.com'),
('Liam Walker',     28, 'Male',   '01755555555', 'A-',  'Paris, France',   'liam@email.com');

INSERT INTO doctors (name, specialty, phone, email, available_days) VALUES
('Dr. Hasan Ali',    'Cardiology',    '01811111111', 'hasan@healix.com',  'Mon-Thu'),
('Dr. Rahman Khan',  'Dermatology',   '01822222222', 'rahman@healix.com', 'Mon-Fri'),
('Dr. Samiha Noor',  'Gynecology',    '01833333333', 'samiha@healix.com', 'Sun-Wed'),
('Dr. Arif Mahmud',  'Orthopedics',   '01844444444', 'arif@healix.com',   'Tue-Sat'),
('Dr. Tania Islam',  'Neurology',     '01855555555', 'tania@healix.com',  'Mon-Fri');

INSERT INTO appointments (patient_id, doctor_id, app_date, app_time, status, reason) VALUES
(1, 1, '2026-05-01', '09:00:00', 'Completed',  'Chest pain follow-up'),
(2, 2, '2026-05-02', '10:30:00', 'Completed',  'Skin rash'),
(3, 3, '2026-05-03', '11:00:00', 'Scheduled',  'Routine checkup'),
(4, 4, '2026-05-10', '14:00:00', 'Scheduled',  'Knee pain'),
(5, 5, '2026-05-12', '16:00:00', 'Cancelled',  'Headache assessment');

INSERT INTO prescriptions (appointment_id, medicines, notes) VALUES
(1, 'Aspirin 75mg - 1 tablet daily, Atorvastatin 20mg - 1 tablet at night', 'Avoid fatty foods. Exercise 30 min daily. Return in 4 weeks.'),
(2, 'Cetirizine 10mg - 1 tablet at night, Betnovate Cream - Apply twice daily', 'Avoid sun exposure. Use mild soap only.');

INSERT INTO bills (patient_id, amount, payment_status, description, bill_date) VALUES
(1, 1500.00, 'Paid',   'Cardiology Consultation + ECG', '2026-05-01'),
(2, 800.00,  'Paid',   'Dermatology Consultation',      '2026-05-02'),
(3, 600.00,  'Unpaid', 'Gynecology Consultation',       '2026-05-03'),
(4, 1200.00, 'Partial','Orthopedic Consultation + X-Ray','2026-05-10');
