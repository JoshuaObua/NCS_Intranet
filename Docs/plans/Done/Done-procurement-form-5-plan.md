# Procurement Form 5 (PPDA Request for Approval of Procurement) Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/procurement-form-5-plan.md`  
> **Reference Document:** `/home/fidi/Projects/NCS_Intranet/Docs/PROCUREMENT FORM 5.docx.pdf`  
> **System Scope:** NCS Intranet - All User Departments (Engineering, Sports Administration, HR, Finance, ICT, Facilities)  
> **Standard Compliance:** Public Procurement and Disposal of Public Assets (PPDA) Act, 2003 - Regulation 3(1), 13(3), 15(3), 17(3), 24(2), 53(6), 54(5)  

---

## 1. Overview & Business Objectives

Procurement Form 5 is the statutory PPDA form required by Ugandan law for any user department within a public entity (National Council of Sports - NCS) to initiate a formal request for approval of procurement.

This plan details the digital implementation of **Form 5** as an interactive, multi-step web interface accessible across all user departments in the NCS Intranet. It specifies:
- Complete database schema & field datatypes.
- Multi-step Vue 3 UI form layout with itemized tables and auto-calculations.
- 3-Stage Approval Workflow (Requester → Head of User Department → Accounting Officer / General Secretary).
- Go REST API endpoints and PDF export generation.

---

## 2. Complete Field Specifications & Data Types Matrix

### 2.1 Section 1: Procurement Reference Number

| Field Name | Datatype | Required | Description / Options |
| :--- | :--- | :--- | :--- |
| `pde_code` | `VARCHAR(50)` | Yes | Code of Procuring & Disposing Entity (Default: `"NCS"`) |
| `procurement_type` | `ENUM` | Yes | `'SUPPLIES'`, `'WORKS'`, `'NON_CONSULTANCY_SERVICES'`, `'CONSULTANCY_SERVICES'` |
| `financial_year` | `VARCHAR(20)` | Yes | Format: `"YYYY/YYYY"` (e.g., `"2026/2027"`) |
| `sequence_number` | `VARCHAR(20)` | Yes | Auto-incremented reference sequence (e.g., `"00045"`) |
| `procurement_ref_no` | `VARCHAR(100)`| Yes (Computed)| Generated e.g., `"NCS/WORKS/2026-2027/00045"` |

### 2.2 Section 2: Category of Procurement and Budget

| Field Name | Datatype | Required | Description / Options |
| :--- | :--- | :--- | :--- |
| `budget_category` | `ENUM` | Yes | `'RECURRENT_BUDGET'`, `'DEVELOPMENT_BUDGET'` |
| `recurrent_budget_code`| `VARCHAR(50)` | Conditional| Budget line code if Recurrent |
| `development_budget_code`| `VARCHAR(50)`| Conditional| Budget line code if Development |
| `project_code` | `VARCHAR(50)` | Optional | Associated Project Code (if applicable) |
| `project_title` | `VARCHAR(255)`| Optional | Associated Project Title |

### 2.3 Section 3: Multi-Year Contracting & Required Resources

| Field Name | Datatype | Required | Description / Options |
| :--- | :--- | :--- | :--- |
| `is_multiyear` | `BOOLEAN` | Yes | Is procurement going to result into multiyear contracting? |
| `required_ugx_yr1` | `NUMERIC(18,2)`| Yes | Required Resources Year 1 (in UGX Billions / Millions) |
| `required_ugx_yr2` | `NUMERIC(18,2)`| Conditional| Required Resources Year 2 (if multiyear = true) |
| `required_ugx_yr3` | `NUMERIC(18,2)`| Conditional| Required Resources Year 3 (if multiyear = true) |
| `required_ugx_yr4` | `NUMERIC(18,2)`| Conditional| Required Resources Year 4 (if multiyear = true) |

### 2.4 Section 4: Particulars of Procurement

| Field Name | Datatype | Required | Description / Options |
| :--- | :--- | :--- | :--- |
| `subject_of_procurement`| `TEXT` | Yes | Clear summary of items or services to be procured |
| `procurement_plan_ref` | `VARCHAR(100)`| Yes | Reference in approved Annual Procurement Plan |
| `location_for_delivery` | `VARCHAR(255)`| Yes | Target venue/office (e.g., `"Lugogo Stadium Warehouse"`) |
| `date_required` | `DATE` | Yes | Desired delivery or execution deadline date |

### 2.5 Section 5: Details Relating to Procurement (Line Items Table)

Array of itemized rows (`procurement_form5_items`):

| Field Name | Datatype | Required | Description / Options |
| :--- | :--- | :--- | :--- |
| `item_no` | `INT` | Yes | Sequential item number (1, 2, 3...) |
| `description` | `TEXT` | Yes | Technical specifications, terms of reference, or scope of works |
| `quantity` | `NUMERIC(12,2)`| Yes | Quantity required |
| `unit_of_measure` | `VARCHAR(50)` | Yes | e.g., `"Pcs"`, `"Meters"`, `"Bags"`, `"Lump Sum"`, `"Hours"` |
| `estimated_unit_cost` | `NUMERIC(15,2)`| Yes | Estimated cost per unit in UGX |
| `market_price` | `NUMERIC(15,2)`| Yes | Current verified market price in UGX |
| `line_total_cost` | `NUMERIC(15,2)`| Yes (Computed)| `quantity * estimated_unit_cost` |
| `currency` | `VARCHAR(10)` | Yes | Default: `"UGX"` |

**Summary Fields:**
- `grand_total_estimated_cost`: `NUMERIC(18,2)` (Auto-computed `SUM(line_total_cost)`)

### 2.6 Section 6: User Department Request & Approval Chain

#### Step (1): Request by Member of User Department
- `requester_user_id`: `UUID` (FK to `users`)
- `requester_name`: `VARCHAR(150)`
- `requester_title`: `VARCHAR(100)`
- `requester_department`: `VARCHAR(100)` (e.g., `"Engineering"`, `"Sports Admin"`, `"Finance"`)
- `requested_date`: `TIMESTAMP WITH TIME ZONE`

#### Step (2): Confirmation of Request by Head of User Department (HOD)
- `hod_user_id`: `UUID`
- `hod_approval_status`: `ENUM('PENDING', 'CONFIRMED', 'REJECTED')`
- `hod_name`: `VARCHAR(150)`
- `hod_title`: `VARCHAR(100)`
- `hod_comments`: `TEXT`
- `hod_decision_date`: `TIMESTAMP WITH TIME ZONE`

#### Step (3): Confirmation of Funding & Approval by Accounting Officer (General Secretary)
- `vote_head_no`: `VARCHAR(50)` (e.g., `"VOTE-104"`)
- `programme`: `VARCHAR(100)`
- `sub_programme`: `VARCHAR(100)`
- `funding_status`: `ENUM('CONFIRMED', 'UNAVAILABLE')`
- `accounting_officer_user_id`: `UUID`
- `accounting_officer_status`: `ENUM('PENDING', 'APPROVED', 'REJECTED')`
- `accounting_officer_comments`: `TEXT`
- `accounting_officer_date`: `TIMESTAMP WITH TIME ZONE`

---

## 3. Database Schema (PostgreSQL)

```sql
-- 1. Procurement Form 5 Master Table
CREATE TABLE procurement_form5 (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    procurement_ref_no VARCHAR(100) UNIQUE NOT NULL,
    pde_code VARCHAR(50) DEFAULT 'NCS',
    procurement_type VARCHAR(50) NOT NULL, -- 'SUPPLIES', 'WORKS', 'NON_CONSULTANCY_SERVICES', 'CONSULTANCY_SERVICES'
    financial_year VARCHAR(20) NOT NULL,
    sequence_number INT NOT NULL,
    
    budget_category VARCHAR(50) NOT NULL, -- 'RECURRENT_BUDGET', 'DEVELOPMENT_BUDGET'
    recurrent_budget_code VARCHAR(50),
    development_budget_code VARCHAR(50),
    project_code VARCHAR(50),
    project_title VARCHAR(255),
    
    is_multiyear BOOLEAN DEFAULT FALSE,
    required_ugx_yr1 NUMERIC(18, 2) NOT NULL DEFAULT 0.00,
    required_ugx_yr2 NUMERIC(18, 2) DEFAULT 0.00,
    required_ugx_yr3 NUMERIC(18, 2) DEFAULT 0.00,
    required_ugx_yr4 NUMERIC(18, 2) DEFAULT 0.00,
    
    subject_of_procurement TEXT NOT NULL,
    procurement_plan_ref VARCHAR(100) NOT NULL,
    location_for_delivery VARCHAR(255) NOT NULL,
    date_required DATE NOT NULL,
    
    currency VARCHAR(10) DEFAULT 'UGX',
    grand_total_estimated_cost NUMERIC(18, 2) NOT NULL DEFAULT 0.00,
    
    -- Workflow Status
    status VARCHAR(50) DEFAULT 'DRAFT', -- 'DRAFT', 'SUBMITTED', 'HOD_CONFIRMED', 'APPROVED', 'REJECTED'
    
    -- Signatures & Workflow Participants
    requester_user_id UUID NOT NULL,
    requested_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    
    hod_user_id UUID,
    hod_approval_status VARCHAR(30) DEFAULT 'PENDING',
    hod_comments TEXT,
    hod_decided_at TIMESTAMP WITH TIME ZONE,
    
    vote_head_no VARCHAR(50),
    programme VARCHAR(100),
    sub_programme VARCHAR(100),
    funding_status VARCHAR(30) DEFAULT 'PENDING',
    
    accounting_officer_user_id UUID,
    accounting_officer_status VARCHAR(30) DEFAULT 'PENDING',
    accounting_officer_comments TEXT,
    accounting_officer_decided_at TIMESTAMP WITH TIME ZONE,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. Procurement Form 5 Line Items Table
CREATE TABLE procurement_form5_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    form5_id UUID REFERENCES procurement_form5(id) ON DELETE CASCADE,
    item_no INT NOT NULL,
    description TEXT NOT NULL,
    quantity NUMERIC(12, 2) NOT NULL,
    unit_of_measure VARCHAR(50) NOT NULL,
    estimated_unit_cost NUMERIC(15, 2) NOT NULL,
    market_price NUMERIC(15, 2) NOT NULL,
    line_total_cost NUMERIC(15, 2) GENERATED ALWAYS AS (quantity * estimated_unit_cost) STORED
);
```

---

## 4. Frontend UI Components & Multi-Step Wizard

All user departments can access `/intranet/procurement/form-5` from their dashboard navigation:

```
+-----------------------------------------------------------------------------------+
|  NCS INTRANET | PPDA Procurement Form 5 Submission Portal                         |
+-----------------------------------------------------------------------------------+
| [STEP 1: Reference & Budget] -> [STEP 2: Particulars & Items] -> [STEP 3: Review] |
+-----------------------------------------------------------------------------------+
| SECTION 1: PROCUREMENT REFERENCE NUMBER                                           |
| PDE Code: [ NCS ]  Type: [ Works v ]  FY: [ 2026/2027 v ]  Seq: [ Auto: 00045 ]    |
| Ref: NCS/WORKS/2026-2027/00045                                                    |
|                                                                                   |
| SECTION 2: CATEGORY OF PROCUREMENT AND BUDGET                                     |
| Budget Category: (o) Recurrent Budget  ( ) Development Budget                     |
| Recurrent Code: [ BUDGET-2026-ENG-001 ]  Project Title: [ Arena Roof Repair ]     |
|                                                                                   |
| SECTION 3: MULTIYEAR CONTRACTING & RESOURCES                                      |
| Multiyear Contract? [ ] Yes                                                       |
| Year 1 (UGX): [ 45,000,000 ]                                                      |
|                                                                                   |
| SECTION 4: PARTICULARS OF PROCUREMENT                                             |
| Subject: Supply and installation of high-capacity water drainage culverts         |
| Procurement Plan Ref: [ PP-2026-CIVIL-012 ]  Location: [ Lugogo Stadium ]         |
| Date Required: [ 2026-08-15 ]                                                     |
|                                                                                   |
| SECTION 5: DETAILS RELATING TO PROCUREMENT (LINE ITEMS)                           |
| +-------------------------------------------------------------------------------+ |
| | # | Description               | Qty | UOM | Est. Unit Cost | Total Cost (UGX) | |
| | 1 | 600mm Reinforced Culvert  | 50  | Pcs | 450,000        | 22,500,000       | |
| | 2 | Excavation & Bedding Sand | 1   | Lot | 5,000,000      | 5,000,000        | |
| +-------------------------------------------------------------------------------+ |
| [ + Add Item Row ]                      Estimated Total Cost: UGX 27,500,000     | |
|                                                                                   |
| [ SAVE DRAFT ]                           [ SUBMIT FORM 5 FOR HOD CONFIRMATION > ]  |
+-----------------------------------------------------------------------------------+
```

---

## 5. Go REST API Mapping

| Endpoint | Method | Allowed Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/procurement/form-5` | POST | All Staff | Create & submit new Form 5 request |
| `/api/v1/procurement/form-5` | GET | All Staff | List department Form 5 submissions |
| `/api/v1/procurement/form-5/:id` | GET | All Staff | Get Form 5 details & line items |
| `/api/v1/procurement/form-5/:id/hod-confirm` | PUT | HODs | Head of User Dept confirmation |
| `/api/v1/procurement/form-5/:id/accounting-approve`| PUT | General Secretary | Accounting Officer funding approval |
| `/api/v1/procurement/form-5/:id/pdf` | GET | All Staff | Export official PDF of Form 5 |

---

## 6. Implementation Checklist

- [ ] **Step 1: Database Migration Execution**
  Execute migration creating `procurement_form5` and `procurement_form5_items` tables.

- [ ] **Step 2: Go Backend Handler Implementation**
  Build `backend/internal/procurement/form5_handler.go` with CRUD handlers, workflow transitions, and PDF generator.

- [ ] **Step 3: Pinia Store (`form5Store.ts`)**
  Create Pinia store to manage wizard state, line item additions, calculation helpers, and submissions.

- [ ] **Step 4: Vue 3 View Assembly (`ProcurementForm5View.vue`)**
  Build multi-step form interface under `frontend/src/views/procurement/ProcurementForm5View.vue`.

- [ ] **Step 5: Integration & Workflow Verification**
  Test end-to-end flow: Staff member creates Form 5 -> HOD confirms -> Accounting Officer approves -> PDF generated.
