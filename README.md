<p>
セットアップ

ターミナルを起動し
- git clone https://github.com/titoworking0624/curriculum-management.git
- cd curriculum-management

ディレクトリ内リネーム
- mv .env.example .env

- composer install
- php artisan key:generate

- ./vendor/bin/sail up -d
- ./vendor/bin/sail npm install
- ./vendor/bin/sail artisan migrate:fresh
- ./vendor/bin/sail artisan db:seed

別ターミナルから
- sail npm run dev
</p>
