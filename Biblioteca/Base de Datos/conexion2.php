<?php
class DBConnection {
    private $dbPath;

    public function __construct() {
        $this->dbPath = __DIR__ . '/biblioteca.sqlite';
    }

    public function getConnection() {
        try {
            $conn = new PDO('sqlite:' . $this->dbPath);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initializeSchema($conn);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }

    private function initializeSchema(PDO $conn) {
        $conn->exec(
            "CREATE TABLE IF NOT EXISTS autores (
                id_autor TEXT PRIMARY KEY,
                nombre TEXT NOT NULL,
                apellido TEXT NOT NULL,
                telefono TEXT NOT NULL,
                direccion TEXT NOT NULL,
                ciudad TEXT NOT NULL,
                estado TEXT NOT NULL,
                pais TEXT NOT NULL,
                cod_postal TEXT NOT NULL,
                photo_path TEXT
            )"
        );

        $conn->exec(
            "CREATE TABLE IF NOT EXISTS comentarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                fecha TEXT NOT NULL,
                correo TEXT NOT NULL,
                nombre TEXT NOT NULL,
                asunto TEXT NOT NULL,
                comentario TEXT NOT NULL
            )"
        );

        $this->ensureColumn($conn, 'autores', 'photo_path', 'TEXT');

        $this->applyDefaultAuthorPhotos($conn);
        $this->seedAutores($conn);
    }

    private function ensureColumn(PDO $conn, string $table, string $column, string $type) {
        $stmt = $conn->query("PRAGMA table_info(" . $table . ")");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $col) {
            if ($col['name'] === $column) {
                return;
            }
        }

        $conn->exec("ALTER TABLE " . $table . " ADD COLUMN " . $column . " " . $type);
    }

    private function applyDefaultAuthorPhotos(PDO $conn) {
        $defaults = [
            'A0000000001' => 'assets/img/authors/author-1.svg',
            'A0000000002' => 'assets/img/authors/author-2.svg',
            'A0000000003' => 'assets/img/authors/author-3.svg'
        ];

        $sql = "UPDATE autores SET photo_path = :photo_path WHERE id_autor = :id_autor AND (photo_path IS NULL OR photo_path = '')";
        $stmt = $conn->prepare($sql);

        foreach ($defaults as $id => $path) {
            $stmt->execute([
                ':photo_path' => $path,
                ':id_autor' => $id
            ]);
        }
    }

    private function seedAutores(PDO $conn) {
        $stmt = $conn->query('SELECT COUNT(*) as total FROM autores');
        $total = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($total > 0) {
            return;
        }

        $autores = [
            ['A0000000001', 'Gabriel', 'Garcia Marquez', '3001234567', 'Calle 10 #5-20', 'Aracataca', 'MA', 'Colombia', '470001', 'assets/img/authors/author-1.svg'],
            ['A0000000002', 'Isabel', 'Allende', '3009876543', 'Av. Libertador 123', 'Santiago', 'RM', 'Chile', '8320000', 'assets/img/authors/author-2.svg'],
            ['A0000000003', 'Jorge Luis', 'Borges', '3005551234', 'Calle Florida 456', 'Buenos Aires', 'BA', 'Argentina', '1005', 'assets/img/authors/author-3.svg']
        ];

        $sql = "INSERT INTO autores (id_autor, nombre, apellido, telefono, direccion, ciudad, estado, pais, cod_postal, photo_path)
            VALUES (:id_autor, :nombre, :apellido, :telefono, :direccion, :ciudad, :estado, :pais, :cod_postal, :photo_path)";
        $stmt = $conn->prepare($sql);

        foreach ($autores as $autor) {
            $stmt->execute([
                ':id_autor' => $autor[0],
                ':nombre' => $autor[1],
                ':apellido' => $autor[2],
                ':telefono' => $autor[3],
                ':direccion' => $autor[4],
                ':ciudad' => $autor[5],
                ':estado' => $autor[6],
                ':pais' => $autor[7],
                ':cod_postal' => $autor[8],
                ':photo_path' => $autor[9]
            ]);
        }
    }
}
?>
