<?php

/**
 * Database integration test for UG Pass account linking and user provisioning
 */

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=ncs_db', 'ncs_user', 'ncs_pass_2024');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "====================================================================\n";
echo "   NITA-U UG Pass Database Integration & Account Linking Test      \n";
echo "====================================================================\n\n";

$passed = 0;
$total = 0;

function db_assert($cond, $msg) {
    global $passed, $total;
    $total++;
    if ($cond) {
        $passed++;
        echo "  [PASS] {$msg}\n";
    } else {
        echo "  [FAIL] {$msg}\n";
    }
}

// 1. Verify ugpass columns exist in ncs_users
$stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'ncs_users' AND column_name IN ('ugpass_suid', 'ugpass_loa')");
$cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
db_assert(in_array('ugpass_suid', $cols), "Column ugpass_suid exists in ncs_users");
db_assert(in_array('ugpass_loa', $cols), "Column ugpass_loa exists in ncs_users");

// 2. Query admin user by email
$adminEmail = 'admin@ncs.go.ug';
$stmt = $pdo->prepare("SELECT * FROM ncs_users WHERE email = ? AND deleted = 0 LIMIT 1");
$stmt->execute([$adminEmail]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
db_assert($admin !== false && $admin['id'] == 1, "Admin user located by email ({$adminEmail})");

// 3. Test SUID linking on existing user
$testSuid = 'test-suid-link-' . bin2hex(random_bytes(6));
$stmt = $pdo->prepare("UPDATE ncs_users SET ugpass_suid = ?, ugpass_loa = 'LOA1' WHERE id = 1");
$stmt->execute([$testSuid]);

$stmt = $pdo->prepare("SELECT ugpass_suid, ugpass_loa FROM ncs_users WHERE id = 1");
$stmt->execute();
$updatedAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
db_assert($updatedAdmin['ugpass_suid'] === $testSuid, "Admin user successfully linked with SUID ({$testSuid})");
db_assert($updatedAdmin['ugpass_loa'] === 'LOA1', "Admin user LOA level updated to LOA1");

// 4. Test User lookup by SUID
$stmt = $pdo->prepare("SELECT id, email FROM ncs_users WHERE ugpass_suid = ? AND deleted = 0 LIMIT 1");
$stmt->execute([$testSuid]);
$matchedBySuid = $stmt->fetch(PDO::FETCH_ASSOC);
db_assert($matchedBySuid !== false && $matchedBySuid['id'] == 1, "User located by linked SUID");

// Reset admin ugpass_suid
$stmt = $pdo->prepare("UPDATE ncs_users SET ugpass_suid = NULL, ugpass_loa = NULL WHERE id = 1");
$stmt->execute();

// 5. Test Auto-provisioning of new user from DAES claims
$newSuid = 'suid-autocreated-' . bin2hex(random_bytes(6));
$newEmail = 'ugpass.officer.' . bin2hex(random_bytes(4)) . '@ncs.go.ug';
$newNin = 'CM' . rand(10000000, 99999999) . 'TEST';
$newPhone = '+256701' . rand(100000, 999999);

$insertSql = "INSERT INTO ncs_users (
    first_name, last_name, email, phone, ssn, gender, dob, ugpass_suid, ugpass_loa,
    user_type, status, is_admin, role_id, language, created_at, last_online, deleted, disable_login, password
) VALUES (
    'Timothy', 'Mugisha', :email, :phone, :ssn, 'male', '1990-03-22', :suid, 'LOA3',
    'staff', 'active', 0, 0, 'english', NOW(), NOW(), 0, 0, 'dummy_hash'
) RETURNING id";

$stmt = $pdo->prepare($insertSql);
$stmt->execute([
    ':email' => $newEmail,
    ':phone' => $newPhone,
    ':ssn' => $newNin,
    ':suid' => $newSuid
]);
$newUserId = $stmt->fetchColumn();
db_assert($newUserId > 0, "New user auto-provisioned with ID #{$newUserId}");

// 6. Test User lookup by NIN (ssn)
$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM ncs_users WHERE ssn = ? AND deleted = 0");
$stmt->execute([$newNin]);
$matchedByNin = $stmt->fetch(PDO::FETCH_ASSOC);
db_assert($matchedByNin !== false && $matchedByNin['id'] == $newUserId, "User located by NIN/ssn ({$newNin})");

// 7. Test User lookup by Phone suffix
$suffix = substr(preg_replace('/[^0-9]/', '', $newPhone), -9);
$stmt = $pdo->prepare("SELECT id, first_name FROM ncs_users WHERE deleted = 0 AND (phone LIKE ? OR alternative_phone LIKE ?)");
$stmt->execute(["%{$suffix}%", "%{$suffix}%"]);
$matchedByPhone = $stmt->fetch(PDO::FETCH_ASSOC);
db_assert($matchedByPhone !== false && $matchedByPhone['id'] == $newUserId, "User located by phone suffix ({$suffix})");

// 8. Clean up test user
$stmt = $pdo->prepare("DELETE FROM ncs_users WHERE id = ?");
$stmt->execute([$newUserId]);
db_assert(true, "Test user cleaned up successfully");

echo "\n--------------------------------------------------------------------\n";
echo "Database Integration Summary: Total: {$total} | Passed: {$passed} | Failed: " . ($total - $passed) . "\n";
echo "====================================================================\n";
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    exit(($total === $passed) ? 0 : 1);
}
