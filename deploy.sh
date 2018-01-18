git fetch
CURRENT_BRANCH="$(git rev-parse --abbrev-ref HEAD)"
git reset --hard origin/$CURRENT_BRANCH

composer install
npm i
npm run prod

php artisan sitemap:create
php artisan cache:clear
php artisan config:clear
php artisan config:cache
