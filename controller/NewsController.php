<?php
// Prevent direct script access
defined('BASEPATH') or exit('No direct script access allowed');

// Include the News model
require_once 'model/NewsModel.php';

class NewsController {

    // Display the list of news items
    public function list() {
        $model = new NewsModel();              // Create an instance of the model
        $news_list = $model->getAll();              // Fetch all news items
        require 'view/news_list.php';          // Load the view to display the list
    }

    // Display a single news item based on the provided slug
    public function show($param) {
        $model = new NewsModel();              // Create an instance of the model
        $news_item = $model->getBySlug($param); // Fetch a news item by slug
        require 'view/news_details.php';       // Load the view to display the item
    }
}
