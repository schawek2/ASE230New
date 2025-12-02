#!/bin/bash

# Template from module3/code/1_Docker/4.Containerizing Laravel with Docker/setup.sh

set -e

echo "Music-api Laravel + Docker Setup"
echo "********************************"

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

LARAVEL_DIR="music-api"

echo ""
echo -e "${BLUE}Starting Docker Containers...${NC}"
docker-compose up -d

echo -e "${YELLOW} Waiting for Containers to be ready...${NC}"
sleep 20

echo -e "${BLUE} Checking database connection...${NC}"
docker exec music-mysql mysql -uroot -pRustycat -e "SHOW DATABASES;" > /dev/null && \
echo -e "${GREEN} Database Connection successful!${NC}" || \
echo -e "${RED} Database connection failed${NC}"

echo -e "${BLUE} Installing Composer dependencies...${NC}"
docker exec music-php bash -c "cd /var/www/html && composer install"

echo -e "${BLUE} Generating Laravel Application key...${NC}"
if docker exec music-php bash -c "cd /var/www/html && php artisan key:generate --no-interaction"; then
	echo -e "${GREEN} Laravel application key generated successfully${NC}"
else
	echo -e "${RED} Failed to generate Laravel application${NC}"
	echo -e "${YELLOW}💡 You can manually run: docker exec laravel-php php artisan key:generate${NC}"
fi

echo -e "${BLUE} Clearing laravel cache...${NC}"
docker exec music-php bash -c "cd /var/www/html && php artisan optimize:clear"

echo ""
echo -e "${GREEN} Setup completed successfully!${NC}"
echo ""
echo -e "${YELLOW} Project structure${NC}"
echo "   |-- music-api/		<-Laravel application"
echo "   |-- docker/		<-Docker configuration"
echo "   |-- docker-compose.yml <-Container setup"
echo "   |-- run.sh		<-Laravel deployment script"
echo "	 |-- setup.sh		<-Docker deployment script"
echo""
echo -e "${GREEN} Visit API at: http://localhost:8080${NC}"
echo -e "${YELLOW} Useful commands:${NC}"
echo "  docker-compose down"
echo "  docker-compose up -d"
echo "  docker exec -it music-php bash"
echo "  docker exec -it music-mysql mysql -uroot -pRustycat"
