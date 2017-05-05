<?php
function BROX_URL_RULER(){
    return array(
        '(^$)' => 'index/index',
        '(^/blog/(\d+)/(\w+).html$)' => 'index/blog?id:1&name:2',
        '(^/asd$)' => 'index/asd',
    );
}