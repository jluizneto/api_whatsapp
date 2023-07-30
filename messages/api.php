<?php


    switch($_POST['tipo_msg']) {
        case 'texto': enviar_msg_texto($_POST);
                    break;
        case 'midia': enviar_msg_midia($_POST);
                    break;
    }

    function enviar_msg_texto($dados) {
        $numero = $dados['numero'];
        $mensagem = $dados['mensagem'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.5.178:8000/zdg-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'number='.$numero.'&message='.$mensagem,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    function enviar_msg_midia($dados) {
        $numero = $dados['numero'];
        $legenda = $dados['legenda'];

        $arquivotemporario = $_FILES['midia']['tmp_name'];
        $aux = explode('.',  $_FILES['midia']['name']);
        $extensao = $aux[1];
        $nomearquivo = "file_" . $numero . date('Ymdhms') . '.' . $extensao;
        $diretorio = "../files/sent/";
        $linkImg = $diretorio . $nomearquivo;
        move_uploaded_file($arquivotemporario, $linkImg);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://192.168.5.178:8000/zdg-media',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'number='.$numero.'&caption='.$legenda.'&file=http://localhost/api_whatsapp/files/sent/'.$nomearquivo,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),  
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    }
?>