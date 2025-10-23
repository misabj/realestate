@"
# RealEstate

Realestate project.

## Dev
- Install: \`npm install\` or \`composer install\` 
- Run: \`npm run dev\` (or your command) and \`php artisan serve\`
"@ | Out-File -Encoding utf8 README.md

## Local setup
1) Copy `.env.example` to `.env` and set DB creds
2) `composer install`
3) `php artisan key:generate`
4) `php artisan migrate --seed`
5) `npm install && npm run build`
6) `php artisan serve` (or your web server)