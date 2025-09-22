<?php

class HomeController extends Controller {
    public function index() {
        $data = [
            'title' => 'Welcome to Your Offline POS System',
            'description' => 'This is the home page. The MVC framework is working!'
        ];
        $this->view('home/index', $data);
    }
}
