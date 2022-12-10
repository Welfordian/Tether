<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\YoutubeVideo;

class IndexController extends Controller
{
    public function show(): string
    {
        $users = User::all();
        $videos = (new YoutubeVideo())->select([])->get();
        
        echo "<pre>";
        print_r($users);
        print_r($videos);
        echo "</pre>";
        
        die();
        
        return $this->view('index');
    }
}