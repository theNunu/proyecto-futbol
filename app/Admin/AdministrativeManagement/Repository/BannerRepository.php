<?php

namespace App\Admin\AdministrativeManagement\Repository;

use App\Models\Banner;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BannerRepository
{

    public function getAll()
    {

        return Banner::with('files')->get();
    }

    public function create(array $data): Banner
    {
        $news = Banner::create([
            'file_id' => $data['file_id'],
            // 'is_active' => $data['is_active'],
        ]);


        return $news->load('files');
    }

    public function findById(int $bannerId): ?Banner
    {
        return Banner::with('files')->where('banner_id', $bannerId)->first();
    }


    public function update(Banner $banners, array $data): Banner
    {
        $banners->update([
            'file_id' => $data['file_id'],
            // 'description' => $data['description'],
            // 'summary' => $data['summary'],
            // 'begin_date' => $data['begin_date'],
            // 'end_date' => $data['end_date'],
            // 'is_active' => $data['is_active'],
        ]);
        return $banners->load('files');
    }






}















