<?php

// Сообщение для шифрования
$message = 'Hello world!';

// Путь к открытому ключу (PEM формат)
$publicKeyPath = 'test_public_pkcs8_key.pem';

// Запись сообщения во временный файл
$tempMessageFile = tempnam(sys_get_temp_dir(), 'msg');
$tempEncryptedFile = tempnam(sys_get_temp_dir(), 'enc');
file_put_contents($tempMessageFile, $message);

// Команда OpenSSL для шифрования с использованием OAEP и SHA-256
$command = sprintf(
    'openssl pkeyutl -encrypt -inkey %s -pubin -in %s -out %s -pkeyopt rsa_padding_mode:oaep -pkeyopt rsa_oaep_md:sha256',
    escapeshellarg($publicKeyPath),
    escapeshellarg($tempMessageFile),
    escapeshellarg($tempEncryptedFile)
);

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    die('Ошибка при шифровании сообщения: ' . implode("\n", $output));
}

// Чтение зашифрованного сообщения и кодирование его в Base64
$encryptedMessage = file_get_contents($tempEncryptedFile);
$encryptedMessageBase64 = base64_encode($encryptedMessage);
echo "Зашифрованное сообщение (Base64): $encryptedMessageBase64\n";

// Удаление временных файлов
unlink($tempMessageFile);
unlink($tempEncryptedFile);

?>