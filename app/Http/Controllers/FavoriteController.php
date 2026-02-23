<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FavoriteController extends Controller
{
    // Restituisce il percorso della directory dove vengono salvati i file JSON dei preferiti. Di default è storage/db, ma centralizzando questa definizione in un metodo, si facilita la manutenzione futura (es. se si volesse spostare in storage/app/favorites o usare un sistema di caching).
    private function dbDir(): string
    {
        return storage_path('db');
    }

    // Restituisce il percorso completo del file JSON che contiene i preferiti. Il file si trova in storage/db/favorites.json. Questo metodo centralizza la definizione del percorso, facilitando eventuali modifiche future.

    private function jsonPath(): string
    {
        return $this->dbDir() . DIRECTORY_SEPARATOR . 'favorites.json';
    }

    // Assicura che la directory e il file dei preferiti esistano, creando un file JSON vuoto se necessario. Questo metodo viene chiamato all'inizio di ogni operazione sui preferiti per garantire che l'ambiente sia pronto.
    private function ensureDbExists(): void
    {
        $dir = $this->dbDir();

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $file = $this->jsonPath();

        if (!file_exists($file)) {
            file_put_contents($file, json_encode(['favorites' => []], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    // Toggle: se l'assessment_id è già nei preferiti, lo rimuove; altrimenti lo aggiunge. Restituisce lo stato aggiornato (favorited: true/false) e la data di aggiunta/rimozione.
    public function toggle(Request $request)
    {
        $request->validate([
            'assessment_id' => ['required', 'integer', 'min:1'],
        ]);

        $this->ensureDbExists();

        $assessmentId = (int) $request->input('assessment_id');

        // Timestamp con timezone Europe/Rome (config('app.timezone') di solito è già Europe/Rome)
        $now = Carbon::now()->toIso8601String();

        $result = $this->withLock(function (&$db) use ($assessmentId, $now) {
            $db = $this->normalizeDb($db);

            // Cerca
            $idx = $this->findIndex($db['favorites'], $assessmentId);

            if ($idx === -1) {
                // Aggiunge
                $db['favorites'][] = [
                    'assessment_id' => $assessmentId,
                    'added_at' => $now,
                ];

                // Facoltativo: ordina per added_at desc
                usort($db['favorites'], fn($a, $b) => strcmp($b['added_at'] ?? '', $a['added_at'] ?? ''));

                return ['favorited' => true, 'added_at' => $now];
            }

            // Rimuove
            $removed = $db['favorites'][$idx] ?? null;
            array_splice($db['favorites'], $idx, 1);

            return ['favorited' => false, 'removed' => $removed];
        });

        return response()->json(['ok' => true] + $result);
    }

    // Controlla se un assessment_id è nei preferiti, utile per aggiornare l'interfaccia utente (es. evidenziare il cuore se è già favorito)
    public function has(Request $request)
    {
        $request->validate([
            'assessment_id' => ['required', 'integer', 'min:1'],
        ]);

        $this->ensureDbExists();

        $assessmentId = (int) $request->input('assessment_id');

        $db = $this->readDb();
        $db = $this->normalizeDb($db);

        $idx = $this->findIndex($db['favorites'], $assessmentId);

        return response()->json([
            'ok' => true,
            'favorited' => $idx !== -1,
            'favorite' => $idx !== -1 ? $db['favorites'][$idx] : null,
        ]);
    }

    //Pagina dei preferiti, opzionale se si vuole gestire tutto via API e mostrare i preferiti in una sezione della dashboard
    public function index()
    {
        $this->ensureDbExists();

        $db = $this->normalizeDb($this->readDb());
        $favorites = $db['favorites'] ?? [];

        usort($favorites, function ($a, $b) {
            $ad = $a['added_at'] ?? '';
            $bd = $b['added_at'] ?? '';
            return strcmp($bd, $ad);
        });

        return view('preferiti', compact('favorites')  );
    }

    // --- HELPERS --- 
    // Questi metodi aiutano a gestire i preferiti, cercando per assessment_id e normalizzando la struttura del file JSON.
    private function findIndex(array $favorites, int $assessmentId): int
    {
        foreach ($favorites as $i => $fav) {
            if ((int)($fav['assessment_id'] ?? 0) === $assessmentId) {
                return $i;
            }
        }
        return -1;
    }

    private function normalizeDb($db): array
    {
        // Se file vuoto o con dati non strutturati, inizializza struttura base
        if (!is_array($db)) {
            return ['favorites' => []];
        }

        // Converte vecchio formato (array di assessment_id) in nuovo formato (array di oggetti con assessment_id e added_at)
        if (array_is_list($db)) {
            $converted = [];
            foreach ($db as $id) {
                $id = (int) $id;
                if ($id > 0) {
                    $converted[] = ['assessment_id' => $id, 'added_at' => null];
                }
            }
            return ['favorites' => $converted];
        }

        if (!isset($db['favorites']) || !is_array($db['favorites'])) {
            $db['favorites'] = [];
        }

        return $db;
    }

    // Nota: i metodi readDb, writeDb e withLock sono implementati con file locking per evitare race condition in caso di richieste concorrenti. 
    // In un'applicazione reale, si potrebbe considerare l'uso di un database o di un sistema di caching più robusto per gestire i preferiti.
    private function readDb(): array
    {
        $raw = file_get_contents($this->jsonPath());
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    private function writeDb(array $db): void
    {
        file_put_contents(
            $this->jsonPath(),
            json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    private function withLock(callable $fn): array
    {
        $this->ensureDbExists();

        $lockFile = $this->dbDir() . DIRECTORY_SEPARATOR . 'favorites.lock';
        $lock = fopen($lockFile, 'c+');

        if (!$lock) {
            abort(500, 'Impossibile creare lock file in storage/db');
        }

        try {
            flock($lock, LOCK_EX);

            $db = $this->readDb();
            $result = $fn($db);

            $this->writeDb($db);

            return is_array($result) ? $result : [];
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}