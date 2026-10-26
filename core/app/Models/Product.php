<?php

namespace App\Models;

use App\Attributes\Casts;
use App\Attributes\Table;
use App\Constants\Status;
use App\Traits\HasModelAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

#[Table('products', key: 'id')]
#[Casts([
    'attribute_info' => 'object',
    'tags' => 'object'
])]
class Product extends Model {
    use HasModelAttributes;

    protected static $trendingProducts = null;
    public function getMyProductAttribute() {
        return auth()->id() == $this->getAttribute('user_id');
    }

    public function getNameAttribute() {
        return $this->attributes['title'] ?? ($this->attributes['name'] ?? '');
    }

    public function getThumbnailUrlAttribute() {
        $candidates = array_filter([
            $this->preview_image,
            $this->thumbnail,
            $this->inline_preview_image,
        ], function ($val) {
            return !empty($val) && $val !== 'default.png';
        });

        foreach ($candidates as $cand) {
            $candStr = trim((string)$cand);
            if (empty($candStr)) {
                continue;
            }

            // External URLs
            if (str_starts_with($candStr, 'http://') || str_starts_with($candStr, 'https://')) {
                return $candStr;
            }

            $candClean = ltrim($candStr, '/');

            // Direct relative path containing assets/
            if (str_starts_with($candClean, 'assets/')) {
                if (file_exists($candClean) || file_exists(base_path('../' . $candClean))) {
                    return asset($candClean);
                }
            }

            // Path relative to product slug folder
            if (!empty($this->slug)) {
                $relSlug = 'assets/files/product/' . $this->slug . '/' . $candClean;
                if (file_exists($relSlug) || file_exists(base_path('../' . $relSlug))) {
                    return asset($relSlug);
                }
            }

            // Direct in product root folder
            $relDirect = 'assets/files/product/' . $candClean;
            if (file_exists($relDirect) || file_exists(base_path('../' . $relDirect))) {
                return asset($relDirect);
            }
        }

        // Search directory for any valid image if no candidate was resolved
        if (!empty($this->slug)) {
            $relDir = 'assets/files/product/' . $this->slug;
            $scanDir = file_exists($relDir) ? $relDir : (file_exists(base_path('../' . $relDir)) ? base_path('../' . $relDir) : null);
            if ($scanDir && is_dir($scanDir)) {
                $files = glob($scanDir . '/*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}', GLOB_BRACE);
                if (!empty($files)) {
                    return asset($relDir . '/' . basename($files[0]));
                }
            }
        }

        return asset('assets/images/default.png');
    }

    public function getVideoEmbedInfoAttribute() {
        $url = trim((string)$this->demo_url);

        // If no demo_url but local preview_video file exists
        if (empty($url) && !empty($this->preview_video)) {
            $path = getFilePath('previewVideo') . '/' . productFilePath($this, 'preview_video');
            $url = getImage($path);
        }

        if (empty($url)) {
            return null;
        }

        // Auto-normalize protocol if omitted
        if (!preg_match('~^(?:f|ht)tps?://~i', $url)) {
            $url = 'https://' . $url;
        }

        // 1. YouTube (watch, embed, v, shorts, live, youtu.be, m.youtube.com, nocookie)
        if (preg_match('~(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})~i', $url, $m)) {
            $ytId = $m[1];
            return (object)[
                'type'      => 'youtube',
                'id'        => $ytId,
                'provider'  => 'YouTube',
                'embed_url' => "https://www.youtube-nocookie.com/embed/{$ytId}?autoplay=1&rel=0&modestbranding=1",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 2. Vimeo
        if (preg_match('~vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)~i', $url, $m)) {
            $vimeoId = end($m);
            return (object)[
                'type'      => 'vimeo',
                'id'        => $vimeoId,
                'provider'  => 'Vimeo',
                'embed_url' => "https://player.vimeo.com/video/{$vimeoId}?autoplay=1&title=0&byline=0",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 3. Google Drive (file/d/, open?id=, uc?id=, docs.google.com)
        if (preg_match('~(?:drive|docs)\.google\.com\/(?:file\/d\/|open\?id=|uc\?(?:.*&)?id=)([a-zA-Z0-9_-]+)~i', $url, $m)) {
            $driveId = $m[1];
            return (object)[
                'type'      => 'gdrive',
                'id'        => $driveId,
                'provider'  => 'Google Drive',
                'embed_url' => "https://drive.google.com/file/d/{$driveId}/preview",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 4. Loom
        if (preg_match('~loom\.com\/(?:share|embed)\/([a-zA-Z0-9_-]+)~i', $url, $m)) {
            $loomId = $m[1];
            return (object)[
                'type'      => 'loom',
                'id'        => $loomId,
                'provider'  => 'Loom',
                'embed_url' => "https://www.loom.com/embed/{$loomId}",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 5. Streamable
        if (preg_match('~streamable\.com\/(?:e\/)?([a-zA-Z0-9_-]+)~i', $url, $m)) {
            $stId = $m[1];
            return (object)[
                'type'      => 'streamable',
                'id'        => $stId,
                'provider'  => 'Streamable',
                'embed_url' => "https://streamable.com/e/{$stId}",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 6. SoundCloud
        if (preg_match('~soundcloud\.com\/([^\/]+\/[^\/\s]+)~i', $url)) {
            return (object)[
                'type'      => 'soundcloud',
                'id'        => null,
                'provider'  => 'SoundCloud',
                'embed_url' => "https://w.soundcloud.com/player/?url=" . urlencode($url) . "&color=%23f0a62f&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true",
                'raw_url'   => $url,
                'is_embed'  => true,
            ];
        }

        // 7. Audio (.mp3, .wav, .m4a, .ogg, .aac, .flac)
        if (preg_match('~\.(mp3|wav|m4a|ogg|aac|flac)(\?.*)?$~i', $url)) {
            return (object)[
                'type'      => 'audio',
                'id'        => null,
                'provider'  => 'Audio Walkthrough',
                'embed_url' => $url,
                'raw_url'   => $url,
                'is_embed'  => false,
            ];
        }

        // 8. Direct Video (.mp4, .webm, .mov, etc.) or stream
        return (object)[
            'type'      => 'video',
            'id'        => null,
            'provider'  => 'Direct Video (MP4)',
            'embed_url' => $url,
            'raw_url'   => $url,
            'is_embed'  => false,
        ];
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', Status::ENABLE);
    }

    public function isTrending() {
        if (is_null(self::$trendingProducts)) {
            self::$trendingProducts = ProductView::selectRaw('product_id, SUM(views) as total_views')
                ->whereBetween('views_date', [now()->subDays(7), now()])
                ->groupBy('product_id')
                ->orderBy('total_views', 'desc')
                ->limit(gs('trending_count'))
                ->pluck('product_id')
                ->toArray();
        }

        return in_array($this->id, self::$trendingProducts);
    }

    public function author() {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'username'   => 'ak23academy',
            'firstname'  => 'AK23',
            'lastname'   => 'Academy',
            'fullname'   => 'AK23 Academy',
            'email'      => 'info@wolinet.com',
            'avg_rating' => 5,
            'total_review' => 48,
        ]);
    }

    public function scopePending($query) {
        return $query->where('status', Status::PRODUCT_PENDING);
    }

    public function scopeCountComment($query) {
        return $query->withCount([
            'comments' => function ($query) {
                $query->where('review_id', 0)->where('parent_id', 0);
            },
        ]);
    }

    public function scopeAllActive($query) {
        $query->whereHas('category', function ($q) {
            $q->active();
        })->whereHas('subcategory', function ($q) {
            $q->active();
        });
    }

    public function scopeApproved($query) {
        return $query->where('status', Status::PRODUCT_APPROVED);
    }

    public function scopeHardRejected($query) {
        return $query->where('status', Status::PRODUCT_HARD_REJECTED);
    }

    public function scopeSoftRejected($query) {
        return $query->where('status', Status::PRODUCT_SOFT_REJECTED);
    }

    public function scopeDown($query) {
        return $query->where('status', Status::PRODUCT_DOWN);
    }

    public function scopeFileUpdated($query) {
        return $query->where('product_updated', 1);
    }

    public function scopeUpdatePending($query) {
        return $query('product_updated', Status::PRODUCT_UPDATE_PENDING);
    }

    public function scopeUpdateApproved($query) {
        return $query('product_updated', Status::PRODUCT_UPDATE_APPROVED);
    }

    public function scopeUpdateSoftRejected($query) {
        return $query('product_updated', Status::PRODUCT_UPDATE_SOFT_REJECT);
    }

    public function scopeUpdateHardRejected($query) {
        return $query('product_updated', Status::PRODUCT_UPDATE_HARD_REJECT);
    }

    public function scopePermanentDown($query) {
        return $query->where('status', Status::PRODUCT_PERMANENT_DOWN);
    }
    public function scopeWaiting($query) {
        return $query->whereIn('status', [Status::PRODUCT_PENDING, Status::PRODUCT_UPDATE_PENDING]);
    }

    public function changelogs() {
        return $this->hasMany(Changelog::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class)->latest();
    }

    public function productData() {
        return $this->hasMany(ProductData::class, 'product_id', 'id');
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function collections() {
        return collect();
    }

    public function rejections() {
        return collect();
    }

    public function downloads() {
        return $this->hasMany(Download::class, 'product_id');
    }

    public function getDownloadCountAttribute() {
        return $this->downloads()->count();
    }

    public function screenshots() {
        $slug          = $this->slug;
        $extractedPath = getFilePath('screenshots') . '/' . $slug . '/screenshots';

        if (!is_dir($extractedPath)) {
            return [];
        }

        $files = File::allFiles($extractedPath);

        return collect($files)->map(function ($file) use ($extractedPath) {
            return $extractedPath . '/' . $file->getRelativePathname();
        });
    }

    public function updateStatusBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->product_updated == Status::PRODUCT_UPDATE_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else if ($this->product_updated == Status::PRODUCT_UPDATE_APPROVED) {
                $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
            } else if ($this->product_updated == Status::PRODUCT_UPDATE_SOFT_REJECT) {
                $html = '<span class="badge badge--warning">' . trans('Soft Rejected') . '</span>';
            } else if ($this->product_updated == Status::PRODUCT_UPDATE_HARD_REJECT) {
                $html = '<span class="badge badge--danger">' . trans('Hard Rejected') . '</span>';
            } else if ($this->product_updated == Status::PRODUCT_NO_UPDATE) {
                $html = '<span class="badge bg-secondary">' . trans('No Update') . '</span>';
            }
            return $html;
        });
    }

    public function statusBadge(): Attribute {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PRODUCT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } else if ($this->status == Status::PRODUCT_APPROVED) {
                $html = '<span class="badge badge--success">' . trans('Approved') . '</span>';
            } else if ($this->status == Status::PRODUCT_SOFT_REJECTED) {
                $html = '<span class="badge badge--warning">' . trans('Soft Rejected') . '</span>';
            } else if ($this->status == Status::PRODUCT_HARD_REJECTED) {
                $html = '<span class="badge badge--danger">' . trans('Hard Rejected') . '</span>';
            } else if ($this->status == Status::PRODUCT_DOWN) {
                $html = '<span class="badge badge--warning">' . trans('Soft Disabled') . '</span>';
            } else if ($this->status == Status::PRODUCT_PERMANENT_DOWN) {
                $html = '<span class="badge badge--danger">' . trans('Permanent Disabled') . '</span>';
            }
            return $html;
        });
    }

    public function campaigns() {
        return $this->BelongsToMany(Campaign::class);
    }

    public function campaignProduct() {
        return $this->belongsTo(CampaignProduct::class, 'id', 'product_id')->where('status', Status::CAMPAIGN_PRODUCT_APPROVED);
    }

    public function getCampaignProductPriceAttribute() {
        $campaignProduct = $this->campaignProduct()
            ->whereHas('campaign', function ($query) {
                $query->where('status', Status::CAMPAIGN_ACTIVE)
                    ->where('end_date', '>', now());
            })
            ->first();

        $personalBuyerFee = $this->personalBuyerFee();
        if ($campaignProduct && $campaignProduct->discount_percentage > 0) {
            return [true, ($this->price + $personalBuyerFee) - (($this->price + $personalBuyerFee) * ($campaignProduct->discount_percentage / 100))];
        }
        return [false];
    }

    public function getCampaignProductCommercialPriceAttribute() {
        $campaignProduct = $this->campaignProduct()
            ->whereHas('campaign', function ($query) {
                $query->where('status', Status::CAMPAIGN_ACTIVE)
                    ->where('end_date', '>', now());
            })
            ->first();

        $commercialBuyerFee = $this->commercialBuyerFee();
        if ($campaignProduct && $campaignProduct->discount_percentage > 0) {
            return [true, ($this->price_cl + $commercialBuyerFee) - (($this->price_cl + $commercialBuyerFee) * ($campaignProduct->discount_percentage / 100))];
        }
        return [false];
    }

    public function productPrice($type) {
        if ($type == 'commercial') {
            return $this->price_cl + $this->category->commercial_buyer_fee;
        } else {
            return $this->price + $this->category->personal_buyer_fee;
        }

    }
    public function personalBuyerFee() {
        return $this->category->personal_buyer_fee;
    }

    public function commercialBuyerFee() {
        return $this->category->commercial_buyer_fee;
    }

    public function twelveMonthExtendedFee() {
        return $this->category->twelve_month_extended_fee;
    }
}
