// Check migrations folder dan lihat file mana yang error

// Biasanya error dari file seperti:
// database/migrations/xxxx_create_foreign_keys.php
// atau
// database/migrations/xxxx_create_posts_table.php (yang punya foreign key ke users)

// Quick fix - jalankan ini:
php artisan migrate:refresh --seed --force

// Atau jika masih error, reset total:
php artisan migrate:reset
php artisan migrate
php artisan db:seed --class=UmkmSeeder
