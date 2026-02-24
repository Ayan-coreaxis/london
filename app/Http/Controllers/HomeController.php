<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = [
            [
                'name'        => 'Leaflets & Flyers',
                'image'       => 'images/one.webp',
                'thumb_class' => 'pt-1',
                'url'         => route('products'),
            ],
            [
                'name'        => 'Greeting Cards',
                'image'       => 'images/two.webp',
                'thumb_class' => 'pt-2',
                'url'         => route('products'),
            ],
            [
                'name'        => 'Business Cards',
                'image'       => 'images/three.jpg',
                'thumb_class' => 'pt-3',
                'url'         => route('business-cards'),
            ],
            [
                'name'        => 'Postcards',
                'image'       => 'images/four.webp',
                'thumb_class' => 'pt-4',
                'url'         => route('products'),
            ],
        ];

        $blogs = [
            [
                'title'       => 'Smart Ways to Save Money on Printing Costs',
                'image'       => 'images/blog1.webp',
                'thumb_class' => 'bt-1',
                'url'         => route('blog'),
            ],
            [
                'title'       => 'The Ultimate Guide to Frame Sizes for Artwork & Photography',
                'image'       => 'images/blog2.webp',
                'thumb_class' => 'bt-2',
                'url'         => route('blog'),
            ],
            [
                'title'       => 'How to Write Wedding RSVP Cards: Wording, Etiquette & Design Tips',
                'image'       => 'images/blog3.webp',
                'thumb_class' => 'bt-3',
                'url'         => route('blog'),
            ],
            [
                'title'       => 'How to Design a Banner in Photoshop: Step-by-Step Guide',
                'image'       => 'images/blog4.webp',
                'thumb_class' => 'bt-4',
                'url'         => route('blog'),
            ],
        ];

        return view('pages.home', compact('products', 'blogs'));
    }
}