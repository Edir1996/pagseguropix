<?php

// Dados de configuração
$token_pagseguro = 'E8D14FDF33339C3BB4C7AFB162A01580'; // Substituir pelo seu token do PagSeguro
$url_servo_motor = 'http://10.10.0.2/libera-servo'; // Substituir pelo endereço do seu ESP32

// Recebe a notificação de PIX do PagSeguro
$notificacao = file_get_contents('php://input');

// Decodifica a notificação em um objeto JSON
$notificacao = json_decode($notificacao);

// Verifica se a notificação é de um PIX aprovado
if ($notificacao->status === 'APROVADO' && $notificacao->tipoTransacao === 'PIX') {

  // Faz uma requisição para liberar o servo motor do ESP32
  $dados = array('liberar' => true);
  $dados = json_encode($dados);
  $headers = array(
    'Authorization: Bearer '.$token_pagseguro,
    'Content-Type: application/json'
  );
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url_servo_motor);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $resposta = curl_exec($ch);
  curl_close($ch);

}

// Retorna uma resposta para o PagSeguro informando que a notificação foi recebida com sucesso
echo 'OK';
