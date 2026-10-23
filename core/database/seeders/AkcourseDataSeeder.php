<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\User;
use App\Constants\Status;
use Illuminate\Support\Str;

class AkcourseDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Configure GeneralSetting so wizard banner never appears and info is correct
        $general = GeneralSetting::first();
        if ($general) {
            $general->site_name = 'AK23 Academy';
            $general->cur_text = 'TSH';
            $general->cur_sym = 'TSH ';
            $general->email_from = 'support@ak23academy.com';
            $general->email_from_name = 'AK23 Academy';
            $general->sms_from = 'AK23';
            $general->config_progress = [
                'general_setting',
                'logo_favicon',
                'notification_template',
                'deposit_method',
                'seo',
                'policy_content',
                'withdrawal_method'
            ];
            $general->save();
        }

        // 2. Ensure Selcom Gateway exists
        $selcom = Gateway::where('code', '801')->orWhere('alias', 'selcom')->first();
        if (!$selcom) {
            $selcom = new Gateway();
            $selcom->code = '801';
            $selcom->name = 'Selcom Mobile Money';
            $selcom->alias = 'selcom';
            $selcom->status = 1;
            $selcom->gateway_parameters = json_encode(['api_key' => 'SELCOM_API_KEY_SECURE', 'vendor_id' => 'AK23_VENDOR']);
            $selcom->supported_currencies = ['TZS' => 'TZS', 'TSH' => 'TSH'];
            $selcom->crypto = 0;
            $selcom->description = 'Direct payment via Selcom Mobile Money (M-Pesa, Tigo Pesa, Airtel Money, Halopesa)';
            $selcom->save();
        }

        // 3. Ensure Students (Users 1-6) exist with full names
        $usersData = [
            ['username' => 'amina', 'email' => 'amina@example.com', 'firstname' => 'Amina', 'lastname' => 'Mushi', 'mobile' => '255754123456'],
            ['username' => 'johnkweka', 'email' => 'john.kweka@example.com', 'firstname' => 'John', 'lastname' => 'Kweka', 'mobile' => '255713987654'],
            ['username' => 'rohema', 'email' => 'rohema.ngowi@example.com', 'firstname' => 'Rohema', 'lastname' => 'Ngowi', 'mobile' => '255784556677'],
            ['username' => 'davidkimaro', 'email' => 'david.kimaro@example.com', 'firstname' => 'David', 'lastname' => 'Kimaro', 'mobile' => '255762334455'],
            ['username' => 'fatma', 'email' => 'fatma.mohamed@example.com', 'firstname' => 'Fatma', 'lastname' => 'Mohamed', 'mobile' => '255773112233'],
            ['username' => 'emmanuel', 'email' => 'emmanuel.mtei@example.com', 'firstname' => 'Emmanuel', 'lastname' => 'Mtei', 'mobile' => '255755998877'],
        ];

        $users = [];
        foreach ($usersData as $ud) {
            $user = User::where('email', $ud['email'])->orWhere('username', $ud['username'])->first();
            if (!$user) {
                $user = new User();
                $user->username = $ud['username'];
                $user->email = $ud['email'];
                $user->password = bcrypt('demo1234');
                $user->status = 1;
                $user->ev = 1;
                $user->sv = 1;
            }
            $user->firstname = $ud['firstname'];
            $user->lastname = $ud['lastname'];
            $user->mobile = $ud['mobile'];
            $user->country_code = 'TZ';
            $user->save();
            $users[] = $user;
        }

        // 4. Product Families / Categories
        $categoriesData = [
            [
                'name' => 'Vocal Mixing Series',
                'file_type' => 'audio',
                'file_size' => 500,
                'preview_file_types' => ['mp3', 'mp4'],
                'personal_buyer_fee' => 0,
                'commercial_buyer_fee' => 0,
                'twelve_month_extended_fee' => 0,
                'status' => 1,
                'featured' => 1,
            ],
            [
                'name' => 'Audio Editing Software Series',
                'file_type' => 'audio',
                'file_size' => 500,
                'preview_file_types' => ['mp3', 'mp4'],
                'personal_buyer_fee' => 0,
                'commercial_buyer_fee' => 0,
                'twelve_month_extended_fee' => 0,
                'status' => 1,
                'featured' => 1,
            ],
            [
                'name' => 'Beat Making Series',
                'file_type' => 'audio',
                'file_size' => 500,
                'preview_file_types' => ['mp3', 'mp4'],
                'personal_buyer_fee' => 0,
                'commercial_buyer_fee' => 0,
                'twelve_month_extended_fee' => 0,
                'status' => 1,
                'featured' => 1,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $cat = Category::firstOrNew(['name' => $cData['name']]);
            $cat->file_type = $cData['file_type'];
            $cat->file_size = $cData['file_size'];
            $cat->preview_file_types = $cData['preview_file_types'];
            $cat->personal_buyer_fee = $cData['personal_buyer_fee'];
            $cat->commercial_buyer_fee = $cData['commercial_buyer_fee'];
            $cat->twelve_month_extended_fee = $cData['twelve_month_extended_fee'];
            $cat->status = $cData['status'];
            $cat->featured = $cData['featured'];
            $cat->save();
            $categories[$cData['name']] = $cat;
        }

        // 5. SubCategories
        $subCategoriesData = [
            ['name' => 'Vocal Mixing Courses', 'category_id' => $categories['Vocal Mixing Series']->id],
            ['name' => 'Vocal Production Bundles', 'category_id' => $categories['Vocal Mixing Series']->id],
            ['name' => 'Audio Software Courses', 'category_id' => $categories['Audio Editing Software Series']->id],
            ['name' => 'Audio Software Bundles', 'category_id' => $categories['Audio Editing Software Series']->id],
            ['name' => 'Beat Making Courses', 'category_id' => $categories['Beat Making Series']->id],
            ['name' => 'Beat Making Bundles', 'category_id' => $categories['Beat Making Series']->id],
        ];

        $subCategories = [];
        foreach ($subCategoriesData as $sData) {
            $sub = SubCategory::firstOrNew(['name' => $sData['name'], 'category_id' => $sData['category_id']]);
            $sub->status = 1;
            $sub->save();
            $subCategories[$sData['name']] = $sub;
        }

        // 6. Products from AKCOURSE.MD (17 courses + 3 bundles)
        $productsData = [
            // Family 1: Vocal Mixing Series
            [
                'title' => 'Tuned Plugins - Getting Started',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Mixing Courses',
                'price' => 120000,
                'price_cl' => 180000,
                'stage' => 'Getting Started',
                'outcome' => 'Build your first simple vocal-processing workflow with pitch and tone plugins.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 42,
                'avg_rating' => 4.9,
                'tags' => ['Vocal Mixing', 'Tuned Plugins', 'Pitch Correction', 'Beginner'],
            ],
            [
                'title' => 'Cymatics Voxity - Vocal Tone & Dynamics',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Mixing Courses',
                'price' => 150000,
                'price_cl' => 220000,
                'stage' => 'Beginner',
                'outcome' => 'Shape vocal tone, dynamics, and space with confidence using Voxity suite.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 68,
                'avg_rating' => 5.0,
                'tags' => ['Cymatics', 'Voxity', 'Vocal Processing', 'Dynamics'],
            ],
            [
                'title' => 'Nuro Audio Xvox Pro - Compression & FX',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Mixing Courses',
                'price' => 180000,
                'price_cl' => 260000,
                'stage' => 'Developing Your Skills',
                'outcome' => 'Balance compression, de-essing, tone, and vocal effects like a pro engineer.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 0,
                'total_sold' => 35,
                'avg_rating' => 4.8,
                'tags' => ['Nuro Audio', 'Xvox Pro', 'Vocal Compression', 'Intermediate'],
            ],
            [
                'title' => 'iZotope Nectar 4 - Intermediate Vocal Suite',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Mixing Courses',
                'price' => 210000,
                'price_cl' => 300000,
                'stage' => 'Intermediate',
                'outcome' => 'Develop a detailed vocal-processing workflow with machine learning assist tools.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 53,
                'avg_rating' => 4.9,
                'tags' => ['iZotope', 'Nectar 4', 'Vocal Suite', 'AI Audio'],
            ],
            [
                'title' => 'Waves Ultimate 17 - Advanced Chain Building',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Mixing Courses',
                'price' => 250000,
                'price_cl' => 360000,
                'stage' => 'Advanced Chain Building',
                'outcome' => 'Assemble an elite vocal chain using CLA-76, PuigChild, Renaissance, and SSL G-Master.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 84,
                'avg_rating' => 5.0,
                'tags' => ['Waves Ultimate', 'Analog Emulation', 'Vocal Chain', 'Mastering'],
            ],
            [
                'title' => 'Complete Vocal Mixing Series (Bundle)',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Production Bundles',
                'price' => 480000,
                'price_cl' => 700000,
                'stage' => 'All Stages Bundle',
                'outcome' => 'Complete 5-course vocal master series with all matching plugin suites & templates.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 112,
                'avg_rating' => 5.0,
                'tags' => ['Complete Bundle', 'Vocal Mixing', 'All Plugins', 'Masterclass'],
            ],

            // Family 2: Audio Editing Software Series
            [
                'title' => 'Fender Studio 8 - Software Setup & Workflow',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 140000,
                'price_cl' => 200000,
                'stage' => 'Foundation',
                'outcome' => 'Master Fender Studio 8 interface, recording tracks, latency optimization, and routing.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 0,
                'total_sold' => 29,
                'avg_rating' => 4.7,
                'tags' => ['Fender Studio 8', 'DAW Setup', 'Recording', 'Audio Editing'],
            ],
            [
                'title' => 'Cubase Pro - Advanced Audio Arrangement',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 190000,
                'price_cl' => 270000,
                'stage' => 'Intermediate',
                'outcome' => 'Learn Steinberg Cubase pro audio editing, VariAudio, group channels, and mix stems.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 46,
                'avg_rating' => 4.9,
                'tags' => ['Cubase', 'Steinberg', 'VariAudio', 'Audio Editing'],
            ],
            [
                'title' => 'FL Studio 21 - Beat & Vocal Arrangement',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 165000,
                'price_cl' => 240000,
                'stage' => 'Beginner to Pro',
                'outcome' => 'Complete guide to playlist arrangement, mixer routing, automation, and stem exports.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 134,
                'avg_rating' => 5.0,
                'tags' => ['FL Studio', 'Image-Line', 'Arrangement', 'Automation'],
            ],
            [
                'title' => 'Ableton Live 12 - Creative Audio Production',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 220000,
                'price_cl' => 310000,
                'stage' => 'Creative Production',
                'outcome' => 'Warp modes, session view, creative clips, MIDI racks, and live performance setup.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 61,
                'avg_rating' => 4.9,
                'tags' => ['Ableton Live', 'Warping', 'Session View', 'Live Performance'],
            ],
            [
                'title' => 'Logic Pro - Studio Recording & Comping',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 185000,
                'price_cl' => 265000,
                'stage' => 'Recording & Editing',
                'outcome' => 'Take folders, quick swipe comping, Flex Pitch, Flex Time, and Apple Loops workflow.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 0,
                'total_sold' => 38,
                'avg_rating' => 4.8,
                'tags' => ['Logic Pro', 'Apple', 'Comping', 'Flex Pitch'],
            ],
            [
                'title' => 'Pro Tools - Industry Standard Audio Editing',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Courses',
                'price' => 240000,
                'price_cl' => 340000,
                'stage' => 'Industry Standard',
                'outcome' => 'Beat Detective, Elastic Audio, playlist lane tracking, I/O routing, and broadcast delivery.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 77,
                'avg_rating' => 5.0,
                'tags' => ['Pro Tools', 'Avid', 'Beat Detective', 'Broadcast Audio'],
            ],
            [
                'title' => 'Complete Audio Software Series (Bundle)',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Bundles',
                'price' => 590000,
                'price_cl' => 850000,
                'stage' => 'Complete DAW Suite',
                'outcome' => 'Master all 6 major DAWs with installation, license guidance, and production templates.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 95,
                'avg_rating' => 5.0,
                'tags' => ['Complete Series', 'DAW Bundle', 'All DAWs', 'Software Suite'],
            ],

            // Family 3: Beat Making Series
            [
                'title' => 'Beat Making Foundations',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 120000,
                'price_cl' => 180000,
                'stage' => 'Level 1: Foundations',
                'outcome' => 'Tempo, groove, rhythm fundamentals, drum kit selection, and building 4-bar loops.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 0,
                'total_sold' => 58,
                'avg_rating' => 4.8,
                'tags' => ['Beat Making', 'Rhythm', 'Drums', 'Foundations'],
            ],
            [
                'title' => 'Drum Programming & Swing Secrets',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 135000,
                'price_cl' => 195000,
                'stage' => 'Level 2: Drums',
                'outcome' => 'Hi-hat rolls, velocity dynamics, ghost notes, punchy kicks, and syncopated snare grooves.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 64,
                'avg_rating' => 4.9,
                'tags' => ['Drum Programming', 'Hi-Hats', 'Swing', 'Afrobeat & Trap'],
            ],
            [
                'title' => 'Melodies & Chord Progressions',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 145000,
                'price_cl' => 210000,
                'stage' => 'Level 3: Harmony',
                'outcome' => 'Emotional minor chords, top-line melodies, counter-melodies, and arpeggios.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 72,
                'avg_rating' => 4.9,
                'tags' => ['Chords', 'Music Theory', 'Melodies', 'Harmony'],
            ],
            [
                'title' => 'Bass & 808 Sound Design',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 140000,
                'price_cl' => 205000,
                'stage' => 'Level 4: Low End',
                'outcome' => 'Tuning 808s, sidechain compression, glide notes, saturation, and sub-bass clarity.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 89,
                'avg_rating' => 5.0,
                'tags' => ['808s', 'Sub Bass', 'Saturation', 'Low End Mix'],
            ],
            [
                'title' => 'Sampling & Sound Design Techniques',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 155000,
                'price_cl' => 225000,
                'stage' => 'Level 5: Sampling',
                'outcome' => 'Chop vinyl samples, time-stretching, pitch shifting, texturing, and custom instrument presets.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 0,
                'total_sold' => 47,
                'avg_rating' => 4.8,
                'tags' => ['Sampling', 'Sound Design', 'Vinyl Chops', 'Synths'],
            ],
            [
                'title' => 'Arrangement & Final Beat Mix',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Courses',
                'price' => 160000,
                'price_cl' => 230000,
                'stage' => 'Level 6: Final Polish',
                'outcome' => 'Full beat arrangement structure, intro/drop transitions, leveling, and stereo width.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 63,
                'avg_rating' => 4.9,
                'tags' => ['Arrangement', 'Transitions', 'Beat Mix', 'Mastering'],
            ],
            [
                'title' => 'Complete Beat Making Series (Bundle)',
                'category' => 'Beat Making Series',
                'sub_category' => 'Beat Making Bundles',
                'price' => 520000,
                'price_cl' => 750000,
                'stage' => 'Complete Beat Series',
                'outcome' => 'All 6 beat making courses plus 10GB certified drum kits, 808 banks, and MIDI libraries.',
                'status' => Status::PRODUCT_APPROVED,
                'is_featured' => 1,
                'total_sold' => 108,
                'avg_rating' => 5.0,
                'tags' => ['Complete Beat Bundle', '10GB Soundbank', 'MIDI Packs', 'All Courses'],
            ],

            // Pending Software Licenses (Status: PENDING) for Software Licenses page
            [
                'title' => 'Waves Ultimate 17 Studio License Key',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Production Bundles',
                'price' => 250000,
                'price_cl' => 360000,
                'stage' => 'Software License',
                'outcome' => 'Official software license key delivery & installation support for Waves Ultimate 17.',
                'status' => Status::PRODUCT_PENDING,
                'is_featured' => 0,
                'total_sold' => 12,
                'avg_rating' => 5.0,
                'tags' => ['Software License', 'Waves', 'License Key'],
            ],
            [
                'title' => 'iZotope Nectar 4 Pro Activation Code',
                'category' => 'Vocal Mixing Series',
                'sub_category' => 'Vocal Production Bundles',
                'price' => 210000,
                'price_cl' => 300000,
                'stage' => 'Software License',
                'outcome' => 'Original iZotope Portal license entitlement with iZotope account activation.',
                'status' => Status::PRODUCT_PENDING,
                'is_featured' => 0,
                'total_sold' => 8,
                'avg_rating' => 4.9,
                'tags' => ['Software License', 'iZotope', 'Nectar 4'],
            ],
            [
                'title' => 'FL Studio 21 Signature Edition License',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Bundles',
                'price' => 290000,
                'price_cl' => 400000,
                'stage' => 'Software License',
                'outcome' => 'Lifetime free updates license key registration for Image-Line FL Studio 21.',
                'status' => Status::PRODUCT_PENDING,
                'is_featured' => 0,
                'total_sold' => 15,
                'avg_rating' => 5.0,
                'tags' => ['Software License', 'FL Studio', 'Image-Line'],
            ],
            [
                'title' => 'Ableton Live 12 Suite Serial Number',
                'category' => 'Audio Editing Software Series',
                'sub_category' => 'Audio Software Bundles',
                'price' => 450000,
                'price_cl' => 600000,
                'stage' => 'Software License',
                'outcome' => 'Full commercial educational authorization code for Ableton Live 12 Suite.',
                'status' => Status::PRODUCT_PENDING,
                'is_featured' => 0,
                'total_sold' => 10,
                'avg_rating' => 5.0,
                'tags' => ['Software License', 'Ableton Live', 'Suite'],
            ],
        ];

        $products = [];
        $adminUser = $users[0]; // Author reference
        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category']];
            $sub = $subCategories[$pData['sub_category']];
            $slug = Str::slug($pData['title']);

            $prod = Product::firstOrNew(['slug' => $slug]);
            $prod->title = $pData['title'];
            $prod->user_id = $adminUser->id;
            $prod->category_id = $cat->id;
            $prod->sub_category_id = $sub->id;
            $prod->price = $pData['price'];
            $prod->price_cl = $pData['price_cl'];
            $prod->status = $pData['status'];
            $prod->is_featured = $pData['is_featured'];
            $prod->total_sold = $pData['total_sold'];
            $prod->avg_rating = $pData['avg_rating'];
            $prod->total_review = rand(15, 60);
            $prod->description = '<p>Welcome to <strong>' . htmlspecialchars($pData['title']) . '</strong> at AK23 Academy. ' . htmlspecialchars($pData['outcome']) . '</p><p>Includes full downloadable video lessons, studio stems, project files, practice presets, and matching licensed software package assistance.</p>';
            $prod->tags = $pData['tags'];
            $prod->attribute_info = [
                (object)['name' => 'Skill Stage', 'value' => $pData['stage']],
                (object)['name' => 'Software License Included', 'value' => 'Yes (Confirmed)'],
                (object)['name' => 'Format', 'value' => 'HD Video + Practice Resources + License Key'],
                (object)['name' => 'Language', 'value' => 'English & Swahili'],
            ];
            $prod->save();
            $products[$pData['title']] = $prod;
        }

        // 7. Orders, OrderItems, Deposits (Live Commerce & Selcom Data)
        // Clear previous test orders if any
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        OrderItem::truncate();
        Order::truncate();
        Deposit::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $ordersList = [
            [
                'user' => $users[0], // Amina Mushi
                'product' => $products['Cymatics Voxity - Vocal Tone & Dynamics'],
                'amount' => 150000,
                'trx' => 'SELCOM-MP-839210',
                'channel' => 'M-Pesa Selcom',
                'created_at' => now()->subHours(2),
                'status' => 1, // Paid
                'is_refunded' => 0,
            ],
            [
                'user' => $users[1], // John Kweka
                'product' => $products['Complete Audio Software Series (Bundle)'],
                'amount' => 590000,
                'trx' => 'SELCOM-TG-592811',
                'channel' => 'Tigo Pesa Selcom',
                'created_at' => now()->subHours(6),
                'status' => 1, // Paid
                'is_refunded' => 0,
            ],
            [
                'user' => $users[2], // Rohema Ngowi
                'product' => $products['Waves Ultimate 17 - Advanced Chain Building'],
                'amount' => 250000,
                'trx' => 'SELCOM-AIR-441029',
                'channel' => 'Airtel Money Selcom',
                'created_at' => now()->subHours(14),
                'status' => 1, // Paid
                'is_refunded' => 0,
            ],
            [
                'user' => $users[3], // David Kimaro
                'product' => $products['FL Studio 21 - Beat & Vocal Arrangement'],
                'amount' => 165000,
                'trx' => 'SELCOM-MP-993814',
                'channel' => 'M-Pesa Selcom',
                'created_at' => now()->subDays(1),
                'status' => 1, // Paid
                'is_refunded' => 0,
            ],
            [
                'user' => $users[4], // Fatma Mohamed
                'product' => $products['Complete Vocal Mixing Series (Bundle)'],
                'amount' => 480000,
                'trx' => 'SELCOM-HALO-123490',
                'channel' => 'Halopesa Selcom',
                'created_at' => now()->subDays(2),
                'status' => 1, // Paid
                'is_refunded' => 0,
            ],
            [
                'user' => $users[5], // Emmanuel Mtei
                'product' => $products['Beat Making Foundations'],
                'amount' => 120000,
                'trx' => 'SELCOM-MP-772108',
                'channel' => 'M-Pesa Selcom',
                'created_at' => now()->subDays(3),
                'status' => 1, // Paid
                'is_refunded' => 1, // Refunded / Entitlement Adjustment
            ],
            [
                'user' => $users[0], // Amina Mushi
                'product' => $products['iZotope Nectar 4 - Intermediate Vocal Suite'],
                'amount' => 210000,
                'trx' => 'SELCOM-MP-662309',
                'channel' => 'M-Pesa Selcom',
                'created_at' => now()->subDays(4),
                'status' => 1,
                'is_refunded' => 0,
            ],
            [
                'user' => $users[1], // John Kweka
                'product' => $products['Pro Tools - Industry Standard Audio Editing'],
                'amount' => 240000,
                'trx' => 'SELCOM-TG-448201',
                'channel' => 'Tigo Pesa Selcom',
                'created_at' => now()->subDays(5),
                'status' => 1,
                'is_refunded' => 0,
            ],
            [
                'user' => $users[2], // Rohema Ngowi
                'product' => $products['Complete Beat Making Series (Bundle)'],
                'amount' => 520000,
                'trx' => 'SELCOM-AIR-882910',
                'channel' => 'Airtel Money Selcom',
                'created_at' => now()->subDays(6),
                'status' => 1,
                'is_refunded' => 0,
            ],
        ];

        foreach ($ordersList as $oItem) {
            $order = new Order();
            $order->user_id = $oItem['user']->id;
            $order->amount = $oItem['amount'];
            $order->discount = 0;
            $order->payment_status = $oItem['status'];
            $order->trx = $oItem['trx'];
            $order->created_at = $oItem['created_at'];
            $order->updated_at = $oItem['created_at'];
            $order->save();

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->purchase_code = 'AK23-' . strtoupper(Str::random(10));
            $orderItem->user_id = $oItem['user']->id;
            $orderItem->product_id = $oItem['product']->id;
            $orderItem->product_price = $oItem['amount'];
            $orderItem->quantity = 1;
            $orderItem->license = 1; // Regular
            $orderItem->downloads = rand(1, 8);
            $orderItem->is_refunded = $oItem['is_refunded'];
            $orderItem->created_at = $oItem['created_at'];
            $orderItem->updated_at = $oItem['created_at'];
            $orderItem->save();

            $deposit = new Deposit();
            $deposit->user_id = $oItem['user']->id;
            $deposit->order_id = $order->id;
            $deposit->method_code = '801';
            $deposit->amount = $oItem['amount'];
            $deposit->method_currency = 'TSH';
            $deposit->charge = 0;
            $deposit->rate = 1;
            $deposit->final_amount = $oItem['amount'];
            $deposit->trx = $oItem['trx'];
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->detail = (object)[
                'channel' => $oItem['channel'],
                'phone' => $oItem['user']->mobile,
                'selcom_order_id' => 'SLCM-' . rand(100000, 999999),
                'customer_name' => $oItem['user']->fullname,
            ];
            $deposit->created_at = $oItem['created_at'];
            $deposit->updated_at = $oItem['created_at'];
            $deposit->save();
        }

        echo "AK23 Academy data seeded successfully!" . PHP_EOL;
    }
}
