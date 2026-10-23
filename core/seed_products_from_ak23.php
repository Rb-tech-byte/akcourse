<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Category;
use App\Constants\Status;

echo "Extracting and seeding courses into main DB products...\n";

// Map series slug to Category ID
$seriesMap = [
    'vocal-mixing' => 1,
    'audio-editing-software' => 2,
    'beat-making' => 3,
];

// Ensure Categories exist and are active
foreach ([
    1 => ['name' => 'Vocal Mixing Series', 'slug' => 'vocal-mixing'],
    2 => ['name' => 'Audio Editing Software Series', 'slug' => 'audio-editing-software'],
    3 => ['name' => 'Beat Making Series', 'slug' => 'beat-making'],
] as $catId => $catData) {
    $cat = Category::find($catId);
    if (!$cat) {
        $cat = new Category();
        $cat->id = $catId;
    }
    $cat->name = $catData['name'];
    $cat->status = Status::ENABLE;
    $cat->featured = 1;
    $cat->save();
}

$seriesList = DB::connection('ak23')->table('series')->orderBy('sequence_no', 'asc')->get();

$seededCount = 0;

foreach ($seriesList as $series) {
    $categoryId = $seriesMap[$series->slug] ?? 1;

    // 1. Fetch courses in this series
    $courses = DB::connection('ak23')->select('
        SELECT 
            c.id as course_id,
            c.sequence_no, 
            c.slug as course_slug, 
            c.title as course_title, 
            c.short_name, 
            c.stage_label, 
            c.learning_outcome, 
            c.lesson_count, 
            c.video_duration_seconds, 
            c.accent_color, 
            sp.product_name as software_name, 
            sp.edition as software_edition, 
            p.sku, 
            p.name as product_name,
            p.description as product_desc,
            p.price_minor, 
            p.currency 
        FROM courses c 
        JOIN course_software cs ON cs.course_id = c.id 
        JOIN software_packages sp ON sp.id = cs.software_package_id 
        LEFT JOIN products p ON p.course_id = c.id 
        WHERE c.series_id = ? 
        ORDER BY c.sequence_no ASC
    ', [$series->id]);

    foreach ($courses as $c) {
        $sku = $c->sku ?: ('AK23-COURSE-' . strtoupper(str_replace('-', '_', $c->course_slug)));
        
        $product = Product::where('sku', $sku)->orWhere('slug', $c->course_slug)->first();
        if (!$product) {
            $product = new Product();
            $product->user_id = 1;
        }

        $product->sku = $sku;
        $product->product_type = 'course';
        $product->title = $c->course_title . ' - ' . $c->stage_label;
        $product->slug = $c->course_slug;
        $product->category_id = $categoryId;
        $product->price = $c->price_minor ?: 150000;
        $product->price_cl = round(($c->price_minor ?: 150000) * 1.5);
        $product->learning_outcome = $c->learning_outcome;
        $product->software_name = $c->software_name;
        $product->software_edition = $c->software_edition;
        $product->lesson_count = $c->lesson_count ?: 8;
        $product->video_duration_seconds = $c->video_duration_seconds ?: (120 * 60);
        $product->sequence_no = $c->sequence_no;
        $product->stage_label = $c->stage_label;
        $product->accent_color = $c->accent_color ?: '#F0A62F';
        $product->description = $c->product_desc ?: "<p>Complete course for <strong>{$c->course_title}</strong> with licensed software included.</p>";
        $product->status = Status::PRODUCT_APPROVED;
        $product->is_featured = 1;
        $product->is_free = 0;
        $product->save();

        echo "Seeded course: [{$product->sku}] {$product->title} - Price: {$product->price} TZS\n";
        $seededCount++;
    }

    // 2. Fetch Bundle in this series
    $bundle = DB::connection('ak23')->selectOne('
        SELECT 
            p.sku, 
            p.name, 
            p.description, 
            p.price_minor, 
            p.currency 
        FROM products p 
        WHERE p.series_id = ? AND p.product_type = "bundle"
    ', [$series->id]);

    if ($bundle) {
        $bundleSku = $bundle->sku ?: ('AK23-BUNDLE-' . strtoupper($series->slug));
        $bundleSlug = Str::slug($bundle->name);

        $bProduct = Product::where('sku', $bundleSku)->orWhere('slug', $bundleSlug)->first();
        if (!$bProduct) {
            $bProduct = new Product();
            $bProduct->user_id = 1;
        }

        $bProduct->sku = $bundleSku;
        $bProduct->product_type = 'bundle';
        $bProduct->title = $bundle->name;
        $bProduct->slug = $bundleSlug;
        $bProduct->category_id = $categoryId;
        $bProduct->price = $bundle->price_minor ?: 480000;
        $bProduct->price_cl = round(($bundle->price_minor ?: 480000) * 1.5);
        $bProduct->learning_outcome = 'Master all stages in the ' . $series->title;
        $bProduct->software_name = 'Complete Suite Bundle';
        $bProduct->software_edition = 'All Licenses Included';
        $bProduct->lesson_count = 50;
        $bProduct->video_duration_seconds = 600 * 60;
        $bProduct->sequence_no = 99;
        $bProduct->stage_label = 'Complete Series';
        $bProduct->accent_color = '#F0A62F';
        $bProduct->description = $bundle->description ?: "<p>Get all courses and matching software licenses in the {$series->title}.</p>";
        $bProduct->status = Status::PRODUCT_APPROVED;
        $bProduct->is_featured = 1;
        $bProduct->is_free = 0;
        $bProduct->save();

        echo "Seeded bundle: [{$bProduct->sku}] {$bProduct->title} - Price: {$bProduct->price} TZS\n";
        $seededCount++;
    }
}

echo "Total products seeded: {$seededCount}\n";
