<?php

function registError($a, $c) {
    if (!empty($a[$c])) {
        return '
            <article class="message is-danger">
            <div class="message-header">
                <p><strong>ERREUR:</strong> ' . $a[$c] . '</p>
            </div>
            </article>';
    }

    return null;
}

function itemImage($img, $own = NULL) {
    $colors = '';
    foreach ($img->palette as $hex)
        $colors .= '<a href="/search.php?color=' . str_replace('#', '', $hex) . '" style="background-color: ' . $hex . '">   </a>';

    return '<div class="column is-2-desktop is-one-third-tablet is-half-mobile">
                <div id="image-modal-' . $img->id . '" class="modal">
                <div class="modal-background"></div>
                <div class="modal-content">
                    <p class="image">
                    <img src="' . $img->path . '">
                    </p>
                </div>
                <button class="modal-close"></button>
                </div>
                <figure class="listing image is-square">
                    <img class="thumbnail" src="' . $img->path . '">'
                    . ( $own
                        ?  '<div class="info">
                                <a class="icon" href="/delete.php?id=' . $img->id . '"><i class="fa fa-trash"></i></a> 
                                <a class="icon modal-button" data-target="#image-modal-' . $img->id . '"><i class="fa fa-eye"></i></a>
                                <a class="icon" href="/edit.php?id=' . $img->id . '"><i class="fa fa-paint-brush"></i></a>
                            </div>'
                        :   '<div class="info">
                                <a href="/user.php?id=' . $img->user_id . '">' . ucfirst($img->user_name) . '<hr></a>
                                <a class="icon modal-button" data-target="#image-modal-' . $img->id . '"><i class="fa fa-eye"></i></a>
                            </div>'
                    ) . 
                '</figure>
                <div class="palette">' . $colors . '</div>
            </div>';
}

?>