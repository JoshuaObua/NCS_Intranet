# NCS Technical & Sports Administration Department Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/technical-sports-department-plan.md`  
> **Role:** Technical Director / Senior Sports Officer / National Sports Federation Liaison / Safeguarding Officer  
> **Department:** Technical & Sports Administration Department - National Council of Sports (NCS)  
> **Authority Scope:** NSMIS Management, 50+ National Sports Associations/Federations, Athletes & Coaches Registry, International Games Delegations, Safeguarding, Medal Tallies, Event Venue Approvals  
> **Compliance Standards:** Uganda National Sports Act (2023), NCS Federation Governance Guidelines, International Olympic Committee (IOC) Safeguarding Protocols  

---

## 1. Executive Role Overview & Mission

The **Technical & Sports Administration Department** is the core operational arm of the National Council of Sports (NCS). It regulates, monitors, and supports over 50 registered National Sports Associations/Federations in Uganda, oversees elite athlete development, manages national sports competitions, coordinates international team delegations (Olympics, Commonwealth, All Africa Games, AFCON), enforces safeguarding policies, and certifies venue readiness.

This plan details the **Technical & Sports Command Workspace** inside `NCS_Intranet`. It integrates directly with the **National Sports Management Information System (NSMIS)** (`NSMISRegistryView.vue`, `GovernanceDashboardView.vue`, `InsightsDashboardView.vue`).



### Key Workstation Tabs & Features

1. **National Sports Associations / Federations 360° Registry (`NSMISRegistryView.vue`):**
   - Profile management for all 50+ registered national sports associations in Uganda.
   - Federation executive committees, constitution records, annual general meeting (AGM) minutes, election cycles, and certificate of registration.

2. **National Athletes, Coaches & Technical Officials Database:**
   - Centralized licensing portal for athletes, national coaches, referees, and technical judges.
   - Bio-data, biometric records, international competition achievements, personal bests, anti-doping clearance (RADO/WADA), and medical clearances.

3. **Governance & Compliance Evaluation Engine (`GovernanceDashboardView.vue`):**
   - Automated scoring index evaluating federations on financial accountabilities, democratic elections, audited books of accounts, and annual activity reports.
   - Direct integration with Finance Department for quarterly grant release clearance.

4. **Safeguarding & Athlete Welfare Portal:**
   - Incident reporting and case management for athlete safeguarding, child protection, anti-harassment, and athlete mental health support.

5. **International Games Delegations & Stadium Venue Booking:**
   - Team Uganda international travel clearance, visa processing support letters, flight logistics, and government delegation briefs for the Accounting Officer (GS) and Minister of Sports.

---

## 3. Database Schema & Technical Architecture

```sql
CREATE TABLE sports_federations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code VARCHAR(32) UNIQUE NOT NULL, -- e.g. 'FUFA', 'UAF', 'UNF', 'UBF'
    name VARCHAR(255) NOT NULL,
    sport_category VARCHAR(64) NOT NULL, -- BALL_GAMES, ATHLETICS, COMBAT, WATER, INDOOR
    president_name VARCHAR(128) NOT NULL,
    general_secretary_name VARCHAR(128) NOT NULL,
    contact_email VARCHAR(128) NOT NULL,
    contact_phone VARCHAR(32) NOT NULL,
    registration_status VARCHAR(32) DEFAULT 'ACTIVE', -- ACTIVE, PROVISIONAL, SUSPENDED
    compliance_score NUMERIC(5,2) DEFAULT 100.00,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE national_athletes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    license_number VARCHAR(64) UNIQUE NOT NULL,
    federation_id UUID NOT NULL REFERENCES sports_federations(id),
    full_name VARCHAR(128) NOT NULL,
    nin_number VARCHAR(32) UNIQUE NOT NULL,
    gender VARCHAR(16) NOT NULL,
    date_of_birth DATE NOT NULL,
    discipline VARCHAR(64) NOT NULL, -- e.g. '100m Sprint', 'Marathon', 'Welterweight Boxing'
    wada_doping_cleared BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 4. REST API Mapping (`backend/internal/handlers/nsmis.go`)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/api/v1/nsmis/federations` | GET/POST | Query and register national sports associations |
| `/api/v1/nsmis/athletes` | GET/POST | Query and license national athletes and coaches |
| `/api/v1/nsmis/governance/evaluate` | POST | Execute federation governance compliance scoring |
| `/api/v1/nsmis/delegations` | GET/POST | Create international team delegation manifest for GS approval |

---

## 5. Implementation Verification Roadmap

- [ ] Connect `NSMISRegistryView.vue` and `GovernanceDashboardView.vue` to Go backend handler `backend/internal/handlers/nsmis.go`.
- [ ] Connect federation grant clearance metrics to Accountant and GS dashboards.
