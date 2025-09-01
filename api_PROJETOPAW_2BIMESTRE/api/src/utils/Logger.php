<?php
/**
 * Classe [Roteador]
 *
 * Responsável por registrar mensagens de erro e exceções em um arquivo de log.
*/

class Logger
{
    /**
     * Caminho do arquivo de log.
     *
     * @var string
     */
    private static string $LOG_FILE = 'system/log.log';

    /**
     * Registra uma mensagem de erro genérica no log.
     *
     * @param string rrorMessage A mensagem de erro a ser registrada.
     */
    public static function logError(string $errorMessage): void
    {
        self::writeLog(type: "ERROR", message: $errorMessage);
    }

    /**
     * Registra uma exceção ou erro (Throwable) no log.
     *
     * @param Throwable xception A exceção capturada.
     */
    public static function log(Throwable $throwable): void
    {
        $message = "Throwable:\n";
        $message .= "Message: " . $throwable->getMessage() . "\n";
        $message .= "Code: " . $throwable->getCode() . "\n";
        $message .= "File: " . $throwable->getFile() . "\n";
        $message .= "Line: " . $throwable->getLine() . "\n";
        $message .= "Trace:\n" . $throwable->getTraceAsString();

        self::writeLog(type: "Throwable", message: $message);
    }

    /**
     * Escreve uma entrada no arquivo de log.
     *
     * @param string $type Tipo da mensagem (ex: "ERROR", "$throwable").
     * @param string $message Conteúdo da mensagem a ser registrada.
     */
    private static function writeLog(string $type, string $message): void
    {
        // Define o caminho completo para o arquivo de log
        $directoryPath = dirname(path: self::$LOG_FILE); // Obtém o diretório do arquivo de log

        // Verifica se o diretório existe; caso contrário, cria o diretório
        if (!is_dir(filename: $directoryPath)) {
            mkdir(directory: $directoryPath, permissions: 0777, recursive: true); // Cria o diretório, incluindo diretórios pai, se necessário
        }

        // Cria a entrada de log
        $dateTime = date(format: 'Y-m-d H:i:s.v');
        $separador = str_repeat(string: "*", times: 100);
        $entry = "[$dateTime] [$type] \n $message \n $separador \n";

        // Escreve no arquivo de log
        file_put_contents(filename: self::$LOG_FILE, data: $entry, flags: FILE_APPEND | LOCK_EX);
    }

}
