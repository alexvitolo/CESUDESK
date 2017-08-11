<?php
namespace Unicesumar\TalismaSync;

use \PDO;

class Connect
{

    public static $conLyceum;
    public static $conMysqlEad;
    public static $conMysqlPresencial;
    public static $conMoodlePresencial;
    public static $conTalisma;
    public static $conPergamum;
    public static $conUniversoEad;
    public static $conAva;
    public static $conLyceumD1;
    public static $conLyceumHm;
    public static $conCRM_Report;
    private $configuracao;

    private function config()
    {

        switch (getenv('APP_UNICESUMAR_ENV')) {
            case 'PRODUCAO':
                $this->configuracao = require __DIR__.'/inc/config_prod.php';
                break;

            case 'HOMOLOGACAO':
                $this->configuracao = require __DIR__.'/inc/config_hm.php';
                break;

            case 'DESENVOLVIMENTO':
                $this->configuracao = require __DIR__.'/inc/config_teste.php';
                break;

            default:
                printf('Error: Não existe configuração para o ambiente %s', getenv('APP_UNICESUMAR_ENV'));;
                exit;
        }

        return $this->configuracao;
    }

    public function getMssqlLyceum()
    {

        if (!isset(self::$conLyceum)) :
            $db = $this->config();
            try {
                self::$conLyceum = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['LYCEUM']['HOST'].';dbname='.$db['LYCEUM']['DB'],
                    $db['LYCEUM']['USER'],
                    $db['LYCEUM']['PASS']
                );
                self::$conLyceum->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['LYCEUM']['CONFIG'] as $key => $value) {
                    self::$conLyceum->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL Lyceum falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conLyceum;
    }

    public function getMysqlEad()
    {

        // if (!isset(self::$conMysqlEad)) :
            $db = $this->config();
            try {
                self::$conMysqlEad = new PDO(
                    'mysql:host='.$db['MYSQL_EAD']['HOST'].';dbname='.$db['MYSQL_EAD']['DB'],
                    $db['MYSQL_EAD']['USER'],
                    $db['MYSQL_EAD']['PASS'],
                    $db['MYSQL_EAD']['CONFIG']
                );
                self::$conMysqlEad->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
                self::$conMysqlEad->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print 'Error: A conexão MySQL EAD falhou: '.$e->getMessage().'<br>';
            }

        // endif;

        return self::$conMysqlEad;
    }

    public function getMoodlePresencial()
    {

        // if (!isset(self::$conMoodlePresencial)) :
            $db = $this->config();
            try {
                self::$conMoodlePresencial = new PDO(
                    'mysql:host='.$db['MOODLE_PRESENCIAL']['HOST'].';dbname='.$db['MOODLE_PRESENCIAL']['DB'],
                    $db['MOODLE_PRESENCIAL']['USER'],
                    $db['MOODLE_PRESENCIAL']['PASS'],
                    $db['MOODLE_PRESENCIAL']['CONFIG']
                );
                self::$conMoodlePresencial->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
                self::$conMoodlePresencial->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print 'Error: A conexão MySQL Moodle Presencial falhou: '.$e->getMessage().'<br>';
            }

        // endif;

        return self::$conMoodlePresencial;
    }

    public function getMysqlPresencial()
    {

        // if (!isset(self::$conMysqlPresencial)) :
            $db = $this->config();
            try {
                self::$conMysqlPresencial = new PDO(
                    'mysql:host='.$db['MYSQL_PRESENCIAL']['HOST'].';dbname='.$db['MYSQL_PRESENCIAL']['DB'],
                    $db['MYSQL_PRESENCIAL']['USER'],
                    $db['MYSQL_PRESENCIAL']['PASS'],
                    $db['MYSQL_PRESENCIAL']['CONFIG']
                );
                self::$conMysqlPresencial->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
                self::$conMysqlPresencial->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print 'Error: A conexão MySQL Presencial falhou: '.$e->getMessage().'<br>';
            }

        // endif;

        return self::$conMysqlPresencial;
    }

    public function getMssqlTalisma()
    {

        if (!isset(self::$conTalisma)) :
            $db = $this->config();
            try {
                self::$conTalisma = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['TALISMA']['HOST'].';dbname='.$db['TALISMA']['DB'],
                    $db['TALISMA']['USER'],
                    $db['TALISMA']['PASS']
                );
                self::$conTalisma->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['TALISMA']['CONFIG'] as $key => $value) {
                    self::$conTalisma->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL Talisma falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conTalisma;
    }
    public function getMssqlPergamum()
    {

        if (!isset(self::$conPergamum)) :
            $db = $this->config();
            try {
                self::$conPergamum = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['PERGAMUM']['HOST'].';dbname='.$db['PERGAMUM']['DB'],
                    $db['PERGAMUM']['USER'],
                    $db['PERGAMUM']['PASS']
                );
                self::$conPergamum->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['PERGAMUM']['CONFIG'] as $key => $value) {
                    self::$conPergamum->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão Pergamum falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conPergamum;
    }

    public function getMssqlUniversoEad()
    {

        if (!isset(self::$conUniversoEad)) :
            $db = $this->config();
            try {
                self::$conUniversoEad = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['UNIVERSOEAD']['HOST'].';dbname='.$db['UNIVERSOEAD']['DB'],
                    $db['UNIVERSOEAD']['USER'],
                    $db['UNIVERSOEAD']['PASS']
                );
                self::$conUniversoEad->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['UNIVERSOEAD']['CONFIG'] as $key => $value) {
                    self::$conUniversoEad->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão UniversoEAD falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conUniversoEad;
    }

    public function getMssqlAva()
    {

        if (!isset(self::$conAva)) :
            $db = $this->config();
            try {
                self::$conAva = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['AVA']['HOST'].';dbname='.$db['AVA']['DB'],
                    $db['AVA']['USER'],
                    $db['AVA']['PASS']
                );
                self::$conAva->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['AVA']['CONFIG'] as $key => $value) {
                    self::$conAva->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL AVA falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conAva;
    }

    public function getMssqlLyceumD1()
    {

        if (!isset(self::$conLyceumD1)) :
            $db = $this->config();
            try {
                self::$conLyceumD1 = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['LYCEUMD1']['HOST'].';dbname='.$db['LYCEUMD1']['DB'],
                    $db['LYCEUMD1']['USER'],
                    $db['LYCEUMD1']['PASS']
                );
                self::$conLyceumD1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['LYCEUMD1']['CONFIG'] as $key => $value) {
                    self::$conLyceumD1->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL Lyceum D-1 falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conLyceumD1;
    }

    public function getMssqlLyceumHm()
    {

        if (!isset(self::$conLyceumHm)) :
            $db = $this->config();
            try {
                self::$conLyceumHm = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['LYCEUMHM']['HOST'].';dbname='.$db['LYCEUMHM']['DB'],
                    $db['LYCEUMHM']['USER'],
                    $db['LYCEUMHM']['PASS']
                );
                self::$conLyceumHm->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['LYCEUMHM']['CONFIG'] as $key => $value) {
                    self::$conLyceumHm->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL Lyceum Homologação falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conLyceumHm;
    }

    public function getMssqlCRM_Report()
    {

        if (!isset(self::$conCRM_Report)) :
            $db = $this->config();
            try {
                self::$conCRM_Report = new PDO(
                    'dblib:version=7.0;appname=TalismaSync;host='.$db['CRM_REPORT']['HOST'].';dbname='.$db['CRM_REPORT']['DB'],
                    $db['CRM_REPORT']['USER'],
                    $db['CRM_REPORT']['PASS']
                );
                self::$conCRM_Report->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                foreach ($db['CRM_REPORT']['CONFIG'] as $key => $value) {
                    self::$conCRM_Report->exec($value);
                }
            } catch (PDOException $e) {
                print 'Error: a conexão MSSQL Lyceum Homologação falhou: '.$e->getMessage().'<br>';
            }

        endif;

        return self::$conCRM_Report;
    }
}
