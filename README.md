# THEME - ANIMEH 2023 - OPHIM CMS

## Demo
### Trang Chủ
![Alt text](https://i.ibb.co/vZrw96p/THEME-ANIMEH-INDEX.png "Home Page")

### Trang Danh Sách Phim
![Alt text](https://i.ibb.co/4NXPwvF/THEME-ANIMEH-CATALOG.png "Catalog Page")

### Trang Thông Tin Phim
![Alt text](https://i.ibb.co/855MgVy/THEME-ANIMEH-SINGLE.png "Single Page")

### Trang Xem Phim
![Alt text](https://i.ibb.co/bj4FpXF/THEME-ANIMEH-EPISODE.png "Episode Page")

## Requirements
https://github.com/hacoidev/ophim-core

## Install
1. Tại thư mục của Project: `composer require ophimcms/theme-animeh`
2. Kích hoạt giao diện trong Admin Panel

## Update
1. Tại thư mục của Project: `composer update ophimcms/theme-animeh`
2. Re-Activate giao diện trong Admin Panel

## Note
- Một vài lưu ý quan trọng của các nút chức năng:
    + `Activate` và `Re-Activate` sẽ publish toàn bộ file js,css trong themes ra ngoài public của laravel.
    + `Reset` reset lại toàn bộ cấu hình của themes

## Document
### List
- Home page: `display_label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_more_url`
####
    Phim chiếu rạp mới||is_shown_in_theater|1|created_at|desc|10|/danh-sach/phim-chieu-rap
    Phim bộ mới||type|series|updated_at|desc|10|/danh-sach/phim-bo
    Phim lẻ mới||type|single|updated_at|desc|10|/danh-sach/phim-le
    Phim hoạt hình mới|categories|slug|hoat-hinh|updated_at|desc|10|/the-loai/hoat-hinh
    Top phim||is_copyright|0|view_week|desc|10|#
####

### Custom View Blade
- File blade gốc trong Package: `/vendor/ophimcms/ophim-animeh/resources/views/themeanimeh`
- Copy file cần custom đến: `/resources/views/vendor/themes/animeh`
