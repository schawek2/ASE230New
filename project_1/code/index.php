<?php
    
    require_once 'helpers.php';
    require_once 'apis/userHandler.php';
    require_once 'apis/playlistHandler.php';
    require_once 'apis/albumHandler.php';
    require_once 'apis/artistHandler.php';
    require_once 'apis/audioBookHandler.php';
    require_once 'apis/employeeHandler.php';
    require_once 'apis/liveEventHandler.php';
    require_once 'apis/podcastHandler.php';
    require_once 'apis/topArtistsHandler.php';
    require_once 'apis/topSongHandler.php';

    
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Origin: *');

    //RestAPI/ Build REST API Server with PHP/8
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    //RestAPI/ Building REST API server with PHP/ 13
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = trim($path, '/');
    $parts = explode('/', $path);

    //put the url components into variables start and id
    $start = $parts[0];
    $id = $parts[1] ?? null;

    //RestAPI/Building REST API server with PHP/16
    if(empty($start)) {

        echo json_encode([
            'message' => 'All APIs',
            'api1' => 'Users',
            'user-endpoints' => [
                'GET /users' => 'Get all users',
                'GET /users/{id}' => 'Get user by id',
                'POST /users' => 'Create new user',
                'PUT /users/{id}' => 'Updates user',
                'DELETE /users/{id}' => 'Deletes a user'
            ],
            'api2' => 'Playlists',
            'playlists-endpoints' => [
                'GET /playlists' => 'Gets all playlists',
                'GET /playlists/{id}' => 'Get playlist by id',
                'POST /playlists' => 'Creates new playlist',
                'PUT /playlists/{id}' => 'Updates a playlist',
                'DELETE /playlists/{id}' => 'Deletes a playlist'
            ],
            'api3' => 'Artists',
            'artist-endpoints' => [
                'GET /artists' => 'Gets all artists',
                'GET /artists/{id}' => 'Gets artist by id',
                'POST /artists' => 'Creates new artist',
                'PUT /artists/{id}' => 'Updates a artist',
                'DELETE /artists/{id}' => 'Deletes an artist'
            ],
            'api4' => 'Albums',
            'albums-endpoints' => [
                'GET /albums' => 'Gets all albums',
                'GET /albums/{id}' => 'Gets an album by id',
                'POST /albums' => 'Creates new album',
                'PUT /albums/{id}' => 'Updates an album',
                'DELETE /albums/{id}' => 'Deletes an album'
            ],
            'api5' => 'Employees',
            'employees-endpoints' => [
                'GET /employees' => 'Gets all employees',
                'GET /employees/{id}' => 'Gets employee by id',
                'POST /employees' => 'Creates new employee',
                'PUT /employees/{id}' => 'Updates an employee',
                'DELETE /employees/{id}' => 'Deletes an employee'
            ],
            'api6' => 'Live Events',
            'live-events-endpoints' => [
                'GET /liveEvents' => 'Gets all live events',
                'GET /liveEvents/{id}' => 'Gets a live event by id',
                'POST /liveEvents' => 'Creates new live event',
                'PUT /liveEvents/{id}' => 'Updates a live event',
                'DELETE /liveEvents/{id}' => 'Deletes a live event'
            ],
            'api7' => 'Audiobooks',
            'audiobooks-endpoints' => [
                'GET /audiobooks' => 'Gets all audiobooks',
                'GET /audiobooks/{id}' => 'Gets audiobook by id',
                'POST /audiobooks' => 'Creates new audiobook',
                'PUT /audiobooks/{id}' => 'Updates audiobook',
                'DELETE /audiobooks/{id}' => 'Deletes audiobook'
            ],
            'api8' => 'Podcasts',
            'podcasts-endpoints' => [
                'GET /podcasts' => 'Gets all podcasts',
                'GET /podcasts/{id}' => 'Gets podcast by id',
                'POST /podcasts' => 'Creates new podcast',
                'PUT /podcasts/{id}' => 'Updates a podcast',
                'DELETE /podcasts' => 'Deletes a podcast'
            ],
            'api9' => 'Top Songs',
            'top-songs-endpoints' => [
                'GET /topSongs' => 'Gets all top songs',
                'GET /topSongs/{id}' => 'Gets top song by id',
                'POST /topSongs' => 'Creates new top song',
                'PUT /topSongs/{id}' => 'Updates a top song',
                'DELETE /topSongs/{id}' => 'Deletes a top song'
            ],
            'api10' => 'Top Artists',
            'top-artists-endpoints' => [
                'GET /topArtists' => 'Gets all top artists',
                'GET /topArtists/{id}' => 'Gets top artist by id',
                'POST /topArtists' => 'Creates new top artist',
                'PUT /topArtists/{id}' => 'Updates top artist',
                'DELETE /topArtists/{id}' => 'Delete a top artist'
            ]
        ]);

        exit;
    }

    //if/elseif/else from RestAPI/Building REST API server with PHP/18
    if($start === 'users') {
        $userId = isset($id) ? (int)$id: null;  //RestAPI/Building REST API server with PHP/17

        switch ($method) {
            case'GET':
                if($userId) {
                    getUser($userId);
                }
                else{
                    allUsers();
                }
                break;
            case 'POST':
                createUser();
                break;
            case 'PUT':
                if($userId){
                    updateUser($userId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'User ID required']);
                }
                break;
            case 'DELETE':
                if($userId) {
                    deleteUser($userId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'User ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }

    }
    else if ($start ==='playlists') {
        $playlistId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($playlistId) {
                    getPlaylist($playlistId);
                }
                else{
                    allPlaylists();
                }
                break;
            case 'POST':
                createPlaylist();
                break;
            case 'PUT':
                if($playlistId){
                    updatePlaylist($playlistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Playlist ID required']);
                }
                break;
            case 'DELETE':
                if($playlistId) {
                    deleteByPlaylistId($playlistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Playlist ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
    else if ($start === 'artists') {
        $artistId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($artistId) {
                    getArtist($artistId);
                }
                else{
                    allArtists();
                }
                break;
            case 'POST':
                createArtist();
                break;
            case 'PUT':
                if($artistId){
                    updateArtist($artistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Artist ID required']);
                }
                break;
            case 'DELETE':
                if($artistId) {
                    deleteArtist($artistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Artist ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'albums') {
    $albumId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($albumId) {
                    getAlbum($albumId);
                }
                else{
                    allAlbums();
                }
                break;
            case 'POST':
                createAlbum();
                break;
            case 'PUT':
                if($albumId){
                    updateAlbum($albumId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Album ID required']);
                }
                break;
            case 'DELETE':
                if($albumId) {
                    deleteAlbum($albumId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Album ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'employees') {
    $employeeId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($employeeId) {
                    getEmployee($employeeId);
                }
                else{
                    allEmployees();
                }
                break;
            case 'POST':
                createEmployee();
                break;
            case 'PUT':
                if($employeeId){
                    updateEmployee($employeeId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Employee ID required']);
                }
                break;
            case 'DELETE':
                if($employeeId) {
                    deleteEmployee($employeeId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Employee ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'events') {
    $eventId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($eventId) {
                    getEvent($eventId);
                }
                else{
                    allEvents();
                }
                break;
            case 'POST':
                createEvent();
                break;
            case 'PUT':
                if($eventId){
                    updateEvent($eventId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Event ID required']);
                }
                break;
            case 'DELETE':
                if($eventId) {
                    deleteEvent($eventId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Event ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'audiobooks') {
    $audioId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($audioId) {
                    getAudiobook($audioId);
                }
                else{
                    allAudiobooks();
                }
                break;
            case 'POST':
                createAudiobook();
                break;
            case 'PUT':
                if($audioId){
                    updateAudiobook($audioId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Audiobook ID required']);
                }
                break;
            case 'DELETE':
                if($audioId) {
                    deleteAudiobook($audioId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Audiobook ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'podcasts') {
    $podcastId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($podcastId) {
                    getPodcast($podcastId);
                }
                else{
                    allPodcasts();
                }
                break;
            case 'POST':
                createPodcast();
                break;
            case 'PUT':
                if($podcastId){
                    updatePodcast($podcastId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Podcast ID required']);
                }
                break;
            case 'DELETE':
                if($podcastId) {
                    deletePodcast($podcastId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Podcast ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'topsongs') {
    $songId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($songId) {
                    getTopSong($songId);
                }
                else{
                    allTopSongs();
                }
                break;
            case 'POST':
                createTopSong();
                break;
            case 'PUT':
                if($songId){
                    updateTopSong($songId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Song ID required']);
                }
                break;
            case 'DELETE':
                if($songId) {
                    deleteTopSong($songId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Song ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else if ($start === 'topartists') {
    $artistId = isset($id) ? (int)$id: null;

        switch($method){
            case 'GET':
                if($artistId) {
                    getTopArtist($artistId);
                }
                else{
                    allTopArtists();
                }
                break;
            case 'POST':
                createTopArtist();
                break;
            case 'PUT':
                if($artistId){
                    updateTopArtists($artistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Artist ID required']);
                }
                break;
            case 'DELETE':
                if($artistId) {
                    deleteTopArtist($artistId);
                }
                else{
                    http_response_code(400);
                    echo json_encode(['error' => 'Artist ID required']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
    }
}
else {
    http_response_code(404);
    echo json_encode(['error' => 'No resource found']);
}
?>