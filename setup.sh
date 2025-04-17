#!/bin/bash

# 1. Create Laravel Project
composer create-project laravel/laravel:^10 "$1" || exit 1
cd $1 || exit 1

# 2. Modify composer.json
jq '. + {
  repositories: [{
    type: "path",
    url: "../packages/quicker-faster/code-gen"
  }],
  require: .require + {
    "quicker-faster/code-gen": "dev-main"
  }
}' composer.json > composer.tmp && mv composer.tmp composer.json

# 3. Install dependencies
composer require laravel/ui || exit 1
php artisan ui bootstrap || exit 1
sudo npm install || exit 1
sudo npm run dev || exit 1

# 4. Delete default files
rm -f app/Models/User.php
rm -f database/migrations/*_create_users_table.php
rm -f routes/web.php
rm -f resources/views/welcome.blade.php
# rm -f database/seeders/DatabaseSeeder.php
# rm -f database/seeders/UserSeeder.php

# 5. Publish your package scaffold
php artisan vendor:publish --tag=code-gen-ct-soft-ui-bootstrap-v2 || exit 1

# 6. Run migration and seed
# php artisan migrate || exit 1
# php artisan db:seed || exit 1

echo "✅ Laravel setup completed successfully!"
