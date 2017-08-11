<?php

add_action('init', 'customRSS');
function customRSS(){
        add_feed('jobs', 'customRSSjobs');
}

function customRSSjobs(){
        get_template_part('rss', 'jobs');
}
