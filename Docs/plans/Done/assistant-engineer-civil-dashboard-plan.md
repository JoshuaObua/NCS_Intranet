# Assistant Engineer Civil Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/assistant-engineer-civil-dashboard-plan.md`  
> **Role:** Assistant Engineer Civil (Operational Field Supervisor)  
> **Reports To:** Engineering Officer Civil / Senior Engineer  
> **Oversees:** Plumbers, Masons, Carpenters, Grounds Technicians  
> **Baseline Civil Assets:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (`LAND`, `NON RESIDENTIAL BUILDINGS`, `RESIDENTIAL BUILDINGS` Worksheets, 26 Assets | UGX 30,199,635,769.00 Valuation + Civil Infrastructure Scope)  
> **Statutory & Technical Standards:** Uganda National Building Code, PPDA Act, IPSAS 17 (Property, Plant & Equipment).

---

## 1. Role Overview & Objectives

The **Assistant Engineer Civil** serves as the primary operational supervisor for civil maintenance, plumbing systems, building structural inspections, sports grounds (Lugogo Hockey Pitch, Cricket Oval, Tennis Courts, Volleyball Courts), and civil fixed asset tag verification (`LAND`, `NON RESIDENTIAL BUILDINGS`, and `RESIDENTIAL BUILDINGS` categories in `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`).

---

## 2. Shared Navigation & Sidebar Menu Items

Inherits the unified **NCS Intranet Navigation Framework**:

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Asst Engineer Civil       [🔍 Search...] [🔄] [🌙] [🔔 5] [💬 2] [User] |
+-----------------------------------------------------------------------------------+
| [SIDEBAR MENU]  | [MAIN CONTENT DASHBOARD]                                        |
| 1. Dashboard    | +-------------------------------------------------------------+ |
| 2. Work Orders  | | STATS: Civil Assets: 26 | Active Plumbers: 4 | Verifications: 7  | |
| 3. Plumbers     | +-------------------------------------------------------------+ |
| 4. Fixed Assets | | [Overview] [Create WO] [My Activities] [Leave] [Profile]     | |
| 5. My Activities| +-------------------------------------------------------------+ |
| 6. Leave        | | Active Civil & Plumbing Work Orders (12)                        | |
| 7. Messages     | | WO-0880 | Arena Washroom Leak | Plumber Kato | [Assign]         | |
| 8. Profile      | +-------------------------------------------------------------+ |
| 9. Settings     |                                                                 |
+-----------------------------------------------------------------------------------+
```

### Functional Features
- **Fixed Assets Register Integration (`FixedAssetsView.vue`):** Direct oversight of civil assets (Lugogo Indoor Arena, Coronation Ave & Hesketh Bell Rd Land Plots, Hockey Pitch, Cricket Oval, Tennis Courts, NCS Hostel Block).
- **My Activities Menu Item:** Routes to `/me/activities`. Displays personal activity logs (work orders created, plumber task assignments, material requisitions drafted, site verifications cleared).
- **Navbar Controls:** Theme switcher, global search, refresh, notifications drawer, and messages drawer.

---

## 3. Fixed Asset Register & Personal Audit Log

```json
// GET /api/v1/assets?category=LAND
{
  "status": "success",
  "data": {
    "assets": [
      {
        "asset_number": "M1007155",
        "tag_number": "VOLUME 505",
        "asset_description": "PLOT 2-10 CORONATION AVENUE IN KAMPALA DISTRICT-Volume-505 (Land)",
        "category_segment3": "LAND",
        "fb_cost": 12825000000.00,
        "adjusted_cost": 12825000000.00,
        "useful_life_years": 0
      }
    ],
    "total": 8
  }
}
```

---

## 4. Go REST API & Checklist

| Endpoint | Method | Scope | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/assets` | GET | Civil | Query 26 civil assets (Land, Buildings, Hostels) | ✅ Implemented |
| `/api/v1/assets/verify` | POST | Civil | Field structural spot-check tag verification | ✅ Implemented |
| `/api/v1/engineering/work-orders` | POST, GET | Asst Eng Civil | Civil & plumbing work order management | ✅ Implemented |
| `/api/v1/me/activities` | GET | Self Only | Query personal account audit log | ✅ Implemented |

- [x] Integrate 26 civil land and building records from `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`.
- [x] Add **"My Activities"** and **"Fixed Assets"** links to `AssistantEngineerCivilDashboard.vue` sidebar.
- [x] Test personal activity audit query for Assistant Engineer Civil account.
