git pull
CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"
git reset --hard origin/$CURRENT_BRANCH
git pull
composer install
npm i
npm run prod

php artisan cache:clear
php artisan config:clear
