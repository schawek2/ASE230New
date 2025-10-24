---
marp: true
---

# NGINX Tutorial and Deployment

---

## NGINX Configuration File

- The inside of your configuration file should look like this:
- Changed the listening port to a different port since 80 was currently used

![alt text](images/nginxCong.png)

---

## PHP

- After this make sure PHP is installed
- run from inside the php-8.4.12 directory:
    - php-cgi.exe -c C:\php-8.4.12 -b 127.0.0.1:9000
- Once this is started there will be no output just a blank line
- Hit ctrl+c to stop php

--- 

## NGINX Start

- To start nginx open a new terminal window leaving the php window alone
- type: start nginx.exe
- to make sure it is running type:
    - tasklist | findstr nginx

![alt text](images/nginxStart.png)

---

## MySQL

- From here you can interact with mysql database

---