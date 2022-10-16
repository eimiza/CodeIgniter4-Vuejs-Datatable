<?php 

function pagingTotalPage($countData, $limit=5){
    $pageCount = ceil($countData/$limit);
    return $pageCount;
}

function pagingOffset($page, $limit=5){
    $offset = ($page-1)*$limit;
    return $offset;
}

?>
