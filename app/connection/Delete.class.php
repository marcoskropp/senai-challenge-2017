<?php

/**
 * <b>Delete.class:</b>
 * Classe destinada realizar exclusões genéricas do nosso sistema.
 */
class Delete extends Connection {

    private $tabela; //tabela que iremos trabalhar.
    private $termos;
    private $places;
    private $result; //flag para sabermos se foi excluído ou não.

    /** @var PDOStatement - responsável pela nossa query - utilizaremos métodos da classe PDOStatement.  */
    private $delete;

    /** @var PDO */
    private $connection;

    /**
     * <b>exeDelete:</b> Executa uma exclusão simples ao banco de dados
     * 
     * @param STRING $tabela = Informa a tabela que vai fazer a leitura.
     * @param ARRAY $termos = ex WHERE | ORDER | LIMIT | OFFSET.
     * @param STRING $parseString = links.
     */
    public function exeDelete($tabela, $termos, $parseString) {
        $this->tabela = $tabela;
        $this->termos = $termos;

        parse_str($parseString, $this->places);
        $this->getSintaxe();
        $this->execute();
    }

    /**
     * <b>Obter resultado:</b> Numero de registros encontrados.
     * @return ARRAY $valor = arrray com os resultados.
     */
    public function getResult() {
        frontErro("Foram excluidos {$this->delete->rowCount()} registros", MS_SUCCES);
    }

    /**
     * <b>Contar linhas:</b> Retorna o numero de registros encontrados pela query.
     * @return INT $valor = quantidade de registros encontrado no BD.
     */
    public function getRowCount() {
        return $this->delete->rowCount();
    }

    /**
     * <b>Alterar Places:</b> Chame esta função após fazer uma leitura.
     * O objetivo é alterar os valores do links passados na leitura
     * do métodos utilizado, exeRead ou fullRead.
     * @return INT $valor = quantidade de registros encontrado no BD.
     */
    public function setPlaces($parseString) {
        //armazenando dentro do places os valores recebidos em $parseString
        parse_str($parseString, $this->places);
        $this->getSintaxe();
        $this->execute();
    }

    //métodos privados

    /**
     * Obtem o objeto PDO e prepara a nossa query.
     *
     */
    private function connect() {
        $this->connection = parent::getConnection();
        $this->delete = $this->connection->prepare($this->delete);
    }

    /**
     * Criamos a sintaxe para utilizar na query com Prepared Statement.   
     */
    private function getSintaxe() {
        $this->delete = "DELETE FROM {$this->tabela} {$this->termos}";
    }

    /**
     * Obtem a conexão, a sintaxe e executa a query
     */
    private function execute() {
        $this->connect();
        try {
            $this->delete->execute($this->places);
            $this->result = true;
        } catch (Exception $ex) {
            $this->result = null;
            frontErro("<b>Erro ao deletar:</b> {$ex->getMessage()}<br>ERRO: {$ex->getCode()}", MS_ERROR);
        }
    }

}
