<?php

$config = require 'config.php';
$pdo = $config['pdo']; 

try {
   
    echo "Starting transaction...\n";
    $pdo->beginTransaction();

    
    if (!$pdo->inTransaction()) {
        throw new Exception("Transaction not started.");
    }

  
    echo "Creating departments table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        );
    ");
    
    echo "Creating doctors table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS doctors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            department_id INT NOT NULL,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
        );
    ");
    
    echo "Creating patients table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS patients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            mobile VARCHAR(15) NOT NULL,
            appointment_date DATE NOT NULL,
            doctor_id INT NOT NULL,
            department_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
        );
    ");

   
    $departments = ['Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics'];
    echo "Inserting departments...\n";
    foreach ($departments as $department) {
        $stmt = $pdo->prepare('INSERT INTO departments (name) VALUES (:name)');
        $stmt->execute(['name' => $department]);
    }

 
    $stmt = $pdo->query('SELECT id, name FROM departments');
    $departmentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $departmentMap = array_column($departmentsData, 'id', 'name');

  
    $doctors = [
        ['name' => 'Dr. John Smith', 'department_name' => 'Cardiology'],
        ['name' => 'Dr. Emily Johnson', 'department_name' => 'Neurology'],
        ['name' => 'Dr. Michael Brown', 'department_name' => 'Orthopedics'],
        ['name' => 'Dr. Sarah White', 'department_name' => 'Pediatrics']
    ];
    
    echo "Inserting doctors...\n";
    foreach ($doctors as $doctor) {
        $departmentId = $departmentMap[$doctor['department_name']];
        $stmt = $pdo->prepare('INSERT INTO doctors (name, department_id) VALUES (:name, :department_id)');
        $stmt->execute(['name' => $doctor['name'], 'department_id' => $departmentId]);
    }

  
    $stmt = $pdo->query('SELECT id, name FROM doctors');
    $doctorsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $doctorMap = array_column($doctorsData, 'id', 'name');

    
    $patients = [
        ['name' => 'Alice Green', 'mobile' => '1234567890', 'appointment_date' => '2024-11-27', 'doctor_name' => 'Dr. John Smith', 'department_name' => 'Cardiology'],
        ['name' => 'Bob Adams', 'mobile' => '9876543210', 'appointment_date' => '2024-11-28', 'doctor_name' => 'Dr. Emily Johnson', 'department_name' => 'Neurology'],
        ['name' => 'Charlie Davis', 'mobile' => '5551234567', 'appointment_date' => '2024-11-29', 'doctor_name' => 'Dr. Michael Brown', 'department_name' => 'Orthopedics'],
        ['name' => 'Diana Clark', 'mobile' => '7771234567', 'appointment_date' => '2024-12-01', 'doctor_name' => 'Dr. Sarah White', 'department_name' => 'Pediatrics']
    ];

    echo "Inserting patients...\n";
    foreach ($patients as $patient) {
        $doctorId = $doctorMap[$patient['doctor_name']];
        $departmentId = $departmentMap[$patient['department_name']];

        $stmt = $pdo->prepare('
            INSERT INTO patients (name, mobile, appointment_date, doctor_id, department_id, created_at, updated_at) 
            VALUES (:name, :mobile, :appointment_date, :doctor_id, :department_id, NOW(), NOW())
        ');
        $stmt->execute([
            'name' => $patient['name'],
            'mobile' => $patient['mobile'],
            'appointment_date' => $patient['appointment_date'],
            'doctor_id' => $doctorId,
            'department_id' => $departmentId
        ]);
    }

 
    echo "Committing transaction...\n";
    $pdo->commit();
    echo "Default data inserted successfully!\n";
} catch (Exception $e) {
    
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Failed to insert data: " . $e->getMessage() . "\n";
}
