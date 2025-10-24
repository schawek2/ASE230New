<?php
    require_once 'classes/users.php';   // includes file that holds the Class users

   
    //module1/code/6_RestAPI/api/handlers.php
    function allUsers() {
        $users = loadUsers();
        echo json_encode([
            'success' => true,
            'data' => $users,
            'count' => count($users)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getUser($id) {
        $users = loadUsers();

        foreach($users as $user) {
            if ($user['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $user
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'User not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createUser() {
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
            $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':email' => $input['email'] ?? '',
                ':password' => $passHash,
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'User created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'email' => $input['email'] ?? '',
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateUser($id) {
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
        $newPassHash = password_hash($input['password'] ?? '', PASSWORD_DEFAULT);

        try{
            $pdo = getPDO();

            $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':email' => $newEmail,
                ':password' => $newPassHash,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'User updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'User not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteUser($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'User deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'User not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }
    

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadUsers() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, email FROM users";
            $users = $pdo->query($sql)->fetchAll();
            return $users ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

?>