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

REM Test 2.1: Get All users
echo %BLUE% Testing GET /users...
set /a total_tests+=1

curl -s %API_BASE_URL%/users > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"data" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"count" temp_response.txt >nul && set /a local_tests+=1
    
    if !local_tests! equ 3 (
        echo %GREEN% Get All Users: All tests passed ^(3/3^)
        set /a passed_tests+=1
    ) else (
        echo %RED% Get All Users: Partial: !local_tests!/3 tests passed
    )
) else (
    echo %RED% Get All Users: Could not get users
)
echo.

REM Test 2.2: Get Single user
echo %BLUE% Testing GET /users/1...
set /a total_tests+=1

curl -s %API_BASE_URL%/users/1 > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"""id"":1" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"name" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"@" temp_response.txt >nul && set /a local_tests+=1
    
    if !local_tests! equ 4 (
        echo %GREEN% Get Single User: All tests passed ^(4/4^)
        set /a passed_tests+=1
    ) else (
        echo %RED% Get Single User: Partial: !local_tests!/4 tests passed
    )
) else (
    echo %RED% Get Single User: Could not get user
)
echo.

REM Test 2.3: Create New user
echo %BLUE% Testing POST /users...
set /a total_tests+=1

echo {"user": "Test user CURL", "email": "test.curl@university.edu", "password": "Password123"} > temp_user.json

curl -s -X POST -H "Content-Type: application/json" -d @temp_user.json %API_BASE_URL%/users > temp_response.txt 2>nul
if %errorlevel% equ 0 (
    set local_tests=0
    findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"Test user CURL" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"test.curl@university.edu" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"""id"":" temp_response.txt >nul && set /a local_tests+=1
    findstr /C:"created_at" temp_response.txt >nul && set /a local_tests+=1
    
    REM Extract user ID for later tests (simplified)
    for /f "tokens=2 delims=:" %%a in ('findstr /C:"""id"":" temp_response.txt') do (
        for /f "tokens=1 delims=," %%b in ("%%a") do set CREATED_user_ID=%%b
    )
    
    if !local_tests! equ 5 (
        echo %GREEN% Create user: Created user with ID: !CREATED_user_ID!
        set /a passed_tests+=1
    ) else (
        echo %RED% Create user: Partial: !local_tests!/5 tests passed
    )
) else (
    echo %RED% Create user: Could not create user
)
echo.

REM Test 2.4: Update user
if defined CREATED_user_ID (
    echo %BLUE% Testing PUT /users/!CREATED_user_ID!...
    set /a total_tests+=1
    
    echo {"name": "Updated Test user CURL", "email": "test2email@gmail.com"} > temp_update.json
    
    curl -s -X PUT -H "Content-Type: application/json" -d @temp_update.json %API_BASE_URL%/users/!CREATED_user_ID! > temp_response.txt 2>nul
    if !errorlevel! equ 0 (
        set local_tests=0
        findstr /C:"success" temp_response.txt >nul && set /a local_tests+=1
        findstr /C:"Updated Test user CURL" temp_response.txt >nul && set /a local_tests+=1
        findstr /C:"test2email@gmail.com" temp_response.txt >nul && set /a local_tests+=1
        
        if !local_tests! equ 3 (
            echo %GREEN% Update user: All tests passed ^(3/3^)
            set /a passed_tests+=1
        ) else (
            echo %RED% Update user: Partial: !local_tests!/3 tests passed
        )
    ) else (
        echo %RED% Update user: Could not update user
    )
    echo.
    
    REM Test 2.5: Delete user
    echo %BLUE% Testing DELETE /users/!CREATED_user_ID!...
    set /a total_tests+=1
    
    curl -s -X DELETE %API_BASE_URL%/users/!CREATED_user_ID! > temp_response.txt 2>nul
    if !errorlevel! equ 0 (
        findstr /C:"success" temp_response.txt >nul
        if !errorlevel! equ 0 (
            REM Verify deletion
            curl -s %API_BASE_URL%/users/!CREATED_user_ID! > temp_verify.txt 2>nul
            findstr /C:"not found" temp_verify.txt >nul
            if !errorlevel! equ 0 (
                echo %GREEN% Delete user: user !CREATED_user_ID! deleted successfully!
                set /a passed_tests+=1
            ) else (
                echo %RED% Delete user: user still exists after deletion
            )
        ) else (
            echo %RED% Delete user: Delete operation failed
        )
    ) else (
        echo %RED% Delete user: Could not delete user
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
if exist temp_user.json del temp_user.json
if exist temp_update.json del temp_update.json
if exist temp_verify.txt del temp_verify.txt

echo.
echo Test completed!
pause