<?php
    require_once 'classes/playlists.php';   //including the file that holds Class playlists
    
   
    //module1/code/6_RestAPI/api/handlers.php
    function allPlaylists() {
        $playlists = loadPlaylists();
        echo json_encode([
            'success' => true,
            'data' => $playlists,
            'count' => count($playlists)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getPlaylist($id) {
        $playlists = loadPlaylists();

        foreach($playlists as $playlist) {
            if ($playlist['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $playlist
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'playlist not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createPlaylist() {
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

            $sql = "INSERT INTO playlists (userName, playlistName, genre) VALUES (:userName, :playlistName, :genre);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':userName' => $input['userName'] ?? '',
                 ':playlistName' => $input['playlistName'] ?? '',
                ':genre' => $input['genre'] ?? '',
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'User created Successfully',
                'data' => [
                    'id' => $newId,
                    'userName' => $input['userName'] ?? '',
                    'playlistName' => $input['playlistName'] ?? '',
                    'genre' => $input['genre'] ?? ''
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updatePlaylist($id) {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }


        $newName = $input['userName'] ?? '';
        $newPlaylist = $input['playlistName'] ?? '';
        $newGenre = $input['genre'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE playlists SET userName = :userName, playlistName = :playlistName, genre = :genre WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':userName' => $newName,
                ':playlistName' => $newPlaylist,
                ':genre' => $newGenre,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'Playlist updated successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Playlist not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deletePlaylist($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM playlists WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Playlist deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'Playlist not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadPlaylists() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, userName, playlistName, genre FROM playlists";
            $playlist = $pdo->query($sql)->fetchAll();
            return $playlist ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

?>