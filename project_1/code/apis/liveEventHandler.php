<?php
    require_once 'classes/liveEvents.php';   // includes file that holds the Class Events

   
    //module1/code/6_RestAPI/api/handlers.php
    function allEvents() {
        $event = loadEvents();
        echo json_encode([
            'success' => true,
            'data' => $event,
            'count' => count($event)
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function getEvent($id) {
        $events = loadEvents();

        foreach($events as $event) {
            if ($event['id'] == $id) {
                echo json_encode([
                    'success' => true,
                    'data' => $event
                ]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode([ 
            'success' => false,
            'error' => 'event not found'
        ]);
    }

    //module1/code/6_RestAPI/api/handlers.php
    function createEvent() {
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

            $sql = "INSERT INTO events (venue, artist, day) VALUES (:venue, :artist, :day);";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([ ':venue' => $input['venue'] ?? '',
                 ':artist' => $input['artist'] ?? '',
                ':day' => $input['day'] ?? '',
            ]);

            $newId = $pdo->lastInsertId();

            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'event created Successfully',
                'data' => [
                    'id' => $newId,
                    'venue' => $input['venue'] ?? '',
                    'artist' => $input['artist'] ?? '',
                    'day' => $input['day'] ?? ''
                ]
            ]);
        } catch (PDOException $e) {
            echo "Error (CREATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

        
    }

    //from lecture/2_Connect_PHP_with_MySQL/4/8
    function updateEvent($id) {
        $input = getRequestData();

        if(!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            return;
        }


        $newVenue = $input['venue'] ?? '';
        $newArtist = $input['artist'] ?? '';
        $newDay = $input['day'] ?? '';

        try{
            $pdo = getPDO();

            $sql = "UPDATE events SET venue = :venue, artist = :artist, day = :day WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':venue' => $newVenue,
                ':artist' => $newArtist,
                ':day' => $newDay,
                ':id' => $id,
            ]);

            if($stmt->rowCount() > 0) {
               echo json_encode([
                    'success' => true,
                    'message' => 'event updates successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'event not found'
                ]);
                return;
            }


        } catch (PDOException $e) {
            echo "Error (UPDATE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }

    }

    //from lecture/2_Connect_PHP_with_MySQL/4/10
    function deleteEvent($id) {

        try{
            $pdo = getPDO();

            $sql = "DELETE FROM events WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'event deleted successfully',
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'success' => false,
                'error' => 'event not found'
                ]);
                return;
            }
        } catch (PDOException $e) {
            echo "Error (DELETE): " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    //module1/lecture/RestAPI/Building REST API server with PHP/27
    function loadEvents() {
       
        try{
            $pdo = getPDO();

            $sql = "SELECT id, venue, artist, day FROM events";
            $Events = $pdo->query($sql)->fetchAll();
            return $Events ?: [];
        } catch (PDOException $e) {
            echo "Error Connection: " . htmlspecialchars($e->getMessage()) . "<br><br>";
        }
    }

    

?>