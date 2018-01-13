git pull
CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"
git reset --hard origin/$CURRENT_BRANCH
git pull

composer install

php artisan cache:clear
php artisan config:clear

npm i
npm run hot

