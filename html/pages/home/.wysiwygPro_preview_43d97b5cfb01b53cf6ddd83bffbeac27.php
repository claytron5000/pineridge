<?php
if ($_GET['randomId'] != "Kt4yPrTmEDcjQujkvYLFbnkFEhQ3kG11mWqukZhEZfaM5ftMtJ3PCXE9dzu2vFXN") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
