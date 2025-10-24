<?php
    require_once 'classes/podcasts.php';   // includes file that holds the Class podcasts

   
    //module1/code/6_RestAPI/api/handlers.php
    function allPodcasts() {
        $podcasts = loadPodcasts();
        echo json_encode([
            'success' => true,
            'data' => $podcasts,
            'count' => count($podcasts)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getPodcast($id) {
        $podcasts = loadPodcasts();

        foreach($podcasts as $podcast) {
            if ($podcast['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $podcast
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'podcast not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createPodcast() {
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

            $sql = "INSERT INTO podcasts (name, hostName, totalTime) VALUES (:name, :hostName, :totalTime);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':hostName' => $input['hostName'] ?? '',
                ':totalTime' => $input['totalTime'] ?? 1,
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'podcast created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'hostName' => $input['hostName'] ?? '',
                    'totalTime' => $input['totalTime'] ?? 1
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updatePodcast($id) {
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
        $newHostName = $input['hostName'] ?? '';
        $newTotalTime = $input['totalTime'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE podcasts SET name = :name, hostName = :hostName, totalTime = :totalTime WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':hostName' => $newHostName,
                ':totalTime' => $newTotalTime,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'podcast updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'podcast not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deletePodcast($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM podcasts WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'podcast deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'podcast not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadPodcasts() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, hostName, totalTime FROM podcasts";
            $podcasts = $pdo->query($sql)->fetchAll();
            return $podcasts ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }


?>