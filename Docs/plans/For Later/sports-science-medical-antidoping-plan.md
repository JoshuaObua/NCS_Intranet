# NCS Sports Science, Medical & Anti-Doping Unit Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/sports-science-medical-antidoping-plan.md`  
> **Role:** Chief Medical Officer (CMO) / Sports Physician / Anti-Doping Officer / Senior Physiotherapist  
> **Department:** Sports Science & Medical Unit - National Council of Sports (NCS)  
> **Reporting Line:** Technical Directorate / Assistant General Secretary - Technical (AGS-T)  
> **Operational Scope:** National Athlete Medical Fitness Clearance, Injury Surveillance & Rehabilitation, WADA/RADO Anti-Doping Compliance, Therapeutic Use Exemptions (TUE), Emergency Match-Day Medical Deployments, and Sports Science Performance Testing.  
> **Standards:** World Anti-Doping Agency (WADA) Code, IOC Medical Code, Uganda National Sports Act (2023).

---

## 1. Role Overview & Sports Medicine Command Architecture

The **Sports Science, Medical & Anti-Doping Unit** safeguards the health, physical readiness, and ethical integrity of Ugandan national athletes across all 50+ sports disciplines. It conducts pre-competition medical screenings, manages acute injuries and rehabilitation, guarantees strict anti-doping compliance, and provides official medical travel fitness clearance for international competitions.

```
+---------------------------------------------------------------------------------------------------+
|               NCS SPORTS SCIENCE & MEDICAL OPERATIONS WORKFLOW                                    |
+---------------------------------------------------------------------------------------------------+
       |                                      |                                      |
       v                                      v                                      v
+-----------------------+              +-----------------------+              +-----------------------+
| 1. MEDICAL FITNESS    |              | 2. INJURY & REHAB     |              | 3. ANTI-DOPING (WADA) |
| - Pre-Games Screening |              | - Incident Logging    |              | - Testing Pool Roster |
| - Cardiac / ECG Check |              | - Treatment Tracking  |              | - TUE Certificate App |
| - Blood & Vitals Scan |              | - Physio Session Logs |              | - Whereabouts Monitor |
| - Travel Fit Pass     |              | - Return-to-Play Pass |              | - Clean Sport Audits  |
+-----------------------+              +-----------------------+              +-----------------------+
       |                                      |                                      |
       +--------------------------------------+--------------------------------------+
                                              |
                                              v
                       +-----------------------------------------------+
                       | HIGH-PERFORMANCE SPORTS SCIENCE LAB           |
                       | - VO2 Max & Lactate Threshold Testing         |
                       | - Biomechanical Video Analysis                |
                       | - Athlete Nutritional & Hydration Profiling   |
                       | - Emergency Match Medical Deployments (AEDs)  |
                       +-----------------------------------------------+
```

---

## 2. Key Modules & User Interface Specifications

---

### 2.1 Athlete Medical Screening & Travel Clearance
- **Pre-Competition Screening:** Standardized medical intake capturing medical history, cardiovascular screening, orthopedic examination, and vision tests.
- **Official Medical Travel Fitness Certificate:** Cryptographically signed travel medical pass required for international team clearance submitted to AGS-T and GS.

### 2.2 Injury Surveillance & Rehabilitation Tracker
- **Electronic Injury Log:** Records injury mechanism, anatomical location, severity, and diagnosis.
- **Physiotherapy & Treatment Milestones:** Tracks clinical progress, rehabilitation sessions, and formal multidisciplinary Return-to-Play (RTP) clearance.

### 2.3 Anti-Doping & Therapeutic Use Exemption (TUE) Portal
- **Registered Testing Pool (RTP) Management:** Tracks elite athletes required to submit quarterly whereabouts under WADA/RADO regulations.
- **TUE Application & Certificate Vault:** Processing medical exemptions for prohibited substances required for legitimate medical conditions.
- **Clean Sport Education Index:** Tracks mandatory anti-doping workshops completed by national athletes and coaches.

### 2.4 Medical Supplies & Emergency Equipment Inventory
- Real-time monitoring of emergency equipment (AEDs, oxygen cylinders, trauma bags, cervical collars, ice baths) deployed at Lugogo sports arenas and regional tournaments.

---

## 3. Medical Dashboard User Interface (`SportsMedicalDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Sports Science & Medical Unit Command  [➕ New Screening] [🩺 Med] |
+-----------------------------------------------------------------------------------+
| [ATHLETE HEALTH & ANTI-DOPING KPI CARDS]                                          |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Medically Cleared  | | Active In Rehab    | | WADA Clean Rate   | | Active TUE Certs  | |
| | 1,420 Elite Athletes| | 18 Athletes        | | 100% Zero Violat. | | 12 Approved TUEs  | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [MEDICAL WORKSPACE: (1) Athlete Health | (2) Injury Surveillance | (3) Anti-Doping | (4) Emergency] |
| +-------------------------------------------------------------------------------+ |
| | [NATIONAL ATHLETE MEDICAL SCREENING & CLEARANCE REGISTRY]                     | |
| | Athlete Name      | Discipline   | Federation     | Screening Date | Status     |
| | Kiplimo Jacob     | Athletics    | UAF            | Aug 10, 2026   | CLEARED_FIT|
| | Ouma George       | Boxing       | UBF            | Aug 12, 2026   | CLEARED_FIT|
| | Nakato Sarah      | Netball      | UNF            | Aug 08, 2026   | IN_REHAB   |
| | Ssenyondo Fred    | Rugby 7s     | URU            | Aug 11, 2026   | CLEARED_FIT|
| | [ Generate Team Uganda Medical Travel Dossier ] [ Export WADA Compliance Pack ]|
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Database Schema & Technical Architecture

```sql
CREATE TABLE athlete_medical_screenings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    athlete_id UUID NOT NULL REFERENCES national_athletes(id),
    screening_date DATE NOT NULL,
    examining_physician_id UUID NOT NULL REFERENCES users(id),
    cardiovascular_passed BOOLEAN DEFAULT TRUE,
    ecg_finding VARCHAR(128) DEFAULT 'Normal Sinus Rhythm',
    blood_pressure VARCHAR(32) NOT NULL,
    musculoskeletal_status VARCHAR(64) DEFAULT 'Fit / No Limitations',
    concussion_baseline_score NUMERIC(5,2) DEFAULT 0.00,
    fitness_verdict VARCHAR(32) NOT NULL DEFAULT 'CLEARED_FIT', -- CLEARED_FIT, TEMPORARILY_UNFIT, RESTRICTED, PERMANENTLY_UNFIT
    medical_notes TEXT,
    certificate_hash VARCHAR(255),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE athlete_injuries (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    athlete_id UUID NOT NULL REFERENCES national_athletes(id),
    incident_date DATE NOT NULL,
    injury_site VARCHAR(64) NOT NULL, -- Knee, Ankle, Hamstring, Shoulder, Concussion
    injury_nature VARCHAR(128) NOT NULL, -- ACL Tear, Grade 2 Sprain, Fracture
    severity VARCHAR(32) NOT NULL, -- MILD, MODERATE, SEVERE
    attending_physio_id UUID NOT NULL REFERENCES users(id),
    rehab_status VARCHAR(32) DEFAULT 'ACUTE_CARE', -- ACUTE_CARE, PHYSIO_REHAB, RETURN_TO_TRAINING, CLEARED_TO_PLAY
    expected_return_date DATE,
    actual_clearance_date DATE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE antidoping_records (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    athlete_id UUID NOT NULL REFERENCES national_athletes(id),
    sample_code VARCHAR(64) UNIQUE NOT NULL,
    testing_authority VARCHAR(64) NOT NULL DEFAULT 'RADO / WADA',
    test_type VARCHAR(32) NOT NULL, -- IN_COMPETITION, OUT_OF_COMPETITION
    collection_date DATE NOT NULL,
    result_status VARCHAR(32) DEFAULT 'PENDING_LAB', -- PENDING_LAB, NEGATIVE_CLEAN, ADVERSE_ANALYTICAL_FINDING
    has_active_tue BOOLEAN DEFAULT FALSE,
    tue_reference VARCHAR(64),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. Go REST API Endpoints (`backend/internal/handlers/medical.go`)

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/medical/screenings` | GET/POST | `medical_officer` | List, query, and register athlete pre-competition medical screenings |
| `/api/v1/medical/screenings/:id/cert`| GET | `medical_officer` | Generate official cryptographically signed medical fitness clearance certificate |
| `/api/v1/medical/injuries` | GET/POST | `physiotherapist` | Log athlete injuries, rehabilitation progress, and return-to-play status |
| `/api/v1/medical/antidoping` | GET/POST | `medical_officer` | Record WADA sample collections, test results, and TUE authorizations |
| `/api/v1/medical/delegations/:id` | GET | `medical_officer` | Compile composite medical clearance dossier for national team travel |
