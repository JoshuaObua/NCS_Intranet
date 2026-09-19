# NCS Applicant & Federation Licensing Portal Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/applicant-licensing-portal-plan.md`  
> **Role:** Applicant Users / National Sports Association Presidents & General Secretaries / Federation License Applicants  
> **System Scope:** Public & Applicant Self-Service Portal (`ApplicantPortalView.vue`, `ApplicantLicensePortalView.vue`, `DynamicPortalFormView.vue`)  
> **Authority Scope:** Online License Applications, Annual Federation Registration Renewals, Sports Association Documentation Uploads, Application Progress Tracking, Digital License Certificates  
> **Compliance Standards:** Uganda National Sports Act (2023), Statutory Instrument on Registration of National Sports Associations  

---

## 1. Executive Purpose & Portal Architecture

The **Applicant & Federation Licensing Portal** provides a transparent, digital self-service entry point for external sports associations, national sports federations, athletes, coaches, and sports promoters applying for registration, annual licensing, or government recognition from the National Council of Sports.

```
+-----------------------------------------------------------------------------------+
|                        APPLICANT & FEDERATION LICENSING ENGINE                    |
+-----------------------------------------------------------------------------------+
       |                                  |                                 |
       v                                  v                                 v
+-----------------------+      +-----------------------+      +---------------------+
| 1. APPLICANT PORTAL   |      | 2. DYNAMIC APPLICATION|      | 3. VERIFICATION &   |
| (`ApplicantPortal.vue`|      |    FORMS & UPLOADS    |      |    LICENSE ISSUANCE |
+-----------------------+      +-----------------------+      +---------------------+
| - Application Tracker |      | - Constitution Upload |      | - Technical Dept    |
| - Certificate Vault   |      | - AGM Minutes         |      | - General Secretary |
| - Invite Acceptor     |      | - Executive Roster    |      | - QR Code License   |
+-----------------------+      +-----------------------+      +---------------------+
```

---

## 2. Key Modules & User Interface Specifications

### 2.1 Applicant Self-Service Dashboard (`ApplicantPortalView.vue`)
- **Application Status Tracker:** Real-time progress tracker (`Draft` $\rightarrow$ `Submitted` $\rightarrow$ `Under Technical Review` $\rightarrow$ `Pending Accounting Officer Sign-off` $\rightarrow$ `Approved & Certificate Issued`).
- **Digital Certificate Vault:** Secure repository where approved federations download their official NCS Certificate of Registration (embedded with cryptographic QR code).
- **Federation Invite Acceptance View (`AcceptOrganisationInviteView.vue`):** Allows newly appointed federation officers to claim their official executive portal account.

### 2.2 Dynamic License Application Engine (`ApplicantLicensePortalView.vue`, `DynamicPortalFormView.vue`)
- **Interactive Form Wizard:** Multi-step registration form capturing:
  - Federation Constitution & Bye-Laws (PDF upload).
  - Executive Committee Roster (President, Vice Presidents, General Secretary, Treasurer).
  - List of Active Regional Clubs/Associations (Minimum 15 districts per National Sports Act 2023).
  - Annual Audited Financial Accounts & Bank Account Details.
  - Safeguarding Policy Declaration.

---

## 3. Database Schema & REST API Mapping

```sql
CREATE TABLE federation_applications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    application_number VARCHAR(64) UNIQUE NOT NULL,
    applicant_user_id UUID NOT NULL REFERENCES users(id),
    association_name VARCHAR(255) NOT NULL,
    sport_discipline VARCHAR(64) NOT NULL,
    submission_data JSONB NOT NULL,
    constitution_url TEXT NOT NULL,
    audited_accounts_url TEXT NOT NULL,
    status VARCHAR(32) DEFAULT 'SUBMITTED', -- DRAFT, SUBMITTED, UNDER_REVIEW, APPROVED, REJECTED
    reviewed_by UUID REFERENCES users(id),
    approved_by UUID REFERENCES users(id),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

| Endpoint | Method | Scope | Function |
| :--- | :--- | :--- | :--- |
| `/api/v1/applications` | GET/POST | Applicant | Submit and track national sports licensing applications |
| `/api/v1/applications/:id/upload` | POST | Applicant | Upload supporting documents (Constitution, Audited Accounts) |
| `/api/v1/applications/:id/review` | PUT | Technical Dept | Review and recommend application approval |
