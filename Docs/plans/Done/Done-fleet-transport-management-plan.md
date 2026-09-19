# NCS Fleet, Logistics & Transport Management Dashboard Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/fleet-transport-management-plan.md`  
> **Role:** Transport Officer / Fleet Manager / Logistics Coordinator / Head Driver  
> **Department:** Transport & Fleet Management Unit - National Council of Sports (NCS)  
> **Reporting Line:** Administration Directorate / Assistant General Secretary - Administration (AGS-A)  
> **Vehicular Fleet Portfolio:** King Long Kingo 16S Bus (UBF 748K), Ford Ranger Pick Up (UAJ 225X), Kia Sorento Station Wagon (UBF 477F), Honda Motorcycle (UFH 770B), and Pool Utility Vehicles.  
> **Compliance Standards:** Public Finance Management Act (PFMA 2015), Ministry of Works & Transport Fleet Guidelines, Traffic & Road Safety Act.

---

## 1. Role Overview & Fleet Operations Architecture

The **Transport & Fleet Management Unit** manages the mobility, logistics, fuel efficiency, and vehicle maintenance of the National Council of Sports. It coordinates transport for national athletes, international sports delegations, executive leadership, and operational field teams while enforcing stringent fuel accountability and asset longevity.

```
+---------------------------------------------------------------------------------------------------+
|               NCS FLEET & TRANSPORT OPERATIONAL WORKFLOW                                          |
+---------------------------------------------------------------------------------------------------+
       |                                      |                                      |
       v                                      v                                      v
+-----------------------+              +-----------------------+              +-----------------------+
| 1. TRIP REQUISITION   |              | 2. FLEET DISPATCH     |              | 3. FUEL & MAINTENANCE |
| - Staff Duty Requests |              | - Driver Assignment   |              | - Electronic Fuel Card|
| - Team Uganda Transit |              | - Vehicle Checkout    |              | - Odometer Mileage Log|
| - Dignitary Airport   |              | - Gate Pass Issuance  |              | - Routine Service Run |
| - HOD / AGS-A Approval|              | - Return Trip Audit   |              | - Wear & Tear Repairs |
+-----------------------+              +-----------------------+              +-----------------------+
       |                                      |                                      |
       +--------------------------------------+--------------------------------------+
                                              |
                                              v
                       +-----------------------------------------------+
                       | ASSET HEALTH & COMPLIANCE HUB                 |
                       | - Third-Party & Comprehensive Insurance       |
                       | - Ministry Roadworthiness Certificates        |
                       | - Integration with Accounting Fixed Assets    |
                       | - Vehicle Decommissioning & Disposal Schedule |
                       +-----------------------------------------------+
```

---

## 2. Key Modules & User Interface Specifications

---

### 2.1 Vehicle Master Registry & Telematics Radar
- Integrates directly with the `LIGHT VEHICLES` and `CYCLES` classes from the NCS Fixed Asset Register (`FIXED ASSET REGISTER ADJUSTMENTS.xlsx`):
  - **UBF 748K:** King Long Kingo 16S Bus (Team Transit & Mass Delegations).
  - **UAJ 225X:** Ford Ranger Double Cabin (Engineering & Field Operations).
  - **UBF 477F:** Kia Sorento Station Wagon (Executive & Protocol Transit).
  - **UFH 770B:** Honda Motorcycle (Dispatch & Rapid Operations).
- Odometer logs, maintenance intervals, insurance expiry alerts, and roadworthiness inspection certificates.

### 2.2 Trip Requisition, Clearance & Gate-Pass Engine
- Staff trip request portal: Requesting department $\rightarrow$ Purpose $\rightarrow$ Destination $\rightarrow$ Passenger list $\rightarrow$ HOD/AGS-A endorsement $\rightarrow$ Vehicle/Driver dispatch $\rightarrow$ Automated security gate pass.

### 2.3 Electronic Fuel Management & Mileage Audit
- Monthly fuel voucher allocations, fuel card receipts, and liters-per-100km fuel consumption monitoring.
- Automated anomaly detection flagging excessive fuel consumption vs logged trip mileage.

### 2.4 Driver Rosters & Performance Profiles
- Driver duty scheduling, driver's license validities, defensive driving certifications, and incident/fine logs.

---

## 3. Fleet Dashboard User Interface (`FleetTransportDashboard.vue`)

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Transport & Fleet Management Command  [➕ New Trip] [⛽ Fuel Log] |
+-----------------------------------------------------------------------------------+
| [FLEET HEALTH & MOBILITY SUMMARY CARDS]                                           |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
| | Active Fleet Size  | | On-Road Today      | | Monthly Fuel Spend| | Upcoming Service  | |
| | 4 Primary + Pool   | | 3 Trips Dispatched | | UGX 14.8M / 18.0M | | 1 Vehicle Due (5d)| |
| +--------------------+ +--------------------+ +-------------------+ +-------------------+ |
+-----------------------------------------------------------------------------------+
| [FLEET WORKSPACE: (1) Vehicle Registry | (2) Trip Requisitions | (3) Fuel Logs | (4) Drivers] |
| +-------------------------------------------------------------------------------+ |
| | [NCS VEHICLE STATUS & DISPATCH RADAR]                                         | |
| | Reg Number | Model / Class            | Driver        | Current Mileage | Status|
| | UBF 748K   | King Long Bus 16S        | Mukasa Ivan   | 48,250 KM       | ON ROAD (Airport)|
| | UAJ 225X   | Ford Ranger Double Cabin | Okello David  | 82,100 KM       | AT BASE (Lugogo) |
| | UBF 477F   | Kia Sorento Station Wagon| Musoke Peter  | 36,400 KM       | RESERVED (GS)    |
| | UFH 770B   | Honda Motorcycle         | Kigozi Sam    | 18,900 KM       | ON ROAD (Kampala)|
| | [ Generate Fleet Efficiency PDF ] [ Export Fuel & Maintenance Cost Summary ]  |
| +-------------------------------------------------------------------------------+ |
+-----------------------------------------------------------------------------------+
```

---

## 4. Database Schema & Technical Architecture

```sql
CREATE TABLE fleet_vehicles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    asset_id UUID REFERENCES fixed_assets(id),
    registration_number VARCHAR(32) UNIQUE NOT NULL,
    make_model VARCHAR(128) NOT NULL,
    vehicle_type VARCHAR(32) NOT NULL, -- BUS, PICKUP, STATION_WAGON, MOTORCYCLE
    seating_capacity INT NOT NULL,
    fuel_type VARCHAR(16) NOT NULL DEFAULT 'DIESEL', -- DIESEL, PETROL
    current_mileage_km INT NOT NULL DEFAULT 0,
    next_service_mileage_km INT NOT NULL,
    insurance_policy_number VARCHAR(64),
    insurance_expiry_date DATE NOT NULL,
    fitness_certificate_expiry DATE NOT NULL,
    status VARCHAR(32) DEFAULT 'AVAILABLE', -- AVAILABLE, ON_TRIP, UNDER_MAINTENANCE, DECOMMISSIONED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE trip_requisitions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    trip_code VARCHAR(64) UNIQUE NOT NULL,
    requesting_department VARCHAR(64) NOT NULL,
    requested_by UUID NOT NULL REFERENCES users(id),
    vehicle_id UUID NOT NULL REFERENCES fleet_vehicles(id),
    assigned_driver_id UUID NOT NULL REFERENCES users(id),
    destination VARCHAR(255) NOT NULL,
    trip_purpose TEXT NOT NULL,
    departure_time TIMESTAMP WITH TIME ZONE NOT NULL,
    return_time TIMESTAMP WITH TIME ZONE NOT NULL,
    start_odometer_km INT,
    end_odometer_km INT,
    approval_status VARCHAR(32) DEFAULT 'PENDING_APPROVAL', -- PENDING_APPROVAL, APPROVED, DISPATCHED, COMPLETED, CANCELLED
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE TABLE fuel_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    vehicle_id UUID NOT NULL REFERENCES fleet_vehicles(id),
    driver_id UUID NOT NULL REFERENCES users(id),
    fuel_card_reference VARCHAR(64) NOT NULL,
    liters_filled NUMERIC(8,2) NOT NULL,
    cost_per_liter_ugx NUMERIC(10,2) NOT NULL,
    total_cost_ugx NUMERIC(18,2) NOT NULL,
    odometer_at_fueling INT NOT NULL,
    receipt_scan_url TEXT,
    fueled_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

---

## 5. Go REST API Endpoints (`backend/internal/handlers/fleet.go`)

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/fleet/vehicles` | GET/POST | `transport_officer` | List, query, and register organizational vehicles and motorcycles |
| `/api/v1/fleet/vehicles/:id` | GET/PUT | `transport_officer` | Update vehicle mileage, service milestones, and insurance records |
| `/api/v1/fleet/trips` | GET/POST | All Staff / Fleet | Submit trip requisitions and process fleet dispatch gate-passes |
| `/api/v1/fleet/fuel` | GET/POST | `transport_officer` | Record fuel logs, calculate fuel burn variance, and track spend |
| `/api/v1/fleet/maintenance` | GET/POST | `transport_officer` | Schedule vehicle maintenance and log mechanical spare parts repairs |
