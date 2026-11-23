<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            'کاغذ و مقوا' => ['کاغذ تحریر', 'کاغذ کرافت', 'مقوای دوپلکس', 'کاغذ حرارتی'],
            'چاپ' => ['چاپ افست', 'چاپ دیجیتال', 'چاپ فلکسو', 'چاپ سیلک'],
            'بسته‌بندی' => ['کارتن', 'فلکسیبل', 'جعبه لوکس', 'برچسب'],
            'ماشین‌آلات' => ['ماشین برش', 'دستگاه تا', 'بوبین‌ساز'],
        ];
        $i = 0;
        foreach ($tree as $parent => $children) {
            $p = Category::firstOrCreate(['slug' => slugify($parent)], ['name' => $parent, 'sort' => $i++]);
            foreach ($children as $c) {
                Category::firstOrCreate(['slug' => slugify($c)], ['name' => $c, 'parent_id' => $p->id]);
            }
        }
        foreach (['تهران', 'اصفهان', 'مشهد', 'تبریز', 'شیراز', 'کرج', 'قم', 'اهواز', 'رشت', 'یزد'] as $c) {
            City::firstOrCreate(['slug' => slugify($c)], ['name' => $c]);
        }
    }
}
