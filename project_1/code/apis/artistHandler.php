<?php
    require_once 'classes/artists.php';   // includes file that holds the Class Artists

   
    //module1/code/6_RestAPI/api/handlers.php
    function allArtists() {
        $artists = loadArtists();
        echo json_encode([
            'success' => true,
            'data' => $artists,
            'count' => count($artists)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getArtist($id) {
        $artists = loadArtists();

        foreach($artists as $artist) {
            if ($artist['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $artist
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'artist not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createArtist() {
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

            $sql = "INSERT INTO artists (name, agency, genre) VALUES (:name, :agency, :genre);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':agency' => $input['agency'] ?? '',
                ':genre' => $input['genre'] ?? '',
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'artist created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'agency' => $input['agency'] ?? '',
                    'genre' => $input['genre'] ?? ''
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateArtist($id) {
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
        $newAgency = $input['agency'] ?? '';
        $newGenre = $input['genre'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE artists SET name = :name, agency = :agency, genre = :genre WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':agency' => $newAgency,
                ':genre' => $newGenre,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'artist updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'artist not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteArtist($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM artists WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'artist deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'artist not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }


    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadArtists() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, agency, genre FROM artists";
            $artists = $pdo->query($sql)->fetchAll();
            return $artists ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    
?>