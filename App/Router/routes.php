<?php

return [
    ['GET', '/', ['App\Controllers\ArticleController', 'index']],
    ['GET', '/{id}', ['App\Controllers\ArticleController', 'index']]
];