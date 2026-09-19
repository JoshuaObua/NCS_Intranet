# Engineering Officer Civil Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/engineering-officer-civil-dashboard-plan.md`  
> **Role:** Engineering Officer Civil (Division Head - Civil Engineering)  
> **Reports To:** Senior Engineer  
> **Oversees:** Assistant Engineer Civil (and indirectly Plumbers, Masons, Groundsmen)  
> **Baseline Civil Assets:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (`LAND`, `NON RESIDENTIAL BUILDINGS`, `RESIDENTIAL BUILDINGS` Worksheets, 26 Assets | UGX 30,199,635,769.00 Valuation + Civil Infrastructure Scope)  
> **Statutory & Technical Standards:** Uganda National Building Code, PPDA Act, IPSAS 17 (Property, Plant & Equipment).

---

## 1. Role Overview & Objectives

The **Engineering Officer Civil** leads the Civil Engineering Division at the National Council of Sports (NCS). This role is responsible for the physical infrastructure integrity of all NCS sports complexes, pitches (National Hockey Pitch, Cricket Oval, Tennis Courts, Volleyball Courts), stadium seating, roofing structures, drainage networks, building structures (NCS Lugogo Indoor Arena, Administration Headquarters, Hostel Blocks), and civil asset lifecycles (`LAND`, `NON RESIDENTIAL BUILDINGS`, and `RESIDENTIAL BUILDINGS` categories in `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`).

---

## 2. Shared Navigation & Sidebar Menu Items

Inherits the unified **NCS Intranet Navigation Framework**:

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Eng Officer Civil        [🔍 Search...] [🔄] [🌙] [🔔 4] [💬 1] [User] |
+-----------------------------------------------------------------------------------+
| [SIDEBAR MENU]  | [MAIN CONTENT DASHBOARD]                                        |
| 1. Dashboard    | +-------------------------------------------------------------+ |
| 2. Civil WOs    | | STATS: Civil Assets: 26 | Reviews: 6 | Pitch Score: 92% | |
| 3. Pitch Health | +-------------------------------------------------------------+ |
| 4. Fixed Assets | | [Overview] [Requisitions] [My Activities] [Leave] [Profile] | |
| 5. My Activities| +-------------------------------------------------------------+ |
| 6. Leave        | | Civil Material Requisitions Pending Approval (3)             | |
| 7. Messages     | | REQ-042 | 50mm PVC Pipes | 1.8M | [Approve]                 | |
| 8. Profile      | +-------------------------------------------------------------+ |
| 9. Settings     |                                                                 |
+-----------------------------------------------------------------------------------+
```

### Functional Features
- **Fixed Assets Register Integration (`FixedAssetsView.vue`):** Direct division oversight of civil assets (Lugogo Indoor Arena, Coronation Ave & Hesketh Bell Rd Land Plots, Hockey Pitch, Cricket Oval, Tennis Courts, NCS Hostel Block).
- **My Activities Menu Item:** Directly routes to `/me/activities`. Queries personal action audit history (civil requisitions authorized, pitch inspections logged, leave requests reviewed, password changes).
- **Navbar Controls:** Theme switcher, global search, instant refresh, notifications drawer, and direct messages drawer.

---

## 3. Fixed Asset Register & Personal Audit Log

```json
// GET /api/v1/assets?category=NON RESIDENTIAL BUILDINGS
{
  "status": "success",
  "data": {
    "assets": [
      {
        "asset_number": "M1007059",
        "tag_number": "166BLNG5",
        "asset_description": "INDOOR STADIUM-166-BLNG-5",
        "category_segment3": "NON RESIDENTIAL BUILDINGS",
        "fb_cost": 1160000000.00,
        "adjusted_cost": 1160000000.00,
        "useful_life_years": 50
      }
    ],
    "total": 17
  }
}
```

---

## 4. Go REST API & Checklist

| Endpoint | Method | Scope | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/assets` | GET | Civil Officer | Query 26 civil assets (Land, Buildings, Hostels) | ✅ Implemented |
| `/api/v1/assets/verify` | POST | Civil Officer | Structural spot-check tag verification | ✅ Implemented |
| `/api/v1/engineering/civil-officer/dashboard` | GET | Civil Officer | Civil dashboard KPIs & queue | ✅ Implemented |
| `/api/v1/me/activities` | GET | Self Only | Query personal account audit log | ✅ Implemented |

- [x] Integrate 26 civil land and building records from `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`.
- [x] Register **"My Activities"** and **"Fixed Assets"** links in `EngineeringOfficerCivilDashboard.vue` sidebar.
- [x] Test personal activity query for Civil Officer account.
