<?php
    require_once 'classes/employees.php';   // includes file that holds the Class employees

   
    //module1/code/6_RestAPI/api/handlers.php
    function allEmployees() {
        $employees = loadEmployees();
        echo json_encode([
            'success' => true,
            'data' => $employees,
            'count' => count($employees)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getEmployee($id) {
        $employees = loadEmployees();

        foreach($employees as $employee) {
            if ($employee['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $employee
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'employee not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createEmployee() {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }

        try{
            $pdo = getPDO();

            $passHash = password_hash($input['password'] ?? '', PASSWORD_DEFAULT);
            $sql = "INSERT INTO employees (name, email, department, password) VALUES (:name, :email, :department, :password);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':email' => $input['email'] ?? '',
                ':department' => $input['department'] ?? '',
                ':password' => $passHash
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'employee created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'email' => $input['email'] ?? '',
                    'department' => $input['department'] ?? ''
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateEmployee($id) {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }


        $newName = $input['name'] ?? '';
        $newEmail = $input['email'] ?? '';
        $newDepartment = $input['department'] ?? '';
         $newPassHash = password_hash($input['password'] ?? '', PASSWORD_DEFAULT);

        try{
            $pdo = getPDO();

            $sql = "UPDATE employees SET name = :name, email = :email, department = :department, password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':email' => $newEmail,
                ':department' => $newDepartment,
                ':password' => $newPassHash,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'employee updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'employee not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteEmployee($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM employees WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'employee deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'employee not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }


    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadEmployees() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, email, department FROM employees";
            $employees = $pdo->query($sql)->fetchAll();
            return $employees ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    
?>