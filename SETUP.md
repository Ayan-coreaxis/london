# London InstantPrint — Setup Guide

## Step 1: .env file banao
```
cp .env.example .env
php artisan key:generate
```

## Step 2: .env mein DB details bharo
```
DB_DATABASE=london_instantprint
DB_USERNAME=root
DB_PASSWORD=        ← XAMPP mein blank rakhein
```

## Step 3: phpMyAdmin mein database banao
```sql
CREATE DATABASE london_instantprint;
```

## Step 4: Migration + Seed run karo
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

## Step 5: Server chalao
```bash
php artisan serve
```

## URLs
- Website: http://localhost:8000
- Business Cards: http://localhost:8000/product/business-cards
- Admin Panel: http://localhost:8000/admin/products
- Add Product: http://localhost:8000/admin/products/create

## Files Jo Replace Ki Hain
- app/Http/Controllers/HomeController.php  ← DB se products
- app/Http/Controllers/ProductController.php ← NEW
- app/Models/Product.php ← NEW
- app/Models/ProductOption.php ← NEW
- app/Models/OptionValue.php ← NEW
- app/Models/ProductPreset.php ← NEW
- app/Models/ProductVariant.php ← NEW
- app/Models/ProductFaq.php ← NEW
- routes/web.php ← Updated
- database/migrations/..._create_products_tables.php ← NEW
- database/seeders/ProductSeeder.php ← NEW
- resources/views/pages/product-details.blade.php ← Fully dynamic
- resources/views/admin/products/index.blade.php ← NEW
- resources/views/admin/products/form.blade.php ← NEW (create+edit both)
