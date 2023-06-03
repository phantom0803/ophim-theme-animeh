<?php

namespace Ophim\ThemeAnimeH;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ThemeAnimeHServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->setupDefaultThemeCustomizer();
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'themes');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('themes/animeh')
        ], 'animeh-assets');
    }

    protected function setupDefaultThemeCustomizer()
    {
        config(['themes' => array_merge(config('themes', []), [
            'animeh' => [
                'name' => 'Theme AnimeH',
                'author' => 'opdlnf01@gmail.com',
                'package_name' => 'ophimcms/theme-animeh',
                'publishes' => ['animeh-assets'],
                'preview_image' => '',
                'options' => [
                    [
                        'name' => 'per_page_limit',
                        'label' => 'Pages limit',
                        'type' => 'number',
                        'value' => 20,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-6',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'movie_related_limit',
                        'label' => 'Movies related limit',
                        'type' => 'number',
                        'value' => 10,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-6',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'latest',
                        'label' => 'Home Page',
                        'type' => 'code',
                        'hint' => 'display_label|display_description|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_more_url',
                        'value' => <<<EOT
                        Phim đề cử|Những bộ phim đang được quan tâm nhiều nhất||is_recommended|1|updated_at|desc|20|#
                        Phim chiếu rạp mới|Tổng hợp phim chiếu rạp vietsub||is_shown_in_theater|1|created_at|desc|10|/danh-sach/phim-chieu-rap
                        Phim bộ mới|Phim bộ mới cập nhật||type|series|updated_at|desc|10|/danh-sach/phim-bo
                        Phim lẻ mới|Phim lẻ mới cập nhật||type|single|updated_at|desc|10|/danh-sach/phim-le
                        Phim hoạt hình mới|Phim hoạt hình mới cập nhật|categories|slug|hoat-hinh|updated_at|desc|10|/the-loai/hoat-hinh
                        Top phim|Những phim được xem nhiều nhất||is_copyright|0|view_week|desc|10|#
                        EOT,
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'hotest',
                        'label' => 'Danh sách hot',
                        'type' => 'code',
                        'hint' => 'Label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_template (top_text|top_thumb)',
                        'value' => "Top phim lẻ||type|single|view_total|desc|9|top_text\r\nTop phim bộ||type|series|view_total|desc|9|top_thumb",
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'additional_css',
                        'label' => 'Additional CSS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'body_attributes',
                        'label' => 'Body attributes',
                        'type' => 'text',
                        'value' => 'style="color: rgb(33, 37, 41); background: rgb(20,20,20); font-family: Kodchasan, sans-serif;"',
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'additional_header_js',
                        'label' => 'Header JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_body_js',
                        'label' => 'Body JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_footer_js',
                        'label' => 'Footer JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'footer',
                        'label' => 'Footer',
                        'type' => 'code',
                        'value' => <<<EOT
                        <footer class="page-footer dark" style="background: rgb(20,20,20);">
                            <div class="text-uppercase footer-copyright" style="background: rgba(20,20,20,0.07);border-style: none;">
                                <p><strong>Elune Media Team 2021</strong></p>
                            </div>
                        </footer>
                        EOT,
                        'tab' => 'Custom HTML'
                    ],
                    [
                        'name' => 'ads_header',
                        'label' => 'Ads header',
                        'type' => 'code',
                        'value' => <<<EOT
                        <img src="" alt="">
                        EOT,
                        'tab' => 'Ads'
                    ],
                    [
                        'name' => 'ads_catfish',
                        'label' => 'Ads catfish',
                        'type' => 'code',
                        'value' => <<<EOT
                        <img src="" alt="">
                        EOT,
                        'tab' => 'Ads'
                    ]
                ],
            ]
        ])]);
    }
}
