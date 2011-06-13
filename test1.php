<?php
require('PdflibWrapper.php');

try {
    $pdf = new PdflibWrapper();

    $pdf->setPage('test1.pdf');

    // テキスト
    $contents = "I desire to do your will, O my God; your law is within my heart.";
    $contents .= "これは日本語テストです。";
    $pdf->setTextBlock('full_name', $contents);
    $pdf->output();
}
catch (PDFlibException $e) {
    die("PDFlib exception occurred in sample:\n" .
        "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
        $e->get_errmsg() . "\n");
}
catch (Exception $e) {
    die($e);
}
exit();
