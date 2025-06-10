<?php
namespace Core;

class JsonDatabase {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;

        // Se o arquivo não existir, cria um arquivo JSON vazio
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    /**
     * Lê todos os dados do arquivo JSON.
     * @return array
     */
    private function readData() {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];
    }

    /**
     * Escreve os dados no arquivo JSON.
     * @param array $data
     */
    private function writeData($data) {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Adiciona um novo registro ao JSON.
     * @param array $newRecord
     * @return array O registro adicionado.
     */
    public function add(array $newRecord): array {
        $data = $this->readData();

        // Gera um ID único para o novo registro
        $newRecord['id'] = count($data) > 0 ? end($data)['id'] + 1 : 1;

        $data[] = $newRecord;
        $this->writeData($data);

        return $newRecord;
    }

    /**
     * Edita um registro existente no JSON.
     * @param int $id
     * @param array $updatedRecord
     * @return bool True se o registro foi atualizado, False caso contrário.
     */
    public function edit($id, array $updatedRecord) {
        $data = $this->readData();

        foreach ($data as &$record) {
            if ($record['id'] == $id) {
                $record = array_merge($record, $updatedRecord);
                $this->writeData($data);
                return true;
            }
        }

        return false; // Registro não encontrado
    }

    /**
     * Deleta um registro do JSON.
     * @param int $id
     * @return bool True se o registro foi deletado, False caso contrário.
     */
    public function delete($id) {
        $data = $this->readData();

        foreach ($data as $key => $record) {
            if ($record['id'] == $id) {
                unset($data[$key]);
                $this->writeData(array_values($data)); // Reindexa o array
                return true;
            }
        }

        return false; // Registro não encontrado
    }

    /**
     * Busca um registro por ID.
     * @param int $id
     * @return array|null O registro encontrado ou null se não existir.
     */
    public function findById($id) {
        $data = $this->readData();

        foreach ($data as $record) {
            if ($record['id'] == $id) {
                return $record;
            }
        }

        return null; // Registro não encontrado
    }

    /**
     * Retorna todos os registros.
     * @return array
     */
    public function findAll() {
        return $this->readData();
    }
}
