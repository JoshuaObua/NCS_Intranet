# NCS Fixed Asset Tracking, Inventory Management & Dual-Dashboard Master Architecture Plan

> **Verification update — 2026-09-08:** Baseline data parity was verified locally, but full workflow replacement is **not complete**. Earlier completion claims below are superseded by the [verification findings and acceptance blockers](../verification/asset-register-2026-09-08/BUG_REPORT.md). FB cost and adjusted valuation are distinct; the source hash-cell exception remains unresolved.

> **Target Document:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/fixed-assets-and-dashboards-plan.md`  
> **Source Baseline Data:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`  
> **Target Roles:** Accounting Department, Auditing Department, General Secretary (GS / Accounting Officer)  
> **Data Scope:** 100% Comprehensive accommodation of all 11 Worksheets in Excel Register  
> **Total Baseline Asset Portfolio:** 297 Asset Records | **UGX 31,015,914,535.00** (~UGX 31.02 Billion)  

---

## 1. 100% Comprehensive Excel Register & Worksheet Analysis

Every single worksheet in `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` has been parsed and integrated into the database schema, seeding scripts, and entry interfaces. No worksheet or row is left unaccommodated.

### 1.1 Complete Worksheet & Asset Inventory Master Table

| Worksheet Name | Primary Class (`SEGMENT1`) | Subcategory (`SEGMENT3`) | Detailed Sub-Classes (`SEGMENT4`) | Rows / Items | Portfolio Valuation (UGX) |
| :--- | :--- | :--- | :--- | :---: | :---: |
| **1. CYCLES** | MACHINERY & EQUIPMENT | CYCLES | Motorcycle (Honda UFH 770B) | 1 | UGX 9,375,000.00 |
| **2. ELECTRICAL MACHINERY** | MACHINERY & EQUIPMENT | ELECTRICAL MACHINERY | Access Control System, Air Conditioner, Brush Cutter, Generator | 11 | UGX 107,394,446.00 |
| **3. FURNITURE & FITTINGS** | FURNITURE & FITTINGS | FURNITURE AND FITTINGS | Book Shelf, Chair, Coat Hanger, Conference table, Filing Cabinet, Sofa Set, Table | 154 | UGX 96,423,651.00 |
| **4. LAND** | NATURALLY OCCURRING ASSETS | LAND | Recreation Land (Plots 2-10 Coronation Avenue, Lugogo Sports Ground plots) | 8 | UGX 27,893,933,769.00 |
| **5. LIGHT ICT HARDWARE** | MACHINERY & EQUIPMENT | LIGHT ICT HARDWARE | CPU_Light Duty, Data Terminal Unit, Laptop, Monitor, Printer, Scanner, UPS_Light Duty | 86 | UGX 59,834,250.00 |
| **6. LIGHT VEHICLES** | MACHINERY & EQUIPMENT | LIGHT VEHICLES | Pick Up double cabin (Ford Ranger UAJ 225X), Station Wagon (Kia Sorento UBF 477F), Van (King Long Bus UBF 748K) | 3 | UGX 508,963,880.00 |
| **7. NON RESIDENTIAL BUILDINGS** | BUILDINGS & STRUCTURES / NON RESIDENTIAL BUILDINGS | NON RESIDENTIAL BUILDINGS | Office buildings, Specialized Non Res (Gym, Tennis Pavilions, Stadium Canteen, Cricket Pavilion), Hotels & Restaurants (Tennis Restaurant 1 & 2) | 17 | UGX 2,138,452,000.00 |
| **8. OFFICE EQUIPMENT** | MACHINERY & EQUIPMENT | OFFICE EQUIPMENT | Binding Machines, Paper Shredder, Photo copiers, Cash Safe, Water Dispenser | 10 | UGX 27,668,036.00 |
| **9. OTHER ICT EQUIPMENT** | MACHINERY & EQUIPMENT | OTHER ICT EQUIPMENT | Digital Cameras (Canon Repromaster), TVs (Samsung 40"), Video Decoder | 6 | UGX 6,619,503.00 |
| **10. RESIDENTIAL BUILDINGS** | BUILDINGS & STRUCTURES | RESIDENTIAL BUILDINGS | Other Principal residences (NCS Lugogo Hostel Block 166BLNG1) | 1 | UGX 167,250,000.00 |
| **11. PIVOT TABLE** | **SUMMARY METRICS** | **SYSTEM CROSS-TABULATION** | Automated system verification matrix linking units, categories, and cost centers | 47 (Pivot) | System Summary Engine |
| **GRAND TOTAL PORTFOLIO** | **ALL CLASSES** | **ALL SUBCATEGORIES** | **ALL SUB-CLASS TAXONOMIES INCLUDED** | **297** | **UGX 31,015,914,535.00** |

---

## 2. Multi-Tier Role Access & Authorization Matrix

The system enforces strict **Segregation of Duties (SoD)** between Accounting (Data Entry, Ingestion & Financial Ledger Execution), Auditing (Independent Physical Tag Scanning & Risk Controls), and the General Secretary (Executive Reporting & Statutory Write-Off Approvals).

```
+-----------------------------------------------------------------------------------+
|                        NCS ASSET & INVENTORY SECURITY ROUTER                      |
+-----------------------------------------------------------------------------------+
       |                                  |                                 |
       v                                  v                                 v
+-----------------------+      +-----------------------+      +---------------------+
| ACCOUNTING DEPARTMENT |      |  AUDITING DEPARTMENT  |      |  GENERAL SECRETARY  |
| (Full Entry & Edit)   |      | (Verification & Risk) |      | (Executive Reports) |
+-----------------------+      +-----------------------+      +---------------------+
| - Create/Edit Assets  |      | - Physical QR Scan    |      | - Macro Valuation   |
| - Excel Ingestion     |      | - Flag Discrepancies  |      | - Board Briefings   |
| - Value Adjustments   |      | - Audit Trail Verification|  | - Statutory Signoff |
| - Depreciation Runs   |      | - Compliance Checks   |      | - CapEx > 5M Review |
| - Stock Requisitions  |      | - Non-Mutating Access |      | - Export PDF/Excel  |
+-----------------------+      +-----------------------+      +---------------------+
```

| Privilege / Action | Accountant Dashboard | Auditor Dashboard | General Secretary Dashboard |
| :--- | :---: | :---: | :---: |
| **Asset Entry & Registration** | Full Write / Edit | Read Only | Read Only |
| **Bulk Excel Ingestion (`11 Worksheets`)** | Execute & Post | Audit Review | Read Summary |
| **Revaluation (`FB_COST` -> `ADJUSTED COST`)** | Draft & Submit | Audit Verification | Statutory Sign-off |
| **Depreciation Schedule Run (IPSAS 17)** | Monthly Execution | Rate Verification | Read Impact Report |
| **Physical Spot-Check & Tag Scan** | Input Location | QR Scan & Audit Match | View Verification % |
| **Discrepancy & Impairment Flagging** | Respond / Remedy | Raise & Lock Issue | View High-Risk Alerts |
| **Consumables Stock Requisition** | Process & Issue | Inventory Audit Log | Read Expenditure Brief |
| **Executive PDF/Board Export** | Operational Ledger | Compliance Report | Board & Ministry Export |

---

## 3. Database Schema & Technical Architecture

### 3.1 `fixed_assets` Database Schema
Matches all 13 Excel columns across all 11 worksheets:

```sql
CREATE TABLE fixed_assets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    interface_line_number VARCHAR(64) UNIQUE NOT NULL, -- e.g. 1413029, 1413116 from Excel
    asset_book VARCHAR(64) NOT NULL DEFAULT 'NCS FA BOOK',
    asset_number VARCHAR(64) UNIQUE NOT NULL,         -- e.g. M1006898, M1007053, M1007058
    tag_number VARCHAR(128) UNIQUE NOT NULL,           -- e.g. NCSUPS014, UBF 748K, 166BLNG10
    asset_description TEXT NOT NULL,                   -- e.g. KING LONG KINGO 16S -UBF 748K
    category_segment1 VARCHAR(128) NOT NULL,           -- MACHINERY AND EQUIPMENT, LAND, BUILDINGS
    category_segment3 VARCHAR(128) NOT NULL,           -- LIGHT ICT HARDWARE, LIGHT VEHICLES, etc.
    category_segment4 VARCHAR(128) NOT NULL,           -- Pickup double cabin, Access Control, Generator, etc.
    asset_units INT NOT NULL DEFAULT 1,
    fb_cost NUMERIC(18,2) NOT NULL,                    -- Initial Fixed Book Cost (UGX)
    adjusted_cost NUMERIC(18,2) NOT NULL,              -- Revalued / Adjusted Cost (UGX)
    date_placed_in_service DATE NOT NULL,              -- e.g. 2023-07-01
    custodian_department VARCHAR(64) NOT NULL DEFAULT 'General Administration',
    location_building VARCHAR(128) NOT NULL DEFAULT 'NCS Lugogo Head Office',
    location_room VARCHAR(64) DEFAULT '',
    depreciation_method VARCHAR(32) NOT NULL DEFAULT 'STRAIGHT_LINE', -- STRAIGHT_LINE, REDUCING_BALANCE, NONE
    useful_life_years INT NOT NULL DEFAULT 5,
    accumulated_depreciation NUMERIC(18,2) DEFAULT 0.00,
    net_book_value NUMERIC(18,2) GENERATED ALWAYS AS (adjusted_cost - accumulated_depreciation) STORED,
    status VARCHAR(32) NOT NULL DEFAULT 'ACTIVE',      -- ACTIVE, UNDER_MAINTENANCE, TRANSFERRED, DISPOSED, WRITE_OFF
    verification_status VARCHAR(32) DEFAULT 'UNVERIFIED', -- VERIFIED, DISCREPANCY, MISSING
    last_verified_at TIMESTAMP WITH TIME ZONE,
    last_verified_by UUID REFERENCES users(id),
    worksheet_source VARCHAR(64) NOT NULL,             -- Tracks source worksheet (e.g. LIGHT VEHICLES, LAND)
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

### 3.2 `asset_transaction_logs` Table Schema
```sql
CREATE TABLE asset_transaction_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    asset_id UUID NOT NULL REFERENCES fixed_assets(id) ON DELETE CASCADE,
    transaction_type VARCHAR(32) NOT NULL, -- INITIAL_IMPORT, REVALUATION, DEPRECIATION, TRANSFER, DISPOSAL
    previous_val NUMERIC(18,2),
    new_val NUMERIC(18,2),
    notes TEXT,
    performed_by UUID NOT NULL REFERENCES users(id),
    approved_by UUID REFERENCES users(id),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 4. Accountant Dashboard Blueprint (`AccountantDashboard.vue`)

The Accountant Dashboard reflects all entry interfaces. Every entry made by an accountant immediately updates the ledger, updates KPI cards, and streams to the Auditor and GS dashboards.

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Accountant Financial & Fixed Asset Workspace  [➕ New Asset] [📥 Import Excel] |
+-----------------------------------------------------------------------------------+
| [FINANCIAL KPI SUMMARY CARDS]                                                      |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Total Portfolio Cost| | Net Book Value (NBV)| | YTD Depreciation  | | Low Stock Items   | |
| | UGX 31,015,914,535 | | UGX 27,428,562,000 | | UGX 3,587,352,335 | | 4 Spare Reorders  | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [ACCOUNTANT WORKSTATION TABS: (1) Asset Register | (2) Adjustments | (3) Depreciation | (4) Stock] |
| +-------------------------------------------------------------------------------+ |
| | [SEARCH & FILTER BAR: Filter by Worksheet v | Category: All v | Search: M100...]  | |
| | ----------------------------------------------------------------------------- | |
| | Asset Code | Tag No     | Description                 | Initial Cost  | Adj Cost     | |
| | M1007053   | UBF 748K   | KING LONG KINGO 16S - VEH   | 240,380,400   | 240,380,400  | |
| | M1007058   | 166BLNG10  | NCS BLOCK - OFFICE FLOOR    | 298,000,000   | 298,000,000  | |
| | M1006898   | NCSUPS014  | UNINTERRUPTED POWER SUPPLY  |     300,000   |     300,000  | |
| | M1007155   | VOLUME 505 | PLOT 2-10 CORONATION AVENUE |12,825,000,000 |12,825,000,000| |
| | [Edit Asset] [Post Adjustment] [Transfer Asset] [Schedule Disposal]           | |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Real-Time Entry Stream & Ledger Sync          | | Depreciation Run Controller   | |
| | - [ENTRY] Added 5 HP Laptops (UGX 8.5M)       | | Next Run: 31 July 2026       | |
| | - [ADJUST] M1007058 Revaluation +UGX 15M      | | Calculated: UGX 245,600,000 | |
| | - [TRANSFER] UBF 748K assigned to Admin Pool  | | [ Run Monthly Depreciation ]| |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 5. Auditor Dashboard Blueprint (`AuditorDashboard.vue`)

The Auditor Dashboard provides independent spot-checking, QR scanning, and verification controls without allowing data manipulation.

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Internal Audit & Asset Verification Portal   [🔍] [📋 Audit Report] |
+-----------------------------------------------------------------------------------+
| [AUDIT INTEGRITY & COMPLIANCE KPIs]                                                |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Verification Rate  | | Tagged vs Untagged | | Value Discrepancies| | Risk Rating       | |
| | 94.2% (280 / 297)  | | 294 Tagged / 3 Open| | 2 High Discrepant | | LOW (Clean Audit) | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [AUDITOR WORKSPACE: (1) Spot-Check Queue | (2) Discrepancy Manager | (3) Compliance]  |
| +-------------------------------------------------------------------------------+ |
| | [PHYSICAL ASSET SPOT-CHECK & QR MOBILE VERIFICATION WIDGET]                    |
| | Scan Asset Tag: [ Input / Scan Barcode / QR Code: UBF 748K           ] [ Verify ]| |
| | Matched Asset : M1007053 - KING LONG KINGO 16S BUS - UBF 748K                  | |
| | System Value  : UGX 240,380,400.00 | Location: Transport Yard                     | |
| | Audit Action  : [ Mark Verified OK ] [ Flag Condition Issue ] [ Flag Missing ]| |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Audit Exception & Discrepancy Queue           | | IPSAS & Statutory Compliance  | |
| | - [ALERT] M1007058 FB_COST vs Adj Cost Diff   | | PFMA 2015 Compliance: PASS  | |
| | - [ALERT] 3 Laptops unverified > 180 Days     | | Treasury Inst 2017: PASS    | |
| | [ Request Accountant Explanation ]            | | Depreciation Rules: PASS    | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 6. General Secretary Executive Dashboard View (`GeneralSecretaryAssetReportView.vue`)

The GS Executive View presents portfolio macro summaries and statutory sign-off tools.

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | General Secretary Executive Asset & Financial Oversight           |
+-----------------------------------------------------------------------------------+
| [EXECUTIVE ASSET PORTFOLIO KPI SUMMARY]                                           |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Total Net Portfolio | | Land Portfolio    | | Buildings & Venues| | Fleet & Equipment | |
| | UGX 31.02 Billion  | | UGX 27.89 Billion  | | UGX 2.31 Billion  | | UGX 820.6 Million | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [GS EXECUTIVE WORKSPACE]                                                          |
| +-----------------------------------------------+ +-------------------------------+ |
| | High-Value Asset CapEx & Disposal Queue       | | Category Valuation Distribution | |
| | - Disposal: 2 Damaged Printers (UGX 1.1M)     | | Land       : 89.9%           | |
| | - Revaluation: Lugogo Tennis Block (+45M)     | | Buildings  : 7.4%            | |
| | [ Approve Statutory Write-off ] [ Reject ]    | | Equipment  : 2.7%            | |
| +-----------------------------------------------+ +-------------------------------+ |
| +-------------------------------------------------------------------------------+ |
| | EXECUTIVE STATUTORY REPORT GENERATOR                                           | |
| | Report Type: [ Annual Fixed Asset Register (IPSAS 17) v ]                     | |
| | Financial Year: [ FY 2025/2026 v ] Format: [ Board PDF Package v ]            | |
| | [ Download Executive Board Brief ] [ Send to Auditor General / MoFPED ]       | |
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 7. Implementation & Verification Plan

### Phase 1: Database Seeding & Schema Setup
- Seed database with all 297 real records from all 11 worksheets in `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`.
- Implement backend handlers in `backend/internal/handlers/fixed_asset_handler.go`.

### Phase 2: Frontend Dashboard Views
- Create `AccountantDashboard.vue` with asset entry modals, multi-worksheet Excel bulk importer, and depreciation runner.
- Create `AuditorDashboard.vue` with mobile QR tag scanner, discrepancy log, and compliance reports.
- Integrate asset summary widgets into `GeneralSecretaryDashboard.vue`.

### Phase 3: Verification
- Verify backend API test suite with seed data totaling **UGX 31,015,914,535.00**.
- Verify role-based routing (Accountant editing, Auditor verifying, GS executive reporting & approvals).
