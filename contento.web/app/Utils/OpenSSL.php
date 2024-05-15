<?php
namespace App\Utils;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Support\Facades\Process;

class OpenSSL
{
    public $certificate_serial_number;
    public $start_date;
    public $end_date;
    public $cer_pem_path = null;
    public $key_pem_path = null;
    public $key_enc_path = null;

    protected $cer_path = null;
    protected $key_path = null;
    protected $pem_directory = null;
    protected $pem_path = null;
    protected $openssl_path = null;
    protected $password;
    protected $password_pac;
    protected $rfc;

    public function __construct($openssl_path, $cer_path = null, $key_path = null, $rfc = null, $password = null,$password_pac)
    {
        $this->cer_path = $cer_path;
        $this->key_path = $key_path;
        $this->password = $password;
        $this->rfc = $rfc;
        $this->openssl_path = $openssl_path;
        $this->password_pac = $password_pac;
    }

    /**
     * Generate the .pem files in the specified directory
     *
     * @param [type] $pem_directory
     * @return void
     */
    public function generatePemFiles($pem_directory = null)
    {
        $this->pem_path = $pem_directory . '/' . strtoupper($this->rfc) . '_' . date('YmdHms');
        $this->generatePEMCer();
        $this->generatePEMKey();
        $this->generateENCKey();

        $this->validateKeyCerFiles();
        // $this->validateCertificateValidity();
        $this->verifyRFC();
    }

    protected function verifyRFC()
    {
        $result = $this->runCommand($this->openssl_path . '/' . "openssl x509 -in {$this->pem_path}.cer.pem -noout -subject -nameopt RFC2253")->output();

        if (!str_contains($result, $this->rfc)) {
            throw new Exception('El certificado no pertence al RFC: ' . $this->rfc);
        }
        return true;
    }

    /**
     * Validate certificate validity
     * @return bool
     */
    protected function validateCertificateValidity()
    {
        $result = $this->runCommand($this->openssl_path . '/' . "openssl x509 -noout -in {$this->pem_path}.cer.pem -dates")->output();
        $result = array_filter(explode(PHP_EOL, $result));
        // dd($result);
        $this->start_date = Carbon::parse(str_replace(' GMT','',str_replace('notBefore=', '', $result[0])));
        $this->end_date = Carbon::parse(str_replace(' GMT','',str_replace('notAfter=', '', $result[1])));

        if (!Carbon::now()->between($this->start_date, $this->end_date)) {
            throw new Exception('Error con el periodo de validez del certificado');
        }
        return true;
    }

    /**
     * Validate a .cer.pem and .key.pem
     * @return bool
     */
    protected function validateKeyCerFiles()
    {
        $result_cer = $this->runCommand($this->openssl_path . '/' . "openssl x509 -noout -modulus -in {$this->cer_pem_path}")->output();
        $result_key = $this->runCommand($this->openssl_path . '/' . "openssl rsa -noout -modulus -in {$this->key_pem_path}")->output();
        if ($result_cer == $result_key) {
            return true;
        }
        throw new Exception('Los archivos .cer y .key no coinciden.');
    }

    /**
     * Get the certificate serial number
     * @param $path
     * @return bool|mixed
     */
    public function getCertificateSerialNumber()
    {
        $result = $this->runCommand($this->openssl_path . '/' . "openssl x509 -inform DER -in {$this->cer_path} -noout -serial")->output();
        $result = explode('=', $result);
        if (isset($result[1])) {
            $this->certificate_serial_number = str_replace(' ', '', $result[1]);
            $this->certificate_serial_number = implode('', array_filter(str_split($this->certificate_serial_number), function ($var, $key) {
                return is_numeric($var) and ($key != 0 and ($key % 2 != 0));
            }, ARRAY_FILTER_USE_BOTH));
            if (strlen($this->certificate_serial_number) == 20) {
                return $this->certificate_serial_number;
            }
        }
        throw new Exception('Ocurrio un error al intentar obtener el número de serie del certificado.');
    }

    /**
     * Generate a file .key.pem
     * @param $path
     * @param $password
     * @return bool
     */
    public function generatePEMKey()
    {
        // dd($this->openssl_path . '/' . "openssl pkcs8 -inform DER -in {$this->key_path} -passin pass:{$this->password} -out {$this->pem_path}.key.pem");
        if ($this->runCommand($this->openssl_path . '/' . "openssl pkcs8 -inform DER -in {$this->key_path} -passin pass:{$this->password} -out {$this->pem_path}.key.pem")->successful()) {
            $this->key_pem_path = $this->pem_path . '.key.pem';
            return true;
        }
        throw new Exception('Ocurrio un error al generar la llave en formato PEM. Por favor verifica la contraseña.');
    }
/**
     * Generate a file .key.pem
     * @param $path
     * @param $password
     * @return bool
     */
    public function generateENCKey()
    {
        // dd($this->openssl_path . '/' . "openssl pkcs8 -inform DER -in {$this->key_path} -passin pass:{$this->password} -out {$this->pem_path}.key.pem");
        if ($this->runCommand($this->openssl_path . '/' . "openssl rsa -in {$this->key_pem_path} -des3 -out {$this->pem_path}.key.enc -passout pass:{$this->password_pac}")->successful()) {
            $this->key_enc_path = $this->pem_path . '.key.enc';
            return true;
        }
        throw new Exception('Ocurrio un error al generar la llave en formato ENC. Por favor verifica la contraseña.');
    }

    /**
     * Generate a file .cer.pem
     * @param $path
     * @return bool
     */
    public function generatePEMCer()
    {
        $command = $this->openssl_path . '/' . "openssl x509 -inform DER -outform PEM -in {$this->cer_path} -pubkey -out {$this->pem_path}.cer.pem";
        if ($this->runCommand($command)->successful()) {
            $this->cer_pem_path = $this->pem_path . '.cer.pem';
            return true;
        }
        $message = 'Ocurrio un error al intentar generar el archivo .cer.pem';
        if (config('app.debug')) {
            $message .= ' - COMMAND: ' . $command;
        }
        throw new Exception($message);
    }

    protected function runCommand($command):ProcessResult
    {
        //  dd($command);
        $process = Process::run($command);
        return $process;
    }
}
