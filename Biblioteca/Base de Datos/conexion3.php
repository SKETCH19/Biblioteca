<?php
class DBConnection1 {
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
            "CREATE TABLE IF NOT EXISTS titulos (
                id_titulo TEXT PRIMARY KEY,
                titulo TEXT NOT NULL,
                tipo TEXT NOT NULL,
                id_pub TEXT NOT NULL,
                precio REAL,
                avance REAL,
                total_ventas INTEGER,
                notas TEXT NOT NULL,
                fecha_pub TEXT NOT NULL,
                contrato TEXT NOT NULL,
                image_path TEXT
            )"
        );

        $this->ensureColumn($conn, 'titulos', 'image_path', 'TEXT');

        $this->applyDefaultBookImages($conn);
        $this->seedTitulos($conn);
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

    private function applyDefaultBookImages(PDO $conn) {
        $defaults = [
            'T00001' => 'assets/img/books/book-1.svg',
            'T00002' => 'assets/img/books/book-2.svg',
            'T00003' => 'assets/img/books/book-3.svg',
            'T00004' => 'assets/img/books/book-4.svg',
            'T00005' => 'assets/img/books/book-5.svg',
            'T00006' => 'assets/img/books/book-6.svg'
        ];

        $sql = "UPDATE titulos SET image_path = :image_path WHERE id_titulo = :id_titulo AND (image_path IS NULL OR image_path = '')";
        $stmt = $conn->prepare($sql);

        foreach ($defaults as $id => $path) {
            $stmt->execute([
                ':image_path' => $path,
                ':id_titulo' => $id
            ]);
        }
    }

    private function seedTitulos(PDO $conn) {
        $stmt = $conn->query('SELECT COUNT(*) as total FROM titulos');
        $total = (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        if ($total > 0) {
            return;
        }

        $titulos = [
            ['T00001', 'Cien Anos de Soledad', 'Novela', 'P001', 19.99, 5.0, 1200000, 'Clasico del realismo magico.', '1967-05-30 00:00:00', 'Y', 'assets/img/books/book-1.svg'],
            ['T00002', 'La Casa de los Espiritus', 'Novela', 'P002', 17.50, 4.5, 950000, 'Saga familiar con toques magicos.', '1982-01-01 00:00:00', 'Y', 'assets/img/books/book-2.svg'],
            ['T00003', 'Ficciones', 'Cuento', 'P003', 14.00, 3.0, 800000, 'Cuentos emblematicos de Borges.', '1944-01-01 00:00:00', 'Y', 'assets/img/books/book-3.svg'],
            ['T00004', 'El Alquimista', 'Ficcion', 'P004', 12.99, 2.0, 2000000, 'Una fabula sobre el destino.', '1988-01-01 00:00:00', 'Y', 'assets/img/books/book-4.svg'],
            ['T00005', 'Rayuela', 'Novela', 'P005', 16.75, 3.5, 700000, 'Lectura no lineal y experimental.', '1963-06-28 00:00:00', 'Y', 'assets/img/books/book-5.svg'],
            ['T00006', 'Pedro Paramo', 'Novela', 'P006', 13.25, 2.5, 650000, 'Realismo magico y atmosfera unica.', '1955-03-19 00:00:00', 'Y', 'assets/img/books/book-6.svg']
        ];

        $sql = "INSERT INTO titulos (id_titulo, titulo, tipo, id_pub, precio, avance, total_ventas, notas, fecha_pub, contrato, image_path)
            VALUES (:id_titulo, :titulo, :tipo, :id_pub, :precio, :avance, :total_ventas, :notas, :fecha_pub, :contrato, :image_path)";
        $stmt = $conn->prepare($sql);

        foreach ($titulos as $titulo) {
            $stmt->execute([
                ':id_titulo' => $titulo[0],
                ':titulo' => $titulo[1],
                ':tipo' => $titulo[2],
                ':id_pub' => $titulo[3],
                ':precio' => $titulo[4],
                ':avance' => $titulo[5],
                ':total_ventas' => $titulo[6],
                ':notas' => $titulo[7],
                ':fecha_pub' => $titulo[8],
                ':contrato' => $titulo[9],
                ':image_path' => $titulo[10]
            ]);
        }
    }
}
?>
