<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseApiController extends Controller {

    public function courses(Request $request) {
        $products = Product::latest('id')->get();

        if ($products->isNotEmpty()) {
            $notify[] = 'Academy courses fetched successfully';
            return responseSuccess('courses', $notify, [
                'vendor'   => 'AK23 Academy',
                'courses'  => $products,
            ]);
        }

        try {
            $courses = DB::connection('ak23')->select('
                SELECT 
                    BIN_TO_UUID(c.id, 1) as course_uuid,
                    c.sequence_no, 
                    c.slug, 
                    c.title, 
                    c.short_name, 
                    c.stage_label, 
                    c.learning_outcome, 
                    c.lesson_count, 
                    c.video_duration_seconds, 
                    c.accent_color, 
                    sp.product_name as software_name, 
                    sp.edition as software_edition, 
                    p.sku, 
                    p.price_minor, 
                    p.currency
                FROM courses c 
                JOIN course_software cs ON cs.course_id = c.id 
                JOIN software_packages sp ON sp.id = cs.software_package_id 
                LEFT JOIN products p ON p.course_id = c.id 
                ORDER BY c.sequence_no ASC
            ');

            if (!empty($courses)) {
                $notify[] = 'Academy courses fetched successfully';
                return responseSuccess('courses', $notify, [
                    'vendor'   => 'AK23 Academy',
                    'count'    => count($courses),
                    'courses'  => $courses,
                ]);
            }
        } catch (\Throwable $e) {}

        $notify[] = 'No courses found';
        return responseError('not_found', $notify);
    }

    public function courseDetail(Request $request) {
        $slugOrSku = $request->slug ?? $request->sku ?? $request->id;

        if (!$slugOrSku) {
            return responseError('validation_error', ['Identifier (slug, sku, or id) is required']);
        }

        if (is_numeric($slugOrSku)) {
            $product = Product::find($slugOrSku);
            if ($product) {
                $notify[] = 'Course details fetched successfully';
                return responseSuccess('course_detail', $notify, [
                    'vendor'        => 'AK23 Academy',
                    'image_path'    => getFilePath('productPreview'),
                    'thumbnail'     => getFilePath('productThumbnail'),
                    'video_preview' => getFilePath('previewVideo'),
                    'course'        => $product,
                ]);
            }
        }

        try {
            $course = DB::connection('ak23')->selectOne('
                SELECT 
                    BIN_TO_UUID(c.id, 1) as course_uuid,
                    c.sequence_no, 
                    c.slug, 
                    c.title, 
                    c.short_name, 
                    c.stage_label, 
                    c.learning_outcome, 
                    c.lesson_count, 
                    c.video_duration_seconds, 
                    c.accent_color, 
                    sp.product_name as software_name, 
                    sp.edition as software_edition, 
                    p.sku, 
                    p.price_minor, 
                    p.currency,
                    p.description as product_description
                FROM courses c 
                JOIN course_software cs ON cs.course_id = c.id 
                JOIN software_packages sp ON sp.id = cs.software_package_id 
                LEFT JOIN products p ON p.course_id = c.id 
                WHERE c.slug = ? OR p.sku = ?
                LIMIT 1
            ', [$slugOrSku, $slugOrSku]);

            if ($course) {
                $notify[] = 'Course details fetched successfully';
                return responseSuccess('course_detail', $notify, [
                    'vendor'        => 'AK23 Academy',
                    'course'        => $course,
                ]);
            }
        } catch (\Throwable $e) {}

        $notify[] = 'Course not found';
        return responseError('not_found', $notify);
    }
}
