<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\Connect.php');
class ControllerContatos
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connect::getInstance();
    }

    // GET /contatos
    public function find()
    {
        header('Content-Type: application/json');

        $stmt = $this->pdo->query("
            SELECT c.*, g.nome as grupo_nome 
            FROM contato c 
            LEFT JOIN grupo g ON c.idGrupo = g.id 
            ORDER BY c.nome
        ");

        $contatos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $contatos
        ]);
    }

    // GET /contatos/{id}
    public function findById($id)
    {
        header('Content-Type: application/json');

        $stmt = $this->pdo->prepare("
            SELECT c.*, g.nome as grupo_nome 
            FROM contato c 
            LEFT JOIN grupo g ON c.idGrupo = g.id 
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        $contato = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contato) {
            echo json_encode(['success' => true, 'data' => $contato]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Contato não encontrado']);
        }
    }

    // POST /contatos
    public function create()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty(trim($input['nome']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "nome" é obrigatório']);
            return;
        }
        if (empty(trim($input['telefone']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "telefone" é obrigatório']);
            return;
        }
        if (empty($input['idGroup'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "idGroup" é obrigatório']);
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO contato (idGrupo, nome, telefone) 
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            (int)$input['idGroup'],
            trim($input['nome']),
            trim($input['telefone'])
        ]);

        $id = $this->pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'Contato criado com sucesso',
            'data' => [
                'id' => (int)$id,
                'nome' => trim($input['nome']),
                'telefone' => trim($input['telefone']),
                'idGroup' => (int)$input['idGroup']
            ]
        ]);
    }

    // PUT /contatos/{id}
    public function update($id)
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty(trim($input['nome']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "nome" é obrigatório']);
            return;
        }
        if (empty(trim($input['telefone']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "telefone" é obrigatório']);
            return;
        }
        if (empty($input['idGroup'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "idGroup" é obrigatório']);
            return;
        }

        $stmt = $this->pdo->prepare("
            UPDATE contato 
            SET idGrupo = ?, nome = ?, telefone = ? 
            WHERE id = ?
        ");

        $result = $stmt->execute([
            (int)$input['idGroup'],
            trim($input['nome']),
            trim($input['telefone']),
            $id
        ]);

        if ($result && $stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Contato atualizado',
                'data' => [
                    'id' => (int)$id,
                    'nome' => trim($input['nome']),
                    'telefone' => trim($input['telefone']),
                    'idGroup' => (int)$input['idGroup']
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Contato não encontrado']);
        }
    }

    // DELETE /contatos/{id}
    public function del($id)
    {
        header('Content-Type: application/json');

        $stmt = $this->pdo->prepare("DELETE FROM contato WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result && $stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Contato removido com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Contato não encontrado']);
        }
    }
}