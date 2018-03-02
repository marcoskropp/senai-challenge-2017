<?php

/**
 * <b>Update.class:</b>
 * Classe destinada as atualizações genéricas do nosso sistema.
 */
class Update extends Connection {

    private $tabela; //tabela que iremos trabalhar.
    private $dados;
    private $termos;
    private $places; //recebe os campos que serão atualizados.
    private $result; //flag para sabermos se foi atualizado ou não.

    /** @var PDOStatement - responsável pela nossa query - utilizaremos métodos da classe PDOStatement.  */
    private $update;

    /** @var PDO */
    private $connection;

    /**
     * <b>exeUpdate:</b> Executa uma atualização simples junto ao banco de dados
     * utilizando prepared statements.
     * 
     * @param STRING $tabela = Informa a tabela que vai fazer a atualização.
     * @param ARRAY $$dados = Recebemos os valores a serem inseridos.
     * @param ARRAY $termos = ex WHERE | ORDER | LIMIT | OFFSET.
     * @param STRING $parseString = links.
     */
    public function exeUpdate($tabela, array $dados, $termos, $parseString) {
        $this->tabela = (string) $tabela;
        $this->dados = $dados;
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
        return $this->result;
    }

    /**
     * <b>Contar linhas:</b> Retorna o numero de registros encontrados pela query.
     * @return INT $valor = quantidade de registros encontrado no BD.
     */
    public function getRowCount() {
        return $this->update->rowCount();
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
        $this->update = $this->connection->prepare($this->update);
    }

    /**
     * Criamos a sintaxe para utilizar na query com Prepared Statement.   
     */
    private function getSintaxe() {
        foreach ($this->dados as $key => $value):
            $place[] = $key . " = :" . $key;
        endforeach;
        $place = implode(", ", $place);
        $this->update = "UPDATE {$this->tabela} SET {$place} {$this->termos}";
    }

    /**
     * Obtem a conexão, a sintaxe e executa a query
     */
    private function execute() {
        $this->connect();
        try {
            $this->update->execute(array_merge($this->dados, $this->places));
            $this->result = true;
        } catch (Exception $ex) {
            $this->result = null;
            frontErro("<b>Erro ao atualizar:</b> {$ex->getMessage()}<br>ERRO: {$ex->getCode()}", MS_ERROR, true);
        }
    }

}
