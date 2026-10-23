<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AkcourseAcademySeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $products = Product::where('status', Status::PRODUCT_APPROVED)->get();
        if ($products->isEmpty()) {
            return;
        }

        // ==========================================
        // 1. SEED STUDENT REVIEWS
        // ==========================================
        $reviewsData = [
            [
                'rating' => 5,
                'review' => 'This vocal mixing masterclass completely leveled up my mixing workflow. The dynamic EQ balance and parallel compression setup on lead vocals gave my tracks the commercial radio presence I was missing!',
            ],
            [
                'rating' => 5,
                'review' => 'Outstanding course and preset bundle! The vocal stems included in the downloads allowed me to follow along step-by-step. The de-essing and Auto-Tune formant tips are absolute gold.',
            ],
            [
                'rating' => 5,
                'review' => 'Best Afrobeats and modern rap vocal mixing tutorial on the internet. AK23 breaks down spatial acoustics, stereo delay throws, and reverb ducking so clearly. 10/10.',
            ],
            [
                'rating' => 4,
                'review' => 'Very comprehensive walkthrough of professional vocal chains. My vocals now sit right on top of heavy 808s and kick drums without harsh sibilance or low-mid mud.',
            ],
            [
                'rating' => 5,
                'review' => 'The DAW templates and plugin presets alone are worth double the price. Opened the project in FL Studio and had a finished commercial vocal mix in under 30 minutes.',
            ],
            [
                'rating' => 5,
                'review' => 'Clear, concise, and straight to the point. No fluff, just practical professional vocal production techniques from a real studio veteran.',
            ],
            [
                'rating' => 4,
                'review' => 'Incredible insight on vocal saturation and harmonics. The license key for the bundled plugin suite arrived in my student dashboard immediately after checkout.',
            ],
            [
                'rating' => 5,
                'review' => 'My clients have already noticed a dramatic improvement in clarity and warmth on their hook vocals. Essential investment for any serious recording and mixing engineer.',
            ],
        ];

        Review::truncate();
        $reviewIndex = 0;
        foreach ($products->take(8) as $product) {
            $student = $users[$reviewIndex % $users->count()];
            $data = $reviewsData[$reviewIndex % count($reviewsData)];

            $rev = new Review();
            $rev->user_id            = $student->id;
            $rev->author_id          = $product->user_id ?: 1;
            $rev->product_id         = $product->id;
            $rev->review_category_id = 0;
            $rev->rating             = $data['rating'];
            $rev->review             = $data['review'];
            $rev->is_reported        = Status::NO;
            $rev->created_at         = now()->subDays(rand(1, 45));
            $rev->updated_at         = $rev->created_at;
            $rev->save();

            // Update product ratings
            $product->avg_rating   = 4.90;
            $product->total_review = rand(12, 68);
            $product->total_sold   = rand(25, 140);
            $product->save();

            $reviewIndex++;
        }

        // ==========================================
        // 2. SEED STUDENT COMMENTS & ADMIN REPLIES
        // ==========================================
        Comment::truncate();
        $commentsData = [
            [
                'q' => 'Does this course and template include the full DAW project file for FL Studio and Pro Tools?',
                'a' => 'Yes! The download package includes ready-to-run template sessions for FL Studio 21/24, Logic Pro 11, and Pro Tools Studio, along with raw and processed WAV stems.',
            ],
            [
                'q' => 'Where do I find the license key for the bundled software tools after purchase?',
                'a' => 'Your license key and installer instructions are automatically dispatched to your student dashboard under the Software Licenses tab immediately upon payment confirmation.',
            ],
            [
                'q' => 'Are the practice vocal stems royalty-free to use in our own commercial mixing portfolio?',
                'a' => 'Absolutely! All stems provided in this academy series are 100% royalty-free for your personal portfolio and mix demonstration reels.',
            ],
            [
                'q' => 'I am on an Apple Silicon M2/M3 Mac. Are the plugins and presets fully compatible with native ARM?',
                'a' => 'Yes, 100% native ARM Apple Silicon and Windows 10/11 64-bit VST3/AU/AAX compatible.',
            ],
            [
                'q' => 'Does the course cover pitch correction with both Antares Auto-Tune and Celemony Melodyne?',
                'a' => 'Yes, Module 3 covers surgical pitch drift correction with Melodyne followed by real-time stylistic tuning and vibrato control with Auto-Tune Pro.',
            ],
        ];

        foreach ($commentsData as $idx => $item) {
            $student = $users[$idx % $users->count()];
            $product = $products[$idx % $products->count()];

            $comment = new Comment();
            $comment->user_id      = $student->id;
            $comment->review_id    = 0;
            $comment->product_id   = $product->id;
            $comment->parent_id    = 0;
            $comment->text         = $item['q'];
            $comment->is_reported  = Status::NO;
            $comment->author_reply = Status::NO;
            $comment->created_at   = now()->subDays(rand(2, 20));
            $comment->save();

            // Admin Instructor Reply
            $reply = new Comment();
            $reply->user_id      = 1; // Admin
            $reply->review_id    = 0;
            $reply->product_id   = $product->id;
            $reply->parent_id    = $comment->id;
            $reply->text         = $item['a'];
            $reply->is_reported  = Status::NO;
            $reply->author_reply = Status::YES;
            $reply->created_at   = Carbon::parse($comment->created_at)->addHours(3);
            $reply->save();
        }

        }
    }
}

