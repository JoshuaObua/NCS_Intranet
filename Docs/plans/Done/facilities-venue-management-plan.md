# NCS Facilities Booking & Venue Operations Management Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/facilities-venue-management-plan.md`  
> **Role:** Facilities Manager / Venue Operations Officer / Hostel Custodian  
> **Department:** Facilities & Venue Operations Unit - National Council of Sports (NCS)  
> **Reporting Lines:** Dual Technical/Administrative Reporting to Assistant General Secretary - Technical (AGS-T) & Assistant General Secretary - Administration (AGS-A)  
> **Facilities Managed:** Lugogo National Indoor Arena, Lugogo Sports Ground, Tennis Complex, Hockey Pitch, Cricket Pavilion, NCS Lugogo Hostels Block, Regional Sports Grounds.  
> **Compliance Standards:** Uganda National Sports Act (2023), MoFPED NTR Guidelines, Public Health & Safety Regulations.

---

## 1. Role Overview & Venue Operations Architecture

The **Facilities & Venue Operations Unit** is responsible for maximizing public and sports access to NCS sporting infrastructure, generating Non-Tax Revenue (NTR) through commercial and corporate event bookings, coordinating national athlete camp accommodations at the Lugogo Hostels, and maintaining clean, safe, match-ready venues.

```
+---------------------------------------------------------------------------------------------------+
|               NCS FACILITIES BOOKING & VENUE OPERATIONS WORKFLOW                                  |
+---------------------------------------------------------------------------------------------------+
       |                                      |                                      |
       v                                      v                                      v
+-----------------------+              +-----------------------+              +-----------------------+
| 1. BOOKING ENGINE     |              | 2. FINANCIAL BILLING  |              | 3. EVENT OPERATIONS   |
| - Sports Federations  |              | - NTR Tariff Schedule |              | - Match-Day Setup     |
| - Commercial Events   |              | - Security Deposits   |              | - Security & Cleaning |
| - Calendar Conflict   |              | - Finance / URA Invoic|              | - Post-Event Damage   |
| - AGS-T Technical OK  |              | - Clearance Gate-Pass |              |   Deposit Clearance   |
+-----------------------+              +-----------------------+              +-----------------------+
       |                                      |                                      |
       +--------------------------------------+--------------------------------------+
                                              |
                                              v
                       +-----------------------------------------------+
                       | HOSTEL ACCOMMODATION & RESIDENCE HUB          |
                       | - National Athlete Training Camps             |
                       | - Visiting Foreign Sports Delegations         |
                       | - Room Allocation & Key Management            |
                       | - Resident Meal & Utilities Supervision       |
                       +-----------------------------------------------+
```

---

## 2. Key Modules & User Interface Specifications

---

### 2.1 Multi-Facility Interactive Master Calendar
- Live color-coded scheduler tracking bookings across all NCS sports zones:
  - *Lugogo Indoor Arena:* Basketball, Netball, Volleyball, Boxing, Badminton, Corporate Galas.
  - *Lugogo Stadium / Ground:* Football, Rugby, Athletics training.
  - *Tennis Complex:* Center Court, Court 1-4, Squash Court.
  - *Lugogo Hostels:* 166BLNG1 Residential Block (Rooms 1-40).
- Automatic detection and resolution of booking conflicts between national federation calendar fixtures and commercial events.

### 2.2 Commercial NTR Tariff & Billing Engine
- Automated fee calculation based on approved government NTR tariff scales:
  - National Sports Federation Fixture (Subsidized / Official Rate).
  - Corporate Sports Gala / School Sports Days (Standard Commercial Rate).
  - Music Concerts, Exhibitions & Religious Conventions (Premium Event Rate).
- Generates official Pro-Forma Invoices and links with Finance Department for URA PRN generation and receipt verification.

### 2.3 Pre-Event & Post-Event Inspection Protocol
- **Pre-Event Sign-Off:** Verifies structural safety, crowd barriers, fire extinguishers, clean washrooms, and floodlights.
- **Post-Event Audit & Damage Deposit Release:** Detailed damage inspection before Finance releases event security caution deposits.

### 2.4 Lugogo Hostel Accommodation Management
- Athlete resident management: Check-in/check-out dates, national federation camp rosters, room occupancy rates, and hostel utility monitoring (water, laundry, Wi-Fi).

---

## 3. Facilities Dashboard User Interface (`FacilitiesManagementDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Facilities & Venue Operations Command  [➕ New Booking] [🏢 Lugogo]|
+-----------------------------------------------------------------------------------+
| [VENUE UTILIZATION & NTR SUMMARY CARDS]                                           |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Monthly Arena NTR  | | Arena Occupancy    | | Hostels Occupancy | | Upcoming Events   | |
| | UGX 142.5 Million  | | 88.5% This Month   | | 32 / 40 Rooms Full| | 12 Fixtures (7d)  | |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [FACILITY WORKSPACE: (1) Calendar Scheduler | (2) Booking Invoices | (3) Hostels | (4) Inspections] |
| +-------------------------------------------------------------------------------+ |
| | [VENUE BOOKING PIPELINE & STATUS RADAR]                                       | |
| | Booking ID | Venue / Space           | Event / Client           | Date        | Status   |
| | BKG-2026-41| Lugogo Indoor Arena     | National Basketball Lge  | Aug 15-17   | CONFIRMED|
| | BKG-2026-42| Tennis Center Court     | Uganda Open Tennis Trials| Aug 18-20   | CONFIRMED|
| | BKG-2026-43| Lugogo Sports Ground    | Corporate Sports Gala    | Aug 22      | PENDING  |
| | BKG-2026-44| Hostel Block (20 Rooms) | National Boxing Camp     | Aug 15-30   | CHECKEDIN|
| | [ Generate Venue Schedule PDF ] [ Export Monthly NTR Revenue Statement ]       |
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Database Schema & Technical Architecture

```sql
CREATE TABLE venue_bookings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    booking_reference VARCHAR(64) UNIQUE NOT NULL,
    venue_id VARCHAR(64) NOT NULL, -- LUGOGO_ARENA, LUGOGO_STADIUM, TENNIS_COURTS, HOSTELS
    client_name VARCHAR(255) NOT NULL,
    client_type VARCHAR(32) NOT NULL, -- FEDERATION, CORPORATE, INDIVIDUAL, GOVERNMENT
    event_title VARCHAR(255) NOT NULL,
    start_time TIMESTAMP WITH TIME ZONE NOT NULL,
    end_time TIMESTAMP WITH TIME ZONE NOT NULL,
    tariff_category VARCHAR(32) NOT NULL, -- SUBSIDIZED, COMMERCIAL_STANDARD, PREMIUM
    total_fee_ugx NUMERIC(18,2) NOT NULL,
    caution_deposit_ugx NUMERIC(18,2) DEFAULT 0.00,
    payment_status VARCHAR(32) DEFAULT 'PENDING', -- PENDING, PARTIALLY_PAID, FULLY_PAID
    technical_approval_status VARCHAR(32) DEFAULT 'APPROVED', -- APPROVED, REJECTED
    post_event_damage_deduction_ugx NUMERIC(18,2) DEFAULT 0.00,
    deposit_refund_status VARCHAR(32) DEFAULT 'PENDING_INSPECTION',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE hostel_occupancies (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    room_number VARCHAR(32) NOT NULL,
    athlete_name VARCHAR(128) NOT NULL,
    nin_or_passport VARCHAR(64) NOT NULL,
    federation_id UUID NOT NULL REFERENCES sports_federations(id),
    gender VARCHAR(16) NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    status VARCHAR(32) DEFAULT 'ACTIVE_CAMP', -- ACTIVE_CAMP, CHECKED_OUT
    key_issued BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. Go REST API Endpoints (`backend/internal/handlers/facilities.go`)

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/facilities/calendar` | GET | All Staff / Public | Query availability calendar across all NCS sporting facilities |
| `/api/v1/facilities/bookings` | GET/POST | Facilities Officer | Create, manage, and filter facility booking applications |
| `/api/v1/facilities/bookings/:id/invoice` | GET/POST | Facilities / Finance | Generate pro-forma tariff invoices and record URA PRN payments |
| `/api/v1/facilities/bookings/:id/inspection`| POST | Facilities Officer | Submit pre/post event condition checklist and refund clearance |
| `/api/v1/facilities/hostels/rooms` | GET/POST | Facilities Custodian | Manage room roster, athlete camp check-ins, and key logs |
