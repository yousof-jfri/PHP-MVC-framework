<?php

namespace App\Controllers;

use App\Models\Article;
use Core\Controller;
use Core\View;
class SeriesController extends Controller 
{
    public function index()
    {
        return 'hello';
    }
    
    public function allEpisodes($title , $id)
    {
        $article = new Article();

        
        return View::render('index', ['title' => $title, 'id' => $id]);
    }
}