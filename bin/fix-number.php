<?php

/**
 * Fixes improperly formatted number values
 *
 * Number data (stored in the "Has Version" element text) should be
 * integers. Some non-numeric data has crept in. This fixes that.
 */

// Read DB config from ini file.
const DB_INI = __DIR__ . '/../../../db.ini';
$db_config = parse_ini_file(DB_INI);

$dsn = "mysql:dbname={$db_config['dbname']};host=127.0.0.1";
$user = $db_config['username'];
$password = $db_config['password'];

// Connect.
try {
    $dbh = new PDO($dsn, $user, $password);
    echo "Connected\n";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$select_sql = <<<SQL
SELECT omeka_element_texts.text, omeka_element_texts.id
FROM omeka_element_texts
LEFT JOIN omeka_elements ON omeka_element_texts.element_id = omeka_elements.id
WHERE omeka_elements.name = "Has Version"
SQL;

$update_sql = <<<SQL
UPDATE omeka_element_texts
SET omeka_element_texts.text = :text
WHERE omeka_element_texts.id = :id
SQL;

$select_stmt = $dbh->query($select_sql);
$update_stmt = $dbh->prepare($update_sql);

// Look at all number values. If they have non-numeric characters in
// them, strip and re-save.
foreach ($select_stmt->fetchAll(PDO::FETCH_ASSOC) as $entry) {
    $new_number = preg_replace('/[^0-9]/', "", $entry['text']);

    if ($new_number !== $entry['text']) {
        echo "Converting {$entry['id']} {$entry['text']} to $new_number...\n";

        $data = [
            'id' => $entry['id'],
            'text' => $new_number
        ];
        $update_stmt->execute($data);
    }
}



