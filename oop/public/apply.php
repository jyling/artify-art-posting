<?php
require_once '../init.php';
Page::addHead();
Page::addNav();
?>
<div class="container mt-sm-3 mb-sm-3">
    <center>
        <div id="apply-image">
            <table>
                <tr>
                    <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='350' width='350'></td>
                    <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='350' width='350'></td>
                </tr>
                <tr>
                    <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='350' width='350'></td>
                    <td><img class='img-thumbnail' src="https://placeimg.com/400/400/any" height='350' width='350'></td>
                </tr>
            </table>
        </div>            
    </center>
</div>
        <div class="container " style="max-width: 701px">
            <h4><a href="#">Artist Name</a></h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo distinctio, eveniet neque praesentium illo nulla ut sed mollitia vel consequatur obcaecati provident at ipsam totam soluta? Adipisci ipsam eaque nemo!</p>
            <input class='btn btn-primary btn-block' type="button" value="Promote">
            <input class='btn btn-danger btn-block' type="button" value="Dismiss">
        </div>
<?php Page::addFoot(); ?>