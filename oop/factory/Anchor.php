<?php

class Anchor {
    public static function build($inputs = array()){
        
        $title = '';
        $href = '';
        $class = '';
        $id = '';
        $disable = '';
        $javascript = '';
        foreach ($inputs as $attributes => $attribute) {
            switch ($attributes) {
                case 'link':
                    if(is_array($attribute) &&
                       isset($attribute['source'], $attribute['get'])) {
                        $getMethods = $attribute['source'];
                        $href .= " href='Page::urlGetMaker($attribute[get])'";
                    }
                    else {
                        $href .= "href='$attribute'";
                    }
                    break;
                case 'title':
                    $title = $attribute;
                    break;
                case 'class':
                    $class = "class='" . implode(' ',$attribute) . "'";
                    break;
                case 'id' :
                    $id = "id='" . implode(' ',$attribute) . "'";
                    break;
                case 'disable':
                    if($attribute == true) {
                        $disable = 'disabled';
                    }
                    break;
                case 'onclick':
                    $javascript = "onclick='$attribute'";
                    break;
                default:
                    # code...
                    break;
            }

        }
        echo "<a $href $class $id $javascript $disable>$title</a>";

    }
}