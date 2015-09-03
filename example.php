<?php

// namespace Awesome\DemoBundle\Controller;

// include_pathからTCPDFとFPDIを読み込む
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/fpdi.php');

// use Doctrine\ORM\EntityManager;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;

class DeliveriesPdfController //extends Controller
{
    public function listAction(/* Request $request */)
    {
        $receipt = new \FPDI();

        // PDFの余白(上左右)を設定
        $receipt->SetMargins(0, 0, 0);

        // ヘッダーの出力を無効化
        $receipt->setPrintHeader(false);

        // フッターの出力を無効化
        $receipt->setPrintFooter(false);

        // フォントを登録
        $fontPathRegular = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-regular.ttf';
//         $regularFont = $receipt->addTTFfont($fontPathRegular, '', '', 32);
        $font = new TCPDF_FONTS();
        $regularFont = $font->addTTFfont($fontPathRegular);

        $fontPathBold = $this->getLibPath() . '/tcpdf/fonts/migmix-2p-bold.ttf';
//         $boldFont = $receipt->addTTFfont($fontPathBold, '', '', 32);
        $font = new TCPDF_FONTS();
        $boldFont = $font->addTTFfont($fontPathBold);

        // ページを追加
        $receipt->AddPage();

        // テンプレートを読み込み
//         $receipt->setSourceFile($this->getLibPath() . '/tcpdf/tpl/receipt.pdf');
//         $receipt->setSourceFile($this->getLibPath() . '/tcpdf/tpl/template.pdf');
//         $receipt->setSourceFile($this->getLibPath() . '/tcpdf/tpl/w01_1.pdf');
        $receipt->setSourceFile($this->getLibPath() . '/tcpdf/tpl/senijiten.pdf');


        // 読み込んだPDFの1ページ目のインデックスを取得
        $tplIdx = $receipt->importPage(1);

        // 読み込んだPDFの1ページ目をテンプレートとして使用
        $receipt->useTemplate($tplIdx, null, null, null, null, true);

        // 書き込む文字列のフォントを指定
        $receipt->SetFont($regularFont, '', 11);

        // 書き込む文字列の文字色を指定
        $receipt->SetTextColor(0, 0, 255);

        // X : 42mm / Y : 108mm の位置に
        $receipt->SetXY(59, 248);

        // 文字列を書き込む

        $receipt->Write(0, isset($_POST['name']) ? $_POST['name'].'さん' : '名無しさん');

/*         $response = new Response(
            // Output関数の第一引数にはファイル名、第二引数には出力タイプを指定する
            // 今回は文字列で返してほしいので、ファイル名はnull、出力タイプは S = String を選択する
            $receipt->Output(null, 'S'),
            200,
            array('content-type' => 'application/pdf')
        );

        // レスポンスヘッダーにContent-Dispositionをセットし、ファイル名をreceipt.pdfに指定
        $response->headers->set('Content-Disposition', 'attachment; filename="receipt.pdf"');

        return $response;
 */
//         $receipt->
        $receipt->output('newpdf.pdf', 'I');
    }

    /**
     * @return string
     */
    private function getLibPath()
    {
        return '.';
//     	return realpath(sprintf('%s/../lib', $this->get('kernel')->getRootDir()));
    }
}

$controller = new DeliveriesPdfController();
$controller->listAction();
