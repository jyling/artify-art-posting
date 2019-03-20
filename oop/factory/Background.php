<?php
class Background{
    public static function get(){
        $db = DB::run();
        $db->getAll('post');
        $ramd = rand(0,$db->getCount() - 1);
        $path = $db->getResult()[$ramd]->artPath;
        echo "<style>
        .background-image {
            position: fixed;
            left: -5px;
            top:-5px;
            bottom:-5px;
            right: -5px;
            margin:-5px;
            z-index: -100 !important;
            background-size: cover;
            transform: scale(1.1); 
            display: block !important;
            background-image: url('$path') !important;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            -webkit-filter: contrast(72%) brightness(12%) saturate(74%) blur(10px);
            filter: contrast(72%) brightness(12%) saturate(74%) blur(10px);

          }
        </style>";
    }
}