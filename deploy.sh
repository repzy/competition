docker-compose down

git pull origin master

echo MYSQL_PASSWORD=$MYSQL_PASSWORD >> .env
echo MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD >> .env
echo NODE_ENV=production >> .env
echo APP_SECRET=$APP_SECRET >> .env
echo MAILER_PASSWORD=$MAILER_PASSWORD >> .env

docker-compose up -d

sleep 10

docker exec -it bzstu_php composer dump-env prod
docker exec -it bzstu_php php bin/console doctrine:migrations:migrate --no-interaction
