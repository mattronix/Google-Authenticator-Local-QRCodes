<?php
   include('lib/full/qrlib.php');


   $tempDir = 'tmp/';

/**
 * Simple script to generate TOTP seeds and to help configure devices for
 * testing.  This almost definitely is not a suitable mechanism for a
 * production environment, but shows how easy it is to setup TOTP.
 */

#create secret
function createSecret($secretLength = 16) {
    $validChars = _getBase32LookupTable();
    unset($validChars[32]);
    $secret = '';
    for ($i = 0; $i < $secretLength; $i++) {
        $secret .= $validChars[array_rand($validChars)];
    }
    return $secret;
}

function getQRCode($name, $secret) {
   global $tempDir;
   $url = 'otpauth://totp/'.$name.'?secret='.$secret.'';
   QRcode::png($url, $tempDir.$secret.".png" , QR_ECLEVEL_L, 10);
   return $tempDir.$secret.".png";
}


function _getBase32LookupTable() {
    return array(
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
        'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
        '='  // padding char
    );
} 

echo '<h1>Hello 2factor!</h1>';

$secret = createSecret();
echo "<strong>Your secret code is</strong>: $secret<br/>";
echo "<strong>QR Code fun</strong>: <br />";

$qr_path = getQRCode("MattronixIDP", $secret);
echo "<img src='$qr_path' />";

