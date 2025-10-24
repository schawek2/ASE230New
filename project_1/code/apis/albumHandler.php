<?php
    require_once 'classes/albums.php';   // includes file that holds the Class Albums

   
    //module1/code/6_RestAPI/api/handlers.php
    function allAlbums() {
        $albums = loadAlbums();
        echo json_encode([
            'success' => true,
            'data' => $albums,
            'count' => count($albums)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getAlbum($id) {
        $albums = loadAlbums();

        foreach($albums as $album) {
            if ($album['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $album
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'Album not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createAlbum() {
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

            $sql = "INSERT INTO albums (albumName, artistName, numOfSongs) VALUES (:albumName, :artistName, :numOfSongs);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':albumName' => $input['albumName'] ?? '',
                 ':artistName' => $input['artistName'] ?? '',
                ':numOfSongs' => $input['numOfSongs'] ?? 1
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Album created Successfully',
                'data' => [
                    'id' => $newId,
                    'albumName' => $input['albumName'] ?? '',
                    'artistName' => $input['artistName'] ?? '',
                    'numOfSongs' => $input['numOfSongs'] ?? 1
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateAlbum($id) {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }


        $newName = $input['albumName'] ?? '';
        $newartistName = $input['artistName'] ?? '';
        $newNumOfSongs = $input['numOfSongs'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE albums SET albumName = :albumName, artistName = :artistName, numOfSongs = :numOfSongs WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':albumName' => $newName,
                ':artistName' => $newartistName,
                ':numOfSongs' => $newNumOfSongs,
                ':id' => $id
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'Album updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Album not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteAlbum($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM albums WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Album deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Album not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadAlbums() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, albumName, artistName, numOfSongs FROM albums";
            $albums = $pdo->query($sql)->fetchAll();
            return $albums ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }   


?>