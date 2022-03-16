<?php
require_once("../vendor/autoload.php");

$html = file_get_contents("https://lifehacker.ru/");

$dom = phpQuery::newDocument($html);

$articles = array();
$i = 0;

// Входим на главную страницу
foreach($dom->find('.article-card__small-wrapper') as $key => $value){
    if ($i > 15) break;
    $pq = pq($value);
    $articles[$key]['href'] = str_replace('https://lifehacker.ru/', '', $pq->find('.lh-small-article-card__link')->attr('href'));
    $articles[$key]['article-name'] = $pq->find('.lh-small-article-card__link')->attr('name');
    $articles[$key]['image'] = $pq->find('.lh-small-article-card__cover')->attr('src');
    $i++;
}

phpQuery::unloadDocuments();

// Парсим статьи
foreach($articles as $key => $value) {
    $currenArticles = file_get_contents('https://lifehacker.ru' . $articles[$key]['href']);
    $domCurrentArticle = phpQuery::newDocument($currenArticles);
    $articlesPage = $domCurrentArticle->find('.single-article__post-content p');
    $textArticle = '';
    foreach($articlesPage as $text){
        $pq = pq($text);
        $textArticle .= $pq->text();
    }
    $articles[$key]['text'] = $textArticle;
    phpQuery::unloadDocuments();
}
?>
 <html>
     <head>
         <title>
             PHP Parser
         </title>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     </head>
     <body>
        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1>Новости</h1>
                    </div>
                </div>
                <?php foreach($articles as $key => $value): ?>
                <div class="row" style="margin-top: 10%;">
                    <div class="col-6">
                        <div class="card">
                            <img src="<?= $articles[$key]['image']; ?>" alt="">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= $articles[$key]['article-name']; ?>
                                </h5>
                                <p class="card-text">
                                    <?php 
                                        $str = substr($articles[$key]['text'], 0, 200);
                                        echo substr($str, 0, strrpos($str, ' ' )) . '...'; 
                                    ?>
                                </p>
                                <a  class="btn btn-primary" data-bs-toggle="collapse" href="#collapse-<?= $key ?>" role="button" aria-expanded="false" aria-controls="collapse-<?= $key ?>">
                                    Читать
                                </a>
                                <div class="collapse" id="collapse-<?= $key ?>">
                                    <div>
                                       <?= $articles[$key]['text'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
     </body>
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
 </html>