<?php
function getPreviousPage() {
    if (isset($_SESSION['history']) && count($_SESSION['history']) > 1) {
        array_pop($_SESSION['history']);
        return end($_SESSION['history']);
    }
    return 'index.php';
}
