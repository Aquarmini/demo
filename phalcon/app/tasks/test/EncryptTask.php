<?php
// +----------------------------------------------------------------------
// | EncryptTask.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class EncryptTask extends Task
{
    static private $iv = '00000000000000000000000000000000';

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP加密测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Encrypt [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  rsa         RSA加密测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  aes         AES加密测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  hex         Hex Str 转化测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  aesMcrypt   Mcrypt的AeS加密', Color::FG_GREEN), PHP_EOL;
    }

    public function aesMcryptAction()
    {
        $data = "41e0442edf5e1071b4a514409fec46cc";
        $key = "41a10264c72a8fee55bc258ee24bc9c0";
        echo Color::colorize("密文：" . $data) . PHP_EOL;
        echo Color::colorize("密钥：" . $key) . PHP_EOL;
        echo Color::colorize("解密：" . $this->decryptData($data, $key)) . PHP_EOL;

    }

    public function hexAction()
    {
        $key = '313233343536373839616263646566';

        echo $this->hexToStr($key);
        echo $this->strToHex($this->hexToStr($key));
    }

    public function decryptData($content, $key)
    {
        $decrypt_data = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $this->hexToStr($key),
            $this->hexToStr($content),
            MCRYPT_MODE_ECB,
            $this->hexToStr(self::$iv)
        );
        return $this->strToHex($decrypt_data);
    }


    public function encryptData($content, $key)
    {
        $encrypt_data = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            $this->hexToStr($key),
            $this->hexToStr($content),
            MCRYPT_MODE_ECB,
            $this->hexToStr(self::$iv)
        );
        return $this->strToHex($encrypt_data);
    }

    private function hexToStr($hex)
    {
        $str = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $str;
    }

    private function strToHex($string)
    {
        return bin2hex($string);
    }

    public function aesAction()
    {
        $data = 'phpbest';
        $key = base64_encode(openssl_random_pseudo_bytes(32));
        echo Color::head("KEY:"), PHP_EOL;
        echo Color::colorize($key, Color::FG_GREEN), PHP_EOL;
        $iv = base64_encode(openssl_random_pseudo_bytes(16)); //echo base64_encode(openssl_random_pseudo_bytes(16));
        echo Color::head("IV:"), PHP_EOL;
        echo Color::colorize($iv, Color::FG_GREEN), PHP_EOL;

        echo Color::head("内容:"), PHP_EOL;
        echo Color::colorize($data, Color::FG_GREEN), PHP_EOL;

        $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
        $encrypted = base64_encode($encrypted);
        echo Color::head("加密:"), PHP_EOL;
        echo Color::colorize($encrypted, Color::FG_GREEN), PHP_EOL;

        $decrypted = openssl_decrypt(base64_decode($encrypted), 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
        echo Color::head("解密:"), PHP_EOL;
        echo Color::colorize($decrypted, Color::FG_GREEN), PHP_EOL;
    }

    public function rsaAction()
    {
        $file = ROOT_PATH . '/data/rsa/test/helloworld.php';
        $this->encrypt($file);
        $file = ROOT_PATH . '/data/rsa/test/encrypted.php';
        $this->decrypt($file);
    }

    private function decrypt($file)
    {
        $public_key = file_get_contents(ROOT_PATH . '/data/rsa/rsa_public_key.pem');
        $msg = file_get_contents($file);
        $msg = json_decode($msg);
        $result = '';
        foreach ($msg as $item) {
            // 公钥解密
            openssl_public_decrypt(base64_decode($item), $decrypted, $public_key);
            $result .= $decrypted;
        }
        $result = base64_decode($result);
        // 存储解密文件
        file_put_contents(ROOT_PATH . '/data/rsa/test/decrypted.php', $result);
    }

    private function encrypt($file)
    {
        $private_key = file_get_contents(ROOT_PATH . '/data/rsa/rsa_private_key.pem');

        $msg = file_get_contents($file);
        $msg = base64_encode($msg);
        $len = strlen($msg);
        $step = 110;
        $result = [];
        for ($i = 0; $i < $len;) {
            $input = substr($msg, $i, $step);
            $private_key = openssl_pkey_get_private($private_key);
            // 私钥加密
            $ret = openssl_private_encrypt($input, $encrypted, $private_key);

            if (!$ret || empty($encrypted)) {
                echo "fail to encrypt file md5";
                return;
            }
            // 存储加密文件
            $encrypted = base64_encode($encrypted);
            $result[] = $encrypted;
            $i += $step;
        }
        file_put_contents(ROOT_PATH . '/data/rsa/test/encrypted.php', json_encode($result));
    }
}