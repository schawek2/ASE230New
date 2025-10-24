<?php
    require_once 'classes/audiobooks.php';   // includes file that holds the Class audiobooks

   
    //module1/code/6_RestAPI/api/handlers.php
    function allAudiobooks() {
        $audiobooks = loadAudiobooks();
        echo json_encode([
            'success' => true,
            'data' => $audiobooks,
            'count' => count($audiobooks)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getAudiobook($id) {
        $audiobooks = loadAudiobooks();

        foreach($audiobooks as $audiobook) {
            if ($audiobook['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $audiobook
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'audiobook not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createAudiobook() {
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

            $sql = "INSERT INTO audiobooks (bookName, writerName, readerName) VALUES (:bookName, :writerName, :readerName);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':bookName' => $input['bookName'] ?? '',
                 ':writerName' => $input['writerName'] ?? '',
                ':readerName' => $input['readerName'] ?? '',
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'audiobook created Successfully',
                'data' => [
                    'id' => $newId,
                    'bookName' => $input['bookName'] ?? '',
                    'writerName' => $input['writerName'] ?? '',
                    'readerName' => $input['readerName'] ?? ''
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateAudiobook($id) {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }


        $newBookName = $input['bookName'] ?? '';
        $newWriterName = $input['writerName'] ?? '';
        $newReaderName = $input['readerName'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE audiobooks SET bookName = :bookName, writerName = :writerName, readerName = :readerName WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':bookName' => $newBookName,
                ':writerName' => $newWriterName,
                ':readerName' => $newReaderName,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'audiobook updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'audiobook not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteAudiobook($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM audiobooks WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'audiobook deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'audiobook not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }


    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadAudiobooks() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, bookName, writerName, readerName FROM audiobooks";
            $audiobooks = $pdo->query($sql)->fetchAll();
            return $audiobooks ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    
?>