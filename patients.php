<?php
$config = include 'config.php';
$smarty = $config['smarty'];
$pdo = $config['pdo'];

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':

       
        
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 3;
        $offset = ($page - 1) * $limit;

       
        $countQuery = "SELECT COUNT(*) FROM patients p 
                       JOIN doctors d ON p.doctor_id = d.id
                       JOIN departments dep ON p.department_id = dep.id
                       WHERE p.name LIKE :search OR d.name LIKE :search OR dep.name LIKE :search";
        $stmt = $pdo->prepare($countQuery);
        $stmt->execute(['search' => "%$search%"]);
        $totalRows = $stmt->fetchColumn();
        $totalPages = ceil($totalRows / $limit);

        $query = "
            SELECT p.*, d.name AS doctor_name, dep.name AS department_name
            FROM patients p
            JOIN doctors d ON p.doctor_id = d.id
            JOIN departments dep ON p.department_id = dep.id
            WHERE p.name LIKE :search OR d.name LIKE :search OR dep.name LIKE :search
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $patients = $stmt->fetchAll();
        $pagination = [
            'total_pages' => $totalPages,
            'current_page' => $page,
        ];
        $smarty->assign('patients', $patients);
        $smarty->assign('pagination', $pagination);
        $smarty->assign('search', $search);
        $smarty->display('patients/list.tpl');
        break;

        case 'add':
            case 'edit':
                $id = $_GET['id'] ?? null;
                $errors = [];
                $patient = [];
            
                if ($id) {
                    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $patient = $stmt->fetch();

                    if (isset($patient['appointment_date'])) {
                        $patient['appointment_date'] = date('Y-m-d\TH:i', strtotime($patient['appointment_date']));
                    }
                }
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $data = $_POST;
                    $patient = $data;
            
                   
                    if (empty($data['name'])) $errors[] = 'Name is required.';
                    if (empty($data['appointment_date'])) $errors[] = 'Appointment date is required.';
                    if (!filter_var($data['mobile'], FILTER_SANITIZE_NUMBER_INT)) $errors[] = 'Invalid mobile number.';
                    if (empty($data['doctor_id'])) $errors[] = 'Doctor is required.';
                    if (empty($data['department_id'])) $errors[] = 'Department is required.';
            
                    if (empty($errors)) {
                        if ($id) {
                            
                            $stmt = $pdo->prepare("
                                UPDATE patients
                                SET name = :name, mobile = :mobile, appointment_date = :appointment_date, doctor_id = :doctor_id, department_id = :department_id
                                WHERE id = :id
                            ");
                            $data['id'] = $id;
                        } else {
                           
                            $stmt = $pdo->prepare("
                                INSERT INTO patients (name, mobile, appointment_date, doctor_id, department_id)
                                VALUES (:name, :mobile, :appointment_date, :doctor_id, :department_id)
                            ");
                        }
                        $stmt->execute($data);
                        header('Location: patients.php');
                        exit;
                    }
                }
            
                $departments = $pdo->query("SELECT * FROM departments")->fetchAll();
                $doctors = $pdo->query("SELECT * FROM doctors")->fetchAll();
                $smarty->assign('departments', $departments);
                $smarty->assign('doctors', $doctors);
                $smarty->assign('errors', $errors);
                $smarty->assign('patient', $patient);
                $smarty->display('patients/form.tpl');
                break;

    case 'delete':
        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM patients WHERE id = :id");
        $stmt->execute(['id' => $id]);
        header('Location: patients.php');
        break;
}
