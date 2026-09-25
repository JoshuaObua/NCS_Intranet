<?php

/**
 * Master Test Runner for UG Pass Integration
 */

echo "\n";
echo "===============================================================================\n";
echo "       NATIONAL COUNCIL OF SPORTS (NCS) - UG PASS INTEGRATION TEST SUITE       \n";
echo "===============================================================================\n\n";

$start = microtime(true);

echo ">> Running Unit Test Suite (Cryptographic, OIDC, Token API, Error Handling)...\n";
require_once __DIR__ . '/UgpassTest.php';
$unitTest = new UgpassTest();
$unitOk = $unitTest->runAllTests();

echo "\n>> Running Database Integration Suite (Account Linking, SUID, Provisioning)...\n";
require_once __DIR__ . '/UgpassDbTest.php';

echo "\n>> Running Organogram & Bottom-to-Top Approval Workflow Suite...\n";
require_once __DIR__ . '/OrganogramWorkflowTest.php';

$elapsed = round(microtime(true) - $start, 3);
echo "\n>> All test suites finished successfully in {$elapsed} seconds!\n";
echo "===============================================================================\n\n";

exit($unitOk ? 0 : 1);
