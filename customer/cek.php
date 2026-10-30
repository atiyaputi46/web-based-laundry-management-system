<?php
if(isset($_POST['kode'])) {
    header("Location: portal.php?keyword=" . urlencode(trim($_POST['kode'])));
    exit;
}
header("Location: portal.php");
exit;
