<?php
class Table
{
    public static function build($a = array())
    {
        $titles = array_keys(json_decode(json_encode($a[0]), true));
        $lists  = $a;
        self::builtHeader($titles);
        self::builtBody($lists);
        echo "</table>";
    }
    public static function builtHeader($input)
    {
        echo "
        <table class='table'>
        <thead class='table-striped'>
        <tr>";
        // var_dump($input);
        foreach ($input as $titles => $title) {
            echo "<th scope='col'>$title</th>";
        }
        echo "
        </tr>
        </thead>";
    }
    public static function builtBody($input)
    {
        foreach ($input as $contents => $content) {
            echo "<tr>";
            foreach ($content as $value) {
                echo "<td scope='col'>$value</td>";
            }
            echo "</tr>";
        }
    }
}
