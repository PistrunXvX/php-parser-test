<?php
require_once("../vendor/autoload.php");

$html = file_get_contents("https://lifehacker.ru/");

$dom = phpQuery::newDocument($html);

$articles = array();
$i = 0;
foreach($dom->find('.article-card__small-wrapper') as $key => $value){
    if ($i > 15) break;
    $pq = pq($value);
    $articles[$key]['href'] = str_replace('https://lifehacker.ru/', '', $pq->find('.lh-small-article-card__link')->attr('href'));
    // $articles[$key]['href'] = str_replace('https://lifehacker.ru/', '', $articles[$key]['href']);
    // $articles[$key]['href'] = trim('https://lifehacker.ru/');
    $articles[$key]['article-name'] = $pq->find('.lh-small-article-card__link')->attr('name');
    $articles[$key]['image'] = $pq->find('.lh-small-article-card__cover')->attr('src');
    $i++;
}

phpQuery::unloadDocuments();

// $articlesText = array();

// $currenArticles = file_get_contents('https://lifehacker.ru/tovary-dlya-remonta-gadzhetov/');
// $domCurrentArticle = phpQuery::newDocument($currenArticles);
// $articlesArr = array();
// $articlesPage = $domCurrentArticle->find('.single-article__post-content p');
//     foreach($articlesPage as $text){
//         $pq = pq($text);
//         $articlesArr .= $pq->text();
//         // var_dump($pq->text());
//         // echo $pq->text();
//     }
// // echo $domCurrentArticle;
// // var_dump($articlesArr);
foreach($articles as $key => $value) {
    $currenArticles = file_get_contents('https://lifehacker.ru' . $articles[$key]['href']);
    $domCurrentArticle = phpQuery::newDocument($currenArticles);
    $articlesPage = $domCurrentArticle->find('.single-article__post-content p');
    $textArticle = '';
    // var_dump($articlesPage);
    // $articles[$key]['text'] = $pq->find('.single-article__post-content')->find('p')->text();
    // foreach($domCurrentArticle->find('.single-article__post-content') as $key => $value) {
    //     $pq = pq($value);
    //     $articlesText[$key]['text'] = $pq->find('p')->text();
    // }
    foreach($articlesPage as $text){
        $pq = pq($text);
        $textArticle .= $pq->text();
        // var_dump($pq->text());
        // echo $pq->text();
    }
    $articles[$key]['text'] = $textArticle;
    phpQuery::unloadDocuments();
}

var_dump($articles);


?>
