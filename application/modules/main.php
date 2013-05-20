<?php

$title = "Главная";

/**
 * query
 */

$currentPosts = "SELECT * FROM post ORDER BY id_post DESC";

/**
 * create object paginator
 */

$paginator = new paginator($currentPosts);

/**
 * if p=% get page
 */

$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;

/**
 * get current page (1)
 */

/**
 * num view post in page (2)
 */

/**
 * size nav. (3)
 */

$paginator->setCurrentPage($page)->setItemsPerPage(3)->setSliceSizeByPages(2);

/**
 * get result
 */

$currentPosts = $paginator->getResult();


// echo '<pre>'; var_dump($currentPosts); echo '</pre>';
