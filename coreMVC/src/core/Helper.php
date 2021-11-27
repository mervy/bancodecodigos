<?php

namespace Core;

class Helper {

    public $atual;
    public $contar;
    public $result;

    public function paginate($sql, $qtd, $request) {
        $q = $qtd;
        $artigos = $sql;
        if ($artigos != null) {
            $pag_arquivo = array_chunk($artigos, $q);

            $page = @$request->get->page ? @$request->get->page : NULL;
            $this->atual = (isset($page)) ? intval($page) : 1;
            $this->contar = count($pag_arquivo);
            $this->result = $pag_arquivo[$this->atual - 1];
        }
        return $this;
        /*
         * html e lógica para a view 
        <!-- Start pagination-->
        <?php if ($this->view->contar > 1): ?>
        <?php
        $pAtual = $this->view->atual;
        $pTotal = $this->view->contar;
        $pMax = 2;
        $pUrl = "/admin/show/category?page="; //Mude esse link 
        ?>
        <ul class="pagination justify-content-center mt-3">
            <li class="page-item">
                <a class="page-link" href="<?= $pUrl ?>1"><i class="fa fa-step-backward"></i></a>
            </li>
            <?php for ($pBefore = $pAtual - $pMax; $pBefore <= $pAtual - 1; $pBefore++): ?>
            <?php if ($pBefore >= 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pUrl . $pBefore ?>"><?= $pBefore ?></a>
            </li>
            <?php endif; ?>
            <?php endfor; ?>
            <li class="page-item active">
                <a class="page-link" href="#"><?= $pAtual ?></a>
            </li>
            <?php for ($pAfter = $pAtual + 1; $pAfter <= $pAtual + $pMax; $pAfter++): ?>
            <?php if ($pAfter <= $pTotal): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pUrl . $pAfter ?>"><?= $pAfter ?></a>
            </li>
            <?php endif; ?>
            <?php endfor; ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pUrl . $pTotal ?>"><i class="fa fa-step-forward"></i></a>
            </li>
        </ul>
        <?php endif; ?>
        <!-- End pagination -->
    */
    }

    public function resumir($texto, $qnt) {
        $resumo = substr(strip_tags($texto), '0', $qnt);
        $last = strrpos($resumo, " ");
        $resumo = substr($resumo, 0, $last);
        return $resumo . "...";
    }

    /**
     * http://clares.com.br/php-pegando-miniaturas-youtube-e-vimeo/
     * As variações para o VIMEO são: return $hash[0]["thumbnail_small"]; return $hash[0]["thumbnail_medium"]; ou 
     * return $hash[0]["thumbnail_large"] e E as variações para o YOUTUBE são:  default.jpg, 0.jpg, 1.jpg, etc
     * @param type $video 
     * @return type
     */
    public function getThumbs($video) {
        if (is_numeric($video)) {
            $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video" . ".php"));
            return $hash[0]["thumbnail_medium"];
        } else {
            return "http://img.youtube.com/vi/$video/0.jpg";
        }
    }

    public function urlSEO($string, $slug = false) {
        $texto = utf8_decode($string);
        $string = strtolower($texto);

        // Código ASCII das vogais
        $ascii['a'] = range(224, 230);
        $ascii['e'] = range(232, 235);
        $ascii['i'] = range(236, 239);
        $ascii['o'] = array_merge(range(242, 246), array(240, 248));
        $ascii['u'] = range(249, 252);

        // Código ASCII dos outros caracteres
        $ascii['b'] = array(223);
        $ascii['c'] = array(231);
        $ascii['d'] = array(208);
        $ascii['n'] = array(241);
        $ascii['y'] = array(253, 255);

        foreach ($ascii as $key => $item) {
            $acentos = '';
            foreach ($item AS $codigo)
                $acentos .= chr($codigo);
            $troca[$key] = '/[' . $acentos . ']/i';
        }

        $string = preg_replace(array_values($troca), array_keys($troca), $string);

        // Slug?
        if ($slug) {
            // Troca tudo que não for letra ou número por um caractere ($slug)
            $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
            // Tira os caracteres ($slug) repetidos
            $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
            $string = trim($string, $slug);
        }

        return $string;
    }

    public function setPathImg($art, $img, $w, $h) {
        $pathArticle = $this->urlSEO($art, '-');
        return "/assets/uploads/articles/thumbs.php?w=" . $w . '&h=' . $h . '&i=' . $pathArticle . '/' . $img;
    }

    public function sendEmail() {
        require("/vendor/phpmailer/phpmailer/src/PHPMailer.php");
        //D:\wamp64\www\01siteOne\vendor\phpmailer\phpmailer\src\PHPMailer.php
        require("/vendor/phpmailer/phpmailer/src/SMTP.php");
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "servidor.hostgator.com.br";
        $mail->Port = 465; // or 587
        $mail->IsHTML(true);
        $mail->Username = "origem@dominio.com.br";
        $mail->Password = "insira a senha aqui";
        $mail->SetFrom("origem@dominio.com.br");
        $mail->Subject = "Assunto da mensagem";
        $mail->Body = "Escreva o texto do email aqui";
        $mail->AddAddress("destino@dominio.com.br");
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Mensagem enviada com sucesso";
        }
    }
}
