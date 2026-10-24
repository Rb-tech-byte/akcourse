<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManageProductController extends Controller
{
    public function details($slug)
    {
        $pageTitle = 'Course Details';
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('admin.product.details', compact('pageTitle', 'product'));
    }

    public function create()
    {
        $pageTitle  = 'Create New Course';
        $categories = Category::active()->with('subCategories')->get();
        return view('admin.product.create', compact('pageTitle', 'categories'));
    }

    public function edit($id)
    {
        $pageTitle  = 'Edit Course';
        $product    = Product::with(['category', 'subCategory'])->findOrFail($id);
        $categories = Category::active()->with('subCategories')->get();
        return view('admin.product.edit', compact('pageTitle', 'product', 'categories'));
    }

    public function save(Request $request, $id = null)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'nullable',
            'price'           => 'nullable|numeric|gte:0',
            'price_cl'        => 'nullable|numeric|gte:0',
            'description'     => 'required|string',
            'demo_url'        => 'nullable|url',
            'thumbnail'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'preview_image'   => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
            'main_file'       => ['nullable', 'file', 'mimes:zip,rar,tar,gz,7z,pdf,mp4,mp3,wav', 'max:1048576'],
        ]);

        if ($id) {
            $product = Product::findOrFail($id);
            $message = 'Course updated successfully';
        } else {
            $product            = new Product();
            $product->user_id   = auth('admin')->id() ?: 1;
            $product->status    = $request->status ?? Status::PRODUCT_APPROVED;
            $product->published_at = now();
            $product->thumbnail = 'default.png';
            $message = 'New course published successfully';
        }

        $product->title           = $request->title;
        $product->slug            = $id && $product->slug ? $product->slug : productSlug($request->title);
        $product->category_id     = $request->category_id;
        $product->sub_category_id = $request->sub_category_id ?: 0;
        $product->is_free         = $request->has('is_free') && $request->is_free == 1 ? Status::YES : Status::NO;
        $product->price           = $product->is_free ? 0 : ($request->price ?? 0);
        $product->price_cl        = $product->is_free ? 0 : ($request->price_cl ?? 0);
        $product->demo_url        = $request->demo_url;
        $product->tags            = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
        $product->description     = $request->description;
        $product->is_featured     = $request->has('is_featured') ? Status::YES : Status::NO;
        if ($request->has('status')) {
            $product->status = $request->status;
        }

        // Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            try {
                $product->thumbnail = fileUploader(
                    $request->thumbnail,
                    getFilePath('productThumbnail') . '/' . $product->slug,
                    getFileSize('productThumbnail'),
                    $product->thumbnail ?? null
                );
            } catch (\Exception $e) {
                $notify[] = ['error', 'Could not upload thumbnail: ' . $e->getMessage()];
                return back()->withInput()->withNotify($notify);
            }
        }

        // Upload Preview Banner
        if ($request->hasFile('preview_image')) {
            try {
                $product->preview_image = fileUploader(
                    $request->preview_image,
                    getFilePath('productPreview') . '/' . $product->slug,
                    getFileSize('productPreview'),
                    $product->preview_image ?? null
                );
            } catch (\Exception $e) {
                $notify[] = ['error', 'Could not upload preview banner: ' . $e->getMessage()];
                return back()->withInput()->withNotify($notify);
            }
        }

        // Upload Main Download File
        if ($request->hasFile('main_file')) {
            try {
                $product->file = fileUploader(
                    $request->main_file,
                    getFilePath('productFile') . '/' . $product->slug,
                    null,
                    $product->file ?? null
                );
            } catch (\Exception $e) {
                $notify[] = ['error', 'Could not upload main file package: ' . $e->getMessage()];
                return back()->withInput()->withNotify($notify);
            }
        }

        $product->product_type           = $request->product_type ?: ($product->product_type ?: 'course');
        $product->sku                    = $request->sku ?: ($product->sku ?: ('AK23-' . ($product->product_type == 'bundle' ? 'BUNDLE-' : 'COURSE-') . strtoupper(Str::slug($product->title, '_'))));
        $product->learning_outcome       = $request->learning_outcome ?: $product->learning_outcome;
        $product->software_name          = $request->software_name ?: $product->software_name;
        $product->software_edition       = $request->software_edition ?: $product->software_edition;
        $product->stage_label            = $request->stage_label ?: $product->stage_label;
        $product->sequence_no            = $request->has('sequence_no') ? (int)$request->sequence_no : ($product->sequence_no ?: 1);
        $product->lesson_count           = $request->has('lesson_count') ? (int)$request->lesson_count : ($product->lesson_count ?: 8);
        $product->video_duration_seconds = $request->has('video_duration_seconds') ? (int)$request->video_duration_seconds : ($product->video_duration_seconds ?: 7200);
        if ($request->has('accent_color') && $request->accent_color) {
            $product->accent_color = $request->accent_color;
        }

        $product->save();

        // Sync to ak23.courses and ak23.products if academy DB is available
        try {
            $ak23Exists = DB::connection('ak23')->table('courses')->where('slug', $product->slug)->first();
            if ($ak23Exists) {
                DB::connection('ak23')->table('courses')->where('id', $ak23Exists->id)->update([
                    'title'                  => $product->title,
                    'stage_label'            => $product->stage_label ?: 'Professional Masterclass',
                    'learning_outcome'       => $product->learning_outcome ?: '',
                    'lesson_count'           => $product->lesson_count ?: 8,
                    'video_duration_seconds' => $product->video_duration_seconds ?: 7200,
                    'sequence_no'            => $product->sequence_no ?: 1,
                    'description'            => strip_tags($product->description),
                    'status'                 => $product->status == Status::PRODUCT_APPROVED ? 'active' : 'draft',
                    'updated_at'             => now(),
                ]);
            } else {
                $firstSeries = DB::connection('ak23')->table('series')->first();
                $seriesId = $firstSeries ? $firstSeries->id : DB::raw('UNHEX(REPLACE(UUID(), "-", ""))');
                DB::connection('ak23')->table('courses')->insert([
                    'id'                     => DB::raw('UNHEX(REPLACE(UUID(), "-", ""))'),
                    'series_id'              => $seriesId,
                    'slug'                   => $product->slug,
                    'title'                  => $product->title,
                    'short_name'             => Str::limit($product->title, 20),
                    'stage_label'            => $product->stage_label ?: 'Professional Masterclass',
                    'learning_outcome'       => $product->learning_outcome ?: 'Master commercial vocal balance and professional spatial acoustics.',
                    'description'            => strip_tags($product->description),
                    'sequence_no'            => $product->sequence_no ?: 1,
                    'lesson_count'           => $product->lesson_count ?: 12,
                    'video_duration_seconds' => $product->video_duration_seconds ?: 7200,
                    'status'                 => 'active',
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            }

            if ($product->sku) {
                DB::connection('ak23')->table('products')->where('sku', $product->sku)->update([
                    'price_minor' => (int)$product->price,
                    'name'        => $product->title,
                    'updated_at'  => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Non-blocking sync
        }

        $notify[] = ['success', $message];
        return to_route('admin.product.all')->withNotify($notify);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->orderItems()->exists()) {
            $notify[] = ['error', 'Cannot delete this course because student orders exist for it. You can disable or unfeature it instead.'];
            return back()->withNotify($notify);
        }

        $product->delete();
        $notify[] = ['success', 'Course deleted successfully'];
        return back()->withNotify($notify);
    }

    public function pending()
    {
        $pageTitle = 'Pending Review Courses';
        $products  = $this->productData('pending');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function approved()
    {
        $pageTitle = 'Published Courses';
        $products  = $this->productData('approved');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function softRejected()
    {
        $pageTitle = 'Soft Rejected Products';
        $products  = $this->productData('softRejected');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function hardRejected()
    {
        $pageTitle = 'Hard Rejected Products';
        $products  = $this->productData('hardRejected');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function down()
    {
        $pageTitle = 'Soft Disabled Products';
        $products  = $this->productData('down');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function permanentDown()
    {
        $pageTitle = 'Permanent Disabled Products';
        $products  = $this->productData('permanentDown');
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    public function all($authorId = 0)
    {
        $pageTitle = 'Course Catalog & Management';
        $products  = $this->productData(authorId: $authorId);
        return view('admin.product.list', compact('pageTitle', 'products'));
    }

    private function productData($scope = null, $authorId = 0)
    {
        $products = $scope ? Product::$scope() : Product::query();
        if ($authorId) $products = $products->where('user_id', $authorId);
        $perPage = request()->get('per_page', 4);
        $products = $products->with(['author', 'category', 'subCategory'])
            ->searchable(['title', 'author:username', 'slug'])
            ->filter(['is_free', 'category_id', 'status'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        return $products;
    }

    public function toggleFeature($id)
    {
        $product = Product::findOrFail($id);

        $product->is_featured = !$product->is_featured;
        $product->save();
        $notify[] = ['success', 'Course featured status changed successfully'];
        return back()->withNotify($notify);
    }
}
