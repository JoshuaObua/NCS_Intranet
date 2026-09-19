# NCS Legal, Compliance & Federation Governance Department Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/legal-compliance-department-plan.md`  
> **Role:** Legal Counsel / Senior Legal Officer / Compliance & Governance Specialist  
> **Department:** Legal & Corporate Affairs Department - National Council of Sports (NCS)  
> **Reporting Line:** Direct Reporting to General Secretary (GS / Accounting Officer) & Council Board  
> **Operational Scope:** Statutory Compliance (National Sports Act 2023), Commercial Contracts & MOUs, Federation Governance Disputes & Arbitration, IP & Trademark Rights, Litigation Management, and Board Secretariat Resolutions.  
> **Statutory Framework:** Uganda National Sports Act (2023), Contracts Act (2010), Arbitration and Conciliation Act, PPDA Act (2003).

---

## 1. Role Overview & Legal Command Architecture

The **Legal & Corporate Affairs Department** safeguards the statutory, financial, and reputational interests of the National Council of Sports. It ensures strict adherence to the National Sports Act (2023), drafts and vets commercial agreements, provides legal guidance on federation regulatory compliance, arbitrates disputes within the sports ecosystem, and coordinates with the Ministry of Justice / Attorney General's Chambers.

```
+---------------------------------------------------------------------------------------------------+
|               NCS LEGAL & CORPORATE GOVERNANCE WORKFLOW                                           |
+---------------------------------------------------------------------------------------------------+
       |                                      |                                      |
       v                                      v                                      v
+-----------------------+              +-----------------------+              +-----------------------+
| 1. STATUTORY MONITOR  |              | 2. CONTRACTS & MOUS   |              | 3. SPORTS ARBITRATION |
| - National Sports Act |              | - Commercial Sponsors |              | - Federation Disputes |
| - Federation Constit. |              | - Procurement Vetting |              | - Tribunal Hearings   |
| - Annual Compliance   |              | - Land Leases & Deeds |              | - Disciplinary Appeals|
| - Statutory Gazettes  |              | - SLA & Vendor Agrmts |              | - Ruling Declarations |
+-----------------------+              +-----------------------+              +-----------------------+
       |                                      |                                      |
       +--------------------------------------+--------------------------------------+
                                              |
                                              v
                       +-----------------------------------------------+
                       | LITIGATION & BOARD RESOLUTION HUB             |
                       | - Attorney General Case Coordination          |
                       | - Council Board Minute & Action Tracker       |
                       | - IP & Team Uganda Trademark Enforcement      |
                       | - Legal Risk Exposure Briefings for GS        |
                       +-----------------------------------------------+
```

---

## 2. Key Modules & User Interface Specifications

---

### 2.1 Contracts & Agreements Repository & Vetting Desk
- Centralized repository of all legal documents:
  - Commercial Sponsorships & Media Broadcasting Rights.
  - Land Ground Leases (Plots 2-10 Coronation Avenue Lugogo).
  - PPDA Procurement Contracts (> UGX 50M) and Vendor Service Level Agreements (SLAs).
  - National Sports Federation Memoranda of Understanding (MOUs).
- Automated milestone and renewal expiry countdown alerts.

### 2.2 National Sports Federation Governance & Dispute Arbitration
- **Federation Constitution Vault:** Validates federations' constitutions for compliance with the National Sports Act 2023.
- **Dispute Registry & Tribunal Case Manager:**
  - Case intake for federation leadership disputes, election petitions, and athlete disciplinary appeals.
  - Hearing schedule coordinator, evidentiary submission vault, and tribunal ruling repository.

### 2.3 Intellectual Property & Brand Protection
- Registry of protected trademarks: NCS Crest, Team Uganda Logo, National Sports Mascot, and National Championship brand rights.
- Cease-and-desist letter generator for unauthorized commercial exploitation.

### 2.4 Litigation Management & Attorney General Liaison
- Live status tracker of all active civil suits, judicial reviews, and court proceedings involving NCS, including legal risk exposure quantifications.

---

## 3. Legal Dashboard User Interface (`LegalComplianceDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Legal & Corporate Governance Command  [➕ New Contract] [⚖️ Case]|
+-----------------------------------------------------------------------------------+
| [LEGAL RISK & COMPLIANCE SUMMARY CARDS]                                           |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Active Contracts   | | Expiring in 60 Days| | Active Disputes   | | Compliance Rate   | |
| | 38 Valid Contracts | | 3 Agreements       | | 2 Open Tribunals  | | 96.2% Federations | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [LEGAL WORKSPACE: (1) Contracts Vault | (2) Federation Disputes | (3) Litigation | (4) Board Acts] |
| +-------------------------------------------------------------------------------+ |
| | [ACTIVE CONTRACTS & SPONSORSHIP AGREEMENT REGISTER]                          | |
| | Contract ID | Party / Entity           | Type              | Expiry Date | Status    |
| | CNT-2026-08 | MTN Uganda (Arena Naming)| Commercial Sponsor| Dec 2027    | ACTIVE    |
| | CNT-2026-14 | Lugogo Security Services | Service Contract  | Sep 2026    | RENEWAL   |
| | CNT-2026-21 | FUFA Annual MOU          | Statutory MOU     | Jun 2027    | COMPLIANT |
| | DISP-2026-03| Netball Fed Governance   | Tribunal Hearing  | Hearing Aug | IN_REVIEW |
| | [ Generate Legal Risk Brief for GS ] [ Export Statutory Compliance Pack ]     |
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Database Schema & Technical Architecture

```sql
CREATE TABLE legal_contracts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    contract_reference VARCHAR(64) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    contract_type VARCHAR(64) NOT NULL, -- COMMERCIAL_SPONSOR, VENDOR_PROCUREMENT, LAND_LEASE, FEDERATION_MOU
    first_party VARCHAR(255) NOT NULL DEFAULT 'National Council of Sports',
    second_party VARCHAR(255) NOT NULL,
    contract_value_ugx NUMERIC(18,2) DEFAULT 0.00,
    start_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    renewal_notice_days INT DEFAULT 60,
    legal_officer_id UUID NOT NULL REFERENCES users(id),
    status VARCHAR(32) DEFAULT 'ACTIVE', -- ACTIVE, UNDER_RENEWAL, EXPIRED, TERMINATED, IN_DISPUTE
    document_url TEXT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE federation_disputes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    case_number VARCHAR(64) UNIQUE NOT NULL,
    federation_id UUID NOT NULL REFERENCES sports_federations(id),
    complainant_name VARCHAR(255) NOT NULL,
    respondent_name VARCHAR(255) NOT NULL,
    subject_matter VARCHAR(255) NOT NULL,
    dispute_category VARCHAR(64) NOT NULL, -- ELECTION_CHALLENGE, DISCIPLINARY_APPEAL, FINANCIAL_IMPROPRIETY, CONSTITUTIONAL
    filing_date DATE NOT NULL,
    tribunal_chair_name VARCHAR(128),
    case_status VARCHAR(32) DEFAULT 'FILED', -- FILED, HEARING_STAGE, RULING_RESERVED, CONCLUDED, DISMISSED
    ruling_summary TEXT,
    ruling_document_url TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. Go REST API Endpoints (`backend/internal/handlers/legal.go`)

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/legal/contracts` | GET/POST | `legal_officer` | List, query, and register commercial contracts and MOUs |
| `/api/v1/legal/contracts/:id` | GET/PUT | `legal_officer` | View contract milestones, obligations, and renewals |
| `/api/v1/legal/disputes` | GET/POST | `legal_officer` | Register and manage federation arbitration cases and hearings |
| `/api/v1/legal/disputes/:id/ruling`| POST | `legal_officer` | Upload and publish tribunal arbitral awards and verdicts |
| `/api/v1/legal/compliance/federations` | GET | `legal_officer` | Query federation constitutional and legal compliance statuses |
