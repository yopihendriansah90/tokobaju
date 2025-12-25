<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $active = $request->query('active');

        $banners = Banner::query()
            ->when($active !== null, function ($query) use ($active) {
                $query->where('is_active', (bool) $active);
            }, function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'data' => $banners->map(function (Banner $banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle,
                    'cta_text' => $banner->cta_text,
                    'cta_link' => $banner->cta_link,
                    'is_active' => (bool) $banner->is_active,
                    'sort_order' => $banner->sort_order,
                    'image_url' => $banner->getFirstMediaUrl('banner_image') ?: null,
                ];
            }),
        ]);
    }
}
