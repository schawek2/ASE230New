<?php
    require_once 'classes/topArtists.php';   // includes file that holds the Class artists

   
    //module1/code/6_RestAPI/api/handlers.php
    function allTopArtists() {
        $artists = loadTopArtists();
        echo json_encode([
            'success' => true,
            'data' => $artists,
            'count' => count($artists)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getTopArtist($id) {
        $artists = loadTopArtists();

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
            'error' => 'Artist not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createTopArtist() {
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

            $sql = "INSERT INTO topArtists (name, genre, rank) VALUES (:name, :genre, :rank);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':genre' => $input['genre'] ?? '',
                ':rank' => $input['rank'] ?? 1,
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'artist created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'genre' => $input['genre'] ?? '',
                    'rank' => $input['rank'] ?? 1
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateTopArtist($id) {
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
        $newGenre = $input['genre'] ?? '';
        $newRank = $input['rank'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE topArtists SET name = :name, genre = :genre, rank = :rank WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':genre' => $newGenre,
                ':rank' => $newRank,
                ':id' => $id
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
    function deleteTopArtist($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM topArtists WHERE id = :id";
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
    function loadTopArtists() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, genre, rank FROM topArtists";
            $artists = $pdo->query($sql)->fetchAll();
            return $artists ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    

?>