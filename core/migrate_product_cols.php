<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$colsToAdd = [
    'sku' => ['type' => 'string', 'length' => 100, 'nullable' => true],
    'product_type' => ['type' => 'string', 'length' => 50, 'default' => 'course'],
    'learning_outcome' => ['type' => 'text', 'nullable' => true],
    'software_name' => ['type' => 'string', 'length' => 255, 'nullable' => true],
    'software_edition' => ['type' => 'string', 'length' => 255, 'nullable' => true],
    'lesson_count' => ['type' => 'integer', 'default' => 0],
    'video_duration_seconds' => ['type' => 'integer', 'default' => 0],
    'sequence_no' => ['type' => 'integer', 'default' => 1],
    'stage_label' => ['type' => 'string', 'length' => 100, 'nullable' => true],
    'accent_color' => ['type' => 'string', 'length' => 50, 'nullable' => true],
];

Schema::table('products', function (Blueprint $table) use ($colsToAdd) {
    foreach ($colsToAdd as $col => $def) {
        if (!Schema::hasColumn('products', $col)) {
            $field = null;
            if ($def['type'] === 'string') {
                $field = $table->string($col, $def['length'] ?? 255);
            } elseif ($def['type'] === 'text') {
                $field = $table->text($col);
            } elseif ($def['type'] === 'integer') {
                $field = $table->integer($col);
            }
            if ($field) {
                if (!empty($def['nullable'])) {
                    $field->nullable();
                }
                if (isset($def['default'])) {
                    $field->default($def['default']);
                }
            }
            echo "Added column: {$col}\n";
        } else {
            echo "Column already exists: {$col}\n";
        }
    }
});

echo "Schema update completed.\n";
