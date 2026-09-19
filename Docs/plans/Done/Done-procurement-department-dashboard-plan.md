# NCS Procurement Unit & PDU Department Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/procurement-department-dashboard-plan.md`  
> **Role:** Head of Procurement Disposal Unit (PDU) / Senior Procurement Officer / Evaluation Committee Secretary  
> **Department:** Procurement & Disposal Unit (PDU) - National Council of Sports (NCS)  
> **Authority Scope:** PPDA Compliance, Annual Procurement Plan (APP) Tracking, Procurement Form 5 Processing Pipeline, Bidding & Tender Evaluation, Supplier & Vendor Database, Blacklist Registry  
> **Compliance Standards:** Public Procurement and Disposal of Public Assets (PPDA) Act Uganda (2003 as amended), PPDA Regulations (2023), Treasury Instructions (2017)  

---

## 1. Executive Role Overview & Mission

The **Procurement and Disposal Unit (PDU)** manages all public procurement of goods, works, non-consultancy, and consultancy services for the National Council of Sports. PDU ensures value for money, transparency, statutory threshold adherence, and timely execution of the Annual Procurement Plan (APP).

This plan details the **Procurement Department Master Command Portal** in `NCS_Intranet`. It builds upon the **Procurement Form 5 Engine** (`procurement-form-5-plan.md`) to provide end-to-end procurement lifecycle controls from departmental requisition to contract award and supplier evaluation.

---

## 2. Key Modules & User Interface Specifications

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Procurement & Disposal Unit (PDU) Command Center [➕ New Tender] [📋 APP Plan] |
+-----------------------------------------------------------------------------------+
| [PROCUREMENT KPI & STATUTORY TRACKING CARDS]                                      |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Annual Procurement | | Form 5 Pipeline    | | Active Contracts  | | PPDA Audit Status | |
| | UGX 8.4B Approved  | | 8 Pending Action   | | 12 Operational    | | 100% Compliant    | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [PDU WORKSPACE: (1) Form 5 Requisitions | (2) Tender Evaluation | (3) APP Tracker | (4) Vendors] |
| +-------------------------------------------------------------------------------+ |
| | [PPDA FORM 5 REQUISITION & PROCESSING QUEUE]                                  | |
| | Requisition ID  | Dept        | Description             | Est. Value (UGX) | Status   | |
| | NCS/WORKS/0045  | Engineering | Lugogo Floodlight Repairs|    45,000,000    | PDU Recv | |
| | NCS/SUPP/0048   | IT / ICT    | 10 High-Spec Laptops    |    28,500,000    | Approved | |
| | NCS/SERV/0052   | Admin       | Stadium Security Guard  |   120,000,000    | Evaluation| |
| | [ Process Form 5 ] [ Issue Bidding Doc ] [ Evaluate Bids ] [ Send to GS Sign ]| |
| +-------------------------------------------------------------------------------+ |
| +-----------------------------------------------+ +-------------------------------+ |
| | Annual Procurement Plan (APP) Monitoring      | | Registered Supplier Database | |
| | Planned Q1-Q4 Items : 42 Procurements         | | Approved Suppliers  : 84      | |
| | Initiated           : 31 Procurements         | | Blacklisted Suppliers: 2 Alerts | |
| | Completed          : 24 Awarded Contracts     | | Market Price Index  : Updated | |
| | [ Export APP Status ] [ Update Timelines ]    | | [ Verify Contractor ]      | |
| +-----------------------------------------------+ +-------------------------------+ |
+-----------------------------------------------------------------------------------+
```

### Key Workstation Tabs & Features

1. **Procurement Form 5 Processing Pipeline (`procurement-form-5-plan.md`):**
   - Departmental requisition intake, procurement method determination (Open Domestic Bidding, Restricted Bidding, Request for Quotation - RFQ, Direct Procurement, Micro Procurement).
   - Approval routing: User Dept Initiator $\rightarrow$ HOD Recommendation $\rightarrow$ Finance Vote Clearance $\rightarrow$ PDU Processing $\rightarrow$ Contracts Committee / Accounting Officer (GS) Sign-off.

2. **Bidding & Evaluation Committee Portal:**
   - Generation of standard PPDA bidding documents, public notice publishing, bid opening registers, and evaluation committee scoring grids (Preliminary, Technical, Financial evaluation).

3. **Annual Procurement Plan (APP) Performance Tracker:**
   - Monitoring quarterly procurement execution against the approved annual budget.
   - Market price reference index matching estimated cost against historical award prices.

4. **Approved Vendor & Blacklist Supplier Database:**
   - Supplier qualification records, PPDA registration status, tax clearance compliance, and PPDA debarred contractor alerts.

---

## 3. Database Schema & Technical Architecture

```sql
CREATE TABLE procurement_plans (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    financial_year VARCHAR(16) NOT NULL, -- e.g. 'FY 2025/2026'
    procurement_ref_no VARCHAR(64) UNIQUE NOT NULL,
    subject_of_procurement VARCHAR(255) NOT NULL,
    procurement_type VARCHAR(32) NOT NULL, -- WORKS, SUPPLIES, SERVICES, CONSULTANCY
    procurement_method VARCHAR(64) NOT NULL, -- OPEN_DOMESTIC, RFQ, DIRECT, MICRO
    estimated_cost NUMERIC(18,2) NOT NULL,
    user_department VARCHAR(64) NOT NULL,
    planned_invitation_date DATE,
    planned_contract_signature_date DATE,
    status VARCHAR(32) DEFAULT 'PLANNED', -- PLANNED, INITIATED, AWARDED, CANCELLED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE supplier_registry (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    company_name VARCHAR(255) NOT NULL,
    ppda_registration_no VARCHAR(64) UNIQUE NOT NULL,
    tin_number VARCHAR(32) NOT NULL,
    contact_person VARCHAR(128) NOT NULL,
    contact_email VARCHAR(128) NOT NULL,
    contact_phone VARCHAR(32) NOT NULL,
    is_blacklisted BOOLEAN DEFAULT FALSE,
    blacklist_reason TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 4. REST API Mapping (`backend/internal/handlers/forms.go`)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/procurement/form5` | GET/POST | List and create PPDA Form 5 procurement requisitions |
| `/api/v1/procurement/form5/:id/process` | PUT | PDU method determination and Form 5 processing |
| `/api/v1/procurement/app` | GET/POST | Query and update Annual Procurement Plan records |
| `/api/v1/procurement/suppliers` | GET/POST | Manage registered suppliers and check PPDA blacklist |

---

## 5. Implementation Verification Roadmap

- [ ] Connect Form 5 pipeline with Finance vote clearance and GS Accounting Officer statutory approval queue.
- [ ] Build Vue frontend view `frontend/src/views/procurement/ProcurementDashboard.vue`.
