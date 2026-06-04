<?php

// Patch Models
$models = ['Category', 'Product', 'Transaction', 'TransactionDetail'];
foreach ($models as $model) {
    $path = "app/Models/{$model}.php";
    $content = file_get_contents($path);
    $content = str_replace(
        "use Illuminate\Database\Eloquent\Model;",
        "use Illuminate\Database\Eloquent\Model;\nuse Stancl\Tenancy\Database\Concerns\BelongsToTenant;",
        $content
    );
    $content = str_replace(
        "use HasFactory;",
        "use HasFactory, BelongsToTenant;\n    protected \$guarded = [];",
        $content
    );
    file_put_contents($path, $content);
}

// User Model
$path = "app/Models/User.php";
$content = file_get_contents($path);
$content = str_replace(
    "use Illuminate\Foundation\Auth\User as Authenticatable;",
    "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Stancl\Tenancy\Database\Concerns\BelongsToTenant;",
    $content
);
$content = str_replace(
    "use HasFactory, Notifiable;",
    "use HasFactory, Notifiable, BelongsToTenant;",
    $content
);
// Make role fillable
$content = str_replace(
    "'password',",
    "'password',\n        'role',\n        'tenant_id',",
    $content
);
file_put_contents($path, $content);

// Patch Migrations
$migrations = glob('database/migrations/2026_06_04_0445*.php');
foreach ($migrations as $file) {
    $content = file_get_contents($file);
    if (strpos($file, 'categories') !== false) {
        $content = str_replace('$table->id();', '$table->id();'."\n".'            $table->string(\'tenant_id\')->index();'."\n".'            $table->string(\'name\');'."\n".'            $table->text(\'description\')->nullable();', $content);
    }
    if (strpos($file, 'products') !== false) {
        $content = str_replace('$table->id();', '$table->id();'."\n".'            $table->string(\'tenant_id\')->index();'."\n".'            $table->foreignId(\'category_id\')->constrained();'."\n".'            $table->string(\'name\');'."\n".'            $table->string(\'sku\');'."\n".'            $table->decimal(\'price\', 15, 2);'."\n".'            $table->decimal(\'cost_price\', 15, 2);'."\n".'            $table->integer(\'stock\')->default(0);', $content);
    }
    if (strpos($file, 'transactions_table') !== false) {
        $content = str_replace('$table->id();', '$table->id();'."\n".'            $table->string(\'tenant_id\')->index();'."\n".'            $table->foreignId(\'user_id\')->constrained();'."\n".'            $table->string(\'invoice_no\');'."\n".'            $table->decimal(\'total_amount\', 15, 2);'."\n".'            $table->string(\'payment_method\');', $content);
    }
    if (strpos($file, 'transaction_details') !== false) {
        $content = str_replace('$table->id();', '$table->id();'."\n".'            $table->foreignId(\'transaction_id\')->constrained()->onDelete(\'cascade\');'."\n".'            $table->foreignId(\'product_id\')->constrained();'."\n".'            $table->integer(\'quantity\');'."\n".'            $table->decimal(\'price\', 15, 2);'."\n".'            $table->decimal(\'subtotal\', 15, 2);', $content);
    }
    file_put_contents($file, $content);
}

// User Migration
$file = 'database/migrations/0001_01_01_000000_create_users_table.php';
$content = file_get_contents($file);
$content = str_replace('$table->id();', '$table->id();'."\n".'            $table->string(\'tenant_id\')->nullable()->index();'."\n".'            $table->enum(\'role\', [\'super_admin\', \'owner\', \'cashier\'])->default(\'owner\');', $content);
file_put_contents($file, $content);

echo "Patched successfully!";
