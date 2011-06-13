<?php
class PdflibWrapper
{
    const PATH_CMAPS        = './resource/cmap';
    const TEMPLATE_PDF_PATH = 'template/sample.pdf';
    const DEFAULT_ENCODE    = "encoding UniJIS-UCS2-H textformat utf8 fontname KozGoPro-Medium";

    private $pdflib = null; // PDFオブジェクト
    private $page   = null; // PDF出力ページオブジェクト
    private $doc    = null; // PDF出力ドキュメントオブジェクト

    public function __construct()
    {
        $this->pdflib = new pdflib();
        $this->pdflib->set_parameter("SearchPath", self::PATH_CMAPS);
        $this->pdflib->set_parameter("errorpolicy", "return");
        $this->pdflib->begin_document('', 'compatibility=1.5');
    }

    public function setPage($tempalte_path = self::TEMPLATE_PDF_PATH)
    {
        $this->doc = $this->pdflib->open_pdi_document($tempalte_path, "pdiwarning=true");
        if ($this->doc == 0) {
            throw new Exception("Error: " . $this->pdflib->get_errmsg());
        }

        $this->page = $this->pdflib->open_pdi_page($this->doc, 1, "");
        if ($this->page == 0) {
            throw new Exception("Error: " . $this->pdflib->get_errmsg());
        }
        // ページは A4 サイズ
        $this->pdflib->begin_page_ext(595, 842, '');
        /* 読み込んだページを出力 */
        $this->pdflib->fit_pdi_page($this->page, 0, 0, "adjustpage");
    }

    public function setTextBlock($block_name, $contents)
    {
        if ($this->pdflib->fill_textblock($this->page, $block_name, $contents, self::DEFAULT_ENCODE) == 0) {
            throw new Exception("Error: " . $this->pdflib->get_errmsg());
        }
    }

    public function setImageBlock($block_name, $image_path)
    {
        $image = $this->pdflib->load_image("auto", $image_path, "");
        if ($image == 0) {
            throw new Exception("Error: " . $p->get_errmsg());
        }
        $this->pdflib->fill_imageblock($this->page, $block_name, $image, "");
    }

    public function output($output_pdfname = "output.pdf")
    {
        // ページ終了処理
        $this->pdflib->end_page_ext('');
        $this->pdflib->close_pdi_page($this->page);
        $this->pdflib->close_pdi_document($this->doc);
        $this->pdflib->end_document('');

        $buf = $this->pdflib->get_buffer();
        header("Content-Type: application/pdf");
        header("Content-Length: " . strlen($buf));
        header("Content-Disposition: inline; filename={$output_pdfname}");
        print $buf;
    }
}

