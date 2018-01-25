git fetch
CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"
git reset --hard origin/$CURRENT_BRANCH

composer install

cp .env.example .env

sudo chmod -R 777 storage bootstrap/cache

php artisan key:generate

php artisan jwt:secret

./dev.sh

