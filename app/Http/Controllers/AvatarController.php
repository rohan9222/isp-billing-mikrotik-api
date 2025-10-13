<?php

namespace App\Http\Controllers;

use Intervention\Image\Laravel\Facades\Image;

class AvatarController extends Controller
{
    public function generateAvatar($name)
    {
        // নাম থেকে প্রথম অক্ষরগুলো বের করা
        // $initials = trim(collect(explode(' ', $name))->map(function ($segment) {
        //     return mb_substr($segment, 0, 1);
        // })->join(' '));
        $initials = collect(explode(' ', $name))->map(fn ($part) => strtoupper($part[0]))->join('');

        // অ্যাভাটার তৈরি করা
        $image = Image::create(500, 500)->fill('#cef2ef')
            ->text($initials, 250, 250, function ($font) {
                $font->file(public_path('webfonts/Tinos-Regular.ttf')); // ফন্ট পাথ
                $font->size(230);
                $font->color('#009e5c');
                $font->stroke('#cef2ef', 1);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
                $font->angle(0);
                $font->wrap(250);
            });

        // লোকাল ফাইলে সেভ করা
        $fileName = 'images/avatars/'.md5($name).'.png';
        $image->save(public_path($fileName));

        // ইমেজ URL রিটার্ন করা
        return asset($fileName);
    }
}
