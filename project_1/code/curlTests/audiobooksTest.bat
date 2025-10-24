@echo off
setlocal enabledelayedexpansion

REM #####################################################################
REM from module1/6_RESTAPI/api_tests/curl
REM #####################################################################

REM Configuration
set API_BASE_URL=http://localhost:8080
set VERBOSE=false

REM Check command line arguments
if "%1"=="--verbose" set VERBOSE=true

REM Colors (simplified for Windows)
set GREEN=✅
set RED=❌
set BLUE=🔍
set MAGENTA=🚀
set CYAN=ℹ️

echo %MAGENTA% Starting REST API Tests using CURL
echo %CYAN% API Base URL: %API_BASE_URL%
echo.

REM Check if CURL is available
curl --version >nul 2>&1
if %errorlevel% neq 0 (
    echo %RED% ERROR: curl is not installed or not in PATH
    echo Please install curl to run these tests
    echo.
    echo Installation:
    echo   Windows 10/11: curl is usually pre-installed
    echo   Older Windows: Download from https://curl.se/windows/
    pause
    exit /b 1
)

set passed_tests=0
set total_tests=0

REM Test 1.1: Server Connection
echo %BLUE% Testing server connection...
set /a total_tests+=1

curl -s -w "%%{http_code}" %API_BASE_URL% > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    for /f "tokens=*" %%i in (temp_response.txt) do set response=%%i
    echo !response! | findstr /C:"200" >nul
    if !errorlevel! equ 0 (
        echo %GREEN% Server Connection: Server is responding correctly!
        set /a passed_tests+=1
    ) else (
        echo %RED% Server Connection: Server connection failed
    )
) else (
    echo %RED% Server Connection: Could not connect to server
)
echo.

REM Test 1.2: API Root Endpoint
echo %BLUE% Testing API root endpoint...
set /a total_tests+=1

curl -s %API_BASE_URL% > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    findstr /C:"message" temp_response.txt >nul && findstr /C:"endpoints" temp_response.txt >nul
    if !errorlevel! equ 0 (
        echo %GREEN% API Root: API root returns expected structure!
        set /a passed_tests+=1
    ) else (
        echo %RED% API Root: API root missing expected fields
    )
) else (
    echo %RED% API Root: Could not get API root
)
echo.

REM Test 2.1: Get All audiobooks
echo %BLUE% Testing GET /audiobooks...
set /a total_tests+=1

curl -s %API_BASE_URL%/audiobooks > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"data" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"count" temp_response.txt >nul && set /a local_tests+=1
    
    if !local_tests! equ 3 (
        echo %GREEN% Get All audiobooks: All tests passed ^(3/3^)
        set /a passed_tests+=1
    ) else (
        echo %RED% Get All audiobooks: Partial: !local_tests!/3 tests passed
    )
) else (
    echo %RED% Get All audiobooks: Could not get audiobooks
)
echo.

REM Test 2.2: Get Single audiobook
echo %BLUE% Testing GET /audiobooks/1...
set /a total_tests+=1

curl -s %API_BASE_URL%/audiobooks/1 > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"""id"":1" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"name" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"@" temp_response.txt >nul && set /a local_tests+=1
    
    if !local_tests! equ 4 (
        echo %GREEN% Get Single audiobook: All tests passed ^(4/4^)
        set /a passed_tests+=1
    ) else (
        echo %RED% Get Single audiobook: Partial: !local_tests!/4 tests passed
    )
) else (
    echo %RED% Get Single audiobook: Could not get audiobook
)
echo.

REM Test 2.3: Create New audiobook
echo %BLUE% Testing POST /audiobooks...
set /a total_tests+=1

echo {"bookName": "Test audiobook CURL", "writerName": "test Name", "readerName": "test Reader"} > temp_audiobook.json

curl -s -X POST -H "Content-Type: application/json" -d @temp_audiobook.json %API_BASE_URL%/audiobooks > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"Test audiobook CURL" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"test Name" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"""id"":" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"created_at" temp_response.txt >nul && set /a local_tests+=1
    
    REM Extract audiobook ID for later tests (simplified)
    for /f "tokens=2 delims=:" %%a in ('findstr /C:"""id"":" temp_response.txt') do (
        for /f "tokens=1 delims=," %%b in ("%%a") do set CREATED_audiobook_ID=%%b
    )
    
    if !local_tests! equ 5 (
        echo %GREEN% Create audiobook: Created audiobook with ID: !CREATED_audiobook_ID!
        set /a passed_tests+=1
    ) else (
        echo %RED% Create audiobook: Partial: !local_tests!/5 tests passed
    )
) else (
    echo %RED% Create audiobook: Could not create audiobook
)
echo.

REM Test 2.4: Update audiobook
if defined CREATED_audiobook_ID (
    echo %BLUE% Testing PUT /audiobooks/!CREATED_audiobook_ID!...
    set /a total_tests+=1
    
    echo {"bookName": "Updated audiobook CURL", "writerName": "new Writer"} > temp_update.json
    
    curl -s -X PUT -H "Content-Type: application/json" -d @temp_update.json %API_BASE_URL%/audiobooks/!CREATED_audiobook_ID! > temp_response.txt 2>nul
    if !errorlevel! equ 0 (
        set local_tests=0
        findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
        findstr /C:"Updated audiobook CURL" temp_response.txt >nul && set /a local_tests+=1
        findstr /C:"new Writer" temp_response.txt >nul && set /a local_tests+=1
        
        if !local_tests! equ 3 (
            echo %GREEN% Update audiobook: All tests passed ^(3/3^)
            set /a passed_tests+=1
        ) else (
            echo %RED% Update audiobook: Partial: !local_tests!/3 tests passed
        )
    ) else (
        echo %RED% Update audiobook: Could not update audiobook
    )
    echo.
    
    REM Test 2.5: Delete audiobook
    echo %BLUE% Testing DELETE /audiobooks/!CREATED_audiobook_ID!...
    set /a total_tests+=1
    
    curl -s -X DELETE %API_BASE_URL%/audiobooks/!CREATED_audiobook_ID! > temp_response.txt 2>nul
    if !errorlevel! equ 0 (
        findstr /C:"success" temp_response.txt >nul
        if !errorlevel! equ 0 (
            REM Verify deletion
            curl -s %API_BASE_URL%/audiobooks/!CREATED_audiobook_ID! > temp_verify.txt 2>nul
            findstr /C:"not found" temp_verify.txt >nul
            if !errorlevel! equ 0 (
                echo %GREEN% Delete audiobook: audiobook !CREATED_audiobook_ID! deleted successfully!
                set /a passed_tests+=1
            ) else (
                echo %RED% Delete audiobook: audiobook still exists after deletion
            )
        ) else (
            echo %RED% Delete audiobook: Delete operation failed
        )
    ) else (
        echo %RED% Delete audiobook: Could not delete audiobook
    )
    echo.
)

REM Summary
echo %MAGENTA% Test Summary
echo %CYAN% Total Tests: %total_tests%
echo %GREEN% Passed: %passed_tests%
set /a failed_tests=%total_tests%-%passed_tests%
echo %RED% Failed: %failed_tests%

if %passed_tests% equ %total_tests% (
    echo %GREEN% 🎉 All tests passed!
) else (
    echo ⚠️  Some tests failed. Check the output above.
)

REM Cleanup
if exist temp_response.txt del temp_response.txt
if exist temp_audiobook.json del temp_audiobook.json
if exist temp_update.json del temp_update.json
if exist temp_verify.txt del temp_verify.txt

echo.
echo Test completed!
pause