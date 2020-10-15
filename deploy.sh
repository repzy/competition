docker-compose down
git pull origin master
docker-compose up -d
sleep 10
docker exec -it bzstu_php php bin/console doctrine:migrations:migrate --no-interaction
