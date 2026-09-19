<?php

namespace App\Libraries;

/** Read-only summaries of the same registers used by the department pages. */
class Department_kpis {
    private $db;
    private string $today;

    public function __construct($db = null) {
        $this->db = $db ?: \Config\Database::connect();
    }

    public static function can_view(string $section, $user, array $modules, bool $all_expenses = false): bool {
        if (!$user || $user->user_type !== 'staff') {
            return false;
        }
        $permissions = is_array($user->permissions ?? null) ? $user->permissions : [];
        if (in_array($section, ['hr', 'payroll', 'fleet'], true)) {
            $module = $section === 'payroll' ? 'hr' : $section;
            return !empty($modules[$module]) && ($user->is_admin || !empty($permissions[$module]) || ($section === 'payroll' && !empty($permissions['hr_payroll'])));
        }
        if ($section === 'expenses') {
            return !empty($modules['expense']) && ($user->is_admin || $all_expenses);
        }
        // Global project counts must not disclose private project membership.
        if ($section === 'projects') {
            return (bool) $user->is_admin;
        }
        // These department controllers allow all staff members to view their registers.
        return true;
    }

    public function overview($user, array $modules, bool $all_expenses, string $today): array {
        $this->today = $today;
        $definitions = [
            'assets' => ['Fixed assets', 'hard-drive', 'fixed_assets', 'Current register · all non-deleted assets'],
            'inventory' => ['Stores & inventory', 'box', 'stores_inventory', 'Current stock · valued at quantity × unit cost'],
            'accounting' => ['Accounting & reconciliation', 'credit-card', 'accounting', 'All-time posted ledger · latest statement per bank account'],
            'grants' => ['Grants & vote clearance', 'dollar-sign', 'accounting/grants', 'All recorded grant allocations and disbursements'],
            'procurement' => ['Procurement & suppliers', 'shopping-cart', 'procurement/procurement_plans', 'All recorded plans and requisitions · currencies kept separate'],
            'administration' => ['Administration & federations', 'briefcase', 'administration', 'Current approvals, board packages and federation register'],
            'engineering' => ['Engineering & maintenance', 'tool', 'engineering', 'All-time work orders and capital requests'],
            'facilities' => ['Facilities & accommodation', 'home', 'facilities', 'All-time bookings · current facility and hostel registers'],
            'ict' => ['ICT & media', 'cpu', 'ict_and_media', 'Current equipment and tickets · all-time recorded expenses'],
            'legal' => ['Legal & compliance', 'shield', 'legal_compliance', 'Contract and case registers · expiry measured as of today'],
            'audit' => ['Internal audit', 'check-square', 'internal_audit', 'Current findings and asset verification records'],
            'hr' => ['Human resources', 'users', 'hr', 'Current staff accounts, profiles and appraisals'],
            'payroll' => ['Payroll', 'file-text', 'hr_payroll', 'Payroll period: ' . date('F Y', strtotime($today)) . ' · all statuses'],
            'fleet' => ['Fleet & transport', 'truck', 'fleet', 'Current vehicles · services due within 30 days · all-time costs'],
            'visitors' => ['Reception & security', 'user-check', 'visitor_logbook', 'Today and currently checked-in visitors'],
            'expenses' => ['Expenses', 'trending-down', 'expenses', 'Recorded expense amounts, excluding separate taxes'],
            'projects' => ['Projects', 'layers', 'projects', 'All non-deleted projects'],
        ];
        $sections = [];
        $this->db->transBegin();
        try {
            $this->db->query('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ, READ ONLY');
            foreach ($definitions as $id => [$title, $icon, $url, $period]) {
                if (!self::can_view($id, $user, $modules, $all_expenses)) {
                    continue;
                }
                $section = compact('id', 'title', 'icon', 'url', 'period');
                $section['metrics'] = [];
                $section['checks'] = [];
                $section['error'] = false;
                $this->db->query('SAVEPOINT department_kpi');
                try {
                    $section = array_merge($section, $this->$id());
                } catch (\Throwable $e) {
                    $this->db->query('ROLLBACK TO SAVEPOINT department_kpi');
                    log_message('error', 'Dashboard KPI {section}: {message}', ['section' => $id, 'message' => $e->getMessage()]);
                    $section['error'] = true;
                }
                $this->db->query('RELEASE SAVEPOINT department_kpi');
                $sections[$id] = $section;
            }
            $this->db->transCommit();
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
        return ['sections' => $sections, 'date' => $today, 'updated_at' => date('c')];
    }

    private function table(string $name): string {
        return $this->db->prefixTable($name);
    }

    private function row(string $sql, array $binds = []): array {
        $result = $this->db->query($sql, $binds);
        if ($result === false) {
            throw new \RuntimeException('The source register could not be read.');
        }
        return $result->getRowArray();
    }

    private function aggregate(string $table, string $select, string $where = '', array $binds = []): array {
        return $this->row('SELECT ' . $select . ' FROM ' . $this->table($table) . ' WHERE deleted=0' . ($where ? ' AND ' . $where : ''), $binds);
    }

    private function metrics(array $row, array $labels): array {
        $metrics = [];
        foreach ($labels as $key => $definition) {
            [$label, $unit, $url] = array_pad((array) $definition, 3, '');
            $metrics[] = ['key' => $key, 'label' => $label, 'value' => $row[$key] ?? null, 'unit' => $unit, 'url' => $url];
        }
        return $metrics;
    }

    private function check(string $label, $difference, $exceptions, string $explanation, string $unit = 'UGX'): array {
        return compact('label', 'difference', 'exceptions', 'explanation', 'unit');
    }

    private function assets(): array {
        $r = (array) model('App\Models\Fixed_assets_model')->get_portfolio_totals();
        $r['verification_rate'] = $r['total_assets'] ? 100 * $r['verified_count'] / $r['total_assets'] : null;
        return ['metrics' => $this->metrics($r, [
            'total_assets' => 'Registered assets', 'total_units' => 'Asset units',
            'total_fb_cost' => ['Acquisition cost', 'UGX'], 'total_adjusted_cost' => ['Adjusted cost', 'UGX'],
            'total_depreciation' => ['Accumulated depreciation', 'UGX'], 'total_nbv' => ['Net book value', 'UGX'],
            'verified_count' => 'Verified assets', 'verification_rate' => ['Verification rate', '%'],
        ]), 'checks' => [$this->check('Asset register', $r['balance_difference'], $r['balance_exceptions'], 'Adjusted cost − accumulated depreciation = net book value; checked per asset.')]];
    }

    private function inventory(): array {
        $r = $this->aggregate('store_inventory_items', "COUNT(*) AS skus, COALESCE(SUM(quantity_on_hand * unit_cost),0) AS value, COUNT(*) FILTER (WHERE quantity_on_hand <= min_reorder_level) AS low, COUNT(*) FILTER (WHERE quantity_on_hand <= 0) AS empty, COUNT(*) FILTER (WHERE quantity_on_hand < 0 OR unit_cost < 0) AS invalid");
        $r += $this->aggregate('store_requisitions', "COUNT(*) FILTER (WHERE LOWER(status) NOT IN ('issued','rejected','cancelled')) AS pending");
        $r += $this->aggregate('store_grn', 'COUNT(*) AS receipts');
        $r += $this->aggregate('store_issuances', 'COUNT(*) AS issues');
        return ['metrics' => $this->metrics($r, ['skus' => 'Stock lines / SKUs', 'value' => ['Stock on hand value', 'UGX'], 'low' => 'At or below reorder level', 'empty' => 'Out of stock', 'pending' => ['Pending requisitions', '', 'stores_inventory/requisitions'], 'receipts' => ['Goods receipt notes', '', 'stores_inventory/grn'], 'issues' => ['Issuance records', '', 'stores_inventory/issuances']]), 'checks' => [$this->check('Inventory validity', null, $r['invalid'], 'Stock value is the sum of quantity × unit cost. Negative quantities or costs need review.', '')]];
    }

    private function accounting(): array {
        $r = $this->aggregate('accounting_ledgers', 'COUNT(*) AS entries, COALESCE(SUM(debit_amount),0) AS debits, COALESCE(SUM(credit_amount),0) AS credits, COALESCE(SUM(debit_amount-credit_amount),0) AS difference', "UPPER(status)='POSTED'");
        $t = $this->table('accounting_ledgers');
        $r += $this->row("SELECT COUNT(*) AS unbalanced FROM (SELECT voucher_number FROM $t WHERE deleted=0 AND UPPER(status)='POSTED' GROUP BY voucher_number HAVING ABS(SUM(COALESCE(debit_amount,0)-COALESCE(credit_amount,0))) > 0.01) v");
        $t = $this->table('accounting_reconciliations');
        $r += $this->row("SELECT COUNT(*) AS accounts, COALESCE(SUM(system_balance-bank_statement_balance),0) AS bank_difference, COUNT(*) FILTER (WHERE ABS(system_balance-bank_statement_balance)>0.01) AS bank_exceptions FROM (SELECT DISTINCT ON (COALESCE(NULLIF(bank_account_number,''),bank_account_name)) * FROM $t WHERE deleted=0 ORDER BY COALESCE(NULLIF(bank_account_number,''),bank_account_name),statement_date DESC,id DESC) latest");
        return ['metrics' => $this->metrics($r, ['entries' => 'Posted entries', 'debits' => ['Posted debits', 'UGX'], 'credits' => ['Posted credits', 'UGX'], 'difference' => ['Debit / credit difference', 'UGX'], 'unbalanced' => 'Unbalanced vouchers', 'accounts' => ['Bank accounts reconciled', '', 'accounting/reconciliation']]), 'checks' => [$this->check('Posted ledger', $r['difference'], $r['unbalanced'], 'Debits = credits, checked per posted voucher; drafts and reversals are excluded.'), $this->check('Bank reconciliation', $r['bank_difference'], $r['bank_exceptions'], 'System balance = statement balance, using the latest reconciliation per account.')]];
    }

    private function grants(): array {
        $r = $this->aggregate('accounting_grants', "COUNT(*) AS grants, COALESCE(SUM(allocated_amount),0) AS allocated, COALESCE(SUM(disbursed_amount),0) AS disbursed, COALESCE(SUM(allocated_amount-disbursed_amount),0) AS remaining, COUNT(*) FILTER (WHERE disbursed_amount>allocated_amount OR allocated_amount<0 OR disbursed_amount<0) AS exceptions, COUNT(*) FILTER (WHERE accountability_status='OVERDUE') AS overdue");
        $r += $this->aggregate('accounting_vote_clearance', "COUNT(*) FILTER (WHERE clearance_status='PASSED') AS cleared");
        $r['rate'] = $r['allocated'] > 0 ? 100 * $r['disbursed'] / $r['allocated'] : null;
        return ['metrics' => $this->metrics($r, ['grants' => 'Grant records', 'allocated' => ['Allocated', 'UGX'], 'disbursed' => ['Disbursed', 'UGX'], 'remaining' => ['Undisbursed allocation', 'UGX'], 'rate' => ['Disbursement rate', '%'], 'overdue' => 'Overdue accountability', 'cleared' => ['Cleared requisitions', '', 'accounting/vote_clearance']]), 'checks' => [$this->check('Grant allocations', null, $r['exceptions'], 'Allocated = disbursed + remaining. Over-disbursement and negative amounts are flagged.')]];
    }

    private function procurement(): array {
        $r = $this->aggregate('procurement_plans', "COUNT(*) AS plans, COALESCE(SUM(estimated_cost),0) AS planned, COUNT(*) FILTER (WHERE status='AWARDED') AS awarded");
        $r += $this->aggregate('procurement_form5', "COUNT(*) AS requisitions, COUNT(*) FILTER (WHERE UPPER(status) NOT IN ('APPROVED','REJECTED','CANCELLED','COMPLETED')) AS pending");
        $r += $this->aggregate('suppliers', "COUNT(*) AS suppliers, COUNT(*) FILTER (WHERE is_blacklisted=true) AS blacklisted");
        $metrics = $this->metrics($r, ['plans' => 'Procurement plans', 'planned' => ['Planned estimated cost', 'UGX'], 'awarded' => 'Awarded plans', 'requisitions' => ['Form 5 requisitions', '', 'procurement/form5_list'], 'pending' => 'Pending requisitions', 'suppliers' => ['Supplier register', '', 'suppliers'], 'blacklisted' => ['Blacklisted suppliers', '', 'suppliers']]);
        $t = $this->table('procurement_form5');
        foreach ($this->db->query("SELECT COALESCE(NULLIF(currency,''),'Unspecified currency') AS currency, COALESCE(SUM(grand_total_estimated_cost),0) AS total FROM $t WHERE deleted=0 GROUP BY currency")->getResultArray() as $row) {
            $metrics[] = ['key'=>'requisition_value_' . $row['currency'], 'label'=>'Requisition estimates', 'unit'=>$row['currency'], 'value'=>$row['total'], 'url'=>'procurement/form5_list'];
        }
        return ['metrics'=>$metrics];
    }

    private function administration(): array {
        $r = $this->aggregate('admin_approvals', "COUNT(*) AS approvals, COUNT(*) FILTER (WHERE status NOT IN ('Approved','Rejected','Cancelled')) AS pending, COALESCE(SUM(amount) FILTER (WHERE status NOT IN ('Approved','Rejected','Cancelled')),0) AS pending_value");
        $r += $this->aggregate('admin_federations', "COUNT(*) AS federations, COUNT(*) FILTER (WHERE governance_status='Fully Compliant') AS compliant");
        $r += $this->aggregate('admin_board_packages', 'COUNT(*) AS packages');
        $r += $this->aggregate('departments', 'COUNT(*) AS departments');
        return ['metrics'=>$this->metrics($r, ['departments'=>'Departments', 'pending'=>'Pending executive approvals', 'pending_value'=>['Pending approval value','UGX'], 'federations'=>['Registered federations','','administration/federations'], 'compliant'=>'Compliant federation records', 'packages'=>['Board packages','','administration/board_packages']])];
    }

    private function engineering(): array {
        $r = $this->aggregate('engineering_work_orders', "COUNT(*) AS orders, COUNT(*) FILTER (WHERE status NOT IN ('COMPLETED','CLOSED','CANCELLED')) AS open, COUNT(*) FILTER (WHERE status IN ('COMPLETED','CLOSED')) AS completed, COALESCE(SUM(estimated_cost),0) AS cost");
        $r += $this->aggregate('engineering_capex', 'COUNT(*) AS capex, COALESCE(SUM(estimated_budget),0) AS budget');
        $r += $this->aggregate('engineering_inspections', 'COUNT(*) AS inspections');
        $r += $this->aggregate('engineering_technicians', "COUNT(*) FILTER (WHERE status='AVAILABLE') AS technicians");
        $r['rate'] = $r['orders'] ? 100 * $r['completed'] / $r['orders'] : null;
        return ['metrics'=>$this->metrics($r, ['orders'=>'Work orders', 'open'=>'Open work orders', 'rate'=>['Completion rate','%'], 'cost'=>['Work order estimates','UGX'], 'budget'=>['Capital request estimates','UGX','engineering/capex'], 'inspections'=>['Inspections','','engineering/inspections'], 'technicians'=>'Available technicians'])];
    }

    private function facilities(): array {
        $r = $this->aggregate('facilities', "COUNT(*) AS facilities, COUNT(*) FILTER (WHERE status='Operational') AS operational");
        $r += $this->aggregate('facility_bookings', "COUNT(*) AS bookings, COALESCE(SUM(total_fee_ugx),0) AS fees, COALESCE(SUM(total_fee_ugx) FILTER (WHERE payment_status='FULLY_PAID'),0) AS paid, COUNT(*) FILTER (WHERE payment_status <> 'FULLY_PAID') AS unpaid");
        $r += $this->aggregate('hostel_occupancies', "COUNT(*) FILTER (WHERE check_in_date <= ? AND (check_out_date IS NULL OR check_out_date >= ?) AND status='Active Camp') AS residents", '', [$this->today,$this->today]);
        return ['metrics'=>$this->metrics($r, ['facilities'=>'Registered facilities', 'operational'=>'Operational facilities', 'bookings'=>['Bookings','','facilities/bookings'], 'fees'=>['Booked fees (not receipts)','UGX'], 'paid'=>['Fees on fully paid bookings','UGX'], 'unpaid'=>'Bookings not fully paid', 'residents'=>['Current hostel occupants','','facilities/hostels']])];
    }

    private function ict(): array {
        $r = $this->aggregate('ict_equipment', "COUNT(*) AS equipment, COALESCE(SUM(purchase_cost),0) AS cost, COUNT(*) FILTER (WHERE status='AVAILABLE') AS available");
        $r += $this->aggregate('ict_helpdesk_tickets', "COUNT(*) FILTER (WHERE status NOT IN ('RESOLVED','CLOSED','CANCELLED')) AS open_tickets");
        $r += $this->aggregate('ict_issuances', "COUNT(*) FILTER (WHERE actual_return_date IS NULL AND expected_return_date < ? AND status <> 'RETURNED') AS overdue", '', [$this->today]);
        $r += $this->aggregate('ict_expenses', 'COALESCE(SUM(amount_ugx),0) AS expenses');
        return ['metrics'=>$this->metrics($r, ['equipment'=>'Equipment records', 'cost'=>['Equipment acquisition cost','UGX'], 'available'=>'Available equipment', 'open_tickets'=>['Open helpdesk tickets','','ict_and_media/helpdesk'], 'overdue'=>'Overdue equipment returns', 'expenses'=>['Recorded ICT expenses','UGX','ict_and_media/expenses']])];
    }

    private function legal(): array {
        $r = $this->aggregate('legal_contracts', "COUNT(*) AS contracts, COALESCE(SUM(contract_value_ugx),0) AS value, COUNT(*) FILTER (WHERE expiry_date < ?) AS expired, COUNT(*) FILTER (WHERE expiry_date >= ? AND expiry_date <= CAST(? AS date)+30) AS expiring", '', [$this->today,$this->today,$this->today]);
        $r += $this->aggregate('legal_disputes', "COUNT(*) FILTER (WHERE case_status NOT IN ('RESOLVED','CLOSED','DISMISSED','WITHDRAWN')) AS disputes");
        $r += $this->aggregate('legal_litigation', "COUNT(*) FILTER (WHERE case_status NOT IN ('RESOLVED','CLOSED','DISMISSED','WITHDRAWN')) AS litigation, COALESCE(SUM(legal_exposure_ugx) FILTER (WHERE case_status NOT IN ('RESOLVED','CLOSED','DISMISSED','WITHDRAWN')),0) AS exposure");
        return ['metrics'=>$this->metrics($r, ['contracts'=>'Contract records', 'value'=>['Recorded contract value','UGX'], 'expired'=>'Expired contracts', 'expiring'=>'Contracts expiring in 30 days', 'disputes'=>['Open disputes','','legal_compliance/disputes'], 'litigation'=>['Open litigation','','legal_compliance/litigation'], 'exposure'=>['Open litigation exposure','UGX']])];
    }

    private function audit(): array {
        $r = $this->aggregate('audit_discrepancies', "COUNT(*) AS findings, COUNT(*) FILTER (WHERE status NOT IN ('RESOLVED','CLOSED')) AS open, COUNT(*) FILTER (WHERE status='RESOLVED') AS resolved, COALESCE(SUM(financial_impact_ugx) FILTER (WHERE status NOT IN ('RESOLVED','CLOSED')),0) AS exposure");
        $r += $this->aggregate('audit_reports', 'COUNT(*) AS reports');
        $r += $this->aggregate('fixed_assets', "COUNT(*) FILTER (WHERE verification_status <> 'VERIFIED' OR verification_status IS NULL) AS unverified");
        return ['metrics'=>$this->metrics($r, ['findings'=>'Audit findings', 'open'=>'Unresolved findings', 'resolved'=>'Resolved findings', 'exposure'=>['Unresolved financial impact','UGX'], 'reports'=>['Audit reports','','internal_audit/generate_report'], 'unverified'=>['Assets awaiting verification','','internal_audit/spot_checks']])];
    }

    private function hr(): array {
        $r = $this->aggregate('users', "COUNT(*) AS staff, COUNT(*) FILTER (WHERE status='active') AS active", "user_type='staff'");
        $r += $this->aggregate('hr_profiles', 'COUNT(DISTINCT user_id) AS profiles, COUNT(*) FILTER (WHERE contract_expiry_date >= ? AND contract_expiry_date <= CAST(? AS date)+30) AS expiring', '', [$this->today,$this->today]);
        $r += $this->aggregate('hr_appraisals', "COUNT(*) AS appraisals, COUNT(*) FILTER (WHERE status='completed') AS completed");
        return ['metrics'=>$this->metrics($r, ['staff'=>'Staff accounts', 'active'=>'Active staff accounts', 'profiles'=>'Staff with HR profiles', 'expiring'=>'Contracts expiring in 30 days', 'appraisals'=>['Appraisals','','hr_appraisals'], 'completed'=>'Completed appraisals'])];
    }

    private function payroll(): array {
        $r = $this->aggregate('hr_payroll', 'COUNT(*) AS records, COALESCE(SUM(gross_salary),0) AS gross, COALESCE(SUM(total_deductions),0) AS deductions, COALESCE(SUM(net_salary),0) AS net, COALESCE(SUM(gross_salary-total_deductions-net_salary),0) AS difference, COUNT(*) FILTER (WHERE ABS(gross_salary-total_deductions-net_salary)>0.01) AS exceptions', 'period_year=? AND period_month=?', [(int)substr($this->today,0,4),(int)substr($this->today,5,2)]);
        return ['metrics'=>$this->metrics($r, ['records'=>'Payroll records this month', 'gross'=>['Gross pay','UGX'], 'deductions'=>['Deductions','UGX'], 'net'=>['Net pay','UGX']]), 'checks'=>[$this->check('Monthly payroll', $r['difference'], $r['exceptions'], 'Gross pay − deductions = net pay, checked per payroll record.')]];
    }

    private function fleet(): array {
        $r = $this->aggregate('fleet_vehicles', "COUNT(*) AS vehicles, COUNT(*) FILTER (WHERE status='available') AS available, COUNT(*) FILTER (WHERE next_service_date <= CAST(? AS date)+30) AS due", '', [$this->today]);
        $r += $this->aggregate('fleet_routes', 'COUNT(*) AS routes');
        $r += $this->aggregate('fleet_service_logs', 'COALESCE(SUM(cost),0) AS cost');
        return ['metrics'=>$this->metrics($r, ['vehicles'=>'Fleet vehicles', 'available'=>'Available vehicles', 'due'=>'Services due / overdue in 30 days', 'routes'=>['Route records','','fleet/routes'], 'cost'=>['Recorded service costs','UGX','fleet/service_logs']])];
    }

    private function visitors(): array {
        $r = $this->aggregate('visitor_logbook', "COUNT(*) AS total, COUNT(*) FILTER (WHERE time_in::date=?) AS today, COUNT(*) FILTER (WHERE status='checked_in') AS inside", '', [$this->today]);
        $r += $this->aggregate('visitor_appointments', "COUNT(*) FILTER (WHERE appointment_date=?) AS appointments, COUNT(*) FILTER (WHERE LOWER(status)='pending') AS pending", '', [$this->today]);
        return ['metrics'=>$this->metrics($r, ['total'=>'All recorded visits', 'today'=>'Visits today', 'inside'=>'Currently checked in', 'appointments'=>['Appointments today','','visitor_appointment'], 'pending'=>'Pending appointments'])];
    }

    private function expenses(): array {
        $r = $this->aggregate('expenses', "COUNT(*) AS records, COALESCE(SUM(amount),0) AS total, COALESCE(SUM(amount) FILTER (WHERE expense_date >= ? AND expense_date <= ?),0) AS month", '', [substr($this->today,0,7).'-01',$this->today]);
        $currency = get_setting('default_currency') ?: 'UGX';
        return ['metrics'=>$this->metrics($r, ['records'=>'Expense records', 'total'=>['All-time expense amounts',$currency], 'month'=>['Month-to-date expense amounts',$currency]])];
    }

    private function projects(): array {
        $r = $this->aggregate('projects', "COUNT(*) AS projects, COUNT(*) FILTER (WHERE status='open') AS open, COUNT(*) FILTER (WHERE status='completed') AS completed, COUNT(*) FILTER (WHERE deadline < ? AND status NOT IN ('completed','canceled')) AS overdue", '', [$this->today]);
        return ['metrics'=>$this->metrics($r, ['projects'=>'Projects', 'open'=>'Open projects', 'completed'=>'Completed projects', 'overdue'=>'Overdue projects'])];
    }
}
