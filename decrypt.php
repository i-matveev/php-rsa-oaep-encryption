<?php

// Зашифрованное сообщение в Base64
$encryptedMessageBase64 = "CDYM9qjyWb27Z+OdbaWwcMkqK3SR56oQVDVV8Pqd1clIRO3xSvrmzo0JmKJEpyyYJE+9Myvmd9gXTbza2xgqhrQ+HQ1otLrw9BDb04UtzQLdIABmVCfctfllYj/7/wws95uhluzJCS28MZvEso2g7IpJqtY9t0AnhXpoEcsiMgAQ4MB48sHu0NY7yDWc+WfmDRPB+cmcuq/eIlAf9ftDkpf3ozKjH/Nr9vrTKQQqSdC9wg9mzWpTNAsHYBv5SCW+dSM0gfm9CPXaetrHj2yNFpCYjb90K5pgFs/k2Vk3qu9vCSFImYROTjCRTQ8ilG4WasAxJ2CnQDScUoKe1RmFyw==";

// Приватный ключ (PEM формат)
$privateKeyPath = 'test_private_pkcs8_key.pem';

// Декодирование сообщения из Base64 и сохранение во временный файл
$encryptedMessage = base64_decode($encryptedMessageBase64);
$tempEncryptedFile = tempnam(sys_get_temp_dir(), 'enc');
$tempDecryptedFile = tempnam(sys_get_temp_dir(), 'dec');
file_put_contents($tempEncryptedFile, $encryptedMessage);

// Команда OpenSSL для расшифровки с использованием OAEP и SHA-256 с pkeyutl
$command = sprintf(
    'openssl pkeyutl -decrypt -inkey %s -in %s -out %s -pkeyopt rsa_padding_mode:oaep -pkeyopt rsa_oaep_md:sha256',
    escapeshellarg($privateKeyPath),
    escapeshellarg($tempEncryptedFile),
    escapeshellarg($tempDecryptedFile)
);

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    die('Ошибка при расшифровке сообщения: ' . implode("\n", $output));
}

// Чтение расшифрованного сообщения
$decryptedMessage = file_get_contents($tempDecryptedFile);
echo "Расшифрованное сообщение: $decryptedMessage\n";

// Удаление временных файлов
unlink($tempEncryptedFile);
unlink($tempDecryptedFile);

?>