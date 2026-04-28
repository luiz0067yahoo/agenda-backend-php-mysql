<?php
require_once($_SERVER['DOCUMENT_ROOT'].'\Connect.php');
class ControllerGrupos
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connect::getInstance();
    }

    // GET /grupos
    public function find()
    {
        header('Content-Type: application/json');

        $stmt = $this->pdo->query("SELECT * FROM grupo ORDER BY nome");
        $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => $grupos
        ]);
    }

    // GET /grupos/{id}
    public function findById($id)
    {
        header('Content-Type: application/json');

        $stmt = $this->pdo->prepare("SELECT * FROM grupo WHERE id = ?");
        $stmt->execute([$id]);
        $grupo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($grupo) {
            echo json_encode(['success' => true, 'data' => $grupo]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Grupo não encontrado']);
        }
    }

    // POST /grupos
    public function create()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty(trim($input['nome']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "nome" é obrigatório']);
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO grupo (nome) VALUES (?)");
        $stmt->execute([trim($input['nome'])]);

        $id = $this->pdo->lastInsertId();

        echo json_encode([
            'success' => true,
            'message' => 'Grupo criado com sucesso',
            'data' => ['id' => (int)$id, 'nome' => trim($input['nome'])]
        ]);
    }

    // PUT /grupos/{id}
    public function update($id)
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || empty(trim($input['nome']))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campo "nome" é obrigatório']);
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE grupo SET nome = ? WHERE id = ?");
        $result = $stmt->execute([trim($input['nome']), $id]);

        if ($result && $stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Grupo atualizado',
                'data' => ['id' => (int)$id, 'nome' => trim($input['nome'])]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Grupo não encontrado']);
        }
    }

    // DELETE /grupos/{id}
    public function del($id)
    {
        header('Content-Type: application/json');

        // Verifica se existe contatos vinculados
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM contato WHERE idGrupo = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Não é possível excluir o grupo: existem contatos vinculados.'
            ]);
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM grupo WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result && $stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Grupo removido com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Grupo não encontrado']);
        }
    }
}