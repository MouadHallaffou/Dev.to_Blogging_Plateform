<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mouadhallaffou\DevtoCms\Article;

$article = new Article();
echo $article->getTitle();
