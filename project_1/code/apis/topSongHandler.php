<?php
    require_once 'classes/topSongs.php';   // includes file that holds the Class songs

   
    //module1/code/6_RestAPI/api/handlers.php
    function allTopSongs() {
        $songs = loadSongs();
        echo json_encode([
            'success' => true,
            'data' => $songs,
            'count' => count($songs)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getTopSong($id) {
        $songs = loadSongs();

        foreach($songs as $song) {
            if ($song['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $song
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'Top Song not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createTopSong() {
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

            $sql = "INSERT INTO topsongs (name, artist, rank) VALUES (:name, :artist, :rank);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':name' => $input['name'] ?? '',
                 ':artist' => $input['artist'] ?? '',
                ':rank' => $input['rank'] ?? 1,
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Top Song created Successfully',
                'data' => [
                    'id' => $newId,
                    'name' => $input['name'] ?? '',
                    'artist' => $input['artist'] ?? '',
                    'rank' => $input['rank'] ?? 1
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateTopSong($id) {
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
        $newArtist = $input['artist'] ?? '';
        $newRank = $input['rank'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE topsongs SET name = :name, artist = :artist, rank = :rank WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':name' => $newName,
                ':artist' => $newArtist,
                ':rank' => $newRank,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'Top Song updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Top Song not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteTopSong($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM topsongs WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Top Song deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Top Song not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadSongs() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, name, artist, rank FROM topsongs";
            $songs = $pdo->query($sql)->fetchAll();
            return $songs ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }


?>